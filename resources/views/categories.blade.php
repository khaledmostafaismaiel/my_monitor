@extends('layouts.master_layout')
@section('content')

<div class="container d-flex justify-content-center align-items-center py-5">
    <div class="card p-4 shadow-lg w-100" style="max-width: 900px; background: rgba(255, 255, 255, 0.95); border-radius: 12px;">
        <h2 class="text-center fw-bold mb-4">
            <a href="{{ route('categories.index') }}" class="text-dark text-decoration-none" style="transition: 0.2s;">
                Categories
            </a>
        </h2>

        <!-- Search & Filter Section -->
        <form method="GET" action="{{ route('categories.index') }}" class="mb-3" id="filter_categories_form">
            <div class="row g-3 align-items-center">
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <input type="text" name="name" class="form-control" placeholder="Search by name..." value="{{ request('name') }}">
                </div>

                <div class="col-lg-3 col-md-4 col-sm-6">
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="col-lg-2 col-md-4 col-sm-6 d-grid">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-filter"></i> Apply
                    </button>
                </div>


            </div>
        </form>

        <div class="col-lg-4 col-md-6 col-sm-6 d-grid mb-4">
            <button type="button" class="btn btn-success px-4" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                <i class="bi bi-plus-lg"></i> Add Category
            </button>
        </div>
        @include('layouts/add_category')

        <!-- Table Alignment Fix -->
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
                    @foreach($categories as $category)
                        <tr>
                            <td class="fw-semibold">{{ ucfirst($category->name) }}</td>
                            <td class="fw-semibold">
                                @if($category->status == "active")
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
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
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Centered Pagination -->
        <div class="d-flex justify-content-center mt-3">
            <nav aria-label="Page navigation">
                {{ $categories->links() }}
            </nav>
        </div>
    </div>
</div>

@endsection

@push('modals')
    @foreach($categories as $category)
        @include('layouts/category', ['category' => $category, 'modalId' => "editCategory{$category->id}"])
        @include('layouts/delete_category', ['category' => $category, 'modalId' => "deleteCategory{$category->id}"])
    @endforeach
@endpush


<!-- Styles for Centering & Spacing -->
<style>
    .pagination {
        flex-wrap: wrap;
        justify-content: center;
    }

    /* Fix card positioning */
    .card {
        margin-top: 20px;
    }

    @media (max-width: 768px) {
        .table-responsive {
            overflow-x: auto;
            white-space: nowrap;
        }
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.querySelector("#filter_categories_form");
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
