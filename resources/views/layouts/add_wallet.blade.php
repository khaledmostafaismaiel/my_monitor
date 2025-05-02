<!-- Modal -->
<div class="modal fade" id="{{$modalId}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content shadow-lg border-0 rounded-3">
            <!-- Modal Header -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="staticBackdropLabel">
                    <i class="bi bi-folder-plus me-2"></i>
                    {{ isset($wallet) ? 'Edit' : 'New' }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Form Start -->
            <form method="POST" action="/wallets">
                <div class="modal-body px-4">
                    {{ csrf_field() }}

                    <!-- Category Name Input -->
                    <div class="mb-3">
                        <label for="walletName" class="form-label fw-semibold">Wallet Name</label>
                        <input type="text" class="form-control form-control-lg shadow-sm" id="walletName" name="name" placeholder="Enter wallet name..." required>
                    </div>

                    <!-- Status Selection -->
                    <div class="mb-3">
                        <label for="walletStatus" class="form-label fw-semibold">Status</label>
                        <select class="form-select form-select-lg shadow-sm" id="walletStatus" name="status" required>
                            <option value="active" selected>Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-success px-4">
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
