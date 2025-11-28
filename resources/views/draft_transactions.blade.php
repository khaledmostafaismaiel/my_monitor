@extends('layouts.master_layout')

@section('content')

    <div class="container d-flex justify-content-center align-items-center py-5 my-4">
        <div class="card p-4 shadow-lg w-100"
            style="max-width: 1100px; background: rgba(255, 255, 255, 0.95); border-radius: 12px;">
            <div class="text-center mb-4">
                <h1 class="fw-bold text-primary mb-2">
                    <i class="bi bi-file-earmark-text"></i> Draft Transactions
                </h1>
                <p class="text-muted">Manage your pending transactions</p>
            </div>

            <!-- Summary Cards -->
            <div class="row g-3 mb-4">
                <!-- Total Drafts -->
                <div class="col-md-4">
                    <div class="card border-0 bg-primary bg-opacity-10 h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-files text-primary fs-2 mb-2"></i>
                            <h6 class="text-muted mb-1">Total Drafts</h6>
                            <h4 class="fw-bold text-primary mb-0">{{ $total_drafts_count }}</h4>
                        </div>
                    </div>
                </div>

                <!-- Estimated Income -->
                <div class="col-md-4">
                    <div class="card border-0 bg-success bg-opacity-10 h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-arrow-down-circle text-success fs-2 mb-2"></i>
                            <h6 class="text-muted mb-1">Est. Income</h6>
                            <h4 class="fw-bold text-success mb-0">E£ {{ number_format($total_estimated_income, 2) }}</h4>
                        </div>
                    </div>
                </div>

                <!-- Estimated Expenses -->
                <div class="col-md-4">
                    <div class="card border-0 bg-danger bg-opacity-10 h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-arrow-up-circle text-danger fs-2 mb-2"></i>
                            <h6 class="text-muted mb-1">Est. Expenses</h6>
                            <h4 class="fw-bold text-danger mb-0">E£ {{ number_format($total_estimated_expense, 2) }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions Bar -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <button type="button" class="btn btn-outline-secondary" id="toggleSearchBtn">
                    <i class="bi bi-search"></i> Search
                </button>
                <button type="button" class="btn btn-success" data-bs-toggle="modal"
                    data-bs-target="#addDraftTransaction-new">
                    <i class="bi bi-plus-lg"></i> Add Draft
                </button>
            </div>

            <!-- Search and Filter Section -->
            <div id="searchSection" class="d-none mb-4 p-3 bg-light rounded shadow-sm">
                <form method="GET" action="/draft_transactions" id="filter_transactions_form">
                    <div class="row g-3 align-items-end">
                        <!-- Search Box -->
                        <div class="col-md-3 col-sm-6">
                            <label class="form-label fw-semibold">Search</label>
                            <input type="text" name="name" class="form-control" placeholder="Enter transaction name..."
                                value="{{ request('name') }}">
                        </div>

                        <!-- Direction Filter -->
                        <div class="col-md-2 col-sm-6">
                            <label class="form-label fw-semibold">Direction</label>
                            <select name="direction" class="form-select">
                                <option value="">All Directions</option>
                                <option value="credit" {{ request('direction') == 'credit' ? 'selected' : '' }}>Credit
                                </option>
                                <option value="debit" {{ request('direction') == 'debit' ? 'selected' : '' }}>Debit</option>
                            </select>
                        </div>
                        <!-- Category Filter -->
                        <div class="col-md-3 col-sm-6">
                            <label class="form-label fw-semibold">Category</label>
                            <select name="category_id" class="form-select select2">
                                <option value="">All Categories</option>
                                @foreach($all_categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Wallet Filter -->
                        <div class="col-md-3 col-sm-6">
                            <label class="form-label fw-semibold">Wallet</label>
                            <select name="wallet_id" class="form-select select2">
                                <option value="">All Wallets</option>
                                @foreach($all_wallets as $wallet)
                                    <option value="{{ $wallet->id }}" {{ request('wallet_id') == $wallet->id ? 'selected' : '' }}>
                                        {{ $wallet->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Year Filter -->
                        <div class="col-md-2 col-sm-6">
                            <label class="form-label fw-semibold">Year</label>
                            <select name="year" class="form-select">
                                <option value="">All Years</option>
                                @foreach($uniqueYears as $year)
                                    <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Month Filter -->
                        <div class="col-md-2 col-sm-6">
                            <label class="form-label fw-semibold">Month</label>
                            <select name="month" class="form-select">
                                <option value="">All Months</option>
                                @php
                                    for ($month = 1; $month <= 12; $month++) {
                                        $monthValue = str_pad($month, 2, '0', STR_PAD_LEFT);
                                        echo '<option value="' . $monthValue . '"' . (request('month') == $monthValue ? ' selected' : '') . '>' . date('F', mktime(0, 0, 0, $month, 1)) . '</option>';
                                    }
                                @endphp
                            </select>
                        </div>

                        <!-- Search Filter Button -->
                        <div class="col-md-2 d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-funnel-fill"></i> Search
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-hover text-center align-middle mb-0">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th class="py-3">Name</th>
                            <th class="py-3">Total</th>
                            <th class="py-3">Unit Price</th>
                            <th class="py-3">Qty</th>
                            <th class="py-3">Category</th>
                            <th class="py-3">Date</th>
                            <th class="py-3">Direction</th>
                            <th class="py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $transaction)
                            <tr class="transaction-row">
                                <td class="fw-semibold text-start ps-4" data-label="Name">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3 text-primary">
                                            <i class="bi bi-file-earmark-text"></i>
                                        </div>
                                        {{ $transaction->name }}
                                    </div>
                                </td>
                                <td class="fw-bold" data-label="Total">E£
                                    {{ number_format($transaction->price * $transaction->quantity, 2) }}</td>
                                <td class="text-muted" data-label="Unit Price">E£ {{ number_format($transaction->price, 2) }}
                                </td>
                                <td data-label="Quantity">{{ number_format($transaction->quantity, 2) }}</td>
                                <td data-label="Category">
                                    <span class="badge bg-info text-dark">{{ $transaction->category?->name }}</span>
                                </td>
                                <td data-label="Date">{{ date('d M Y', strtotime($transaction->date)) }}</td>
                                <td data-label="Direction">
                                    @if ($transaction->direction === 'credit')
                                        <span class="badge rounded-pill bg-success px-3 py-2">
                                            <i class="bi bi-arrow-down-circle me-1"></i> Credit
                                        </span>
                                    @elseif ($transaction->direction === 'debit')
                                        <span class="badge rounded-pill bg-danger px-3 py-2">
                                            <i class="bi bi-arrow-up-circle me-1"></i> Debit
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">N/A</span>
                                    @endif
                                </td>
                                <td data-label="Actions">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal"
                                            data-bs-target="#transferDraftTransaction-{{ $transaction->id }}"
                                            title="Make Normal">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                            data-bs-target="#editTransaction{{ $transaction->id }}" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal"
                                            data-bs-target="#deleteTransaction{{ $transaction->id }}" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-file-earmark-text fs-1 d-block mb-3 opacity-50"></i>
                                        <h5>No draft transactions found</h5>
                                        <p class="mb-0">Add a new draft to get started</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $transactions->appends(request()->query())->links() }}
            </div>
        </div>
    </div>

@endsection

@push('modals')
    @include('layouts/add_draft_transaction', ['modalId' => "addDraftTransaction-new"])

    @foreach($transactions as $transaction)
        @include('layouts/transfer_draft_transaction', ['transaction' => $transaction, 'modalId' => "transferDraftTransaction-{$transaction->id}"])
        @include('layouts/edit_draft_transaction', ['transaction' => $transaction, 'modalId' => "editTransaction{$transaction->id}"])
        @include('layouts/delete_draft_transaction', ['transaction' => $transaction, 'modalId' => "deleteTransaction{$transaction->id}"])
    @endforeach
@endpush

<!-- Styles -->
<style>
    .pagination {
        flex-wrap: wrap;
        justify-content: center;
    }

    /* Only apply hover effects to page cards, not modals */
    .container>.card {
        margin-top: 20px;
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
        animation: none !important;
    }

    .transaction-row {
        transition: background-color 0.2s ease;
    }

    .transaction-row:hover {
        background-color: rgba(13, 110, 253, 0.05) !important;
    }

    .btn-group .btn {
        transition: all 0.2s ease;
    }

    .btn-group .btn:hover {
        transform: translateY(-2px);
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

    .container>.card {
        animation: fadeIn 0.5s ease;
    }

    @media (max-width: 768px) {
        .table thead {
            display: none;
        }

        .transaction-row {
            display: block;
            margin-bottom: 1rem;
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 0.5rem;
            padding: 1.25rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        }

        .transaction-row td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: none;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f8f9fa;
        }

        .transaction-row td:last-child {
            border-bottom: none;
            padding-bottom: 0;
            margin-top: 0.5rem;
            justify-content: center;
        }

        .transaction-row td::before {
            content: attr(data-label);
            font-weight: 600;
            color: #6c757d;
            font-size: 0.9rem;
            margin-right: auto;
        }

        /* Special handling for the name column */
        .transaction-row td:first-child {
            flex-direction: row;
            justify-content: space-between;
        }

        .transaction-row td:first-child .d-flex {
            margin-left: auto;
        }
    }
</style>

<!-- Scripts -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const toggleBtn = document.getElementById('toggleSearchBtn');
        const searchSection = document.getElementById('searchSection');
        const form = document.querySelector("#filter_transactions_form");
        const submitButton = form.querySelector("button[type='submit']");

        // Initialize Select2 for dropdowns
        $('.select2').select2({
            width: '100%',
            placeholder: 'Select an option',
            allowClear: true,
            dropdownParent: $('#searchSection')
        });

        // Toggle search section visibility
        toggleBtn.addEventListener('click', function () {
            const isHidden = searchSection.classList.contains('d-none');
            if (isHidden) {
                searchSection.classList.remove('d-none');
                // Small animation for opening
                searchSection.style.opacity = '0';
                searchSection.style.transform = 'translateY(-10px)';
                searchSection.style.transition = 'all 0.3s ease';
                setTimeout(() => {
                    searchSection.style.opacity = '1';
                    searchSection.style.transform = 'translateY(0)';
                }, 10);
                toggleBtn.innerHTML = '<i class="bi bi-x-circle"></i> Close';
                toggleBtn.classList.replace('btn-outline-secondary', 'btn-outline-danger');
            } else {
                searchSection.classList.add('d-none');
                toggleBtn.innerHTML = '<i class="bi bi-search"></i> Search';
                toggleBtn.classList.replace('btn-outline-danger', 'btn-outline-secondary');
            }
        });

        // Show search section on page load if filters exist
        const urlParams = new URLSearchParams(window.location.search);
        if (
            urlParams.has('name') ||
            urlParams.has('direction') ||
            urlParams.has('category_id') ||
            urlParams.has('year') ||
            urlParams.has('month') ||
            urlParams.has('wallet_id')
        ) {
            searchSection.classList.remove('d-none');
            toggleBtn.innerHTML = '<i class="bi bi-x-circle"></i> Close';
            toggleBtn.classList.replace('btn-outline-secondary', 'btn-outline-danger');
        }

        // Add loading indicator to submit
        form.addEventListener("submit", function () {
            submitButton.disabled = true;
            submitButton.innerHTML = `
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Searching...
            `;
        });
    });
</script>