@extends('layouts.master_layout')

@section('content')

<div class="container d-flex justify-content-center align-items-center py-5">
    <div class="card p-4 shadow-lg w-100" style="max-width: 900px; background: rgba(255, 255, 255, 0.9); border-radius: 12px;">
        <h2 class="text-center fw-bold mb-4">
            <a href="/normal_transactions" class="text-dark text-decoration-none" style="transition: 0.2s;">
                Normal Transactions
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
            <form method="GET" action="/normal_transactions" id="filter_transactions_form">
                <div class="row g-3 align-items-end">
                    <!-- Search Box -->
                    <div class="col-md-3 col-sm-6">
                        <label class="form-label fw-semibold">Search</label>
                        <input type="text" name="name" class="form-control" placeholder="Enter transaction name..." value="{{ request('name') }}">
                    </div>

                    <!-- Direction Filter -->
                    <div class="col-md-2 col-sm-6">
                        <label class="form-label fw-semibold">Direction</label>
                        <select name="direction" class="form-select">
                            <option value="">All Directions</option>
                            <option value="credit" {{ request('direction') == 'credit' ? 'selected' : '' }}>Credit</option>
                            <option value="debit" {{ request('direction') == 'debit' ? 'selected' : '' }}>Debit</option>
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

        <!-- Add Transaction Button -->
        <div class="col-lg-4 col-md-6 col-sm-6 d-grid mb-4">
            <button type="button" class="btn btn-success px-4" data-bs-toggle="modal" data-bs-target="#addNormalTransaction-new">
                <i class="bi bi-plus-lg"></i> Add
            </button>
        </div>
        @include('layouts/add_normal_transaction')

        <div class="table-responsive">
            <table class="table table-striped table-hover text-center align-middle">
                <thead class="bg-primary text-white">
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Price Per Unit</th>
                        <th scope="col">Category</th>
                        <th scope="col">Date</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr>
                            <td class="fw-semibold text-truncate">{{ $transaction->name }}</td>
                            <td class="fw-bold">{{ number_format($transaction->quantity, 2) }}</td>
                            <td class="fw-bold">E£ {{ number_format($transaction->price, 2) }}</td>
                            <td>{{ $transaction->category->name }}</td>
                            <td>{{ date('D d-M-Y', strtotime($transaction->date)) }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary me-2" data-bs-toggle="modal" data-bs-target="#editTransaction{{ $transaction->id }}">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteTransaction{{ $transaction->id }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-muted">No normal transactions found.</td>
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

@push('modals')
    @foreach($transactions as $transaction)
        @include('layouts/edit_normal_transaction', ['transaction' => $transaction, 'modalId' => "editTransaction{$transaction->id}"])
        @include('layouts/delete_normal_transaction', ['transaction' => $transaction, 'modalId' => "deleteTransaction{$transaction->id}"])
    @endforeach
@endpush

<!-- Styles -->
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

<!-- Scripts -->
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
            urlParams.has('month')
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
    });
</script>
