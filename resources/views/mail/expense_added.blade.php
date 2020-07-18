@component('mail::message')
# New expense had been added!
* expense name: {{ $expense->expense_name }}
* price: {{ $expense->price }}
* category: {{ $expense->category }}

@component('mail::button', ['url' => '/expenses'])
All Expenses
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
