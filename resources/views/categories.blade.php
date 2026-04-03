@extends('layouts.master_layout')

@section('content')
    <div class="container py-5 mt-5">
        <!-- Header Section -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-5">
            <div class="mb-3 mb-md-0">
                <h1 class="h2 fw-bold text-secondary mb-1" style="font-family: 'Outfit', sans-serif;">Categories</h1>
                <p class="text-muted mb-0">Organize your expenses by category</p>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-secondary shadow-sm" id="toggleSearchBtn">
                    <i class="bi bi-search"></i> Search
                </button>
                <button type="button" class="btn btn-primary-custom shadow-sm" data-bs-toggle="modal"
                    data-bs-target="#addCategoryModal">
                    <i class="bi bi-plus-lg"></i> Add Category
                </button>
            </div>
        </div>

        <!-- Search & Filter Section -->
        <div id="searchSection" class="d-none mb-4">
            <div class="card-custom p-4">
                <form method="GET" action="{{ route('categories.index') }}" id="filter_categories_form">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-secondary small">Search by Name</label>
                            <input type="text" name="name" class="form-control-custom" placeholder="Category name..."
                                value="{{ request('name') }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-secondary small">Status</label>
                            <select name="status" class="form-select select2">
                                <option value="">All Statuses</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive
                                </option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary-custom w-100">
                                <i class="bi bi-filter"></i> Apply
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @include('layouts/add_category')

