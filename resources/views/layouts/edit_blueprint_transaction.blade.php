<div class="modal fade" id="editTransaction{{ $transaction->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="editTransactionLabel{{ $transaction->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-secondary" id="editTransactionLabel{{ $transaction->id }}"
                    style="font-family: 'Outfit', sans-serif;">
                    <i class="bi bi-pencil-square me-2 text-primary-custom"></i> Edit Blueprint
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="/blueprint_transactions/{{ $transaction->id }}">
                <div class="modal-body px-4 pt-3">
                    {{ csrf_field() }}
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="transactionName{{ $transaction->id }}"
                                class="form-label fw-semibold text-secondary small">Transaction Name</label>
                            <input type="text" class="form-control-custom" id="transactionName{{ $transaction->id }}"
                                name="name" value="{{ $transaction->name }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small">Price Per Unit</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"
                                    style="border-color: #e2e8f0;">E£</span>
                                <input type="number" class="form-control-custom border-start-0" name="price"
                                    value="{{ $transaction->price }}" min="0" step="0.01" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small">Quantity</label>
                            <input type="number" class="form-control-custom" name="quantity"
                                value="{{ $transaction->quantity }}" min="1" step="0.01" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small">Transaction Direction</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="direction"
                                        id="debit{{ $transaction->id }}" value="debit" {{ $transaction->direction == 'debit' ? 'checked' : '' }}>
                                    <label class="form-check-label text-danger fw-semibold small"
                                        for="debit{{ $transaction->id }}">Debit (Expense)</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="direction"
                                        id="credit{{ $transaction->id }}" value="credit" {{ $transaction->direction == 'credit' ? 'checked' : '' }}>
                                    <label class="form-check-label text-success fw-semibold small"
                                        for="credit{{ $transaction->id }}">Credit (Income)</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="categorySelect{{ $transaction->id }}"
                                class="form-label fw-semibold text-secondary small">Category</label>
                            <select class="form-select"
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 0.5rem 1rem;"
                                id="categorySelect{{ $transaction->id }}" name="category_id" required>
                                <option disabled>Select a category</option>
                                @foreach($all_categories as $category)
                                    @if($category->status == "active")
                                        <option value="{{ $category->id }}" {{ $transaction->category_id == $category->id ? 'selected' : '' }}>{{ ucfirst($category->name) }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="user_id{{ $transaction->id }}"
                                class="form-label fw-semibold text-secondary small">User</label>
                            <select class="form-select"
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 0.5rem 1rem;"
                                id="userSelect{{ $transaction->id }}" name="user_id" required>
                                <option disabled>Select User</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ $transaction->user_id == $user->id ? 'selected' : '' }}>{{ $user->first_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="transactionComment{{ $transaction->id }}"
                                class="form-label fw-semibold text-secondary small">Comment (Optional)</label>
                            <textarea class="form-control-custom" id="transactionComment{{ $transaction->id }}" rows="3"
                                name="comment"
                                placeholder="Enter any additional details...">{{ $transaction->comment }}</textarea>
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
        const form = document.querySelector("#editTransaction{{ $transaction->id }} form");
        const submitButton = form.querySelector("button[type='submit']");
        form.addEventListener("submit", function () {
            submitButton.disabled = true;
            submitButton.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...`;
        });
        const modalElement = document.querySelector("#editTransaction{{ $transaction->id }}");
        modalElement.addEventListener("shown.bs.modal", function () {
            $('#categorySelect{{ $transaction->id }}').select2({ dropdownParent: $('#editTransaction{{ $transaction->id }}'), placeholder: "Select a category", width: '100%' });
            $('#userSelect{{ $transaction->id }}').select2({ dropdownParent: $('#editTransaction{{ $transaction->id }}'), placeholder: "Select a user", width: '100%' });
        });
    });
</script>