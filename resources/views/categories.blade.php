@extends('layouts.master_layout')

@section('content')
<div class="container d-flex justify-content-center align-items-center py-5">
    <div class="card p-4 shadow-lg w-100" style="max-width: 900px; background: rgba(255, 255, 255, 0.95); border-radius: 12px;">
        <h2 class="text-center fw-bold mb-4">
            <a href="{{ route('categories.index') }}" class="text-dark text-decoration-none" style="transition: 0.2s;">
                Categories
            </a>
        </h2>

        <!-- Toggle Search Button -->
        <div class="d-flex justify-content-end mb-3">
            <button type="button" class="btn btn-outline-secondary" id="toggleSearchBtn">
                <i class="bi bi-search"></i> Search
            </button>
        </div>

        <!-- Search & Filter Section -->
        <div id="searchSection" class="d-none mb-4">
            <form method="GET" action="{{ route('categories.index') }}" class="mb-3" id="filter_categories_form">
                <div class="row g-3 align-items-end">
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <label class="form-label fw-semibold">Search by Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Category name..." value="{{ request('name') }}">
                    </div>

                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select select2">
                            <option value="">All Statuses</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="col-lg-2 d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-filter"></i> Apply
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Add Category Button -->
        <div class="col-lg-4 col-md-6 col-sm-6 d-grid mb-4">
            <button type="button" class="btn btn-success px-4" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                <i class="bi bi-plus-lg"></i> Add
            </button>
        </div>
        @include('layouts/add_category')

        <!-- Category Table -->
        <div class="table-responsive">
            <table class="table table-striped table-hover text-center align-middle">
                <thead class="bg-primary text-white">
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Status</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td class="fw-semibold">{{ ucfirst($category->name) }}</td>
                            <td class="fw-semibold">
                                <span class="badge bg-{{ $category->status == 'active' ? 'success' : 'danger' }}">
                                    {{ ucfirst($category->status) }}
                                </span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary me-2" data-bs-toggle="modal" data-bs-target="#editCategory{{ $category->id }}">
                                    <i class="bi bi-pencil-square"></i>
                                </button>

                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteCategory{{ $category->id }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-muted">No categories found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Centered Pagination -->
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
        @include('layouts/edit_category', ['category' => $category, 'modalId' => "editCategory{$category->id}"])
        @include('layouts/delete_category', ['category' => $category, 'modalId' => "deleteCategory{$category->id}"])
    @endforeach
@endpush

<!-- Responsive Table + Search Toggle Script -->
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
        const form = document.querySelector("#filter_categories_form");
        const submitButton = form.querySelector("button[type='submit']");

        // Initialize select2
        $('.select2').select2({
            width: '100%',
            placeholder: 'All Statuses',
            allowClear: true
        });

        // Toggle filter section
        toggleBtn.addEventListener('click', function () {
            const isShown = !searchSection.classList.contains('d-none');
            searchSection.classList.toggle('d-none');
            toggleBtn.innerHTML = isShown
                ? '<i class="bi bi-search"></i> Search'
                : '<i class="bi bi-x-circle"></i> Close';
        });

        // Show filters on page load if query params exist
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('name') || urlParams.has('status')) {
            searchSection.classList.remove('d-none');
            toggleBtn.innerHTML = '<i class="bi bi-x-circle"></i> Close';
        }

        // Loading state on search
        form.addEventListener("submit", function () {
            submitButton.disabled = true;
            submitButton.innerHTML = `
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Applying...
            `;
        });
    });
</script>
