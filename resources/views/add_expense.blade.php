@extends('layouts.master_layout')
@section('content')
    <form method = "POST" action="/expenses">
        {{ csrf_field() }}


        <fieldset class="form_add_expense">
            <legend>
                <h2>
                    Add Expense ...
                </h2>
            </legend>

            <div class="form_add_expense-name">
                <label>Name:</label>

                <input type="text" name="expense_name" value="{{ old('name') }}" placeholder="Expense Name ?"  >
            </div>

            <div class="form_add_expense-price">
                <label>Price:</label>

                <input type="text" name="price" value="{{ old('price') }}" placeholder="Expense Price ?"  >
            </div>

            <div class="form_add_expense-category">
                <label>Category:</label>

                <select name="category" id=""  size="4" class="form_add_expense-category-menu"  >

                        @foreach($categories as $category)
                            <option value="{{$category->id}}"
                                @if($category->id == old('category_id'))
                                    selected
                                @endif >
                                {{ucfirst($category->name)}}
                            </option>

                        @endforeach;

                </select>
            </div>

            <div class="form_add_expense-comment">
                <label>Comment:</label>
                <textarea id="" cols="20" name="comment" rows="3" value="{{ old('comment') }}" placeholder="Like,place..."></textarea>
            </div>

            <div class="form_add_expense-date">
                <label>Date:</label>
                <input name="created_at" type="date" value="{{ old('created_at') }}">
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
