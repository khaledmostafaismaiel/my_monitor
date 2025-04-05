@include('layouts/header')

@include('layouts/error_messages')

<div class="flex-grow-1">
    @yield('content')
</div>

@stack('modals')

@include('layouts/footer')
