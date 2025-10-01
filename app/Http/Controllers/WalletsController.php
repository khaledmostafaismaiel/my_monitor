<?php

namespace App\Http\Controllers;

use App\Http\Requests\WalletDestroyRequest;
use App\Models\Wallet;
use App\Http\Requests\WalletStoreRequest;
use App\Http\Requests\WalletUpdateRequest;

class WalletsController extends Controller
{
    public function index()
    {
        $wallets = auth()->user()->family
            ->wallets()
            ->when(\request("name") != "", function ($query) {
                $query->where("name", "LIKE", "%" . \request("name") . "%");
            })
            ->when(null !== \request("status"), function($query){
                $query->where("status", \request("status"));
            })
            ->orderBy("name", "asc")
            ->paginate(10);

        return view('wallets', compact('wallets'));
    }

    public function store(WalletStoreRequest $request)
    {
        Wallet::create(
            array_merge(
                $request->toArray(),
                [
                    'family_id'=> auth()->user()->family_id,
                ]
            )
        );

        return redirect('/wallets');
    }

    public function update(WalletUpdateRequest $request, $id)
    {
        $wallet = Wallet::findOrFail($id);

        $wallet->update($request->toArray());

        return redirect('/wallets');
    }

    public function destroy(WalletDestroyRequest $request, Wallet $wallet)
    {
        $wallet->delete();

        return redirect('/wallets');
    }
}
