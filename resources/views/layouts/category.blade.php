<div class="modal fade" id="{{ $modalId }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="staticBackdropLabel">Category</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method = "POST" action="/categories/{{$category->id}}">
            <div class="modal-body">

                {{ csrf_field() }}
                @method('PUT')

                <div class="mb-3">
                    <label for="exampleInputName" class="form-label">Name</label>
                    <input type="text" class="form-control" id="exampleInputName" aria-describedby="nameHelp" name="name" value="{{$category->name}}">
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
