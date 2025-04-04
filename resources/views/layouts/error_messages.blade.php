@if($errors->any())
    <div class="alert alert-danger" role="alert">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li class="mb-2">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
