<div class="modal fade" id="addBlueprintTransaction" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content shadow-lg border-0 rounded-3">

            <!-- Modal Header -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="staticBackdropLabel">
                    <i class="bi bi-folder-plus me-2"></i> New Blueprint Transaction
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Form Start -->
            <form method="POST" action="/blueprint_transactions">
                <input type="hidden" name="is_blue_print" value="1">

                <div class="modal-body px-4">
                    {{ csrf_field() }}

                    <div class="row g-3">
                        <input type="text" class="form-control" id="is_blue_print" name="is_blue_print" value="0" hidden>

                        <!-- Transaction Name -->
                        <div class="col-md-6">
                            <label for="transactionName" class="form-label">Transaction Name</label>
                            <input type="text" class="form-control" id="transactionName" name="name" placeholder="Enter transaction name" required>
                        </div>

                        <!-- Amount Input -->
                        <div class="col-md-6">
                            <label class="form-label">Price Per Unit</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">E£</span>
                                <input type="number" class="form-control" name="price" placeholder="Enter amount" min="0" step="0.01" required>
                            </div>
                        </div>

                        <!-- Quantity Input -->
                        <div class="col-md-6">
                            <label class="form-label">Quantity</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="quantity" value="1" min="1" step="0.01" required>
                            </div>
                        </div>

                        <!-- Transaction Type -->
                        <div class="col-md-6">
                            <label class="form-label">Transaction Type</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input border-primary" type="radio" name="type" id="debit" value="debit" checked>
                                    <label class="form-check-label text-danger fw-bold" for="debit">Debit (Expense)</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input border-primary" type="radio" name="type" id="credit" value="credit">
                                    <label class="form-check-label text-success fw-bold" for="credit">Credit (Income)</label>
                                </div>
                            </div>
                        </div>

                        <!-- Category Selection -->
                        <div class="col-md-6">
                            <label class="form-label">Category</label>
                            <select class="form-select" name="category_id" required>
                                <option disabled selected>Select a category</option>
                                @foreach($categories as $category)
                                    @if($category->status == "active")
                                        <option value="{{ $category->id }}">{{ ucfirst($category->name) }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <!-- Comment -->
                        <div class="col-12">
                            <label for="transactionComment" class="form-label">Comment (Optional)</label>
                            <textarea class="form-control" id="transactionComment" rows="3" name="comment" placeholder="Enter any additional details..."></textarea>
                        </div>
                    </div>

                </div>

                <!-- Modal Footer -->
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-success px-4">
                        <i class="bi bi-check-circle"></i> Save Transaction
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>


<script>
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.querySelector("#addTransaction form");
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
