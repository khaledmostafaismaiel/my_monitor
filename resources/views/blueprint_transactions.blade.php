@extends('layouts.master_layout')

@section('content')
<div class="container py-5 mt-5">
    <!-- Header Section -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-5">
        <div class="mb-3 mb-md-0">
            <h1 class="h2 fw-bold text-secondary mb-1" style="font-family: 'Outfit', sans-serif;">Blueprint Transactions</h1>
            <p class="text-muted mb-0">Templates for recurring transactions</p>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-secondary shadow-sm" id="toggleSearchBtn">
                <i class="bi bi-search"></i> Search
            </button>
            <button type="button" class="btn btn-primary-custom shadow-sm" data-bs-toggle="modal" data-bs-target="#addBlueprintTransaction">
                <i class="bi bi-plus-lg"></i> Add Blueprint
            </button>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div id="searchSection" class="d-none mb-4">
        <div class="card-custom p-4">
            <form method="GET" action="/blueprint_transactions" id="filter_transactions_form">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-secondary small">Search</label>
                        <input type="text" name="name" class="form-control-custom" placeholder="Transaction name..." value="{{ request('name') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold text-secondary small">Direction</label>
                        <select name="direction" class="form-select select2">
                            <option value="">All</option>
                            <option value="credit" {{ request('direction') == 'credit' ? 'selected' : '' }}>Credit</option>
                            <option value="debit" {{ request('direction') == 'debit' ? 'selected' : '' }}>Debit</option>
                        </select>
                    </div>

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

                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary-custom w-100">
                            <i class="bi bi-funnel-fill"></i> Apply
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @include('layouts.add_blueprint_transaction')

    <!-- Blueprint Transactions Table -->
    <div class="card-custom overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-secondary">
                    <tr>
                        <th class="ps-4 py-3 text-uppercase small fw-bold ls-1 border-0">Category</th>
                        <th class="border-0"></th>
                        <th class="border-0"></th>
                        <th class="border-0"></th>
                        <th class="border-0"></th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @foreach($categories as $category)
                        <tr class="group-row bg-light">
                            <td colspan="5" class="ps-4 py-3">
                                <a href="javascript:void(0);" class="category-toggle d-flex align-items-center text-decoration-none" data-target="#category{{ $category->id }}">
                                    <button class="btn btn-sm btn-light rounded-circle shadow-sm p-0 d-flex align-items-center justify-content-center me-2" 
                                            style="width: 24px; height: 24px;">
                                        <i class="bi bi-plus-circle small transition-transform text-primary-custom"></i>
                                    </button>
                                    <span class="fw-bold text-secondary">{{ $category->name }}</span>
                                </a>
                            </td>
                        </tr>

                        <tr id="category{{ $category->id }}" class="collapse">
                            <td colspan="5" class="p-0 border-0">
                                <div class="bg-light p-4">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-body p-0">
                                            <table class="table table-sm mb-0">
                                                <thead class="text-muted">
                                                    <tr>
                                                        <th class="ps-4 py-2 small fw-medium border-0"></th>
                                                        <th class="py-2 small fw-medium border-0">Name</th>
                                                        <th class="py-2 small fw-medium border-0">Price</th>
                                                        <th class="py-2 small fw-medium border-0">Per Unit</th>
                                                        <th class="py-2 small fw-medium border-0">Qty</th>
                                                        <th class="py-2 small fw-medium border-0">Direction</th>
                                                        <th class="pe-4 py-2 small fw-medium border-0 text-end">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($category->blueprintTransactions as $transaction)
                                                        <tr>
                                                            <td class="ps-4 py-2 border-0"></td>
                                                            <td class="py-2 border-0">
                                                                <span class="text-secondary small fw-semibold">{{ $transaction->name }}</span>
                                                            </td>
                                                            <td class="py-2 border-0">
                                                                <span class="small fw-bold text-secondary">E£ {{ number_format($transaction->price * $transaction->quantity, 2) }}</span>
                                                            </td>
                                                            <td class="py-2 border-0">
                                                                <span class="small text-secondary">E£ {{ number_format($transaction->price, 2) }}</span>
                                                            </td>
                                                            <td class="py-2 border-0">
                                                                <span class="small text-secondary">{{ number_format($transaction->quantity, 2) }}</span>
                                                            </td>
                                                            <td class="py-2 border-0">
                                                                @if ($transaction->direction === 'credit')
                                                                    <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1 small">
                                                                        <i class="bi bi-arrow-down-circle"></i> Credit
                                                                    </span>
                                                                @elseif ($transaction->direction === 'debit')
                                                                    <span class="badge bg-danger-subtle text-danger rounded-pill px-2 py-1 small">
                                                                        <i class="bi bi-arrow-up-circle"></i> Debit
                                                                    </span>
                                                                @else
                                                                    <span class="badge bg-secondary rounded-pill px-2 py-1 small">N/A</span>
                                                                @endif
                                                            </td>
                                                            <td class="pe-4 py-2 border-0 text-end">
                                                                <button type="button" class="btn btn-sm btn-outline-success border shadow-sm rounded-pill px-2 me-1" data-bs-toggle="modal" data-bs-target="#addNormalTransaction{{ $transaction->id }}" title="Add as Normal">
                                                                    <i class="bi bi-check-circle"></i>
                                                                </button>
                                                                <button type="button" class="btn btn-sm btn-outline-secondary border shadow-sm rounded-pill px-2 me-1" data-bs-toggle="modal" data-bs-target="#addDraftTransaction{{ $transaction->id }}" title="Add as Draft">
                                                                    <i class="bi bi-file-earmark"></i>
                                                                </button>
                                                                <button type="button" class="btn btn-sm btn-outline-light text-secondary border shadow-sm rounded-pill px-2 me-1" data-bs-toggle="modal" data-bs-target="#editTransaction{{ $transaction->id }}" title="Edit">
                                                                    <i class="bi bi-pencil"></i>
                                                                </button>
                                                                <button type="button" class="btn btn-sm btn-outline-warning border shadow-sm rounded-pill px-2 me-1" data-bs-toggle="modal" data-bs-target="#updateAndAddTransaction{{ $transaction->id }}" title="Add & Edit">
                                                                    <i class="bi bi-pencil-square"></i>
                                                                </button>
                                                                <button type="button" class="btn btn-sm btn-outline-danger border shadow-sm rounded-pill px-2" data-bs-toggle="modal" data-bs-target="#deleteTransaction{{ $transaction->id }}" title="Delete">
                                                                    <i class="bi bi-trash"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($categories->hasPages())
            <div class="p-4 border-top bg-light">
                {{ $categories->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('modals')
    @foreach($categories as $category)
        @foreach ($category->blueprintTransactions as $transaction)
            @include('layouts.add_normal_transaction', ['transaction' => $transaction, 'modalId' => "addNormalTransaction{$transaction->id}"])
            @include('layouts.add_draft_transaction', ['transaction' => $transaction, 'modalId' => "addDraftTransaction{$transaction->id}"])
            @include('layouts.edit_blueprint_transaction', ['transaction' => $transaction, 'modalId' => "editTransaction{$transaction->id}"])
            @include('layouts.update_and_add_transaction', ['transaction' => $transaction, 'modalId' => "updateAndAddTransaction{$transaction->id}"])
            @include('layouts.delete_blueprint_transaction', ['transaction' => $transaction, 'modalId' => "deleteTransaction{$transaction->id}"])
        @endforeach
    @endforeach
@endpush

<style>
    .ls-1 { letter-spacing: 0.05em; }
    
    .bg-success-subtle { background-color: #dcfce7 !important; }
    .bg-danger-subtle { background-color: #fee2e2 !important; }
    
    .transition-transform { transition: transform 0.2s ease; }
    
    .table > :not(caption) > * > * {
        background-color: transparent;
        box-shadow: none;
    }
    
    .group-row:hover {
        background-color: #f1f5f9 !important;
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
    const toggleBtn = document.getElementById('toggleSearchBtn');
    const searchSection = document.getElementById('searchSection');
    const form = document.querySelector("#filter_transactions_form");
    const submitButton = form.querySelector("button[type='submit']");

    toggleBtn.addEventListener('click', function () {
        const isShown = !searchSection.classList.contains('d-none');
        searchSection.classList.toggle('d-none');
        toggleBtn.innerHTML = isShown
            ? '<i class="bi bi-search"></i> Search'
            : '<i class="bi bi-x-circle"></i> Close';
    });

    const urlParams = new URLSearchParams(window.location.search);
    if (
        urlParams.has('name') ||
        urlParams.has('direction') ||
        urlParams.has('category_id')
    ) {
        searchSection.classList.remove('d-none');
        toggleBtn.innerHTML = '<i class="bi bi-x-circle"></i> Close';
    }

    form.addEventListener("submit", function () {
        submitButton.disabled = true;
        submitButton.innerHTML = `
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Searching...
        `;
    });

    $('.select2').select2({
        width: '100%',
        placeholder: 'Select an option',
        allowClear: true
    });

    const categoryRows = document.querySelectorAll('.category-toggle');
    categoryRows.forEach(row => {
        row.addEventListener('click', function (e) {
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
