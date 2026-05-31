<?php

namespace App\Http\Controllers;

use App\Http\Requests\MonthYearStoreRequest;
use App\Models\MonthYear;
use App\Models\Transaction;
use Inertia\Inertia;

class MonthYearsController extends Controller
{
    public function store(MonthYearStoreRequest $request)
    {
        [$year, $month] = explode('-', $request->month_year);

        MonthYear::updateOrCreate([
            'family_id' => auth()->user()->family_id,
            'year' => $year,
            'month' => $month,
        ]);

        return redirect('/')->with('message', 'Month added.');
    }

    public function update(MonthYearStoreRequest $request, MonthYear $monthYear)
    {
        abort_unless($monthYear->family_id === auth()->user()->family_id, 403);

        [$year, $month] = explode('-', $request->month_year);

        $duplicate = MonthYear::where('family_id', auth()->user()->family_id)
            ->where('year', $year)
            ->where('month', $month)
            ->where('id', '!=', $monthYear->id)
            ->exists();

        if ($duplicate) {
            return back()->with('error', 'That month already exists.');
        }

        $monthYear->update(['year' => $year, 'month' => $month]);

        return back()->with('message', 'Month updated.');
    }

    public function destroy(MonthYear $monthYear)
    {
        abort_unless($monthYear->family_id === auth()->user()->family_id, 403);

        $txCount = Transaction::where('month_year_id', $monthYear->id)->count();
        if ($txCount > 0) {
            return back()->with('error', "Cannot delete a month that has {$txCount} transaction(s). Remove or reassign them first.");
        }

        $monthYear->delete();

        return back()->with('message', 'Month deleted.');
    }

    private function buildTree($categories)
    {
        $map = [];
        $tree = [];
        foreach ($categories as $cat) {
            $map[$cat->id] = $cat;
            $cat->children = collect();
        }
        foreach ($categories as $cat) {
            if ($cat->parent_id && isset($map[$cat->parent_id])) {
                $map[$cat->parent_id]->children->push($cat);
            } else {
                $tree[] = $cat;
            }
        }
        return collect($tree);
    }

    public function show(MonthYear $monthYear)
    {
        $family = auth()->user()->family;

        $prev = MonthYear::where('family_id', $family->id)
            ->where(function ($q) use ($monthYear) {
                $q->where('year', '<', $monthYear->year)
                  ->orWhere(function ($q) use ($monthYear) {
                      $q->where('year', $monthYear->year)->where('month', '<', $monthYear->month);
                  });
            })
            ->orderByDesc('year')->orderByDesc('month')->first();

        $next = MonthYear::where('family_id', $family->id)
            ->where(function ($q) use ($monthYear) {
                $q->where('year', '>', $monthYear->year)
                  ->orWhere(function ($q) use ($monthYear) {
                      $q->where('year', $monthYear->year)->where('month', '>', $monthYear->month);
                  });
            })
            ->orderBy('year')->orderBy('month')->first();

        $categories = $family->categories()
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
            ->with(['normalTransactions' => function ($query) use ($monthYear) {
                $query->where('month_year_id', $monthYear->id)
                    ->where('type', 'normal')
                    ->with('wallet')
                    ->orderByDesc('date');
            }])
            ->get();

        $allCategories = $family->categories()->orderBy('name')->get();
        $categorySpent = $categories->keyBy('id');
        $allCategories = $allCategories->map(function ($cat) use ($categorySpent) {
            if (isset($categorySpent[$cat->id])) {
                $cat->total_spent = $categorySpent[$cat->id]->total_spent;
                $cat->normal_transactions = $categorySpent[$cat->id]->normalTransactions;
            }
            return $cat;
        });

        $categoryTree = $this->buildTree($allCategories);
        $categoryTree = $categoryTree->map(function ($root) {
            if ($root->children && $root->children->count() > 0) {
                $root->total_spent = $root->children->sum('total_spent');
            }
            return $root;
        });

        return Inertia::render('MonthYears/Show', [
            'monthYear' => $monthYear,
            'prev' => $prev,
            'next' => $next,
            'rootCategories' => $categoryTree->values(),
            'options' => [
                'categories' => $family->categories()->whereNotNull('parent_id')->orderBy('name')->get(['id', 'name']),
                'wallets' => $family->wallets()->orderBy('name')->get(['id', 'name']),
                'month_years' => $family->monthYears()->orderByDesc('id')->get(['id', 'month', 'year']),
            ],
        ]);
    }
}
