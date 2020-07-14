<?php
//
//    require_once("../includes/initialize.php") ;
//
//    global $database;
//
//    $id=Helper::get_from_url("expenseid");
//
//    $expense = new Expense();
//
//    if(! $expense = $expense::find_by_id($id)){
//        Helper::redirect_to("not_available.php");
//    }
//
//    if($expense->delete() && $database->affected_rows($database->get_connection() ) >= 1){
//        //success
//        Log::write_in_log("{$_SESSION['user_id']} delete expense ".date("d-m-Y")." ".date("h:i:sa")."\n");
//
//        $_SESSION["message"] = "Delete success" ;
//    }else{
//        //failed
//        $_SESSION["message"] = "Delete Didn't success" ;
//    }
//
//    if(isset($database)){
//        $database->close_connection();
//    }
//
//    Helper::redirect_to("expenses.php?pagenumber=1");
?>
@extends('layouts.master_layout')
@section('content')
    <form method = "post" action="/expenses/{{ $expense->id }}/delete">
        {{csrf_field()}}
        {{method_field('PATCH')}}

        <fieldset class="form_edit_expense">
            <legend>
                <h2>
                    Delete Expense ...

                </h2>
            </legend>

            <div class="form_edit_expense-name">
                <label>Name:</label>

                <input type="text" name="expense_name" value="{{ $expense->expense_name }}" placeholder="Expense Name ?">
            </div>

            <div class="form_edit_expense-price">
                <label>Price:</label>

                <input type="text" name="price" value="{{ $expense->price }}" placeholder="Expense Price ?">
            </div>

            <div class="form_edit_expense-category">
                <label>Category:</label>

                <select name="category"  size="4" class="form_edit_expense-category-menu">
                    @foreach($category_set as $category)
                        <option
                            @if(ucfirst($category->category_name) == ucfirst($expense->category))
                            selected
                            @endif
                        >
                            {{ucfirst($category->category_name)}}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form_edit_expense-comment">
                <label>Comment:</label>
                <textarea id="" cols="20" name="comment" value="" rows="3" placeholder="Like,place...">{{ $expense->comment }}</textarea>
            </div>

            <div class="form_edit_expense-date">
                <label>Date:</label>
                <input name="created_at" type="date" value="{{ date( 'Y-m-d', strtotime($expense->created_at) ) }}">
            </div>


            <div class="form_edit_expense-cancel">
                <a href="/expenses?" class="btn">
                    cancel
                </a>
            </div>

            <div class="form_edit_expense-submit">
                <input type="submit" name="submit_edit_expense" value="update" class="form-sign_in-animated btn">
            </div>


        </fieldset>
    </form>

@endsection
