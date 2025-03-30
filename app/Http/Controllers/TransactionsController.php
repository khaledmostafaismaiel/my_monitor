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
            ->when(null !== \request("search"), function($query){
                $query->where("name", "LIKE", "%".\request("search")."%");
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
