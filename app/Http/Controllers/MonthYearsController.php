<?php

namespace App\Http\Controllers;

use App\Models\MonthYear;
use DB;
use Illuminate\Http\Request;

class MonthYearsController extends Controller
{

    public function store(Request $request)
    {
        $monthYear = $request->month_year;

        list($year, $month) = explode('-', $monthYear);

        MonthYear::updateOrCreate(
            [
                'family_id'=> auth()->user()->family_id,
                'year'=> $year,
                'month'=> $month,
            ]
        );

        return redirect('/');
    }

    public function show(MonthYear $monthYear)
    {
        $transactions = $monthYear->normalTransactions()->with('category')->get();

        $paginatedTransactions = $monthYear->normalTransactions()
        ->selectRaw("*, (price * quantity) AS total_amount")
        ->orderByRaw("total_amount DESC")
        ->with('category')
        ->paginate(10);


        $categorySummary = $transactions->groupBy('category_id')->map(function ($categoryTransactions) {
            return [
                'category' => $categoryTransactions->first()->category->name,
                'total_spent' => $categoryTransactions->sum(function ($transaction) {
                    return $transaction->price * $transaction->quantity;
                })
            ];
        });

        return view('month_year', compact('monthYear', 'paginatedTransactions', 'categorySummary'));
    }

    public function update(Request $request, $id)
    {
        $monthYear = MonthYear::findOrFail($id);

        $monthYear->update($request->toArray());

        return redirect('/');
    }

}
