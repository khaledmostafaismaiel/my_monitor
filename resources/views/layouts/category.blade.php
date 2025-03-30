<div class="modal fade" id="{{ $modalId }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg" style="border-radius: 12px;">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="staticBackdropLabel">Edit Category</h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="/categories/{{$category->id}}">
                <div class="modal-body p-4">
                    {{ csrf_field() }}
                    @method('PUT')

                    <div class="mb-3">
                        <label for="categoryName" class="form-label fw-semibold">Category Name</label>
                        <input type="text" class="form-control" id="categoryName" name="name" value="{{$category->name}}" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success px-4">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
