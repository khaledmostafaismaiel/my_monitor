<div class="todo-item p-2 p-md-3 mb-2 rounded-3 bg-white shadow-sm priority-{{ $todo->priority }} status-{{ $todo->status }}"
    data-id="{{ $todo->id }}" data-order="{{ $todo->order }}">
    <div class="d-flex align-items-start gap-2">
        <!-- Drag Handle -->
        <div class="drag-handle text-muted" style="cursor: grab; padding: 0 2px;">
            <i class="bi bi-grip-vertical"></i>
        </div>

        <!-- Collapse Toggle (if has children) -->
        <div style="width: 20px;">
            @if($todo->children->count() > 0)
                <a class="text-secondary toggle-children collapsed" href="javascript:void(0)" data-target="children-{{ $todo->id }}">
                    <i class="bi bi-chevron-down collapse-icon"></i>
                </a>
            @endif
        </div>

        <!-- Status Toggle -->
        <form action="/todos/{{ $todo->id }}/toggle" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-sm p-0 border-0 bg-transparent">
                @if($todo->status === 'completed')
                    <i class="bi bi-check-circle-fill text-success fs-5"></i>
                @elseif($todo->status === 'in_progress')
                    <i class="bi bi-arrow-repeat text-primary fs-5"></i>
                @else
                    <i class="bi bi-circle text-muted fs-5"></i>
                @endif
            </button>
        </form>

        <!-- Content -->
        <div class="flex-grow-1 min-width-0">
            <div class="d-flex flex-wrap align-items-center gap-1 gap-md-2 mb-1">
                <span class="todo-title fw-semibold text-secondary text-truncate" style="max-width: 200px;">{{ $todo->title }}</span>

                <!-- Badges - Hidden on mobile, shown on desktop -->
                <div class="d-none d-md-flex gap-1">
                    <!-- Scope Badge -->
                    @if($todo->scope === 'private')
                        <span class="badge badge-scope-private rounded-pill px-2 py-1 small">
                            <i class="bi bi-lock-fill"></i> Private
                        </span>
                    @else
                        <span class="badge badge-scope-public rounded-pill px-2 py-1 small">
                            <i class="bi bi-globe"></i> Public
                        </span>
                    @endif

                    <!-- Priority Badge -->
                    <span class="badge badge-priority-{{ $todo->priority }} rounded-pill px-2 py-1 small">
                        {{ ucfirst($todo->priority) }}
                    </span>

                    <!-- Status Badge -->
                    <span class="badge badge-status-{{ $todo->status }} rounded-pill px-2 py-1 small">
                        {{ str_replace('_', ' ', ucfirst($todo->status)) }}
                    </span>
                </div>

                <!-- Mobile: Show only icons -->
                <div class="d-flex d-md-none gap-1">
                    @if($todo->scope === 'private')
                        <i class="bi bi-lock-fill text-danger small"></i>
                    @endif
                    @if($todo->priority === 'high')
                        <i class="bi bi-exclamation-circle-fill text-danger small"></i>
                    @endif
                </div>
            </div>

            @if($todo->description)
                <p class="text-muted small mb-1 text-truncate d-none d-md-block">{{ Str::limit($todo->description, 100) }}</p>
            @endif

            <div class="d-none d-md-flex align-items-center gap-3 small text-muted flex-wrap">
                <!-- Creator -->
                <span>
                    <i class="bi bi-person"></i> {{ $todo->user->first_name }}
                </span>

                <!-- Due Date -->
                @if($todo->due_date)
                    <span class="{{ $todo->isOverdue() ? 'overdue' : '' }}">
                        <i class="bi bi-calendar"></i>
                        {{ $todo->due_date->format('M d, Y') }}
                        @if($todo->isOverdue())
                            (Overdue)
                        @endif
                    </span>
                @endif

                <!-- Month/Year -->
                @if($todo->monthYear)
                    <span>
                        <i class="bi bi-calendar-month"></i>
                        {{ $todo->monthYear->month }}/{{ $todo->monthYear->year }}
                    </span>
                @endif

                <!-- Children Count -->
                @if($todo->children->count() > 0)
                    <span>
                        <i class="bi bi-diagram-3"></i>
                        {{ $todo->children->count() }} sub-tasks
                    </span>

                    <!-- Progress -->
                    @php
                        $completed = $todo->children->where('status', 'completed')->count();
                        $total = $todo->children->count();
                        $progress = $total > 0 ? round(($completed / $total) * 100) : 0;
                    @endphp
                    <div class="d-flex align-items-center gap-1">
                        <div class="progress progress-mini" style="width: 60px;">
                            <div class="progress-bar bg-success" style="width: {{ $progress }}%"></div>
                        </div>
                        <span>{{ $progress }}%</span>
                    </div>
                @endif
            </div>

            <!-- Mobile: Minimal info -->
            <div class="d-flex d-md-none align-items-center gap-2 small text-muted">
                @if($todo->due_date)
                    <span class="{{ $todo->isOverdue() ? 'overdue' : '' }}">
                        <i class="bi bi-calendar"></i> {{ $todo->due_date->format('m/d') }}
                    </span>
                @endif
                @if($todo->children->count() > 0)
                    <span><i class="bi bi-diagram-3"></i> {{ $todo->children->count() }}</span>
                @endif
            </div>
        </div>

        <!-- Actions -->
        <div class="d-flex flex-column flex-md-row gap-1 todo-actions">
            <button type="button" class="btn btn-sm btn-outline-success border-0 p-1" title="Add Sub-task"
                data-bs-toggle="modal" data-bs-target="#addChildTodo{{ $todo->id }}">
                <i class="bi bi-plus-circle"></i>
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary border-0 p-1" title="Edit"
                data-bs-toggle="modal" data-bs-target="#editTodo{{ $todo->id }}">
                <i class="bi bi-pencil"></i>
            </button>
            <button type="button" class="btn btn-sm btn-outline-danger border-0 p-1" title="Delete"
                data-bs-toggle="modal" data-bs-target="#deleteTodo{{ $todo->id }}">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    </div>

    <!-- Children (Nested) -->
    @if($todo->children->count() > 0)
        <div class="todo-children sortable-container mt-2 hidden" id="children-{{ $todo->id }}" data-parent-id="{{ $todo->id }}">
            @foreach($todo->children as $child)
                @include('layouts/todo_item', ['todo' => $child, 'level' => $level + 1])
            @endforeach
        </div>
    @endif
</div>
