@extends('layouts.master_layout')

@section('content')
    <div class="container d-flex justify-content-center align-items-center py-5">
        <div class="card p-4 shadow-lg w-100" style="max-width: 900px; background: rgba(255, 255, 255, 0.9); border-radius: 12px;">
            <h2 class="text-center fw-bold mb-4">
                <a href="/blueprint_transactions" class="text-dark text-decoration-none" style="transition: 0.2s;">
                    Blueprint Transactions
                </a>
            </h2>

            <!-- Toggle Search Button -->
            <div class="d-flex justify-content-end mb-3">
                <button type="button" class="btn btn-outline-secondary" id="toggleSearchBtn">
                    <i class="bi bi-search"></i> Search
                </button>
            </div>

            <!-- Search and Filter Section -->
            <div id="searchSection" class="d-none mb-4">
                <form method="GET" action="/blueprint_transactions" class="mb-4" id="filter_transactions_form">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3 col-sm-6">
                            <label class="form-label fw-semibold">Search</label>
                            <input type="text" name="name" class="form-control" placeholder="Enter transaction name..." value="{{ request('name') }}">
                        </div>

                        <div class="col-md-2 col-sm-6">
                            <label class="form-label fw-semibold">Direction</label>
                            <select name="direction" class="form-select select2">
                                <option value="">All Directions</option>
                                <option value="credit" {{ request('direction') == 'credit' ? 'selected' : '' }}>Credit</option>
                                <option value="debit" {{ request('direction') == 'debit' ? 'selected' : '' }}>Debit</option>
                            </select>
                        </div>

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

                        <div class="col-md-2 d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-funnel-fill"></i> Search
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Add Transaction Button -->
            <div class="col-lg-4 col-md-6 col-sm-6 d-grid mb-4">
                <button type="button" class="btn btn-success px-4" data-bs-toggle="modal" data-bs-target="#addBlueprintTransaction">
                    <i class="bi bi-plus-lg"></i> Add
                </button>
            </div>
            @include('layouts.add_blueprint_transaction')

            <div class="table-responsive">
                <table class="table table-striped table-hover text-center align-middle">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>Category</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                            <tr class="table-secondary">
                                <td colspan="5" class="fw-bold text-start">
                                    <a href="javascript:void(0);" class="category-toggle d-flex align-items-center" data-target="#category{{ $category->id }}">
                                        <i class="bi bi-plus-circle me-2"></i> {{ $category->name }}
                                    </a>
                                </td>
                            </tr>

                            <tr id="category{{ $category->id }}" class="collapse">
                                <td colspan="5">
                                    <table class="table table-striped table-hover text-center align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th></th>
                                                <th>Name</th>
                                                <th>Price</th>
                                                <th>Price Per Unit</th>
                                                <th>Quantity</th>
                                                <th>Direction</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($category->blueprintTransactions as $transaction)
                                                <tr>
                                                    <td class="fw-bold"></td>
                                                    <td class="fw-semibold text-truncate">{{ $transaction->name }}</td>
                                                    <td class="fw-bold">E£ {{ number_format($transaction->price * $transaction->quantity, 2) }}</td>
                                                    <td class="fw-bold">E£ {{ number_format($transaction->price, 2) }}</td>
                                                    <td class="fw-bold">{{ number_format($transaction->quantity, 2) }}</td>
                                                    <td>
                                                        @if ($transaction->direction === 'credit')
                                                            <span class="badge bg-success">
                                                                <i class="bi bi-arrow-down-circle me-1"></i> Credit
                                                            </span>
                                                        @elseif ($transaction->direction === 'debit')
                                                            <span class="badge bg-danger">
                                                                <i class="bi bi-arrow-up-circle me-1"></i> Debit
                                                            </span>
                                                        @else
                                                            <span class="badge bg-secondary">N/A</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-success me-2" data-bs-toggle="modal" data-bs-target="#addNormalTransaction{{ $transaction->id }}">
                                                            Normal
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-dark me-2" data-bs-toggle="modal" data-bs-target="#addDraftTransaction{{ $transaction->id }}">
                                                            Draft
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-primary me-2" data-bs-toggle="modal" data-bs-target="#editTransaction{{ $transaction->id }}">
                                                            Edit
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-warning me-2" data-bs-toggle="modal" data-bs-target="#updateAndAddTransaction{{ $transaction->id }}">
                                                            Normal Then Edit
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteTransaction{{ $transaction->id }}">
                                                            Delete
                                                        </button>
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

            <!-- Centered Pagination with filters preserved -->
            <div class="d-flex justify-content-center mt-3">
                <nav aria-label="Page navigation">
                    {{ $categories->appends(request()->query())->links() }}
                </nav>
            </div>
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
