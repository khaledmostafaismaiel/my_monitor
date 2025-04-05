@extends('layouts.master_layout')

@section('content')

<div class="container d-flex justify-content-center align-items-center py-5">
    <div class="card p-4 shadow-lg w-100" style="max-width: 900px; background: rgba(255, 255, 255, 0.9); border-radius: 12px;">
        <h2 class="text-center fw-bold mb-4">
            <a href="/blueprint_transactions" class="text-dark text-decoration-none" style="transition: 0.2s;">
                Blueprint Transactions
            </a>
        </h2>

        <!-- Search and Filter Section -->
        <form method="GET" action="/blueprint_transactions" class="mb-4" id="filter_transactions_form">
            <div class="row g-3 align-items-end">
                <!-- Search Box -->
                <div class="col-md-3 col-sm-6">
                    <label class="form-label fw-semibold">Search</label>
                    <input type="text" name="name" class="form-control" placeholder="Enter transaction name..." value="{{ request('name') }}">
                </div>

                <!-- Type Filter -->
                <div class="col-md-2 col-sm-6">
                    <label class="form-label fw-semibold">Type</label>
                    <select name="type" class="form-select">
                        <option value="">All Types</option>
                        <option value="credit" {{ request('type') == 'credit' ? 'selected' : '' }}>Credit</option>
                        <option value="debit" {{ request('type') == 'debit' ? 'selected' : '' }}>Debit</option>
                    </select>
                </div>

                <!-- Category Filter -->
                <div class="col-md-3 col-sm-6">
                    <label class="form-label fw-semibold">Category</label>
                    <select name="category_id" class="form-select">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Apply Filter Button -->
                <div class="col-md-2 d-grid">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-funnel-fill"></i> Apply
                    </button>
                </div>
            </div>
        </form>

        <!-- Add Transaction Button -->
        <div class="col-lg-4 col-md-6 col-sm-6 d-grid mb-4">
            <button type="button" class="btn btn-success px-4" data-bs-toggle="modal" data-bs-target="#addBlueprintTransaction">
                <i class="bi bi-plus-lg"></i> Add Blueprint Transaction
            </button>
        </div>
        @include('layouts/add_blueprint_transaction')

        <div class="table-responsive">
            <table class="table table-striped table-hover text-center align-middle">
                <thead class="bg-primary text-white">
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Price Per Unit</th>
                        <th scope="col">Category</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $grouped = $transactions->getCollection()->groupBy(function ($t) {
                            return optional($t->category)->name ?? 'Uncategorized';
                        });
                    @endphp


                    @forelse($grouped as $categoryName => $categoryTransactions)
                        <tr class="table-secondary">
                            <td colspan="6" class="fw-bold text-start">
                                <i class="bi bi-folder-fill me-1 text-muted"></i>
                                {{ $categoryName }}
                            </td>
                        </tr>

                        @foreach ($categoryTransactions as $transaction)
                            <tr>
                                <td class="fw-semibold text-truncate">{{ $transaction->name }}</td>
                                <td class="fw-bold">{{ number_format($transaction->quantity, 2) }}</td>
                                <td class="fw-bold">E£ {{ number_format($transaction->price, 2) }}</td>
                                <td>{{ optional($transaction->category)->name ?? '—' }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-success me-2" data-bs-toggle="modal" data-bs-target="#addNormalTransaction-{{ $transaction->id }}">
                                        <i class="bi bi-plus-circle"></i>
                                    </button>
                                    @include('layouts.add_normal_transaction', ['transaction' => $transaction])

                                    <button type="button" class="btn btn-sm btn-primary me-2" data-bs-toggle="modal" data-bs-target="#editTransaction{{ $transaction->id }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    @include('layouts.edit_blueprint_transaction', ['transaction' => $transaction, 'modalId' => "editTransaction{$transaction->id}"])

                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteTransaction{{ $transaction->id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    @include('layouts.delete_blueprint_transaction', ['transaction' => $transaction, 'modalId' => "deleteTransaction{$transaction->id}"])
                                </td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="6" class="text-muted">No blueprint transactions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- Centered Pagination -->
        <div class="d-flex justify-content-center mt-3">
            <nav aria-label="Page navigation">
                {{ $transactions->links() }}
            </nav>
        </div>
    </div>
</div>

@endsection

<!-- Styles for Responsive Table -->
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

    /* Make table cells more responsive */
    table {
        min-width: 600px; /* Prevents columns from shrinking too much */
    }

    th, td {
        white-space: nowrap;
        word-wrap: break-word;
        min-width: 100px; /* Ensures each column has a minimum width */
    }

    @media (max-width: 768px) {
        .table-responsive {
            overflow-x: auto;
        }

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
        const form = document.querySelector("#filter_transactions_form");
        const submitButton = form.querySelector("button[type='submit']");

        form.addEventListener("submit", function () {
            submitButton.disabled = true;
            submitButton.innerHTML = `
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Appling...
            `;
        });
    });
</script>
