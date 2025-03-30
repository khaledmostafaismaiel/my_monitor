<div class="modal fade" id="{{ $modalId }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="deleteCategoryLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg" style="border-radius: 12px;">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold" id="deleteCategoryLabel">Delete Category</h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <p class="fs-5 fw-semibold">Are you sure you want to delete this category?</p>
                <p class="text-muted">This action cannot be undone.</p>
            </div>
            <form action="/categories/{{$category->id}}" method="POST">
                {{ csrf_field() }}
                @method('DELETE')

                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger px-4">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