@php
function renderCategoryRow($category, $level = 0, $collapsed = false) {
    $display = $collapsed ? 'display:none;' : '';
    $hasChildren = $category->children && $category->children->count() > 0;
    $chevronClass = $hasChildren ? ($collapsed ? 'bi-chevron-right' : 'bi-chevron-down') : '';
    $cursorStyle = $hasChildren ? 'cursor:pointer;' : '';
    $iconClass = $hasChildren ? 'text-warning' : 'text-muted';
    $rowClass = $hasChildren ? 'parent-row' : '';
    
    $parentId = $category->parent_id ?? '';
    echo '<tr class="group-row child-row ' . $rowClass . '" data-category-id="' . $category->id . '" data-parent-id="' . $parentId . '" data-children-parent-id="' . $category->id . '" style="' . $display . '">';
    echo '<td class="ps-4 py-3"><div class="d-flex align-items-center gap-2" style="padding-left: ' . ($level * 24) . 'px' . $cursorStyle . '">';
    if ($hasChildren) {
        echo '<i class="bi ' . $chevronClass . ' toggle-icon" style="cursor:pointer;"></i>';
    } else {
        echo '<span style="width:16px;display:inline-block;"></span>';
    }
    echo '<i class="bi bi-folder ' . $iconClass . '"></i>';
    echo '<span class="fw-semibold text-secondary">' . ucfirst($category->name) . '</span>';
    if ($hasChildren) {
        echo '<span class="text-muted small">(' . $category->children->count() . ')</span>';
    }
    echo '</div></td>';
    echo '<td class="py-3"><span class="text-secondary fw-medium">' . ($category->limit ? 'E£ ' . number_format($category->limit, 2) : 'No limit') . '</span></td>';
    $badgeClass = $category->status == 'active' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger';
    echo '<td class="py-3"><span class="badge ' . $badgeClass . ' rounded-pill px-3 py-2">' . ucfirst($category->status) . '</span></td>';
    echo '<td class="pe-4 py-3 text-end">';
    echo '<button type="button" class="btn btn-sm btn-outline-light text-secondary border shadow-sm rounded-pill px-3 me-2" data-bs-toggle="modal" data-bs-target="#editCategory' . $category->id . '"><i class="bi bi-pencil"></i> Edit</button>';
    echo '<button type="button" class="btn btn-sm btn-outline-danger border shadow-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#deleteCategory' . $category->id . '"><i class="bi bi-trash"></i> Delete</button>';
    echo '</td></tr>';
    
    if ($hasChildren) {
        foreach ($category->children as $child) {
            renderCategoryRow($child, $level + 1, $collapsed);
        }
    }
}
@endphp

        <!-- Category Table -->
        <div class="card-custom overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase small fw-bold ls-1 border-0">Name</th>
                            <th class="py-3 text-uppercase small fw-bold ls-1 border-0">Limit</th>
                            <th class="py-3 text-uppercase small fw-bold ls-1 border-0">Status</th>
                            <th class="pe-4 py-3 text-uppercase small fw-bold ls-1 border-0 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($rootCategories as $category)
                            @if($category->children && $category->children->count() > 0)
                                <tr class="group-row bg-light parent-row" data-category-id="{{ $category->id }}">
                                    <td class="ps-4 py-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="bi bi-chevron-right toggle-icon" style="cursor:pointer;"></i>
                                            <i class="bi bi-folder-fill text-primary-custom"></i>
                                            <span class="fw-bold text-secondary">{{ ucfirst($category->name) }}</span>
                                            <span class="text-muted small">({{ $category->children->count() }})</span>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <span class="text-secondary fw-medium">
                                            {{ $category->limit ? 'E£ ' . number_format($category->limit, 2) : 'No limit' }}
                                        </span>
                                    </td>
                                    <td class="py-3">
                                        <span
                                            class="badge {{ $category->status == 'active' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} rounded-pill px-3 py-2">
                                            {{ ucfirst($category->status) }}
                                        </span>
                                    </td>
                                    <td class="pe-4 py-3 text-end">
                                        <button type="button"
                                            class="btn btn-sm btn-outline-light text-secondary border shadow-sm rounded-pill px-3 me-2"
                                            data-bs-toggle="modal" data-bs-target="#editCategory{{ $category->id }}">
                                            <i class="bi bi-pencil"></i> Edit
                                        </button>
                                        <button type="button"
                                            class="btn btn-sm btn-outline-danger border shadow-sm rounded-pill px-3"
                                            data-bs-toggle="modal" data-bs-target="#deleteCategory{{ $category->id }}">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </td>
                                </tr>
                                @php renderCategoryRow($category, 1, true) @endphp
                            @else
                            <tr class="group-row bg-light">
                                <td class="ps-4 py-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-folder-fill text-primary-custom"></i>
                                        <span class="fw-bold text-secondary">{{ ucfirst($category->name) }}</span>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <span class="text-secondary fw-medium">
                                        {{ $category->limit ? 'E£ ' . number_format($category->limit, 2) : 'No limit' }}
                                    </span>
                                </td>
                                <td class="py-3">
                                    <span
                                        class="badge {{ $category->status == 'active' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} rounded-pill px-3 py-2">
                                        {{ ucfirst($category->status) }}
                                    </span>
                                </td>
                                <td class="pe-4 py-3 text-end">
                                    <button type="button"
                                        class="btn btn-sm btn-outline-light text-secondary border shadow-sm rounded-pill px-3 me-2"
                                        data-bs-toggle="modal" data-bs-target="#editCategory{{ $category->id }}">
                                        <i class="bi bi-pencil"></i> Edit
                                    </button>
                                    <button type="button"
                                        class="btn btn-sm btn-outline-danger border shadow-sm rounded-pill px-3"
                                        data-bs-toggle="modal" data-bs-target="#deleteCategory{{ $category->id }}">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="py-4">
                                        <div class="mb-3">
                                            <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle"
                                                style="width: 64px; height: 64px;">
                                                <i class="bi bi-tags text-muted fs-3"></i>
                                            </div>
                                        </div>
                                        <h5 class="text-secondary fw-bold">No Categories Found</h5>
                                        <p class="text-muted mb-3">Start by adding your first category.</p>
                                        <button type="button" class="btn btn-primary-custom" data-bs-toggle="modal"
                                            data-bs-target="#addCategoryModal">
                                            Add First Category
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('modals')
    @foreach($allCategories as $category)
        @include('layouts/edit_category', ['category' => $category, 'modalId' => "editCategory{$category->id}"])
        @include('layouts/delete_category', ['category' => $category, 'modalId' => "deleteCategory{$category->id}"])
    @endforeach
@endpush

<style>
    .ls-1 {
        letter-spacing: 0.05em;
    }

    .bg-success-subtle {
        background-color: #dcfce7 !important;
    }

    .bg-danger-subtle {
        background-color: #fee2e2 !important;
    }

    .table> :not(caption)>*>* {
        background-color: transparent;
        box-shadow: none;
    }

    .group-row:hover {
        background-color: #f8fafc !important;
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
</style>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Use event delegation for dynamically rendered rows
        document.addEventListener('click', function(e) {
            const toggleIcon = e.target.closest('.toggle-icon');
            if (toggleIcon) {
                const row = toggleIcon.closest('tr');
                const categoryId = row.dataset.categoryId || row.dataset.childrenParentId;
                const childRows = document.querySelectorAll('.child-row[data-parent-id="' + categoryId + '"]');
                
                childRows.forEach(function(childRow) {
                    if (childRow.style.display === 'none') {
                        childRow.style.display = '';
                        toggleIcon.classList.remove('bi-chevron-right');
                        toggleIcon.classList.add('bi-chevron-down');
                    } else {
                        childRow.style.display = 'none';
                        toggleIcon.classList.remove('bi-chevron-down');
                        toggleIcon.classList.add('bi-chevron-right');
                    }
                });
            }
        });

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