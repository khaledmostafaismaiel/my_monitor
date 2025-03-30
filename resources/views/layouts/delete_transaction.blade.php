<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-3">
            
            <!-- Modal Header -->
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> Confirm Deletion
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body text-center p-4">
                <h5 class="fw-bold text-danger">Are you sure you want to delete this transaction?</h5>
                <p class="text-muted">This action cannot be undone.</p>

                <!-- Delete Form -->
                <form action="/transactions/{{$transaction->id}}" method="POST">
                    @csrf
                    @method('DELETE')
                    
                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-center gap-3 mt-4">
                        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger px-4">Delete</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
