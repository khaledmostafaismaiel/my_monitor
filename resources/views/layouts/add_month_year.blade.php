<div class="modal fade" id="addMonthYearModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="addMonthYearLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content card-custom border-0 overflow-hidden">
            <!-- Modal Header -->
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold text-secondary" id="addMonthYearLabel">
                    Add New Month
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Form -->
            <form method="POST" action="/month_years">
                <div class="modal-body p-4">
                    {{ csrf_field() }}

                    <div class="text-center mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center bg-primary-subtle rounded-circle mb-3"
                            style="width: 64px; height: 64px;">
                            <i class="bi bi-calendar-plus text-primary-custom fs-3"></i>
                        </div>
                        <p class="text-muted small">Select the month and year you want to track.</p>
                    </div>

                    <!-- Month-Year Filter -->
                    <div class="mb-3">
                        <label class="form-label fw-medium text-secondary small text-uppercase ls-1">Select Month &
                            Year</label>
                        <input type="month" name="month_year" class="form-control form-control-custom"
                            value="{{ request('month_year') }}" required>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer border-top-0 px-4 pb-4">
                    <button type="button" class="btn btn-light text-secondary fw-medium" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary-custom px-4">
                        Create Month
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.querySelector("#addMonthYearModal form");
        const submitButton = form.querySelector("button[type='submit']");

        form.addEventListener("submit", function () {
            submitButton.disabled = true;
            submitButton.innerHTML = `
                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                Saving...
            `;
        });
    });
</script>