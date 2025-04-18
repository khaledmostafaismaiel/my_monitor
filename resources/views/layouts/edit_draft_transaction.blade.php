<div class="modal fade" id="editTransaction{{ $transaction->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editTransactionLabel{{ $transaction->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content shadow-lg border-0 rounded-3">

            <!-- Modal Header -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editTransactionLabel{{ $transaction->id }}">
                    <i class="bi bi-pencil-square me-2"></i> Edit
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Form Start -->
            <form method="POST" action="/draft_transactions/{{ $transaction->id }}">
                <div class="modal-body px-4">
                    {{ csrf_field() }}
                    @method('PUT')

                    <div class="row g-3">
                        <!-- Transaction Name -->
                        <div class="col-md-6 text-start">
                            <label for="transactionName{{ $transaction->id }}" class="form-label">Transaction Name</label>
                            <input type="text" class="form-control" id="transactionName{{ $transaction->id }}" name="name" value="{{ $transaction->name }}" placeholder="Enter transaction name" required>
                        </div>

                        <!-- Amount Input -->
                        <div class="col-md-6 text-start">
                            <label class="form-label">Price Per Unit</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">E£</span>
                                <input type="number" class="form-control" name="price" value="{{ $transaction->price }}" min="0" step="0.01" required>
                            </div>
                        </div>

                        <!-- Quantity Input -->
                        <div class="col-md-6">
                            <label class="form-label">Quantity</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="quantity" value="{{ $transaction->quantity }}" min="1" step="0.01" required>
                            </div>
                        </div>

                        <!-- Transaction Direction -->
                        <div class="col-md-6 text-start">
                            <label class="form-label">Transaction Direction</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input border-primary" type="radio" name="direction" id="debit{{ $transaction->id }}" value="debit" {{ $transaction->direction == 'debit' ? 'checked' : '' }}>
                                    <label class="form-check-label text-danger fw-bold" for="debit{{ $transaction->id }}">Debit (Expense)</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input border-primary" type="radio" name="direction" id="credit{{ $transaction->id }}" value="credit" {{ $transaction->direction == 'credit' ? 'checked' : '' }}>
                                    <label class="form-check-label text-success fw-bold" for="credit{{ $transaction->id }}">Credit (Income)</label>
                                </div>
                            </div>
                        </div>

                        <!-- Category Selection -->
                        <div class="col-md-6 text-start">
                            <label class="form-label">Category</label>
                            <select class="form-select" name="category_id" required>
                                <option disabled>Select a category</option>
                                @foreach($categories as $category)
                                    @if($category->status == "active")
                                        <option value="{{ $category->id }}" {{ $transaction->category_id == $category->id ? 'selected' : '' }}>
                                            {{ ucfirst($category->name) }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <!-- MonthYear Dropdown -->
                        <div class="col-md-6 text-start">
                            <label for="monthYear{{ $transaction->id }}" class="form-label">Month-Year</label>
                            <select class="form-select" name="month_year_id" id="monthYear{{ $transaction->id }}" required>
                                <option disabled>Select Month-Year</option>
                                @foreach(auth()->user()->family->monthYears as $monthYear)
                                    <option value="{{ $monthYear->id }}" {{ $transaction->month_year_id == $monthYear->id ? 'selected' : '' }}>
                                        {{ $monthYear->year }} - {{ $monthYear->month }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Date Picker -->
                        <div class="col-md-6 text-start">
                            <label for="transactionDate{{ $transaction->id }}" class="form-label">Transaction Date</label>
                            <input type="date" class="form-control" id="transactionDate{{ $transaction->id }}" name="date" value="{{ $transaction->date }}" required>
                        </div>

                        <!-- User Dropdown -->
                        <div class="col-md-6 text-start">
                            <label for="user_id{{ $transaction->id }}" class="form-label">User</label>
                            <select class="form-select" name="user_id" id="user_id{{ $transaction->id }}" required>
                                <option disabled>Select User</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ $transaction->user_id == $user->id ? 'selected' : '' }}>
                                        {{ $user->first_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Comment -->
                        <div class="col-12 text-start">
                            <label for="transactionComment{{ $transaction->id }}" class="form-label">Comment (Optional)</label>
                            <textarea class="form-control" id="transactionComment{{ $transaction->id }}" rows="3" name="comment" placeholder="Enter any additional details...">{{ $transaction->comment }}</textarea>
                        </div>
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
        const form = document.querySelector("#editTransaction{{ $transaction->id }} form");
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
