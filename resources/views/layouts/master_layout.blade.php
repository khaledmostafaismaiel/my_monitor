@include('layouts/header')

<div class="flex-grow-1">
    @yield('content')
</div>

@include('layouts/error_messages')
