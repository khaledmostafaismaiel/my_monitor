<?php

namespace App\Http\Controllers;

use App\Http\Requests\WalletDestroyRequest;
use App\Http\Requests\WalletStoreRequest;
use App\Http\Requests\WalletUpdateRequest;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WalletsController extends Controller
{
    public function index(Request $request)
    {
        $wallets = auth()->user()->family
            ->wallets()
            ->when($request->filled('name'), function ($query) use ($request) {
                $query->where('name', 'LIKE', '%' . $request->name . '%');
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Wallets/Index', [
            'wallets' => $wallets,
            'filters' => $request->only(['name', 'status']),
        ]);
    }

    public function store(WalletStoreRequest $request)
    {
        Wallet::create(array_merge(
            $request->validated(),
            ['family_id' => auth()->user()->family_id],
        ));

        return back()->with('message', 'Wallet created.');
    }

    public function update(WalletUpdateRequest $request, Wallet $wallet)
    {
        $wallet->update($request->validated());

        return back()->with('message', 'Wallet updated.');
    }

    public function destroy(WalletDestroyRequest $request, Wallet $wallet)
    {
        $wallet->delete();

        return back()->with('message', 'Wallet deleted.');
    }
}
