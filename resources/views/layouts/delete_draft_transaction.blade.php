<div class="modal fade" id="{{ $modalId }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="deleteTransactionLabel{{ $transaction->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
            <div class="modal-header border-0 pb-2">
                <h5 class="modal-title fw-bold text-danger" id="deleteTransactionLabel{{ $transaction->id }}"
                    style="font-family: 'Outfit', sans-serif;">
                    <i class="bi bi-trash3 me-2"></i> Delete Draft
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center px-4 py-3">
                <div class="mb-3">
                    <div class="d-inline-flex align-items-center justify-content-center bg-danger-subtle rounded-circle"
                        style="width: 64px; height: 64px;">
                        <i class="bi bi-exclamation-triangle-fill text-danger fs-2"></i>
                    </div>
                </div>
                <h6 class="fw-bold text-secondary mb-2">Are you sure?</h6>
                <p class="text-muted small mb-0">This action cannot be undone.</p>
            </div>
            <form action="/draft_transactions/{{$transaction->id}}" method="POST">
                {{ csrf_field() }}
                @method('DELETE')
                <div class="modal-footer border-0 pt-0 justify-content-center">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal"><i
                            class="bi bi-x-circle me-1"></i> Cancel</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4"><i class="bi bi-trash me-1"></i>
                        Delete</button>
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
            submitButton.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Deleting...`;
        });
    });
</script>
<style>
    .bg-danger-subtle {
        background-color: #fee2e2 !important;
    }
</style>