<div class="modal fade" id="addCategoryModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="addCategoryLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg"> <!-- Increased modal size -->
        <div class="modal-content shadow-lg rounded-3 border-0">
            <!-- Modal Header -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="addCategoryLabel">
                    <i class="bi bi-folder-plus me-2"></i> New
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <!-- Form -->
            <form method="POST" action="/categories">
                <div class="modal-body p-4">
                    {{ csrf_field() }}

                    <!-- Category Name Input -->
                    <div class="mb-3">
                        <label for="categoryName" class="form-label fw-semibold">Category Name</label>
                        <input type="text" class="form-control form-control-lg shadow-sm" id="categoryName" name="name"
                            placeholder="Enter category name..." required>
                    </div>

                    <!-- Status Selection -->
                    <div class="mb-3">
                        <label for="categoryStatus" class="form-label fw-semibold">Status</label>
                        <select class="form-select form-select-lg shadow-sm" id="categoryStatus" name="status" required>
                            <option value="active" selected>Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <!-- Limit Input -->
                    <div class="mb-3">
                        <label for="categoryLimit" class="form-label fw-semibold">Limit</label>
                        <input type="number" step="0.01" min="0" class="form-control form-control-lg shadow-sm"
                            id="categoryLimit" name="limit" placeholder="Enter limit (optional)...">
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
        const form = document.querySelector("#addCategoryModal form");
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