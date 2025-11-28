@extends('layouts.master_layout')

@section('content')

    <div class="container d-flex justify-content-center align-items-center py-5 my-4">
        <div class="card p-4 shadow-lg w-100"
            style="max-width: 900px; background: rgba(255, 255, 255, 0.95); border-radius: 12px;">
            <div class="text-center mb-4">
                <h1 class="fw-bold text-primary mb-2">
                    <i class="bi bi-wallet2"></i> Wallets
                </h1>
                <p class="text-muted">Manage your financial sources</p>
            </div>

            <!-- Summary Cards -->
            <div class="row g-3 mb-4">
                <!-- Total Wallets -->
                <div class="col-md-4">
                    <div class="card border-0 bg-primary bg-opacity-10 h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-collection text-primary fs-2 mb-2"></i>
                            <h6 class="text-muted mb-1">Total Wallets</h6>
                            <h4 class="fw-bold text-primary mb-0">{{ $wallets->total() }}</h4>
                        </div>
                    </div>
                </div>

                <!-- Active Wallets -->
                <div class="col-md-4">
                    <div class="card border-0 bg-success bg-opacity-10 h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-check-circle text-success fs-2 mb-2"></i>
                            <h6 class="text-muted mb-1">Active</h6>
                            <h4 class="fw-bold text-success mb-0">
                                {{ \App\Models\Wallet::where('family_id', auth()->user()->family_id)->where('status', 'active')->count() }}
                            </h4>
                        </div>
                    </div>
                </div>

                <!-- Inactive Wallets -->
                <div class="col-md-4">
                    <div class="card border-0 bg-secondary bg-opacity-10 h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-archive text-secondary fs-2 mb-2"></i>
                            <h6 class="text-muted mb-1">Inactive</h6>
                            <h4 class="fw-bold text-secondary mb-0">
                                {{ \App\Models\Wallet::where('family_id', auth()->user()->family_id)->where('status', 'inactive')->count() }}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions Bar -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <button type="button" class="btn btn-outline-secondary" id="toggleSearchBtn">
                    <i class="bi bi-search"></i> Search
                </button>
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addWallet">
                    <i class="bi bi-plus-lg"></i> Add Wallet
                </button>
            </div>

            <!-- Search and Filter Section -->
            <div id="searchSection" class="d-none mb-4 p-3 bg-light rounded shadow-sm">
                <form method="GET" action="/wallets" id="filter_wallets_form">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-5">
                            <label class="form-label fw-semibold">Search Name</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                                <input type="text" name="name" class="form-control" placeholder="Enter wallet name..."
                                    value="{{ request('name') }}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select select2">
                                <option value="">All Statuses</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive
                                </option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-filter"></i> Apply Filters
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
                            <th class="py-3">Status</th>
                            <th class="py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($wallets as $wallet)
                            <tr class="wallet-row">
                                <td class="fw-semibold text-start ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3 text-primary">
                                            <i class="bi bi-wallet2"></i>
                                        </div>
                                        {{ $wallet->name }}
                                    </div>
                                </td>
                                <td>
                                    <span
                                        class="badge rounded-pill bg-{{ $wallet->status == 'active' ? 'success' : 'secondary' }} px-3 py-2">
                                        <i
                                            class="bi bi-{{ $wallet->status == 'active' ? 'check-circle' : 'archive' }} me-1"></i>
                                        {{ ucfirst($wallet->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                            data-bs-target="#editWallet{{ $wallet->id }}" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal"
                                            data-bs-target="#deleteWallet{{ $wallet->id }}" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-wallet2 fs-1 d-block mb-3 opacity-50"></i>
                                        <h5>No wallets found</h5>
                                        <p class="mb-0">Create a new wallet to get started</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $wallets->appends(request()->query())->links() }}
            </div>
        </div>
    </div>

@endsection

@push('modals')
    @include('layouts/add_wallet', ['modalId' => "addWallet"])

    @foreach($wallets as $wallet)
        @include('layouts/edit_wallet', ['wallet' => $wallet, 'modalId' => "editWallet{$wallet->id}"])
        @include('layouts/delete_wallet', ['wallet' => $wallet, 'modalId' => "deleteWallet{$wallet->id}"])
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

    .wallet-row {
        transition: background-color 0.2s ease;
    }

    .wallet-row:hover {
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

        .wallet-row {
            display: block;
            margin-bottom: 1rem;
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
            padding: 1rem;
        }

        .wallet-row td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: none;
            padding: 0.5rem 0;
        }

        .wallet-row td::before {
            content: attr(data-label);
            font-weight: bold;
            margin-right: 1rem;
        }
    }
</style>

<!-- Scripts -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const toggleBtn = document.getElementById('toggleSearchBtn');
        const searchSection = document.getElementById('searchSection');
        const form = document.querySelector("#filter_wallets_form");
        const submitButton = form.querySelector("button[type='submit']");

        // Initialize select2
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

        // Show filters on page load if query params exist
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('name') || urlParams.has('status')) {
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