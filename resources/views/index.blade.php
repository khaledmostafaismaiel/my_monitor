@extends('layouts.master_layout')
@section('content')
<div class="money_spent">
    <p class="money_spent-first_line">
        Hi,<span class = "money_spent-first_line-user_name">{{auth()->user()->first_name}}</span> you spent
    </p>
    <p class="money_spent-second_line">
        <?php /*echo $database->html_sanitize(Expense::get_all_month_prices()? :0) */?> E£
    </p>

    <p class="money_spent-third_line">
        {{ date("1/n/Y") }}<span class = "money_spent-first_line-user_name"> TO </span>{{ date("j/n/Y",strtotime("today")) }}
    </p>
</div>
@endsection
