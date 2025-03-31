@extends('layouts.master_layout')

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card p-4 shadow-lg w-100" style="max-width: 900px; background: rgba(255, 255, 255, 0.95); border-radius: 12px;">
        <div class="text-center mb-3">
            <h1 class="fw-bold text-primary">My Monitor</h1>
            <p class="text-muted">Track your transactions with ease</p>
        </div>

        <!-- Responsive Table Wrapper -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-center align-middle">
                <thead class="bg-primary text-white">
                    <tr>
                        <th scope="col">Month</th>
                        <th scope="col">Credit</th>
                        <th scope="col">Debit</th>
                        <th scope="col">Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $transaction)
                        <tr>
                            <td class="fw-bold">{{ $transaction->month_year }}</td>
                            <td class="text-success fw-bold">+{{ number_format($transaction->credit, 2) }}</td>
                            <td class="text-danger fw-bold">-{{ number_format($transaction->debit, 2) }}</td>
                            <td class="{{ ($transaction->credit - $transaction->debit) < 0 ? 'text-danger' : 'text-success' }} fw-bold">
                                {{ number_format($transaction->credit - $transaction->debit, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Responsive Pagination -->
        <div class="d-flex justify-content-center mt-3">
            <div class="w-100 overflow-auto">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Additional Styling for Mobile -->
<style>
    .pagination {
        flex-wrap: wrap;
        justify-content: center;
    }
</style>
@endsection
