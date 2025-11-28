@extends('layouts.master_layout')

@section('content')

    <div class="container py-5 mt-5">
        <!-- Header Section -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-5">
            <div class="mb-3 mb-md-0">
                <h1 class="h2 fw-bold text-secondary mb-1" style="font-family: 'Outfit', sans-serif;">Draft Transactions
                </h1>
                <p class="text-muted mb-0">Manage pending and draft transactions</p>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-secondary shadow-sm" id="toggleSearchBtn">
                    <i class="bi bi-search"></i> Search
                </button>
                <button type="button" class="btn btn-primary-custom shadow-sm" data-bs-toggle="modal"
                    data-bs-target="#addDraftTransaction-new">
                    <i class="bi bi-plus-lg"></i> Add Draft
                </button>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div id="searchSection" class="d-none mb-4">
            <div class="card-custom p-4">
                <form method="GET" action="/draft_transactions" id="filter_transactions_form">
                    <div class="row g-3 align-items-end">
                        <!-- Search Box -->
                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-secondary small">Search</label>
                            <input type="text" name="name" class="form-control-custom" placeholder="Transaction name..."
                                value="{{ request('name') }}">
                        </div>

                        <!-- Direction Filter -->
                        <div class="col-md-2">
                            <label class="form-label fw-semibold text-secondary small">Direction</label>
                            <select name="direction" class="form-select select2">
                                <option value="">All</option>
                                <option value="credit" {{ request('direction') == 'credit' ? 'selected' : '' }}>Credit
                                </option>
                                <option value="debit" {{ request('direction') == 'debit' ? 'selected' : '' }}>Debit</option>
                            </select>
                        </div>

                        <!-- Category Filter -->
                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-secondary small">Category</label>
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
                        <div class="col-md-2">
                            <label class="form-label fw-semibold text-secondary small">Wallet</label>
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
                        <div class="col-md-2">
                            <label class="form-label fw-semibold text-secondary small">Year</label>
                            <select name="year" class="form-select select2">
                                <option value="">All Years</option>
                                @foreach($uniqueYears as $year)
                                    <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Month Filter -->
                        <div class="col-md-2">
                            <label class="form-label fw-semibold text-secondary small">Month</label>
                            <select name="month" class="form-select select2">
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
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary-custom w-100">
                                <i class="bi bi-funnel-fill"></i> Apply
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @include('layouts/add_draft_transaction', ['modalId' => "addDraftTransaction-new"])

        <!-- Transactions Table -->
        <div class="card-custom overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase small fw-bold ls-1 border-0">Name</th>
                            <th class="py-3 text-uppercase small fw-bold ls-1 border-0">Price</th>
                            <th class="py-3 text-uppercase small fw-bold ls-1 border-0">Per Unit</th>
                            <th class="py-3 text-uppercase small fw-bold ls-1 border-0">Qty</th>
                            <th class="py-3 text-uppercase small fw-bold ls-1 border-0">Category</th>
                            <th class="py-3 text-uppercase small fw-bold ls-1 border-0">Date</th>
                            <th class="py-3 text-uppercase small fw-bold ls-1 border-0">Direction</th>
                            <th class="pe-4 py-3 text-uppercase small fw-bold ls-1 border-0 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($transactions as $transaction)
                            <tr class="group-row">
                                <td class="ps-4 py-3">
                                    <span class="fw-semibold text-secondary">{{ $transaction->name }}</span>
                                </td>
                                <td class="py-3">
                                    <span class="fw-bold text-secondary">E£
                                        {{ number_format($transaction->price * $transaction->quantity, 2) }}</span>
                                </td>
                                <td class="py-3">
                                    <span class="text-secondary">E£ {{ number_format($transaction->price, 2) }}</span>
                                </td>
                                <td class="py-3">
                                    <span class="text-secondary">{{ number_format($transaction->quantity, 2) }}</span>
                                </td>
                                <td class="py-3">
                                    <span class="text-secondary small">{{ $transaction->category?->name }}</span>
                                </td>
                                <td class="py-3">
                                    <span
                                        class="text-secondary small">{{ date('D d-M-Y', strtotime($transaction->date)) }}</span>
                                </td>
                                <td class="py-3">
                                    @if ($transaction->direction === 'credit')
                                        <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">
                                            <i class="bi bi-arrow-down-circle me-1"></i> Credit
                                        </span>
                                    @elseif ($transaction->direction === 'debit')
                                        <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-2">
                                            <i class="bi bi-arrow-up-circle me-1"></i> Debit
                                        </span>
                                    @else
                                        <span class="badge bg-secondary rounded-pill px-3 py-2">N/A</span>
                                    @endif
                                </td>
                                <td class="pe-4 py-3 text-end">
                                    <button type="button"
                                        class="btn btn-sm btn-outline-success border shadow-sm rounded-pill px-3 me-1"
                                        data-bs-toggle="modal"
                                        data-bs-target="#transferDraftTransaction-{{ $transaction->id }}">
                                        <i class="bi bi-check-circle"></i>
                                    </button>
                                    <button type="button"
                                        class="btn btn-sm btn-outline-light text-secondary border shadow-sm rounded-pill px-3 me-1"
                                        data-bs-toggle="modal" data-bs-target="#editTransaction{{ $transaction->id }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button type="button"
                                        class="btn btn-sm btn-outline-danger border shadow-sm rounded-pill px-3"
                                        data-bs-toggle="modal" data-bs-target="#deleteTransaction{{ $transaction->id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="py-4">
                                        <div class="mb-3">
                                            <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle"
                                                style="width: 64px; height: 64px;">
                                                <i class="bi bi-file-earmark text-muted fs-3"></i>
                                            </div>
                                        </div>
                                        <h5 class="text-secondary fw-bold">No Draft Transactions</h5>
                                        <p class="text-muted mb-3">Start by adding your first draft.</p>
                                        <button type="button" class="btn btn-primary-custom" data-bs-toggle="modal"
                                            data-bs-target="#addDraftTransaction-new">
                                            Add First Draft
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($transactions->hasPages())
                <div class="p-4 border-top bg-light">
                    {{ $transactions->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>

@endsection

@push('modals')
    @foreach($transactions as $transaction)
        @include('layouts/transfer_draft_transaction', ['transaction' => $transaction, 'modalId' => "transferDraftTransaction-{$transaction->id}"])
        @include('layouts/edit_draft_transaction', ['transaction' => $transaction, 'modalId' => "editTransaction{$transaction->id}"])
        @include('layouts/delete_draft_transaction', ['transaction' => $transaction, 'modalId' => "deleteTransaction{$transaction->id}"])
    @endforeach
@endpush

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

    .table> :not(caption)>*>* {
        background-color: transparent;
        box-shadow: none;
    }

    .group-row:hover {
        background-color: #f8fafc !important;
    }

    .form-select {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        transition: all 0.3s ease;
    }

    .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const toggleBtn = document.getElementById('toggleSearchBtn');
        const searchSection = document.getElementById('searchSection');
        const form = document.querySelector("#filter_transactions_form");
        const submitButton = form.querySelector("button[type='submit']");

        // Toggle search section visibility
        toggleBtn.addEventListener('click', function () {
            const isShown = !searchSection.classList.contains('d-none');
            searchSection.classList.toggle('d-none');
            toggleBtn.innerHTML = isShown
                ? '<i class="bi bi-search"></i> Search'
                : '<i class="bi bi-x-circle"></i> Close';
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
        }

        // Add loading indicator to submit
        form.addEventListener("submit", function () {
            submitButton.disabled = true;
            submitButton.innerHTML = `
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Searching...
            `;
        });

        // Initialize select2
        $('.select2').select2({
            width: '100%',
            placeholder: 'Select an option',
            allowClear: true
        });
    });
</script>