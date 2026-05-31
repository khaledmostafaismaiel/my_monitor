<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function dashboard(): Response
    {
        $user = auth()->user();

        $monthYears = $user
            ->family
            ->monthYears()
            ->leftJoin('transactions', function ($join) {
                $join->on('transactions.month_year_id', '=', 'month_years.id')
                    ->where('transactions.type', 'normal');
            })
            ->selectRaw("
                month_years.id as id,
                month_years.month as month,
                month_years.year as year,
                CONCAT(month_years.year, '-', LPAD(month_years.month, 2, '0')) as month_year,
                SUM(CASE WHEN transactions.direction = 'credit' THEN transactions.price * transactions.quantity ELSE 0 END) as credit,
                SUM(CASE WHEN transactions.direction = 'debit' THEN transactions.price * transactions.quantity ELSE 0 END) as debit
            ")
            ->groupBy('month_years.id', 'month_years.month', 'month_years.year')
            ->orderByDesc('month_years.id')
            ->paginate(10);

        $wallets = $user
            ->family
            ->normalTransactions()
            ->whereIn('transactions.month_year_id', $monthYears->pluck('id')->toArray())
            ->leftJoin('wallets', 'wallets.id', 'transactions.wallet_id')
            ->selectRaw("
                transactions.month_year_id,
                wallets.id as wallet_id,
                wallets.name as wallet_name,
                SUM(CASE WHEN transactions.direction = 'credit' THEN transactions.price * transactions.quantity ELSE 0 END) as credit,
                SUM(CASE WHEN transactions.direction = 'debit' THEN transactions.price * transactions.quantity ELSE 0 END) as debit
            ")
            ->groupBy('transactions.month_year_id', 'wallets.id', 'wallets.name')
            ->orderBy('wallets.name')
            ->get()
            ->groupBy('month_year_id');

        $totalCredit = (float) $monthYears->getCollection()->sum('credit');
        $totalDebit = (float) $monthYears->getCollection()->sum('debit');

        return Inertia::render('Dashboard', [
            'monthYears' => $monthYears,
            'wallets' => $wallets,
            'totals' => [
                'credit' => $totalCredit,
                'debit' => $totalDebit,
                'balance' => $totalCredit - $totalDebit,
            ],
        ]);
    }
}
