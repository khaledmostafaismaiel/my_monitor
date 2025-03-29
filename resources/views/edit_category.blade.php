@extends('layouts.master_layout')
@section('content')
<form method = "post" action="/categories/{{ $category->id }}">
    {{csrf_field()}}
    {{method_field('PATCH')}}

    <fieldset class="form_edit_expense">
        <legend>
            <h2>
                Edit Category ...

            </h2>
        </legend>

        <div class="form_edit_expense-name">
            <label>Name:</label>

            <input type="text" name="expense_name" value="{{ $category->name }}" placeholder="Category Name ?">
        </div>

        <div class="form_edit_expense-cancel">
                <a href="/categories?" class="btn">
                    cancel
                </a>
        </div>

        <div class="form_edit_expense-submit">
            <input type="submit" name="submit_edit_expense" value="update" class="form-sign_in-animated btn">
        </div>


    </fieldset>
</form>

@endsection
