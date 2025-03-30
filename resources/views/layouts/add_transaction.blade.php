<div class="modal fade" id="addTransaction" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-3">
            
            <!-- Modal Header -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="staticBackdropLabel">New Transaction</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Form Start -->
            <form method="POST" action="/transactions">
                <div class="modal-body px-4">
                    {{ csrf_field() }}

                    <!-- Transaction Name -->
                    <div class="mb-3">
                        <label for="transactionName" class="form-label">Transaction Name</label>
                        <input type="text" class="form-control" id="transactionName" name="name" placeholder="Enter transaction name" required>
                    </div>

                    <!-- Transaction Type -->
                    <div class="mb-3">
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

                    <!-- Amount Input -->
                    <div class="mb-3">
                        <label class="form-label">Amount</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">E£</span>
                            <input type="number" class="form-control" name="price" placeholder="Enter amount" min="0" step="0.01" required>
                        </div>
                    </div>

                    <!-- Category Selection -->
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select class="form-select" name="category_id" required>
                            <option disabled selected>Select a category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ ucfirst($category->name) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date Picker -->
                    <div class="mb-3">
                        <label for="transactionDate" class="form-label">Transaction Date</label>
                        <input type="date" class="form-control" id="transactionDate" name="date" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <!-- Comment -->
                    <div class="mb-3">
                        <label for="transactionComment" class="form-label">Comment (Optional)</label>
                        <textarea class="form-control" id="transactionComment" rows="3" name="comment" placeholder="Enter any additional details..."></textarea>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer bg-light d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Transaction</button>
                </div>
            </form>
        </div>
    </div>
</div>
