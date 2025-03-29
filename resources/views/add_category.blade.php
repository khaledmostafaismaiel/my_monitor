@extends('layouts.master_layout')
@section('content')
    <form method = "POST" action="/categories">
        {{ csrf_field() }}


        <fieldset class="form_add_expense">
            <legend>
                <h2>
                    Add Category ...
                </h2>
            </legend>

            <div class="form_add_expense-name">
                <label>Name:</label>

                <input type="text" name="name" value="{{ old('name') }}" placeholder="Category Name ?"  >
            </div>

            <div class="form_add_expense-cancel">
                    <a href="/" class="btn">
                        cancel
                    </a>
            </div>

            <div class="form_add_expense-submit">
                <input type="submit" name="submit_add_expense" value="+ add" class="form-sign_in-animated btn">
            </div>

        </fieldset>
    </form>

@endsection
