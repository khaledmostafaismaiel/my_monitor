@extends('layouts.master_layout')
@section('content')


<div class="d-flex justify-content-end mt-20">
    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addTransaction">
        Add
    </button>
</div>
@include('layouts/add_transaction')

<table class="table table-striped  table-light table-hover">
    <thead>
        <tr>
            <th scope="col">Name</th>
            <th scope="col">Type</th>
            <th scope="col">Price</th>
            <th scope="col">Category</th>
            <th scope="col">Date</th>
            <th scope="col">Options</th>
        </tr>
    </thead>
    <tbody>
        @foreach($transactions as $transaction)
            <tr>
                <td scope="row">{{ $transaction->name }}</td>
                <td>{{ $transaction->type }}</td>
                <td>{{ $transaction->price }}</td>
                <td>{{ $transaction->category->name }}</td>
                <td>{{ date( 'D d-M-Y', strtotime( $transaction->date ) ) }}</td>

                <td class="table-expenses-td">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editTransaction{{ $transaction->id }}">
                        Edit
                    </button>
                    @include('layouts/transaction', ['transaction' => $transaction, 'modalId' => "editTransaction{$transaction->id}"])

                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteTransaction{{ $transaction->id }}">
                        Delete
                    </button>
                    @include('layouts/delete_transaction', ['transaction' => $transaction, 'modalId' => "deleteTransaction{$transaction->id}"])
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<div class="d-flex justify-content-center">
    {{$transactions->links()}}
</div>

@endsection
