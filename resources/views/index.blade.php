@extends('layouts.master_layout')
@section('content')
<table class="table table-striped  table-light table-hover mt-20">
    <thead>
        <tr>
        <th scope="col">Month</th>
        <th scope="col">Credit</th>
        <th scope="col">Debit</th>
        <th scope="col">Undocumented</th>
        </tr>
    </thead>
    <tbody>
        @foreach($transactions as $transaction)
            <tr>
                <th scope="row">{{$transaction->month_year}}</th>
                <td>{{$transaction->credit}}</td>
                <td>{{$transaction->debit}}</td>
                <td>{{$transaction->credit - $transaction->debit}}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
