<?php

namespace App\Http\Controllers;

use App\Http\Requests\DraftTransactionStoreRequest;
use App\Http\Requests\DraftTransactionTransferRequest;
use App\Http\Requests\DraftTransactionUpdateRequest;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DraftTransactionsController extends Controller
{
    public function index(Request $request)
    {
        $family = auth()->user()->family;

        $transactions = $family->draftTransactions()
            ->when($request->filled('name'), fn ($q) => $q->where('name', 'LIKE', '%' . $request->name . '%'))
            ->when($request->filled('direction'), fn ($q) => $q->where('direction', $request->direction))
            ->when($request->filled('category_id'), fn ($q) => $q->where('category_id', $request->category_id))
            ->when($request->filled('wallet_id'), fn ($q) => $q->where('wallet_id', $request->wallet_id))
            ->when($request->filled('month') || $request->filled('year'), function ($q) use ($request) {
                $q->whereHas('monthYear', function ($q) use ($request) {
                    $q->when($request->filled('month'), fn ($q) => $q->whereRaw('CAST(month_years.month AS INTEGER) = ?', [(int) $request->month]))
                      ->when($request->filled('year'), fn ($q) => $q->whereRaw('CAST(month_years.year AS INTEGER) = ?', [(int) $request->year]));
                });
            })
            ->with('category', 'user', 'wallet', 'monthYear')
            ->orderByDesc('date')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('DraftTransactions/Index', [
            'transactions' => $transactions,
            'filters' => $request->only(['name', 'direction', 'category_id', 'wallet_id', 'month', 'year']),
            'options' => [
                'categories' => $family->categories()->whereNotNull('parent_id')->orderBy('name')->get(['id', 'name']),
                'wallets' => $family->wallets()->orderBy('name')->get(['id', 'name']),
                'month_years' => $family->monthYears()->orderByDesc('id')->get(['id', 'month', 'year']),
                'years' => $family->monthYears()->distinct()->orderByDesc('year')->pluck('year'),
            ],
        ]);
    }

    public function store(DraftTransactionStoreRequest $request)
    {
        Transaction::create(array_merge(
            $request->validated(),
            ['user_id' => auth()->id(), 'family_id' => auth()->user()->family_id, 'type' => 'draft'],
        ));

        return back()->with('message', 'Draft created.');
    }

    public function update(DraftTransactionUpdateRequest $request, Transaction $draftTransaction)
    {
        $draftTransaction->update($request->validated());
        return back()->with('message', 'Draft updated.');
    }

    public function destroy(Transaction $draftTransaction)
    {
        $draftTransaction->delete();
        return back()->with('message', 'Draft deleted.');
    }

    public function transferToNormal(DraftTransactionTransferRequest $request)
    {
        $transaction = Transaction::findOrFail($request->id);
        $transaction->fill($request->only([
            'name', 'price', 'quantity', 'direction', 'category_id',
            'month_year_id', 'date', 'comment', 'wallet_id',
        ]));
        $transaction->type = 'normal';
        $transaction->save();

        return redirect('/normal_transactions')->with('message', 'Promoted to normal.');
    }
}
