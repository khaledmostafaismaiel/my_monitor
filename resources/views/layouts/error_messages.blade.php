@if($errors->any())
    <div class="alert alert-danger" role="alert" style="position: absolute; top: 80px; left: 50%; transform: translateX(-50%); z-index: 9999; width: 80%; max-width: 600px; text-align: center;">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li class="mb-2">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
