<?php

namespace App\Http\Controllers;

use App\Http\Requests\NormalTransactionStoreRequest;
use App\Http\Requests\NormalTransactionTransferToDraftRequest;
use App\Http\Requests\NormalTransactionUpdateRequest;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NormalTransactionsController extends Controller
{
    public function index(Request $request)
    {
        $family = auth()->user()->family;

        $transactions = $family->normalTransactions()
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

        return Inertia::render('NormalTransactions/Index', [
            'transactions' => $transactions,
            'filters' => $request->only(['name', 'direction', 'category_id', 'wallet_id', 'month', 'year']),
            'options' => $this->options($family),
        ]);
    }

    public function store(NormalTransactionStoreRequest $request)
    {
        Transaction::create(array_merge(
            $request->validated(),
            ['user_id' => auth()->id(), 'family_id' => auth()->user()->family_id, 'type' => 'normal'],
        ));

        return back()->with('message', 'Transaction created.');
    }

    public function update(NormalTransactionUpdateRequest $request, Transaction $normalTransaction)
    {
        $normalTransaction->update($request->validated());
        return back()->with('message', 'Transaction updated.');
    }

    public function destroy(Transaction $normalTransaction)
    {
        $normalTransaction->delete();
        return back()->with('message', 'Transaction deleted.');
    }

    public function transferToDraft(NormalTransactionTransferToDraftRequest $request)
    {
        $transaction = Transaction::findOrFail($request->id);
        $transaction->update(['type' => 'draft']);
        return redirect('/draft_transactions')->with('message', 'Moved to drafts.');
    }

    private function options($family): array
    {
        return [
            'categories' => $family->categories()->whereNotNull('parent_id')->orderBy('name')->get(['id', 'name']),
            'wallets' => $family->wallets()->orderBy('name')->get(['id', 'name']),
            'month_years' => $family->monthYears()->orderByDesc('id')->get(['id', 'month', 'year']),
            'years' => $family->monthYears()->distinct()->orderByDesc('year')->pluck('year'),
        ];
    }
}
