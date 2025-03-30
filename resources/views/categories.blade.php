@extends('layouts.master_layout')
@section('content')

<div class="container d-flex justify-content-center align-items-center mt-5">
    <div class="card p-4 shadow-lg w-100" style="max-width: 900px; background: rgba(255, 255, 255, 0.9); border-radius: 12px;">
        <h2 class="text-center fw-bold mb-4">
            <a href="{{ route('categories.index') }}" class="text-dark text-decoration-none" style="transition: 0.2s;">
                Categories
            </a>
        </h2>
        <!-- Search & Filter Section -->
        <form method="GET" action="{{ route('categories.index') }}" class="mb-3">
            <div class="row g-2 align-items-center">
                <!-- Search Box -->
                <div class="col-md-4">
                    <input type="text" name="name" class="form-control" placeholder="Search by name..." value="{{ request('name') }}">
                </div>

                <!-- Apply Button -->
                <div class="col-md-2 d-grid">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-filter"></i> Apply
                    </button>
                </div>

                <!-- Add Category Button -->
                <div class="col-md-3 d-grid">
                    <button type="button" class="btn btn-success px-4" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                        <i class="bi bi-plus-lg"></i> Add Category
                    </button>
                </div>
            </div>
        </form>

        @include('layouts/add_category')

        <div class="table-responsive">
            <table class="table table-striped table-hover text-center">
                <thead class="bg-primary text-white">
                    <tr>
                        <th scope="col">Category Name</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                        <tr>
                            <td class="fw-semibold">{{ ucfirst($category->name) }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary me-2" data-bs-toggle="modal" data-bs-target="#editCategory{{ $category->id }}">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                @include('layouts/category', ['category' => $category, 'modalId' => "editCategory{$category->id}"])

                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteCategory{{ $category->id }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                                @include('layouts/delete_category', ['category' => $category, 'modalId' => "deleteCategory{$category->id}"])
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{$categories->links()}}
        </div>
    </div>
</div>

@endsection
