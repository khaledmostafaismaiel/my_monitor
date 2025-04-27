@extends('layouts.master_layout')

@section('content')
<div class="container d-flex justify-content-center align-items-center py-5 my-4">
    <div class="card p-4 shadow-lg w-100" style="max-width: 900px; background: rgba(255, 255, 255, 0.95); border-radius: 12px;">
        <div class="text-center mb-3">
            <h1 class="fw-bold text-primary">My Monitor</h1>
            <p class="text-muted">Track your transactions with ease</p>
        </div>

        <!-- Add Transaction Button -->
        <div class="col-lg-4 col-md-6 col-sm-6 d-grid mb-4">
            <button type="button" class="btn btn-success px-4" data-bs-toggle="modal" data-bs-target="#addMonthYearModal">
                <i class="bi bi-plus-lg"></i> Add
            </button>
        </div>
        @include('layouts/add_month_year')

        <!-- Responsive Table Wrapper -->
        <div class="table-responsive">
            <table class="table table-striped table-hover text-center align-middle">
                <thead class="bg-primary text-white">
                    <tr>
                        <th scope="col">Month</th>
                        <th scope="col">Credit</th>
                        <th scope="col">Debit</th>
                        <th scope="col">Balance</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($monthYears as $monthYear)
                        <tr>
                            <td class="fw-bold"> {{ $monthYear->month_year }} </td>
                            <td class="text-success fw-bold">+{{ number_format($monthYear->credit, 2) }}</td>
                            <td class="text-danger fw-bold">-{{ number_format($monthYear->debit, 2) }}</td>
                            <td class="{{ ($monthYear->credit - $monthYear->debit) < 0 ? 'text-danger' : 'text-success' }} fw-bold">
                                {{ number_format($monthYear->credit - $monthYear->debit, 2) }}
                            </td>
                            <td>
                                <!-- Show Button (Redirect to "/") -->
                                <a href="/month_years/{{ $monthYear->id }}" class="btn btn-sm btn-info me-2">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Responsive Pagination -->
        <div class="d-flex justify-content-center mt-3">
            <div class="w-100 overflow-auto">
                {{ $monthYears->appends(request()->query())->links() }}
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
