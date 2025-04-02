<div class="modal fade" id="{{ $modalId }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="deleteCategoryLabel{{ $category->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm"> <!-- Optimized modal size -->
        <div class="modal-content shadow-lg rounded-3 border-0">
            <!-- Modal Header -->
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold" id="deleteCategoryLabel{{ $category->id }}">
                    <i class="bi bi-trash3 me-2"></i> Delete Category
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body text-center p-4">
                <i class="bi bi-exclamation-triangle-fill text-danger fs-1 mb-3"></i>
                <p class="fs-5 fw-semibold">Are you sure?</p>
                <p class="text-muted">This action cannot be undone.</p>
            </div>

            <!-- Form -->
            <form action="/categories/{{$category->id}}" method="POST">
                {{ csrf_field() }}
                @method('DELETE')

                <!-- Modal Footer -->
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-danger px-4">
                        <i class="bi bi-trash"></i> Delete
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