<!-- Modal -->
<div class="modal fade" id="{{$modalId}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
            <!-- Modal Header -->
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-secondary" id="staticBackdropLabel"
                    style="font-family: 'Outfit', sans-serif;">
                    <i class="bi bi-check2-square me-2 text-primary-custom"></i>
                    {{ isset($parentId) ? 'New Sub-task' : 'New Todo' }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Form Start -->
            <form method="POST" action="/todos" id="form-{{ $modalId }}">
                <div class="modal-body px-4 pt-3">
                    {{ csrf_field() }}

                    @if(isset($parentId))
                        <input type="hidden" name="parent_id" value="{{ $parentId }}">
                    @endif

                    <div class="row">
                        <!-- Title Input -->
                        <div class="col-md-8 mb-3">
                            <label for="todoTitle-{{ $modalId }}" class="form-label fw-semibold text-secondary small">Title</label>
                            <input type="text" class="form-control-custom" id="todoTitle-{{ $modalId }}" name="title"
                                placeholder="Enter todo title..." required>
                        </div>

                        <!-- Priority Selection -->
                        <div class="col-md-4 mb-3">
                            <label for="todoPriority-{{ $modalId }}" class="form-label fw-semibold text-secondary small">Priority</label>
                            <select class="form-select" id="todoPriority-{{ $modalId }}" name="priority" required
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 0.5rem 1rem;">
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                    </div>

                    <!-- Description Input -->
                    <div class="mb-3">
                        <label for="todoDescription-{{ $modalId }}" class="form-label fw-semibold text-secondary small">Description</label>
                        <textarea class="form-control-custom" id="todoDescription-{{ $modalId }}" name="description"
                            placeholder="Enter description..." rows="2"></textarea>
                    </div>

                    <div class="row">
                        <!-- Scope Selection -->
                        <div class="col-md-4 mb-3">
                            <label for="todoScope-{{ $modalId }}" class="form-label fw-semibold text-secondary small">Scope</label>
                            <select class="form-select" id="todoScope-{{ $modalId }}" name="scope" required
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 0.5rem 1rem;">
                                <option value="private" selected>Private (Only me)</option>
                                <option value="public">Public (Family visible)</option>
                            </select>
                        </div>

                        <!-- Status Selection -->
                        <div class="col-md-4 mb-3">
                            <label for="todoStatus-{{ $modalId }}" class="form-label fw-semibold text-secondary small">Status</label>
                            <select class="form-select" id="todoStatus-{{ $modalId }}" name="status"
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 0.5rem 1rem;">
                                <option value="pending" selected>Pending</option>
                                <option value="in_progress">In Progress</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>

                        <!-- Due Date Input -->
                        <div class="col-md-4 mb-3">
                            <label for="todoDueDate-{{ $modalId }}" class="form-label fw-semibold text-secondary small">Due Date</label>
                            <input type="date" class="form-control-custom" id="todoDueDate-{{ $modalId }}" name="due_date">
                        </div>
                    </div>

                    <div class="row">
                        <!-- Month/Year Selection -->
                        <div class="col-md-6 mb-3">
                            <label for="todoMonthYear-{{ $modalId }}" class="form-label fw-semibold text-secondary small">Link to Month/Year (Optional)</label>
                            <select class="form-select" id="todoMonthYear-{{ $modalId }}" name="month_year_id"
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 0.5rem 1rem;">
                                <option value="">None</option>
                                @foreach($all_month_years as $my)
                                    <option value="{{ $my->id }}">{{ $my->month }}/{{ $my->year }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Parent Todo Selection (if not already a child) -->
                        @if(!isset($parentId))
                        <div class="col-md-6 mb-3">
                            <label for="todoParent-{{ $modalId }}" class="form-label fw-semibold text-secondary small">Parent Todo (Optional)</label>
                            <select class="form-select" id="todoParent-{{ $modalId }}" name="parent_id"
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 0.5rem 1rem;">
                                <option value="">None (Root level)</option>
                                @foreach($allTodos as $parentTodo)
                                    <option value="{{ $parentTodo->id }}">{{ $parentTodo->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
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
        const form = document.querySelector("#form-{{ $modalId }}");
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
