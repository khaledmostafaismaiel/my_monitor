<!-- Modal -->
<div class="modal fade" id="{{$modalId}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content shadow-lg border-0 rounded-3">
            <!-- Modal Header -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="staticBackdropLabel">
                    <i class="bi bi-folder-plus me-2"></i>
                    {{ 'Transfer' }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Form Start -->
            <form method="POST" action="/draft_transactions/transfer_to_normal">
                <div class="modal-body px-4">
                    {{ csrf_field() }}
                    <input type="text" class="form-control" id="transactionId" name="id" value="{{ $transaction->id}}" hidden>

                    <div class="row g-3">
                        <!-- Transaction Name -->
                        <div class="col-md-6">
                            <label for="transactionName" class="form-label">Transaction Name</label>
                            <input type="text" class="form-control" id="transactionName" name="name" placeholder="Enter transaction name" value="{{ $transaction->name}}" required>
                        </div>

                        <!-- Amount Input -->
                        <div class="col-md-6">
                            <label class="form-label">Price Per Unit</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">E£</span>
                                <input type="number" class="form-control" name="price" placeholder="Enter amount" min="0" step="0.01" value="{{ $transaction->price }}" required>
                            </div>
                        </div>

                        <!-- Quantity Input -->
                        <div class="col-md-6">
                            <label class="form-label">Quantity</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="quantity" value="{{$transaction->quantity }}" min="1" step="0.01" required>
                            </div>
                        </div>

                        <!-- Transaction Direction -->
                        <div class="col-md-6">
                            <label class="form-label">Transaction Direction</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input border-primary" type="radio" name="direction" id="debit-{{  $transaction->id  }}" value="debit" {{ $transaction->direction == 'debit' ? 'checked' : '' }}>
                                    <label class="form-check-label text-danger fw-bold" for="debit-{{  $transaction->id  }}">Debit (Expense)</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input border-primary" type="radio" name="direction" id="credit-{{ $transaction->id }}" value="credit" {{ $transaction->direction == 'credit' ? 'checked' : '' }}>
                                    <label class="form-check-label text-success fw-bold" for="credit-{{ $transaction->id  }}">Credit (Income)</label>
                                </div>
                            </div>
                        </div>

                        <!-- Category Selection -->
                        <div class="col-md-6">
                            <label for="categorySelect{{ $transaction->id }}" class="form-label">Category</label>
                            <select class="form-select" id="categorySelect{{ $transaction->id }}" name="category_id" required>
                                <option disabled>Select a category</option>
                                @foreach($all_categories as $category)
                                    @if($category->status == "active")
                                        <option value="{{ $category->id }}" {{ $transaction->category_id == $category->id ? 'selected' : '' }}>
                                            {{ ucfirst($category->name) }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <!-- Wallet Selection -->
                        <div class="col-md-6 text-start">
                            <label for ="wallet_id{{ $transaction->id }}" class="form-label">Wallet</label>
                            <select class="form-select select2-inside-modal" id="wallet_id{{ $transaction->id }}" name="wallet_id" required>
                                <option disabled selected>Select a wallet</option>
                                @foreach($all_wallets as $wallet)
                                    @if($category->status == "active")
                                        <option value="{{ $wallet->id }}" {{ $transaction->wallet_id == $wallet->id ? 'selected' : '' }}>
                                            {{ ucfirst($wallet->name) }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <!-- MonthYear Dropdown -->
                        <div class="col-md-6">
                            <label class="form-label">Month-Year</label>
                            <select class="form-select" id="monthYearSelect{{ $transaction->id }}" name="month_year_id" required>
                                <option disabled>Select Month-Year</option>
                                @foreach(auth()->user()->family->monthYears as $monthYear)
                                    <option value="{{ $monthYear->id }}" {{ $transaction->month_year_id == $monthYear->id ? 'selected' : '' }}>
                                        {{ $monthYear->year }} - {{ $monthYear->month }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Date Picker -->
                        <div class="col-md-6">
                            <label for="transactionDate" class="form-label">Transaction Date</label>
                            <input type="date" class="form-control" id="transactionDate" name="date" value="{{ isset($transaction) ? $transaction->date : date('Y-m-d') }}">
                        </div>

                        <!-- Comment -->
                        <div class="col-12">
                            <label for="transactionComment" class="form-label">Comment (Optional)</label>
                            <textarea class="form-control" id="transactionComment" rows="3" name="comment" placeholder="Enter any additional details...">{{  $transaction->comment }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-success px-4">
                        <i class="bi bi-check-circle"></i> Transfer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const modalId = "#{{ $modalId }}";
        const form = document.querySelector(`${modalId} form`);
        const submitButton = form.querySelector("button[type='submit']");

        form.addEventListener("submit", function () {
            submitButton.disabled = true;
            submitButton.innerHTML = `
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Saving...
            `;
        });

        // Initialize Select2 when modal is shown
        const modalElement = document.querySelector(modalId);
        modalElement.addEventListener("shown.bs.modal", function () {
            $('#categorySelect{{ $transaction->id }}').select2({
                dropdownParent: $(modalId),
                placeholder: "Select a category",
                width: '100%'
            });

            $('#walletSelect{{ $transaction->id }}').select2({
                dropdownParent: $(modalId),
                placeholder: "Select a wallet",
                width: '100%'
            });
        });

        $('#monthYearSelect{{ $transaction->id }}').select2({
            dropdownParent: $(modalId),
            placeholder: "Select Month-Year",
            width: '100%'
        });
    });
</script>
