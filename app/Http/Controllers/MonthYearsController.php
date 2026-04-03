<?php

namespace App\Http\Controllers;

use App\Models\MonthYear;
use App\Models\Category;
use DB;
use Illuminate\Http\Request;
use App\Http\Requests\MonthYearStoreRequest;

class MonthYearsController extends Controller
{

    public function store(MonthYearStoreRequest $request)
    {
        $monthYear = $request->month_year;

        list($year, $month) = explode('-', $monthYear);

        MonthYear::updateOrCreate(
            [
                'family_id' => auth()->user()->family_id,
                'year' => $year,
                'month' => $month,
            ]
        );

        return redirect('/');
    }

    private function buildTree($categories)
    {
        $grouped = $categories->groupBy('parent_id');
        $rootCategories = $grouped->get(null) ?? collect();

        $mainCategory = $rootCategories->firstWhere('name', 'Main');
        
        if ($mainCategory) {
            $mainChildren = $grouped->get($mainCategory->id) ?? collect();
            
            if ($mainChildren->isNotEmpty()) {
                $result = collect([$this->addChildren($mainCategory, $grouped)]);
                
                $otherRoots = $rootCategories->filter(fn($c) => $c->name !== 'Main');
                return $result->concat($otherRoots->map(function ($category) use ($grouped) {
                    return $this->addChildren($category, $grouped);
                }));
            }
        }

        return $rootCategories->map(function ($category) use ($grouped) {
            return $this->addChildren($category, $grouped);
        });
    }

    private function addChildren($category, $grouped)
    {
        $children = $grouped->get($category->id) ?? collect();
        $category->children = $children->map(function ($child) use ($grouped) {
            return $this->addChildren($child, $grouped);
        });
        return $category;
    }

    public function show(MonthYear $monthYear)
    {
        // Calculate previous and next month/year for navigation
        $prevMonthYear = MonthYear::where('family_id', auth()->user()->family_id)
            ->where(function ($query) use ($monthYear) {
                $query->where('year', '<', $monthYear->year)
                    ->orWhere(function ($q) use ($monthYear) {
                        $q->where('year', '=', $monthYear->year)
                            ->where('month', '<', $monthYear->month);
                    });
            })
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->first();

        $nextMonthYear = MonthYear::where('family_id', auth()->user()->family_id)
            ->where(function ($query) use ($monthYear) {
                $query->where('year', '>', $monthYear->year)
                    ->orWhere(function ($q) use ($monthYear) {
                        $q->where('year', '=', $monthYear->year)
                            ->where('month', '>', $monthYear->month);
                    });
            })
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->first();

        $categorySummary = $monthYear->normalTransactions()
            ->with('category')
            ->get()
            ->groupBy('category_id')
            ->map(function ($categoryTransactions) {
                return [
                    'category' => $categoryTransactions->first()->category->name,
                    'total_spent' => abs($categoryTransactions->sum(function ($transaction) {
                        $amount = $transaction->price * $transaction->quantity;
                        // Credit = income (positive), Debit = expense (negative)
                        return $transaction->direction === 'credit' ? $amount : -$amount;
                    }))
                ];
            });

        $categories = auth()->user()
            ->family->categories()
            ->select('categories.*')
            ->selectRaw("SUM(
                CASE
                    WHEN transactions.direction = 'credit' THEN transactions.price * transactions.quantity
                    WHEN transactions.direction = 'debit' THEN -(transactions.price * transactions.quantity)
                    ELSE 0
                END
            ) as total_spent")
            ->join('transactions', 'transactions.category_id', 'categories.id')
            ->where('transactions.month_year_id', $monthYear->id)
            ->where('transactions.type', 'normal')
            ->groupBy('categories.id')
            ->orderByDesc('total_spent')
            ->with([
                'normalTransactions' => function ($query) use ($monthYear) {
                    $query->where('month_year_id', $monthYear->id)
                        ->where('type', 'normal');
                }
            ])
            ->get();

        $allCategories = auth()->user()->family->categories()->orderBy("name")->get();
        $categoryTree = $this->buildTree($allCategories);
        $rootCategories = $categoryTree->slice(0, 10);
        $hasMore = $categoryTree->count() > 10;

        return view('month_year', compact('monthYear', 'categories', 'categorySummary', 'prevMonthYear', 'nextMonthYear', 'rootCategories', 'hasMore'));
    }
}
