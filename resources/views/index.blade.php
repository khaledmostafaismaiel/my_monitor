@extends('layouts.master_layout')

@section('content')
<div class="container d-flex justify-content-center align-items-center py-5 my-4">
    <div class="card p-4 shadow-lg w-100" style="max-width: 900px; background: rgba(255, 255, 255, 0.95); border-radius: 12px;">
        <div class="text-center mb-3">
            <h1 class="fw-bold text-primary">My Monitor</h1>
            <p class="text-muted">Track your transactions with ease</p>
        </div>

        <!-- Responsive Table Wrapper -->
        <div class="table-responsive">
            <table class="table table-striped table-hover text-center align-middle">
                <thead class="bg-primary text-white">
                    <tr>
                        <th scope="col">Month</th>
                        <th scope="col">Credit</th>
                        <th scope="col">Debit</th>
                        <th scope="col">Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($monthYears as $monthYear)
                        <tr onclick="window.location.href='/month_years/{{ $monthYear->id }}'" style="cursor: pointer;">
                            <td class="fw-bold">{{ $monthYear->month_year }}</td>
                            <td class="text-success fw-bold">+{{ number_format($monthYear->credit, 2) }}</td>
                            <td class="text-danger fw-bold">-{{ number_format($monthYear->debit, 2) }}</td>
                            <td class="{{ ($monthYear->credit - $monthYear->debit) < 0 ? 'text-danger' : 'text-success' }} fw-bold">
                                {{ number_format($monthYear->credit - $monthYear->debit, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Responsive Pagination -->
        <div class="d-flex justify-content-center mt-3">
            <div class="w-100 overflow-auto">
                {{ $monthYears->links() }}
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
