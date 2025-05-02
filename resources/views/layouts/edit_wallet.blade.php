<div class="modal fade" id="editWallet{{ $wallet->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editWalletLabel{{ $wallet->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content shadow-lg border-0 rounded-3">

            <!-- Modal Header -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editWalletLabel{{ $wallet->id }}">
                    <i class="bi bi-pencil-square me-2"></i> Edit
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Form Start -->
            <form method="POST" action="/wallets/{{ $wallet->id }}">
                <div class="modal-body p-4">
                    {{ csrf_field() }}
                    @method('PUT')

                    <!-- Wallet Name Input -->
                    <div class="mb-3 text-start">
                        <label for="walletName{{ $wallet->id }}" class="form-label fw-semibold text-start">Wallet Name</label>
                        <input type="text" class="form-control form-control-lg shadow-sm" id="walletName{{ $wallet->id }}" name="name" value="{{ $wallet->name }}" placeholder="Enter wallet name..." required>
                    </div>

                    <!-- Status Selection -->
                    <div class="mb-3 text-start">
                        <label for="walletStatus{{ $wallet->id }}" class="form-label fw-semibold text-start">Status</label>
                        <select class="form-select form-select-lg shadow-sm" id="walletStatus{{ $wallet->id }}" name="status" required>
                            <option value="active" {{ $wallet->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $wallet->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
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
        const modalId = "#editWallet{{ $wallet->id }}";

        const form = document.querySelector(modalId + " form");
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
