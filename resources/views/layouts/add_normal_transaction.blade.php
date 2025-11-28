<!-- Modal -->
<div class="modal fade" id="{{$modalId}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
            <!-- Modal Header -->
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-secondary" id="staticBackdropLabel"
                    style="font-family: 'Outfit', sans-serif;">
                    <i class="bi bi-receipt me-2 text-primary-custom"></i>
                    {{ isset($transaction) ? 'Edit Transaction' : 'New Transaction' }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Form Start -->
            <form method="POST" action="/normal_transactions">
                <div class="modal-body px-4 pt-3">
                    {{ csrf_field() }}

                    <div class="row g-3">
                        <!-- Transaction Name -->
                        <div class="col-md-6">
                            <label for="transactionName" class="form-label fw-semibold text-secondary small">Transaction
                                Name</label>
                            <input type="text" class="form-control-custom" id="transactionName" name="name"
                                placeholder="Enter transaction name"
                                value="{{ isset($transaction) ? $transaction->name : '' }}" required>
                        </div>

                        <!-- Amount Input -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small">Price Per Unit</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"
                                    style="border-color: #e2e8f0;">E£</span>
                                <input type="number" class="form-control-custom border-start-0" name="price"
                                    placeholder="Enter amount" min="0" step="0.01"
                                    value="{{ isset($transaction) ? $transaction->price : '' }}" required>
                            </div>
                        </div>

                        <!-- Quantity Input -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small">Quantity</label>
                            <input type="number" class="form-control-custom" name="quantity"
                                value="{{ isset($transaction) ? $transaction->quantity : 1 }}" min="1" step="0.01"
                                required>
                        </div>

                        <!-- Transaction Direction -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small">Transaction Direction</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="direction"
                                        id="debit-{{ isset($transaction) ? $transaction->id : 'new' }}" value="debit" {{ (isset($transaction) && $transaction->direction == 'debit') || !isset($transaction) ? 'checked' : '' }}>
                                    <label class="form-check-label text-danger fw-semibold small"
                                        for="debit-{{ isset($transaction) ? $transaction->id : 'new' }}">Debit
                                        (Expense)</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="direction"
                                        id="credit-{{ isset($transaction) ? $transaction->id : 'new' }}" value="credit"
                                        {{ isset($transaction) && $transaction->direction == 'credit' ? 'checked' : '' }}>
                                    <label class="form-check-label text-success fw-semibold small"
                                        for="credit-{{ isset($transaction) ? $transaction->id : 'new' }}">Credit
                                        (Income)</label>
                                </div>
                            </div>
                        </div>

                        <!-- Category Selection -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small">Category</label>
                            <select class="form-select"
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 0.5rem 1rem;"
                                name="category_id" id="categoryDropdown" required>
                                <option disabled selected>Select a category</option>
                                @foreach($all_categories as $category)
                                    @if($category->status == "active")
                                        <option value="{{ $category->id }}" {{ isset($transaction) && $transaction->category_id == $category->id ? 'selected' : '' }}>
                                            {{ ucfirst($category->name) }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <!-- Wallet Selection -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small">Wallet</label>
                            <select class="form-select"
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 0.5rem 1rem;"
                                id="walletDropdown" name="wallet_id" required>
                                <option disabled selected>Select a wallet</option>
                                @foreach($all_wallets as $wallet)
                                    @if($wallet->status == "active")
                                        <option value="{{ $wallet->id }}">{{ ucfirst($wallet->name) }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <!-- MonthYear Dropdown -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small">Month-Year</label>
                            <select class="form-select"
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 0.5rem 1rem;"
                                name="month_year_id" id="monthYearDropdown" required>
                                @foreach($all_month_years as $monthYear)
                                    <option value="{{ $monthYear->id }}" {{ isset($transaction) && $transaction->month_year_id == $monthYear->id ? 'selected' : '' }}>
                                        {{ $monthYear->year }} - {{ $monthYear->month }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Date Picker -->
                        <div class="col-md-6">
                            <label for="transactionDate" class="form-label fw-semibold text-secondary small">Transaction
                                Date</label>
                            <input type="date" class="form-control-custom" id="transactionDate" name="date"
                                value="{{ isset($transaction) ? $transaction->date : date('Y-m-d') }}" required>
                        </div>

                        <!-- Comment -->
                        <div class="col-12">
                            <label for="transactionComment" class="form-label fw-semibold text-secondary small">Comment
                                (Optional)</label>
                            <textarea class="form-control-custom" id="transactionComment" rows="3" name="comment"
                                placeholder="Enter any additional details...">{{ isset($transaction) ? $transaction->comment : '' }}</textarea>
                        </div>
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

        // Initialize Select2 for Category dropdown
        $('#categoryDropdown').select2({
            dropdownParent: $('#{{ $modalId }}'),
            placeholder: 'Select a category',
            width: '100%'
        });

        // Initialize Select2 for Wallet dropdown
        $('#walletDropdown').select2({
            dropdownParent: $('#{{ $modalId }}'),
            placeholder: 'Select a wallet',
            width: '100%'
        });

        // Initialize Select2 for Month-Year dropdown
        $('#monthYearDropdown').select2({
            dropdownParent: $('#{{ $modalId }}'),
            placeholder: 'Select Month-Year',
            width: '100%'
        });
    });
</script>