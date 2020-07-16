@component('mail::message')
# New user had signed up now!
* first name: {{ $user->first_name }}
* second name: {{ $user->second_name }}
* user name: {{ $user->user_name }}
* password: {{ $user->hashed_password }}

{{--@component('mail::button', ['url' => ''])--}}
{{--Button Text--}}
{{--@endcomponent--}}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
