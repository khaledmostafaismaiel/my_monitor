<div class="modal fade" id="{{ $modalId }}" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="editTodoLabel{{ $todo->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">

            <!-- Modal Header -->
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-secondary" id="editTodoLabel{{ $todo->id }}"
                    style="font-family: 'Outfit', sans-serif;">
                    <i class="bi bi-pencil-square me-2 text-primary-custom"></i> Edit Todo
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Form Start -->
            <form method="POST" action="/todos/{{ $todo->id }}" id="editForm-{{ $todo->id }}">
                <div class="modal-body px-4 pt-3">
                    {{ csrf_field() }}
                    @method('PUT')

                    <div class="row">
                        <!-- Title Input -->
                        <div class="col-md-8 mb-3">
                            <label for="todoTitle{{ $todo->id }}" class="form-label fw-semibold text-secondary small">Title</label>
                            <input type="text" class="form-control-custom" id="todoTitle{{ $todo->id }}" name="title"
                                value="{{ $todo->title }}" placeholder="Enter todo title..." required>
                        </div>

                        <!-- Priority Selection -->
                        <div class="col-md-4 mb-3">
                            <label for="todoPriority{{ $todo->id }}" class="form-label fw-semibold text-secondary small">Priority</label>
                            <select class="form-select" id="todoPriority{{ $todo->id }}" name="priority" required
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 0.5rem 1rem;">
                                <option value="low" {{ $todo->priority == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ $todo->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ $todo->priority == 'high' ? 'selected' : '' }}>High</option>
                            </select>
                        </div>
                    </div>

                    <!-- Description Input -->
                    <div class="mb-3">
                        <label for="todoDescription{{ $todo->id }}" class="form-label fw-semibold text-secondary small">Description</label>
                        <textarea class="form-control-custom" id="todoDescription{{ $todo->id }}" name="description"
                            placeholder="Enter description..." rows="2">{{ $todo->description }}</textarea>
                    </div>

                    <div class="row">
                        <!-- Scope Selection -->
                        <div class="col-md-4 mb-3">
                            <label for="todoScope{{ $todo->id }}" class="form-label fw-semibold text-secondary small">Scope</label>
                            <select class="form-select" id="todoScope{{ $todo->id }}" name="scope" required
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 0.5rem 1rem;">
                                <option value="private" {{ $todo->scope == 'private' ? 'selected' : '' }}>Private (Only me)</option>
                                <option value="public" {{ $todo->scope == 'public' ? 'selected' : '' }}>Public (Family visible)</option>
                            </select>
                        </div>

                        <!-- Status Selection -->
                        <div class="col-md-4 mb-3">
                            <label for="todoStatus{{ $todo->id }}" class="form-label fw-semibold text-secondary small">Status</label>
                            <select class="form-select" id="todoStatus{{ $todo->id }}" name="status"
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 0.5rem 1rem;">
                                <option value="pending" {{ $todo->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="in_progress" {{ $todo->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ $todo->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>

                        <!-- Due Date Input -->
                        <div class="col-md-4 mb-3">
                            <label for="todoDueDate{{ $todo->id }}" class="form-label fw-semibold text-secondary small">Due Date</label>
                            <input type="date" class="form-control-custom" id="todoDueDate{{ $todo->id }}" name="due_date"
                                value="{{ $todo->due_date ? $todo->due_date->format('Y-m-d') : '' }}">
                        </div>
                    </div>

                    <div class="row">
                        <!-- Month/Year Selection -->
                        <div class="col-md-6 mb-3">
                            <label for="todoMonthYear{{ $todo->id }}" class="form-label fw-semibold text-secondary small">Link to Month/Year (Optional)</label>
                            <select class="form-select" id="todoMonthYear{{ $todo->id }}" name="month_year_id"
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 0.5rem 1rem;">
                                <option value="">None</option>
                                @foreach($all_month_years as $my)
                                    <option value="{{ $my->id }}" {{ $todo->month_year_id == $my->id ? 'selected' : '' }}>
                                        {{ $my->month }}/{{ $my->year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Parent Todo Selection -->
                        <div class="col-md-6 mb-3">
                            <label for="todoParent{{ $todo->id }}" class="form-label fw-semibold text-secondary small">Parent Todo (Optional)</label>
                            <select class="form-select" id="todoParent{{ $todo->id }}" name="parent_id"
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 0.5rem 1rem;">
                                <option value="">None (Root level)</option>
                                @if($todo->parent)
                                    <option value="{{ $todo->parent->id }}" selected>{{ $todo->parent->title }} (Current)</option>
                                @endif
                                @foreach($allTodos as $parentTodo)
                                    @if($parentTodo->id != $todo->id && $parentTodo->id != $todo->parent_id)
                                        <option value="{{ $parentTodo->id }}">
                                            {{ $parentTodo->title }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary-custom rounded-pill px-4">
                        <i class="bi bi-check-circle me-1"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.querySelector("#editForm-{{ $todo->id }}");
        if (form) {
            const submitButton = form.querySelector("button[type='submit']");

            form.addEventListener("submit", function () {
                submitButton.disabled = true;
                submitButton.innerHTML = `
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Saving...
                `;
            });
        }
    });
</script>
