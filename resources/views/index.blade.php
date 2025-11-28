@extends('layouts.master_layout')

@section('content')
    <div class="container py-5 mt-5">
        <!-- Header Section -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-5">
            <div class="mb-3 mb-md-0">
                <h1 class="h2 fw-bold text-secondary mb-1">Dashboard</h1>
                <p class="text-muted mb-0">Overview of your financial journey</p>
            </div>
            <button type="button" class="btn btn-primary-custom shadow-sm d-flex align-items-center gap-2"
                data-bs-toggle="modal" data-bs-target="#addMonthYearModal">
                <i class="bi bi-plus-lg"></i>
                <span>Add Month</span>
            </button>
        </div>

        <!-- Summary Cards -->
        @php
            $totalCredit = $monthYears->sum('credit');
            $totalDebit = $monthYears->sum('debit');
            $totalBalance = $totalCredit - $totalDebit;
        @endphp

        <div class="row g-4 mb-5">
            <!-- Total Balance -->
            <div class="col-md-4">
                <div class="card-custom p-4 h-100 position-relative overflow-hidden">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="text-muted small text-uppercase fw-bold ls-1 mb-1">Total Balance</p>
                            <h3 class="fw-bold mb-0 {{ $totalBalance >= 0 ? 'text-success' : 'text-danger' }}">
                                E£ {{ number_format($totalBalance, 2) }}
                            </h3>
                        </div>
                        <div
                            class="p-3 rounded-circle {{ $totalBalance >= 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                            <i class="bi bi-wallet2 fs-4"></i>
                        </div>
                    </div>
                    <div class="mt-auto">
                        <span
                            class="badge {{ $totalBalance >= 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} rounded-pill px-3 py-2">
                            <i class="bi {{ $totalBalance >= 0 ? 'bi-arrow-up-right' : 'bi-arrow-down-right' }} me-1"></i>
                            {{ $totalBalance >= 0 ? 'Healthy' : 'Deficit' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Total Income -->
            <div class="col-md-4">
                <div class="card-custom p-4 h-100">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="text-muted small text-uppercase fw-bold ls-1 mb-1">Total Income</p>
                            <h3 class="fw-bold text-secondary mb-0">
                                E£ {{ number_format($totalCredit, 2) }}
                            </h3>
                        </div>
                        <div class="p-3 rounded-circle bg-primary-subtle text-primary-custom">
                            <i class="bi bi-graph-up-arrow fs-4"></i>
                        </div>
                    </div>
                    <div class="mt-auto">
                        <span class="text-muted small">Total earnings to date</span>
                    </div>
                </div>
            </div>

            <!-- Total Expenses -->
            <div class="col-md-4">
                <div class="card-custom p-4 h-100">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="text-muted small text-uppercase fw-bold ls-1 mb-1">Total Expenses</p>
                            <h3 class="fw-bold text-secondary mb-0">
                                E£ {{ number_format($totalDebit, 2) }}
                            </h3>
                        </div>
                        <div class="p-3 rounded-circle bg-warning-subtle text-warning">
                            <i class="bi bi-graph-down-arrow fs-4"></i>
                        </div>
                    </div>
                    <div class="mt-auto">
                        <span class="text-muted small">Total spending to date</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Overview Table -->
        <div class="card-custom overflow-hidden">
            <div class="p-4 border-bottom bg-light">
                <h5 class="fw-bold mb-0 text-secondary">Monthly History</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase small fw-bold ls-1 border-0">Month</th>
                            <th class="py-3 text-uppercase small fw-bold ls-1 border-0">Balance</th>
                            <th class="py-3 text-uppercase small fw-bold ls-1 border-0">Credit</th>
                            <th class="py-3 text-uppercase small fw-bold ls-1 border-0">Debit</th>
                            <th class="pe-4 py-3 text-uppercase small fw-bold ls-1 border-0 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($monthYears as $monthYear)
                            @php
                                $balance = $monthYear->credit - $monthYear->debit;
                            @endphp
                            <tr class="group-row">
                                <td class="ps-4 py-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <a href="javascript:void(0);"
                                            class="monthYear-toggle d-flex align-items-center text-decoration-none"
                                            data-target="#monthYear{{ $monthYear->id }}">
                                            <button
                                                class="btn btn-sm btn-light rounded-circle shadow-sm p-0 d-flex align-items-center justify-content-center me-2"
                                                style="width: 24px; height: 24px;">
                                                <i class="bi bi-plus-circle small transition-transform text-primary-custom"></i>
                                            </button>
                                            <span class="fw-semibold text-secondary">{{ $monthYear->month_year }}</span>
                                        </a>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <span class="fw-bold {{ $balance >= 0 ? 'text-success' : 'text-danger' }}">
                                        E£ {{ number_format($balance, 2) }}
                                    </span>
                                </td>
                                <td class="py-3">
                                    <span class="text-success fw-medium">+E£ {{ number_format($monthYear->credit, 2) }}</span>
                                </td>
                                <td class="py-3">
                                    <span class="text-danger fw-medium">-E£ {{ number_format($monthYear->debit, 2) }}</span>
                                </td>
                                <td class="pe-4 py-3 text-end">
                                    <a href="/month_years/{{ $monthYear->id }}"
                                        class="btn btn-sm btn-outline-light text-secondary border shadow-sm rounded-pill px-3">
                                        View Details
                                    </a>
                                </td>
                            </tr>

                            <!-- Collapsible Details -->
                            <tr id="monthYear{{ $monthYear->id }}" class="collapse">
                                <td colspan="5" class="p-0 border-0">
                                    <div class="bg-light p-4">
                                        <div class="card border-0 shadow-sm">
                                            <div class="card-body p-0">
                                                <table class="table table-sm mb-0">
                                                    <thead class="text-muted">
                                                        <tr>
                                                            <th class="ps-4 py-2 small fw-medium border-0">Wallet</th>
                                                            <th class="py-2 small fw-medium border-0">Balance</th>
                                                            <th class="py-2 small fw-medium border-0">Credit</th>
                                                            <th class="py-2 small fw-medium border-0">Debit</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($wallets[$monthYear->id] ?? [] as $wallet)
                                                            @php
                                                                $walletBalance = $wallet->credit - $wallet->debit;
                                                            @endphp
                                                            <tr>
                                                                <td class="ps-4 py-2 border-0">
                                                                    <div class="d-flex align-items-center gap-2">
                                                                        <i class="bi bi-wallet2 text-muted small"></i>
                                                                        <span
                                                                            class="text-secondary small">{{ $wallet->wallet_name }}</span>
                                                                    </div>
                                                                </td>
                                                                <td class="py-2 border-0">
                                                                    <span
                                                                        class="small fw-semibold {{ $walletBalance >= 0 ? 'text-success' : 'text-danger' }}">
                                                                        E£ {{ number_format($walletBalance, 2) }}
                                                                    </span>
                                                                </td>
                                                                <td class="py-2 border-0">
                                                                    <span class="small text-success">+E£
                                                                        {{ number_format($wallet->credit, 2) }}</span>
                                                                </td>
                                                                <td class="py-2 border-0">
                                                                    <span class="small text-danger">-E£
                                                                        {{ number_format($wallet->debit, 2) }}</span>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                        @if(empty($wallets[$monthYear->id]))
                                                            <tr>
                                                                <td colspan="4" class="text-center py-3 text-muted small">
                                                                    No wallet data available
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="py-4">
                                        <div class="mb-3">
                                            <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle"
                                                style="width: 64px; height: 64px;">
                                                <i class="bi bi-calendar-x text-muted fs-3"></i>
                                            </div>
                                        </div>
                                        <h5 class="text-secondary fw-bold">No Data Found</h5>
                                        <p class="text-muted mb-3">Start tracking your finances by adding a new month.</p>
                                        <button type="button" class="btn btn-primary-custom" data-bs-toggle="modal"
                                            data-bs-target="#addMonthYearModal">
                                            Add First Month
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($monthYears->hasPages())
                <div class="p-4 border-top bg-light">
                    {{ $monthYears->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>

    @include('layouts/add_month_year')

    <style>
        .ls-1 {
            letter-spacing: 0.05em;
        }

        .bg-success-subtle {
            background-color: #dcfce7 !important;
        }

        .bg-danger-subtle {
            background-color: #fee2e2 !important;
        }

        .bg-primary-subtle {
            background-color: #e0e7ff !important;
        }

        .bg-warning-subtle {
            background-color: #fef3c7 !important;
        }

        .transition-transform {
            transition: transform 0.2s ease;
        }

        .table> :not(caption)>*>* {
            background-color: transparent;
            box-shadow: none;
        }

        .group-row:hover {
            background-color: #f8fafc !important;
        }

        /* Styles required for the manual toggle script */
        .collapse {
            display: none;
        }

        .collapsing {
            position: relative;
            height: 0;
            overflow: hidden;
            transition: height 0.35s ease;
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const monthYearRows = document.querySelectorAll('.monthYear-toggle');
            monthYearRows.forEach(row => {
                row.addEventListener('click', function (e) {
                    // Prevent default if it's an anchor tag
                    e.preventDefault();

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
@endsection