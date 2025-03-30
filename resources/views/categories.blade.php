@extends('layouts.master_layout')
@section('content')



<div class="d-flex justify-content-end mt-20">
    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
        Add
    </button>
</div>
@include('layouts/add_category')

<table class="table table-striped  table-light table-hover">
    <thead>
        <tr>
            <th scope="col">Name</th>
            <th scope="col">Options</th>
        </tr>
    </thead>
    <tbody>
    @foreach($categories as $category)
        <tr>
            <td scope="row">{{ $category->name }}</td>

            <td class="table-expenses-td">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editCategory{{ $category->id }}">
                    Edit
                </button>
                @include('layouts/category', ['category' => $category, 'modalId' => "editCategory{$category->id}"])

                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteCategory{{ $category->id }}">
                    Delete
                </button>
                @include('layouts/delete_category', ['category' => $category, 'modalId' => "deleteCategory{$category->id}"])
            </td>
        </tr>
        @endforeach
    </tbody>
</table>


<div class="d-flex justify-content-center">
    {{$categories->links()}}
</div>

@endsection
