<!-- Modal -->
<div class="modal fade" id="{{$modalId}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
            <!-- Modal Header -->
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-secondary" id="staticBackdropLabel"
                    style="font-family: 'Outfit', sans-serif;">
                    <i class="bi bi-wallet2 me-2 text-primary-custom"></i>
                    {{ isset($wallet) ? 'Edit Wallet' : 'New Wallet' }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Form Start -->
            <form method="POST" action="/wallets">
                <div class="modal-body px-4 pt-3">
                    {{ csrf_field() }}

                    <!-- Wallet Name Input -->
                    <div class="mb-3">
                        <label for="walletName" class="form-label fw-semibold text-secondary small">Wallet Name</label>
                        <input type="text" class="form-control-custom" id="walletName" name="name"
                            placeholder="Enter wallet name..." required>
                    </div>

                    <!-- Status Selection -->
                    <div class="mb-3">
                        <label for="walletStatus" class="form-label fw-semibold text-secondary small">Status</label>
                        <select class="form-select"
                            style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 0.5rem 1rem;"
                            id="walletStatus" name="status" required>
                            <option value="active" selected>Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
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