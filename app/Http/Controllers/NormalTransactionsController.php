<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Requests\NormalTransactionStoreRequest;
use App\Http\Requests\NormalTransactionUpdateRequest;

class NormalTransactionsController extends Controller
{
    public function index()
    {
        $transactions = auth()->user()->family
            ->normalTransactions()
            ->when(\request("name") != "", function ($query) {
                $query->where("name", "LIKE", "%" . \request("name") . "%");
            })
            ->when(\request("direction") != "", function ($query) {
                $query->where("direction", \request("direction"));
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

        $categories = auth()->user()->family->categories()->orderBy("name")->get();

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

        return view('normal_transactions', compact('transactions', 'categories', 'users', 'uniqueYears'));
    }

    public function store(NormalTransactionStoreRequest $request)
    {
        Transaction::create(
            array_merge(
                $request->toArray(),
                [
                    'user_id'=> auth()->id(),
                    'family_id'=> auth()->user()->family_id,
                    'type'=> 'normal',
                ]
            )
        );

        return redirect('/normal_transactions');
    }

    public function update(NormalTransactionUpdateRequest $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        $transaction->update($request->toArray());

        return redirect('/normal_transactions');
    }

    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);

        $transaction->delete();

        return redirect('/normal_transactions');
    }
}
