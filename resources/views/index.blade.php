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

        <div class="table-responsive">
            <table class="table table-striped table-hover text-center align-middle">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>Month</th>
                        <th>Credit</th>
                        <th>Debit</th>
                        <th>Balance</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($monthYears as $monthYear)
                        <tr class="table-secondary">
                            <td class="fw-bold text-start">
                                <a href="javascript:void(0);" class="monthYear-toggle d-flex align-items-center" data-target="#monthYear{{ $monthYear->id }}">
                                    <i class="bi bi-plus-circle me-2"></i> {{ $monthYear->month_year }}
                                </a>
                            </td>
                            <td class="text-success fw-bold">+{{ number_format($monthYear->credit, 2) }}</td>
                            <td class="text-danger fw-bold">-{{ number_format($monthYear->debit, 2) }}</td>
                            <td class="{{ ($monthYear->credit - $monthYear->debit) < 0 ? 'text-danger' : 'text-success' }} fw-bold">
                                {{ number_format($monthYear->credit - $monthYear->debit, 2) }}
                            </td>
                            <td>
                                <!-- Show Button (Redirect to "/") -->
                                <a href="/month_years/{{ $monthYear->id }}" class="btn btn-sm btn-info me-2">
                                    Show
                                </a>
                            </td>
                        </tr>

                        <tr id="monthYear{{ $monthYear->id }}" class="collapse">
                            <td colspan="5">
                                <table class="table table-striped table-hover text-center align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Wallet</th>
                                            <th>Credit</th>
                                            <th>Debit</th>
                                            <th>Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($wallets[$monthYear->id] ?? [] as $wallet)
                                            <tr>
                                                <td class="fw-semibold text-truncate">{{ $wallet->wallet_name }}</td>
                                                <td class="text-success fw-bold">+{{ number_format($wallet->credit, 2) }}</td>
                                                <td class="text-danger fw-bold">-{{ number_format($wallet->debit, 2) }}</td>
                                                <td class="{{ ($wallet->credit - $wallet->debit) < 0 ? 'text-danger' : 'text-success' }} fw-bold">
                                                    {{ number_format($wallet->credit - $wallet->debit, 2) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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

@endsection
<!-- Additional Styling for Mobile -->
<style>
    .pagination {
        flex-wrap: wrap;
        justify-content: center;
    }

    .card {
        margin-top: 20px;
    }

    .table-responsive {
        overflow-x: auto;
    }

    table {
        min-width: 600px;
    }

    th, td {
        white-space: nowrap;
        word-wrap: break-word;
        min-width: 100px;
    }

    @media (max-width: 768px) {
        th, td {
            min-width: 80px;
        }

        .table thead {
            font-size: 14px;
        }
    }
</style>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const monthYearRows = document.querySelectorAll('.monthYear-toggle');
    monthYearRows.forEach(row => {
        row.addEventListener('click', function () {
            const target = document.querySelector(this.dataset.target);
            const icon = this.querySelector('i');

            if (target.classList.contains('collapse')) {
                target.classList.remove('collapse');
                target.classList.add('collapsing');
                icon.classList.remove('bi-plus-circle');
                icon.classList.add('bi-dash-circle');
                setTimeout(() => target.classList.remove('collapsing'), 300);
            } else {
                target.classList.add('collapse');
                target.classList.remove('collapsing');
                icon.classList.remove('bi-dash-circle');
                icon.classList.add('bi-plus-circle');
            }
        });
    });
});
</script>
