@extends('layouts.master_layout')

@section('content')
    <div class="container d-flex justify-content-center align-items-center py-5 my-4">
        <div class="card p-4 shadow-lg w-100"
            style="max-width: 900px; background: rgba(255, 255, 255, 0.95); border-radius: 12px;">
            <div class="text-center mb-4">
                <h1 class="fw-bold text-primary mb-2">
                    <i class="bi bi-graph-up-arrow"></i> My Monitor
                </h1>
                <p class="text-muted">Track your financial journey month by month</p>
            </div>

            <!-- Summary Cards -->
            @php
                $totalCredit = $monthYears->sum('credit');
                $totalDebit = $monthYears->sum('debit');
                $totalBalance = $totalCredit - $totalDebit;
            @endphp

            <div class="row g-3 mb-4">
                <!-- Total Balance Card -->
                <div class="col-md-4">
                    <div class="card border-0 h-100 {{ $totalBalance >= 0 ? 'bg-success' : 'bg-danger' }} bg-opacity-10">
                        <div class="card-body text-center">
                            <i
                                class="bi bi-wallet2 {{ $totalBalance >= 0 ? 'text-success' : 'text-danger' }} fs-2 mb-2"></i>
                            <h6 class="text-muted mb-1">Total Balance</h6>
                            <h4 class="fw-bold mb-0 {{ $totalBalance >= 0 ? 'text-success' : 'text-danger' }}">
                                E£ {{ number_format($totalBalance, 2) }}
                            </h4>
                            <small class="text-muted">
                                {{ $totalBalance >= 0 ? 'Positive' : 'Negative' }} Balance
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Total Income Card -->
                <div class="col-md-4">
                    <div class="card border-0 bg-success bg-opacity-10 h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-arrow-down-circle text-success fs-2 mb-2"></i>
                            <h6 class="text-muted mb-1">Total Income</h6>
                            <h4 class="fw-bold text-success mb-0">
                                E£ {{ number_format($totalCredit, 2) }}
                            </h4>
                            <small class="text-muted">All credit transactions</small>
                        </div>
                    </div>
                </div>

                <!-- Total Expenses Card -->
                <div class="col-md-4">
                    <div class="card border-0 bg-danger bg-opacity-10 h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-arrow-up-circle text-danger fs-2 mb-2"></i>
                            <h6 class="text-muted mb-1">Total Expenses</h6>
                            <h4 class="fw-bold text-danger mb-0">
                                E£ {{ number_format($totalDebit, 2) }}
                            </h4>
                            <small class="text-muted">All debit transactions</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add Month/Year Button -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0 fw-semibold">
                    <i class="bi bi-calendar-month"></i> Monthly Overview
                </h5>
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addMonthYearModal">
                    <i class="bi bi-plus-lg"></i> Add Month
                </button>
            </div>


            <div class="table-responsive">
                <table class="table table-striped table-hover text-center align-middle">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>Month</th>
                            <th>Balance</th>
                            <th>Credit</th>
                            <th>Debit</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($monthYears as $monthYear)
                            <tr class="table-secondary">
                                <td class="fw-bold text-start">
                                    <a href="javascript:void(0);" class="monthYear-toggle d-flex align-items-center"
                                        data-target="#monthYear{{ $monthYear->id }}">
                                        <i class="bi bi-plus-circle me-2"></i>
                                        <span>{{ $monthYear->month_year }}</span>
                                    </a>
                                </td>
                                <td class="fw-bold">
                                    @php
                                        $balance = $monthYear->credit - $monthYear->debit;
                                    @endphp
                                    <span class="badge {{ $balance >= 0 ? 'bg-success' : 'bg-danger' }} fs-6">
                                        E£ {{ number_format($balance, 2) }}
                                    </span>
                                </td>
                                <td class="fw-bold">
                                    <span class="badge bg-success bg-opacity-25 text-success fs-6">
                                        +E£ {{ number_format($monthYear->credit, 2) }}
                                    </span>
                                </td>
                                <td class="fw-bold">
                                    <span class="badge bg-danger bg-opacity-25 text-danger fs-6">
                                        -E£ {{ number_format($monthYear->debit, 2) }}
                                    </span>
                                </td>
                                <td>
                                    <!-- Show Button (Redirect to "/") -->
                                    <a href="/month_years/{{ $monthYear->id }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                </td>
                            </tr>

                            <tr id="monthYear{{ $monthYear->id }}" class="collapse">
                                <td colspan="5">
                                    <table class="table table-striped table-hover text-center align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Wallet</th>
                                                <th>Balance</th>
                                                <th>Credit</th>
                                                <th>Debit</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($wallets[$monthYear->id] ?? [] as $wallet)
                                                <tr>
                                                    <td class="fw-semibold text-truncate">
                                                        <i class="bi bi-wallet"></i> {{ $wallet->wallet_name }}
                                                    </td>
                                                    <td class="fw-bold">
                                                        @php
                                                            $walletBalance = $wallet->credit - $wallet->debit;
                                                        @endphp
                                                        <span class="badge {{ $walletBalance >= 0 ? 'bg-success' : 'bg-danger' }}">
                                                            E£ {{ number_format($walletBalance, 2) }}
                                                        </span>
                                                    </td>
                                                    <td class="fw-bold">
                                                        <span class="text-success">+E£
                                                            {{ number_format($wallet->credit, 2) }}</span>
                                                    </td>
                                                    <td class="fw-bold">
                                                        <span class="text-danger">-E£ {{ number_format($wallet->debit, 2) }}</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        @endforeach

                        @if($monthYears->count() === 0)
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <i class="bi bi-calendar-x fs-1 text-muted mb-3 d-block"></i>
                                    <h5 class="text-muted">No months added yet</h5>
                                    <p class="text-muted">Click "Add Month" to start tracking your finances.</p>
                                </td>
                            </tr>
                        @endif
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
    @include('layouts/add_month_year')

