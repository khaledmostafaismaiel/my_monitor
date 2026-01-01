@extends('layouts.master_layout')

@section('content')

    <div class="container py-5 mt-5">
        <!-- Header Section -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-5">
            <div class="mb-3 mb-md-0">
                <h1 class="h2 fw-bold text-secondary mb-1" style="font-family: 'Outfit', sans-serif;">Todos</h1>
                <p class="text-muted mb-0">Manage your family tasks and goals</p>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-secondary shadow-sm" id="toggleSearchBtn">
                    <i class="bi bi-search"></i> Search
                </button>
                <button type="button" class="btn btn-primary-custom shadow-sm" data-bs-toggle="modal"
                    data-bs-target="#addTodo">
                    <i class="bi bi-plus-lg"></i> Add Todo
                </button>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div id="searchSection" class="d-none mb-4">
            <div class="card-custom p-4">
                <form method="GET" action="/todos" id="filter_todos_form">
                    <div class="row g-3 align-items-end">
                        <!-- Search Box -->
                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-secondary small">Search</label>
                            <input type="text" name="title" class="form-control-custom" placeholder="Enter todo title..."
                                value="{{ request('title') }}">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fw-semibold text-secondary small">Status</label>
                            <select name="status" class="form-select select2">
                                <option value="">All Statuses</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fw-semibold text-secondary small">Priority</label>
                            <select name="priority" class="form-select select2">
                                <option value="">All Priorities</option>
                                <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                                <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fw-semibold text-secondary small">Scope</label>
                            <select name="scope" class="form-select select2">
                                <option value="">All Scopes</option>
                                <option value="private" {{ request('scope') == 'private' ? 'selected' : '' }}>Private</option>
                                <option value="public" {{ request('scope') == 'public' ? 'selected' : '' }}>Public</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fw-semibold text-secondary small">Month/Year</label>
                            <select name="month_year_id" class="form-select select2">
                                <option value="">All Periods</option>
                                @foreach($all_month_years as $my)
                                    <option value="{{ $my->id }}" {{ request('month_year_id') == $my->id ? 'selected' : '' }}>
                                        {{ $my->month }}/{{ $my->year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-1">
                            <button type="submit" class="btn btn-primary-custom w-100">
                                <i class="bi bi-filter"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @include('layouts/add_todo', ['modalId' => "addTodo", 'all_month_years' => $all_month_years, 'allTodos' => $allTodos])

        <!-- Todos List -->
        <div class="card-custom overflow-hidden">
            <div class="todo-list p-3 sortable-container" id="root-todos" data-parent-id="">
                @forelse($todos as $todo)
                    @include('layouts/todo_item', ['todo' => $todo, 'level' => 0])
                @empty
                    <div class="text-center py-5">
                        <div class="py-4">
                            <div class="mb-3">
                                <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle"
                                    style="width: 64px; height: 64px;">
                                    <i class="bi bi-check2-square text-muted fs-3"></i>
                                </div>
                            </div>
                            <h5 class="text-secondary fw-bold">No Todos Found</h5>
                            <p class="text-muted mb-3">Start by adding your first todo.</p>
                            <button type="button" class="btn btn-primary-custom" data-bs-toggle="modal"
                                data-bs-target="#addTodo">
                                Add First Todo
                            </button>
                        </div>
                    </div>
                @endforelse
            </div>

            @if($todos->hasPages())
                <div class="p-4 border-top bg-light">
                    {{ $todos->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>

@endsection

@push('modals')
    @foreach($todos as $todo)
        @include('layouts/todo_modals', ['todo' => $todo, 'all_month_years' => $all_month_years, 'allTodos' => $allTodos])
    @endforeach
@endpush

<style>
    .ls-1 {
        letter-spacing: 0.05em;
    }

    .todo-item {
        border-left: 3px solid transparent;
        transition: all 0.2s ease;
    }

    .todo-item:hover {
        background-color: #f8fafc !important;
    }

    .todo-item.priority-high {
        border-left-color: #ef4444;
    }

    .todo-item.priority-medium {
        border-left-color: #3b82f6;
    }

    .todo-item.priority-low {
        border-left-color: #9ca3af;
    }

    .todo-item.status-completed {
        opacity: 0.7;
    }

    .todo-item.status-completed .todo-title {
        text-decoration: line-through;
    }

    .todo-children {
        border-left: 2px dashed #e2e8f0;
        margin-left: 1rem;
        padding-left: 1rem;
    }

    .badge-priority-high {
        background-color: #fee2e2 !important;
        color: #dc2626 !important;
    }

    .badge-priority-medium {
        background-color: #dbeafe !important;
        color: #2563eb !important;
    }

    .badge-priority-low {
        background-color: #f3f4f6 !important;
        color: #6b7280 !important;
    }

    .badge-status-pending {
        background-color: #fef3c7 !important;
        color: #d97706 !important;
    }

    .badge-status-in_progress {
        background-color: #dbeafe !important;
        color: #2563eb !important;
    }

    .badge-status-completed {
        background-color: #dcfce7 !important;
        color: #16a34a !important;
    }

    .badge-scope-private {
        background-color: #fce7f3 !important;
        color: #db2777 !important;
    }

    .badge-scope-public {
        background-color: #e0f2fe !important;
        color: #0284c7 !important;
    }

    .overdue {
        color: #dc2626 !important;
        font-weight: 600;
    }

    .toggle-children {
        cursor: pointer;
        text-decoration: none;
    }

    .toggle-children .collapse-icon {
        transition: transform 0.2s ease;
        display: inline-block;
    }

    .toggle-children.collapsed .collapse-icon {
        transform: rotate(-90deg);
    }

    .todo-children.hidden {
        display: none;
    }

    .drag-handle {
        opacity: 0.5;
        transition: opacity 0.2s;
        touch-action: none;
        padding: 4px;
    }

    .todo-item:hover .drag-handle {
        opacity: 1;
    }

    .drag-handle:active {
        cursor: grabbing;
        opacity: 1;
    }

    @media (max-width: 767.98px) {
        .drag-handle {
            opacity: 0.7;
            padding: 2px 4px;
            font-size: 1rem;
        }
    }

    .todo-item.sortable-ghost {
        opacity: 0.4;
        background: #e2e8f0;
    }

    .todo-item.sortable-chosen {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .todo-item.sortable-fallback {
        opacity: 0.9;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }

    .todo-actions {
        flex-shrink: 0;
    }

    @media (max-width: 767.98px) {
        .todo-actions {
            align-self: center;
        }
        .todo-actions .btn {
            padding: 0.25rem 0.5rem !important;
            font-size: 0.875rem;
        }
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

    .progress-mini {
        height: 4px;
        border-radius: 2px;
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const toggleBtn = document.getElementById('toggleSearchBtn');
        const searchSection = document.getElementById('searchSection');
        const form = document.querySelector("#filter_todos_form");
        const submitButton = form?.querySelector("button[type='submit']");

        // Initialize select2
        $('.select2').select2({
            width: '100%',
            placeholder: 'Select an option',
            allowClear: true
        });

        // Toggle search section visibility
        toggleBtn.addEventListener('click', function () {
            const isShown = !searchSection.classList.contains('d-none');
            searchSection.classList.toggle('d-none');
            toggleBtn.innerHTML = isShown
                ? '<i class="bi bi-search"></i> Search'
                : '<i class="bi bi-x-circle"></i> Close';
        });

        // Show filters on page load if query params exist
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('title') || urlParams.has('status') || urlParams.has('priority') || urlParams.has('scope') || urlParams.has('month_year_id')) {
            searchSection.classList.remove('d-none');
            toggleBtn.innerHTML = '<i class="bi bi-x-circle"></i> Close';
        }

        // Add loading indicator to submit
        if (form && submitButton) {
            form.addEventListener("submit", function () {
                submitButton.disabled = true;
                submitButton.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
            });
        }

        // Handle children toggle
        document.querySelectorAll('.toggle-children').forEach(function(toggle) {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                var targetId = this.getAttribute('data-target');
                var target = document.getElementById(targetId);

                if (target) {
                    target.classList.toggle('hidden');
                    this.classList.toggle('collapsed');
                }
            });
        });

        // Initialize SortableJS on all sortable containers
        function initSortable(container) {
            new Sortable(container, {
                group: 'todos',
                animation: 150,
                handle: '.drag-handle',
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                dragClass: 'sortable-drag',
                forceFallback: true,
                fallbackClass: 'sortable-fallback',
                fallbackOnBody: true,
                swapThreshold: 0.65,
                delay: 100,
                delayOnTouchOnly: true,
                touchStartThreshold: 3,
                onEnd: function(evt) {
                    var item = evt.item;
                    var newParentId = evt.to.getAttribute('data-parent-id');
                    var todoId = item.getAttribute('data-id');

                    // Collect new order for all items in the target container
                    var orders = {};
                    var items = evt.to.querySelectorAll(':scope > .todo-item');
                    items.forEach(function(el, index) {
                        orders[el.getAttribute('data-id')] = index;
                    });

                    // Send AJAX request to update order
                    fetch('/todos/reorder', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            orders: orders,
                            parent_id: newParentId || null,
                            todo_id: todoId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (!data.success) {
                            console.error('Failed to update order');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                }
            });
        }

        // Initialize sortable on all containers
        document.querySelectorAll('.sortable-container').forEach(function(container) {
            initSortable(container);
        });
    });
</script>
