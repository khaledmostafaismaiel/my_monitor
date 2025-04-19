<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Requests\DraftTransactionStoreRequest;
use App\Http\Requests\DraftTransactionUpdateRequest;
use App\Http\Requests\DraftTransactionTransferRequest;

class DraftTransactionsController extends Controller
{

    public function index()
    {
        $transactions = auth()->user()->family
            ->draftTransactions()
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

        return view('draft_transactions', compact('transactions', 'categories', 'users', 'uniqueYears'));
    }

    public function store(DraftTransactionStoreRequest $request)
    {
        Transaction::create(
            array_merge(
                $request->toArray(),
                [
                    'user_id'=> auth()->id(),
                    'family_id'=> auth()->user()->family_id,
                    'type'=> 'draft',
                ]
            )
        );

        return redirect('/draft_transactions');
    }

    public function update(DraftTransactionUpdateRequest $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        $transaction->update($request->toArray());

        return redirect('/draft_transactions');
    }

    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);

        $transaction->delete();

        return redirect('/draft_transactions');
    }

    public function transferToNormal(DraftTransactionTransferRequest $request)
    {
        $transaction = Transaction::findOrFail($request->id);

        $transaction->name = $request->name;
        $transaction->price = $request->price;
        $transaction->quantity = $request->quantity;
        $transaction->direction = $request->direction;
        $transaction->category_id = $request->category_id;
        $transaction->month_year_id = $request->month_year_id;
        $transaction->date = $request->date;
        $transaction->comment = $request->comment;
        $transaction->type = 'normal';
        $transaction->save();

        return redirect('/draft_transactions');
    }
}
