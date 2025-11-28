<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Requests\NormalTransactionStoreRequest;
use App\Http\Requests\NormalTransactionUpdateRequest;
use App\Http\Requests\NormalTransactionTransferToDraftRequest;

class NormalTransactionsController extends Controller
{
    public function index()
    {
        $query = auth()->user()->family
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
            ->when(\request("wallet_id") != "", function ($query) {
                $query->where("wallet_id", \request("wallet_id"));
            });

        // Clone query for totals to avoid modifying the base query for pagination
        $totalsQuery = clone $query;
        $transactionsForTotals = $totalsQuery->get();

        $total_income = $transactionsForTotals->where('direction', 'credit')->sum(function ($t) {
            return $t->price * $t->quantity;
        });

        $total_expense = $transactionsForTotals->where('direction', 'debit')->sum(function ($t) {
            return $t->price * $t->quantity;
        });

        $total_balance = $total_income - $total_expense;

        $transactions = $query->with('category', 'user')
            ->orderBy("date", "desc")
            ->paginate(10);

        $all_categories = auth()->user()->family->categories()->orderBy("name")
            ->get();

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

        $all_wallets = auth()->user()->family->wallets()->orderBy("name")
            ->get();

        $all_month_years = auth()->user()->family->monthYears()->orderBy("id", "Desc")
            ->get();

        return view('normal_transactions', compact(
            'transactions',
            'all_categories',
            'users',
            'uniqueYears',
            'all_wallets',
            'all_month_years',
            'total_income',
            'total_expense',
            'total_balance'
        ));
    }

    public function store(NormalTransactionStoreRequest $request)
    {
        Transaction::create(
            array_merge(
                $request->toArray(),
                [
                    'user_id' => auth()->id(),
                    'family_id' => auth()->user()->family_id,
                    'type' => 'normal',
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

    public function transferToDraft(NormalTransactionTransferToDraftRequest $request)
    {
        $transaction = Transaction::findOrFail($request->id);

        $transaction->update(["type" => "draft"]);

        return redirect('/draft_transactions');
    }
}
