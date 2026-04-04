<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Requests\BlueprintTransactionStoreRequest;
use App\Http\Requests\BlueprintTransactionUpdateRequest;
use App\Http\Requests\BlueprintTransactionUpdateAndAddRequest;

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

    public function index()
    {
        $categories = auth()->user()->family->categories()
            ->whereHas('blueprintTransactions', function ($query) {
                $query->when(request("name") != "", function ($query) {
                    $query->where("transactions.name", "LIKE", "%" . request("name") . "%");
                })
                ->when(request("direction") != "", function ($query) {
                    $query->where("transactions.direction", request("direction"));
                })
                ->when(request("category_id") != "", function ($query) {
                    $query->where("transactions.category_id", request("category_id"));
                });
            })
            ->orderBy("name")
            ->with(['blueprintTransactions' => function ($query) {
                $query->when(request("name") != "", function ($query) {
                    $query->where("transactions.name", "LIKE", "%" . request("name") . "%");
                })
                ->when(request("direction") != "", function ($query) {
                    $query->where("transactions.direction", request("direction"));
                })
                ->when(request("category_id") != "", function ($query) {
                    $query->where("transactions.category_id", request("category_id"));
                });
            }])
            ->get();

        $categoryTree = $this->buildTree($categories);
        $rootCategories = $categoryTree->slice(0, 10);
        $hasMore = $categoryTree->count() > 10;

        $users = auth()->user()
            ->family
            ->users()
            ->get();

        $all_categories = auth()->user()->family->categories()->whereNotNull('parent_id')->orderBy("name")
            ->get();

        $all_wallets = auth()->user()->family->wallets()->orderBy("name")
            ->get();


        $all_month_years = auth()->user()->family->monthYears()->orderBy("id", "Desc")
            ->get();

        return view('blueprint_transactions', compact('rootCategories', 'users', 'all_categories', 'all_wallets', 'all_month_years', 'hasMore'));
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

    public function updateAndAddTransaction(BlueprintTransactionUpdateAndAddRequest $request)
    {
        $transaction = Transaction::findOrFail($request->id);
        $transaction->name = $request->name;
        $transaction->price = $request->price;
        $transaction->quantity = $request->quantity;
        $transaction->direction = $request->direction;
        $transaction->category_id = $request->category_id;
        $transaction->comment = $request->comment;
        $transaction->save();

        $transaction = new Transaction();
        $transaction->name = $request->name;
        $transaction->price = $request->price;
        $transaction->quantity = $request->quantity;
        $transaction->direction = $request->direction;
        $transaction->category_id = $request->category_id;
        $transaction->month_year_id = $request->month_year_id;
        $transaction->date = $request->date;
        $transaction->comment = $request->comment;
        $transaction->type = 'normal';
        $transaction->user_id = auth()->id();
        $transaction->family_id = auth()->user()->family_id;
        $transaction->wallet_id = $request->wallet_id;
        $transaction->save();

        return redirect('/normal_transactions');
    }
}
