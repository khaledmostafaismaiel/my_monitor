@if(session('message'))
    <div class="message-session">
        <span class = "message-session-span">{{ session('message') }}</span>
    </div>
@endif
