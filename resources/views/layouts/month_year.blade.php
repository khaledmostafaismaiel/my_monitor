<div class="modal fade" id="{{ $modalId }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editMonthYearLabel{{ $monthYear->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg"> <!-- Increased modal size -->
        <div class="modal-content shadow-lg rounded-3 border-0">
            <!-- Modal Header -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold text-start" id="editMonthYearLabel{{ $monthYear->id }}">
                    <i class="bi bi-pencil-square me-2"></i> Edit
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Form -->
            <form method="POST" action="/month_years/{{$monthYear->id}}">
                <div class="modal-body p-4">
                    {{ csrf_field() }}
                    @method('PUT')

                    <!-- Settled On Input (Float) -->
                    <div class="mb-3 text-start">
                        <label for="settledOn{{ $monthYear->id }}" class="form-label fw-semibold text-start">Settled On</label>
                        <input type="number" class="form-control form-control-lg shadow-sm" id="settledOn{{ $monthYear->id }}" name="settled_on" value="{{ old('settled_on', $monthYear->settled_on) }}" placeholder="Enter settled amount..." step="0.01" required>
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
