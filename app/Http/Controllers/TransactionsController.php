<?php

namespace App\Http\Controllers;

use App\Transaction;
use App\Category;
use App\User;

use Illuminate\Http\Request;

class TransactionsController extends Controller
{

    public function index()
    {
        $transactions = auth()->user()->family
            ->transactions()
            ->when(\request("name") != "", function ($query) {
                $query->where("name", "LIKE", "%" . \request("name") . "%");
            })
            ->when(\request("type") != "", function ($query) {
                $query->where("type", \request("type"));
            })
            ->when(\request("category_id") != "", function ($query) {
                $query->where("category_id", \request("category_id"));
            })
            ->when(\request("month") != "" || \request("year") != "", function ($query) {
                $query->whereHas('monthYear', function ($query) {
                    $query->when(\request("month") != "", function ($query) {
                        $query->where("month_years.month", \request("month"));
                    })->when(\request("year") != "", function ($query) {
                        $query->where("month_years.year", \request("year"));
                    });
                });
            })
            ->with('category', 'user')
            ->orderBy("date", "desc")
            ->paginate(10);

        $categories = Category::orderBy("name")->get();

        $users = auth()->user()
            ->family
            ->users()
            ->get();

        $uniqueYears = auth()->user()
            ->family
            ->monthYears()
            ->distinct('year')
            ->pluck('year')
            ->sortDesc();

        return view('transactions', compact('transactions', 'categories', 'users', 'uniqueYears'));
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'category_id'=> ['required', 'gt:0'] ,
                'quantity'=> ['required', 'gte:1'],
                'price'=> ['required', 'gt:0'],
                'month_year_id'=> ['required', 'gt:0'],
            ]
        );

        Transaction::create(
            array_merge(
                $request->toArray(),
                [
                    'user_id'=> auth()->id(),
                    'family_id'=> auth()->user()->family_id,
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
                'quantity'=> ['required', 'gte:1'],
                'price'=> ['required', 'gt:0'],
                'month_year_id'=> ['required', 'gt:0'],
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
