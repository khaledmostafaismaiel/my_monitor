<div class="modal fade" id="addBlueprintTransaction" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-secondary" id="staticBackdropLabel"
                    style="font-family: 'Outfit', sans-serif;">
                    <i class="bi bi-diagram-3 me-2 text-primary-custom"></i> New Blueprint
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="/blueprint_transactions">
                <input type="hidden" name="type" value="blue_print">
                <div class="modal-body px-4 pt-3">
                    {{ csrf_field() }}
                    <div class="row g-3">
                        <input type="text" class="form-control" id="type" name="type" value="normal" hidden>
                        <div class="col-md-6">
                            <label for="transactionName" class="form-label fw-semibold text-secondary small">Transaction
                                Name</label>
                            <input type="text" class="form-control-custom" id="transactionName" name="name"
                                placeholder="Enter transaction name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small">Price Per Unit</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"
                                    style="border-color: #e2e8f0;">E£</span>
                                <input type="number" class="form-control-custom border-start-0" name="price"
                                    placeholder="Enter amount" min="0" step="0.01" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small">Quantity</label>
                            <input type="number" class="form-control-custom" name="quantity" value="1" min="1"
                                step="0.01" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small">Transaction Direction</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="direction" id="debit"
                                        value="debit" checked>
                                    <label class="form-check-label text-danger fw-semibold small" for="debit">Debit
                                        (Expense)</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="direction" id="credit"
                                        value="credit">
                                    <label class="form-check-label text-success fw-semibold small" for="credit">Credit
                                        (Income)</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="categorySelectNew"
                                class="form-label fw-semibold text-secondary small">Category</label>
                            <select class="form-select"
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 0.5rem 1rem;"
                                id="categorySelectNew" name="category_id" required>
                                <option disabled selected>Select a category</option>
                                @foreach($all_categories as $category)
                                    @if($category->status == "active")
                                        <option value="{{ $category->id }}">{{ ucfirst($category->name) }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="transactionComment" class="form-label fw-semibold text-secondary small">Comment
                                (Optional)</label>
                            <textarea class="form-control-custom" id="transactionComment" rows="3" name="comment"
                                placeholder="Enter any additional details..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal"><i
                            class="bi bi-x-circle me-1"></i> Cancel</button>
                    <button type="submit" class="btn btn-primary-custom rounded-pill px-4"><i
                            class="bi bi-check-circle me-1"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.querySelector("#addBlueprintTransaction form");
        const submitButton = form.querySelector("button[type='submit']");
        form.addEventListener("submit", function () {
            submitButton.disabled = true;
            submitButton.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...`;
        });
        const modalElement = document.querySelector("#addBlueprintTransaction");
        modalElement.addEventListener("shown.bs.modal", function () {
            $('#categorySelectNew').select2({ dropdownParent: $('#addBlueprintTransaction'), placeholder: "Select a category", width: '100%' });
        });
    });
</script>