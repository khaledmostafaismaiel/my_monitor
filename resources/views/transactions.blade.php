@extends('layouts.master_layout')

@section('content')

<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card p-4 shadow-lg w-100" style="max-width: 900px; background: rgba(255, 255, 255, 0.9); border-radius: 12px;">
        <h2 class="text-center fw-bold mb-4">
            <a href="{{ route('transactions.index') }}" class="text-dark text-decoration-none" style="transition: 0.2s;">
                Transactions
            </a>
        </h2>

        <!-- Search and Filter Section -->
        <form method="GET" action="{{ route('transactions.index') }}" class="mb-4">
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

                <!-- Month-Year Filter -->
                <div class="col-md-2 col-sm-6">
                    <label class="form-label fw-semibold">Month-Year</label>
                    <select name="month_year" class="form-select">
                        <option value="">All Dates</option>
                        @php
                            $start = new DateTime();
                            $end = new DateTime('2022-02-01');
                            while ($start >= $end) {
                                $value = $start->format('m-y');
                                echo '<option value="' . $value . '"' . (request('month_year') == $value ? ' selected' : '') . '>' . $value . '</option>';
                                $start->modify('-1 month');
                            }
                        @endphp
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
        <div class="d-flex justify-content-end mb-3">
            <button type="button" class="btn btn-success px-4" data-bs-toggle="modal" data-bs-target="#addTransaction">
                <i class="bi bi-plus-lg"></i> Add Transaction
            </button>
        </div>
        @include('layouts/add_transaction')

        <div class="table-responsive">
            <table class="table table-striped table-hover text-center align-middle">
                <thead class="bg-primary text-white">
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Type</th>
                        <th scope="col">Price</th>
                        <th scope="col">Category</th>
                        <th scope="col">Date</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $transaction)
                        <tr>
                            <td class="fw-semibold">{{ $transaction->name }}</td>
                            <td>
                                <span class="badge {{ $transaction->type == 'credit' ? 'bg-success' : 'bg-danger' }}">
                                    {{ ucfirst($transaction->type) }}
                                </span>
                            </td>
                            <td class="fw-bold">E£ {{ number_format($transaction->price, 2) }}</td>
                            <td>{{ $transaction->category->name }}</td>
                            <td>{{ date('D d-M-Y', strtotime($transaction->date)) }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary me-2" data-bs-toggle="modal" data-bs-target="#editTransaction{{ $transaction->id }}">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                @include('layouts/transaction', ['transaction' => $transaction, 'modalId' => "editTransaction{$transaction->id}"])

                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteTransaction{{ $transaction->id }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                                @include('layouts/delete_transaction', ['transaction' => $transaction, 'modalId' => "deleteTransaction{$transaction->id}"])
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $transactions->appends(request()->query())->links() }}
        </div>
    </div>
</div>

@endsection
