@extends('layouts.master_layout')

@section('content')
    <div class="container d-flex justify-content-center align-items-center py-5 my-4">
        <div class="card p-4 shadow-lg w-100"
            style="max-width: 900px; background: rgba(255, 255, 255, 0.95); border-radius: 12px;">
            <div class="text-center mb-4">
                <h1 class="fw-bold text-primary mb-2">
                    <i class="bi bi-tags"></i> Categories
                </h1>
                <p class="text-muted">Organize your transaction types</p>
            </div>

            <!-- Summary Cards -->
            <div class="row g-3 mb-4">
                <!-- Total Categories -->
                <div class="col-md-4">
                    <div class="card border-0 bg-primary bg-opacity-10 h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-collection text-primary fs-2 mb-2"></i>
                            <h6 class="text-muted mb-1">Total Categories</h6>
                            <h4 class="fw-bold text-primary mb-0">{{ $categories->total() }}</h4>
                        </div>
                    </div>
                </div>

                <!-- Active Categories -->
                <div class="col-md-4">
                    <div class="card border-0 bg-success bg-opacity-10 h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-check-circle text-success fs-2 mb-2"></i>
                            <h6 class="text-muted mb-1">Active</h6>
                            <h4 class="fw-bold text-success mb-0">
                                {{ \App\Models\Category::where('family_id', auth()->user()->family_id)->where('status', 'active')->count() }}
                            </h4>
                        </div>
                    </div>
                </div>

                <!-- Inactive Categories -->
                <div class="col-md-4">
                    <div class="card border-0 bg-secondary bg-opacity-10 h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-archive text-secondary fs-2 mb-2"></i>
                            <h6 class="text-muted mb-1">Inactive</h6>
                            <h4 class="fw-bold text-secondary mb-0">
                                {{ \App\Models\Category::where('family_id', auth()->user()->family_id)->where('status', 'inactive')->count() }}
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
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                    <i class="bi bi-plus-lg"></i> Add Category
                </button>
            </div>

            <!-- Search & Filter Section -->
            <div id="searchSection" class="d-none mb-4 p-3 bg-light rounded shadow-sm">
                <form method="GET" action="{{ route('categories.index') }}" class="mb-3" id="filter_categories_form">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-5">
                            <label class="form-label fw-semibold">Search Name</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                                <input type="text" name="name" class="form-control" placeholder="Category name..."
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

            <!-- Category Table -->
            <div class="table-responsive">
                <table class="table table-hover text-center align-middle mb-0">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th class="py-3">Name</th>
                            <th class="py-3">Limit</th>
                            <th class="py-3">Status</th>
                            <th class="py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr class="category-row">
                                <td class="fw-semibold text-start ps-4" data-label="Name">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3 text-primary">
                                            <i class="bi bi-tag"></i>
                                        </div>
                                        {{ ucfirst($category->name) }}
                                    </div>
                                </td>
                                <td class="fw-semibold" data-label="Limit">
                                    @if($category->limit)
                                        <span class="badge bg-info text-dark">
                                            E£ {{ number_format($category->limit, 2) }}
                                        </span>
                                    @else
                                        <span class="text-muted small">No limit</span>
                                    @endif
                                </td>
                                <td data-label="Status">
                                    <span
                                        class="badge rounded-pill bg-{{ $category->status == 'active' ? 'success' : 'secondary' }} px-3 py-2">
                                        <i
                                            class="bi bi-{{ $category->status == 'active' ? 'check-circle' : 'archive' }} me-1"></i>
                                        {{ ucfirst($category->status) }}
                                    </span>
                                </td>
                                <td data-label="Actions">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                            data-bs-target="#editCategory{{ $category->id }}" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal"
                                            data-bs-target="#deleteCategory{{ $category->id }}" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-tags fs-1 d-block mb-3 opacity-50"></i>
                                        <h5>No categories found</h5>
                                        <p class="mb-0">Create a new category to get started</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $categories->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
@endsection

@push('modals')
    @include('layouts/add_category')

    @foreach($categories as $category)
        @include('layouts/edit_category', ['category' => $category, 'modalId' => "editCategory{$category->id}"])
        @include('layouts/delete_category', ['category' => $category, 'modalId' => "deleteCategory{$category->id}"])
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

    .category-row {
        transition: background-color 0.2s ease;
    }

    .category-row:hover {
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

        .category-row {
            display: block;
            margin-bottom: 1rem;
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 0.5rem;
            padding: 1.25rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        }

        .category-row td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: none;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f8f9fa;
        }

        .category-row td:last-child {
            border-bottom: none;
            padding-bottom: 0;
            margin-top: 0.5rem;
            justify-content: center;
        }

        .category-row td::before {
            content: attr(data-label);
            font-weight: 600;
            color: #6c757d;
            font-size: 0.9rem;
            margin-right: auto;
        }

        /* Special handling for the name column to keep icon and text together */
        .category-row td:first-child {
            flex-direction: row;
            justify-content: space-between;
        }

        .category-row td:first-child .d-flex {
            margin-left: auto;
        }
    }
</style>

<!-- Scripts -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const toggleBtn = document.getElementById('toggleSearchBtn');
        const searchSection = document.getElementById('searchSection');
        const form = document.querySelector("#filter_categories_form");
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
                Applying...
            `;
        });
    });
</script>