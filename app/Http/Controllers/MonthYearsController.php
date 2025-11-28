<?php

namespace App\Http\Controllers;

use App\Models\MonthYear;
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

    public function show(MonthYear $monthYear)
    {
        $categorySummary = $monthYear->normalTransactions()
            ->with('category')
            ->get()
            ->groupBy('category_id')
            ->map(function ($categoryTransactions) {
                return [
                    'category' => $categoryTransactions->first()->category->name,
                    'total_spent' => $categoryTransactions->sum(function ($transaction) {
                        return $transaction->price * $transaction->quantity;
                    })
                ];
            });

        $categories = auth()->user()
            ->family->categories()
            ->select('categories.*')
            ->selectRaw('SUM(transactions.price * transactions.quantity) as total_spent')
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

        return view('month_year', compact('monthYear', 'categories', 'categorySummary'));
    }
}