@endsection

<style>
    .pagination {
        flex-wrap: wrap;
        justify-content: center;
    }

    .card {
        margin-top: 20px;
    }

    /* Only apply hover effects to page cards, not modals */
    .container>.card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .container>.card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15) !important;
    }

    /* Ensure modal stays stable */
    .modal-content {
        transform: none !important;
        transition: none !important;
    }

    .table-responsive {
        overflow-x: auto;
    }

    table {
        min-width: 600px;
    }

    th,
    td {
        white-space: nowrap;
        word-wrap: break-word;
        min-width: 100px;
    }

    /* Enhanced UX Animations */
    .monthYear-toggle {
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
        color: inherit;
    }

    .monthYear-toggle:hover {
        color: #0d6efd !important;
        transform: translateX(5px);
    }

    .monthYear-toggle i {
        transition: transform 0.3s ease;
    }

    .monthYear-toggle:hover i {
        transform: scale(1.2);
    }

    .table-secondary {
        transition: background-color 0.3s ease;
    }

    .table-secondary:hover {
        background-color: rgba(108, 117, 125, 0.15) !important;
    }

    .badge {
        transition: all 0.2s ease;
    }

    .badge:hover {
        transform: scale(1.05);
    }

    .btn {
        transition: all 0.2s ease;
    }

    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    /* Smooth collapse animation */
    .collapse {
        display: none;
    }

    .collapsing {
        position: relative;
        height: 0;
        overflow: hidden;
        transition: height 0.35s ease;
    }

    /* Fade-in animation */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Apply fade-in only to page content, not modals */
    .container>.card,
    .table-responsive>.table {
        animation: fadeIn 0.5s ease;
    }

    /* Prevent animation on modal content */
    .modal .card,
    .modal-content {
        animation: none !important;
    }

    @media (max-width: 768px) {

        th,
        td {
            min-width: 80px;
        }

        .table thead {
            font-size: 14px;
        }

        .card-body {
            padding: 1rem !important;
        }

        h1 {
            font-size: 1.75rem;
        }

        h5 {
            font-size: 1rem;
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