<div class="modal fade" id="{{ $modalId }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editCategoryLabel{{ $category->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg"> <!-- Increased modal size -->
        <div class="modal-content shadow-lg rounded-3 border-0">
            <!-- Modal Header -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold text-start" id="editCategoryLabel{{ $category->id }}">
                    <i class="bi bi-pencil-square me-2"></i> Edit
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Form -->
            <form method="POST" action="/categories/{{$category->id}}">
                <div class="modal-body p-4">
                    {{ csrf_field() }}
                    @method('PUT')

                    <!-- Category Name Input -->
                    <div class="mb-3 text-start">
                        <label for="categoryName{{ $category->id }}" class="form-label fw-semibold text-start">Category Name</label>
                        <input type="text" class="form-control form-control-lg shadow-sm" id="categoryName{{ $category->id }}" name="name" value="{{ $category->name }}" placeholder="Enter category name..." required>
                    </div>

                    <!-- Status Selection -->
                    <div class="mb-3 text-start">
                        <label for="categoryStatus{{ $category->id }}" class="form-label fw-semibold text-start">Status</label>
                        <select class="form-select form-select-lg shadow-sm" id="categoryStatus{{ $category->id }}" name="status" required>
                            <option value="active" {{ $category->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $category->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-check-circle"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.querySelector("#{{ $modalId }} form");
        const submitButton = form.querySelector("button[type='submit']");

        form.addEventListener("submit", function () {
            submitButton.disabled = true;
            submitButton.innerHTML = `
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Saving...
            `;
        });
    });
</script>
