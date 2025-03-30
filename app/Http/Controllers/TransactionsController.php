<?php

namespace App\Http\Controllers;

use App\Transaction;
use App\Category;

use Illuminate\Http\Request;

class TransactionsController extends Controller
{

    public function index()
    {
        $transactions = Transaction::with('category', 'user')
            ->when(null !== \request("name"), function($query){
                $query->where("name", "LIKE", "%".\request("name")."%");
            })
            ->when(null !== \request("type") and (\request("type") != ""), function($query){
                $query->where("type", \request("type"));
            })
            ->when(null !== \request("category_id") and (\request("category_id") != ""), function($query){
                $query->where("category_id", \request("category_id"));
            })
            ->when(null !== \request("month_year") and (\request("month_year") != ""), function($query){
                $monthYear = explode('-', \request("month_year"));
                if (count($monthYear) == 2) {
                    $year = '20' . $monthYear[1];
                    $month = $monthYear[0];
        
                    $query->whereYear('date', $year)->whereMonth('date', $month);
                }
            })
            ->orderBy("date", "desc")
            ->paginate(10);

        $categories = Category::orderBy("name")
            ->get();

        return view('transactions' ,compact('transactions', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'category_id'=> ['required', 'gt:0'] ,
                'price'=> ['required', 'gt:0'] ,
            ]
        );

        Transaction::create(
            array_merge(
                $request->toArray(),
                [
                    'user_id'=> auth()->id(),
                ]
            )
        );

        return redirect('/transactions');
    }

    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'category_id'=> ['required', 'gt:0'] ,
                'price'=> ['required', 'gt:0'] ,
            ]
        );
        
        $expense = Transaction::findOrFail($id);

        $expense->update($request->toArray());

        return redirect('/transactions/');
    }

    public function destroy($id)
    {
        $expense = Transaction::findOrFail($id);

        $expense->delete();

        return redirect('/transactions');
    }
}
