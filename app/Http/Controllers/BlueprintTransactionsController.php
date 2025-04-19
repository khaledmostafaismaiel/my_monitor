<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Requests\BlueprintTransactionStoreRequest;
use App\Http\Requests\BlueprintTransactionUpdateRequest;

class BlueprintTransactionsController extends Controller
{
    public function index()
    {
        $transactions = auth()->user()->family
            ->blueprintTransactions()
            ->when(\request("name") != "", function ($query) {
                $query->where("name", "LIKE", "%" . \request("name") . "%");
            })
            ->when(\request("direction") != "", function ($query) {
                $query->where("direction", \request("direction"));
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

        return view('blueprint_transactions', compact('transactions', 'categories', 'users'));
    }

    public function store(BlueprintTransactionStoreRequest $request)
    {
        Transaction::create(
            array_merge(
                $request->toArray(),
                [
                    'user_id'=> auth()->id(),
                    'family_id'=> auth()->user()->family_id,
                    'type'=> 'blue_print',
                ]
            )
        );

        return redirect('/blueprint_transactions');
    }

    public function update(BlueprintTransactionUpdateRequest $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        $transaction->update($request->toArray());

        return redirect('/blueprint_transactions/');
    }

    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);

        $transaction->delete();

        return redirect('/blueprint_transactions');
    }
}
