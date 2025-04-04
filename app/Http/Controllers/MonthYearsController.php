<?php

namespace App\Http\Controllers;

use App\MonthYear;
use App\Transaction;
use \DB;

use Illuminate\Http\Request;

class MonthYearsController extends Controller
{
    public function show(MonthYear $monthYear)
    {
        // Fetch all transactions for the chart
        $transactions = $monthYear->transactions()->with('category')->get(); // No pagination here, for the chart
    
        // Paginate transactions for the table
        $paginatedTransactions = $monthYear->transactions()
        ->selectRaw("*, (price * quantity) AS total_amount")
        ->orderByRaw("total_amount DESC") // Sorting by total_amount (price * quantity)
        ->with('category')
        ->paginate(10); // Pagination for table
    
    
        // Calculate total spent per category (for the chart)
        $categorySummary = $transactions->groupBy('category_id')->map(function ($categoryTransactions) {
            return [
                'category' => $categoryTransactions->first()->category->name, // Get the category name
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
