<?php

namespace App\Http\Controllers;

use App\Http\Requests\BlueprintTransactionStoreRequest;
use App\Http\Requests\BlueprintTransactionUpdateAndAddRequest;
use App\Http\Requests\BlueprintTransactionUpdateRequest;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BlueprintTransactionsController extends Controller
{
    private function buildTree($categories)
    {
        $map = [];
        $tree = [];
        foreach ($categories as $cat) {
            $map[$cat->id] = $cat;
            $cat->children = collect();
        }
        foreach ($categories as $cat) {
            if ($cat->parent_id && isset($map[$cat->parent_id])) {
                $map[$cat->parent_id]->children->push($cat);
            } else {
                $tree[] = $cat;
            }
        }
        return collect($tree);
    }

    public function index(Request $request)
    {
        $family = auth()->user()->family;

        $filterFn = function ($query) use ($request) {
            $query->when($request->filled('name'), fn ($q) => $q->where('transactions.name', 'LIKE', '%' . $request->name . '%'))
                  ->when($request->filled('direction'), fn ($q) => $q->where('transactions.direction', $request->direction))
                  ->when($request->filled('category_id'), fn ($q) => $q->where('transactions.category_id', $request->category_id));
        };

        $categories = $family->categories()
            ->whereHas('blueprintTransactions', $filterFn)
            ->orderBy('name')
            ->with(['blueprintTransactions' => $filterFn])
            ->get();

        $rootCategories = $this->buildTree($categories);

        return Inertia::render('BlueprintTransactions/Index', [
            'rootCategories' => $rootCategories,
            'filters' => $request->only(['name', 'direction', 'category_id']),
            'options' => [
                'categories' => $family->categories()->whereNotNull('parent_id')->orderBy('name')->get(['id', 'name']),
                'wallets' => $family->wallets()->orderBy('name')->get(['id', 'name']),
                'month_years' => $family->monthYears()->orderByDesc('id')->get(['id', 'month', 'year']),
            ],
        ]);
    }

    public function store(BlueprintTransactionStoreRequest $request)
    {
        Transaction::create(array_merge(
            $request->validated(),
            ['user_id' => auth()->id(), 'family_id' => auth()->user()->family_id, 'type' => 'blue_print'],
        ));
        return back()->with('message', 'Blueprint created.');
    }

    public function update(BlueprintTransactionUpdateRequest $request, Transaction $blueprintTransaction)
    {
        $blueprintTransaction->update($request->validated());
        return back()->with('message', 'Blueprint updated.');
    }

    public function destroy(Transaction $blueprintTransaction)
    {
        $blueprintTransaction->delete();
        return back()->with('message', 'Blueprint deleted.');
    }

    public function updateAndAddTransaction(BlueprintTransactionUpdateAndAddRequest $request)
    {
        $blueprint = Transaction::findOrFail($request->id);
        $blueprint->fill($request->only(['name', 'price', 'quantity', 'direction', 'category_id', 'comment']));
        $blueprint->save();

        Transaction::create([
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'direction' => $request->direction,
            'category_id' => $request->category_id,
            'month_year_id' => $request->month_year_id,
            'date' => $request->date,
            'comment' => $request->comment,
            'wallet_id' => $request->wallet_id,
            'type' => 'normal',
            'user_id' => auth()->id(),
            'family_id' => auth()->user()->family_id,
        ]);

        return redirect('/normal_transactions')->with('message', 'Transaction added from blueprint.');
    }
}
