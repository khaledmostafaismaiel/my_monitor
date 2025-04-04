@include('layouts/header')

@include('layouts/error_messages')

<div class="flex-grow-1">
    @yield('content')
</div>

@include('layouts/footer')