<div class="modal fade" id="{{ $modalId }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="staticBackdropLabel">Transaction</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method = "POST" action="/transactions/{{$transaction->id}}">
            <div class="modal-body">

                {{ csrf_field() }}
                @method('PUT')

                <div class="mb-3">
                    <label for="exampleInputName" class="form-label">Name</label>
                    <input type="text" class="form-control" id="exampleInputName" aria-describedby="nameHelp" name="name" value="{{$transaction->name}}">
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="radio" name="type" id="transactionRadioDefault1" value="debit" @if($transaction->type == "debit") checked @endif>
                    <label class="form-check-label" for="transactionRadioDefault1">
                        Debit
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="radio" name="type" id="transactionRadioDefault2" value="credit" @if($transaction->type == "credit") checked @endif>
                    <label class="form-check-label" for="transactionRadioDefault2">
                        Credit
                    </label>
                </div>

                <div class="input-group mb-3">
                    <span class="input-group-text">E£</span>
                    <span class="input-group-text">0.00</span>
                    <input type="text" class="form-control" aria-label="E£ amount (with dot and two decimal places)" name="price" value="{{$transaction->price}}">
                </div>


                <select class="form-select" aria-label="Default select example" name="category_id">
                    <option>Open this select menu</option>
                    @foreach($categories as $category)
                        <option value="{{$category->id}}"
                            @if($transaction->category_id == $category->id)
                                selected
                            @endif
                        >
                            {{ucfirst($category->name)}}
                        </option>
                    @endforeach;
                </select>

                <div class="mb-3">
                    <label for="exampleInputDate" class="form-label">Date</label>
                    <input type="date" class="form-control" id="exampleInputDate" aria-describedby="dateHelp" name="date" value="{{$transaction->date}}">
                </div>

                <div class="mb-3">
                    <label for="exampleFormControlTextarea1" class="form-label">Comment</label>
                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="comment">{{$transaction->comment}}</textarea>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
  </div>
</div>
