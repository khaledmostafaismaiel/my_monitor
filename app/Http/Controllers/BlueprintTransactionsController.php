<?php

namespace App\Http\Controllers;

use App\BlueprintTransaction;
use Illuminate\Http\Request;

class BlueprintTransactionsController extends Controller
{

    public function index()
    {
        $transactions = auth()->user()->family
            ->blueprintTransactions()
            ->when(\request("name") != "", function ($query) {
                $query->where("name", "LIKE", "%" . \request("name") . "%");
            })
            ->when(\request("type") != "", function ($query) {
                $query->where("type", \request("type"));
            })
            ->when(\request("category_id") != "", function ($query) {
                $query->where("category_id", \request("category_id"));
            })
            ->with('category')
            ->orderBy("date", "desc")
            ->paginate(10);

        $categories = auth()->user()->family->categories()->orderBy("name")->get();

        $users = auth()->user()
            ->family
            ->users()
            ->get();

        return view('blueprint_transactions', compact('transactions', 'categories', 'users', 'uniqueYears'));
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'category_id'=> ['required', 'gt:0'] ,
                'quantity'=> ['required', 'gte:1'],
                'price'=> ['required', 'gt:0'],
            ]
        );

        BlueprintTransaction::create(
            array_merge(
                $request->toArray(),
                [
                    'user_id'=> auth()->id(),
                    'family_id'=> auth()->user()->family_id,
                    'is_blue_print'=> 1,
                ]
            )
        );

        return redirect('/blueprint_transactions');
    }

    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'category_id'=> ['required', 'gt:0'] ,
                'quantity'=> ['required', 'gte:1'],
                'price'=> ['required', 'gt:0'],
            ]
        );

        $expense = BlueprintTransaction::findOrFail($id);

        $expense->update($request->toArray());

        return redirect('/blueprint_transactions/');
    }

    public function destroy($id)
    {
        $expense = BlueprintTransaction::findOrFail($id);

        $expense->delete();

        return redirect('/blueprint_transactions');
    }
}
