<?php
//    require_once("../includes/initialize.php");
//
//    global $database;
//
//    $month = date('Y-m-00');
//    $sql = "SELECT * FROM expenses " ;
//    $sql .= " WHERE `created_at` > '{$month}' AND user_id = {$_SESSION['user_id']}  ORDER BY id DESC";
//    $expenses_set  = Expense::find_by_sql($sql);
//
//
//    // $expenses = Expense::forMonth('June')->all();
//    $number_of_expenses = $database->num_rows($expenses_set)  ;
//    $number_of_expenses_per_page = 6 ;
//    $number_of_pages= ceil((float)$number_of_expenses/(float)$number_of_expenses_per_page);
//
//    $page_number = Helper::get_from_url("pagenumber") ;
//    if(($page_number > $number_of_pages) || ($page_number < 1)){
//        if($number_of_pages != 0){
//            Helper::redirect_to("not_available.php");
//        }
//    }
//
//    $pagination = new Pagination($page_number,$number_of_expenses_per_page,$number_of_expenses);
//
//    $sql = "SELECT * FROM expenses " ;
//    $sql .= " WHERE `created_at` > '{$month}' AND user_id = {$_SESSION['user_id']}  ORDER BY id DESC";
//    $sql .= " LIMIT ".$pagination->per_page ;
//    $sql .= " OFFSET ".$pagination->offset() ;
//
//    $expenses_set  = Expense::find_by_sql($sql);

?>


@extends('layouts.master_layout')
@section('content')
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

<div>
    <table class="table-expenses table table-hover">

        <thead>
            <tr>
                <th>Name</th>
                <th>Price</th>
                <th>Category</th>
                <th>Comment</th>
                <th>Date</th>
                <th>Options</th>
            </tr>
        </thead>

        <tbody class="table-expenses-body">

            @foreach($expenses as $expense)

            <tr class="table-expenses-body-raw">

                        <td class="table-expenses-td">{{ $expense->expense_name }}</td>
                        <td class="table-expenses-td">{{ $expense->price }}</td>
                        <td class="table-expenses-td">{{ $expense->category }}</td>
                        <td class="table-expenses-td">{{  $expense->comment }}</td>
                        <td class="table-expenses-td">{{ date( 'D d-M-Y', strtotime( $expense->created_at ) ) }}</td>

                        <td class="table-expenses-td">
                            <div class="btn-action">
                                    <a class= "btn-action-edit" href="/expenses/{{ $expense->id }}/edit"  value="edit">
                                            <img src="/images/edit.png" class="btn-action-edit-image" alt="edit"></a>
                                    <a class= "btn-action-delete" href="/expenses/{{ $expense->id }}/delete"  value="delete" onclick="return confirm('Are you sure?');">
                                        <img src="/images/delete.png" class="btn-action-delete-image" alt="delete"></a>

                            </div>
                        </td>
                    </tr>
            @endforeach


        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="btn-list">

    {{$expenses->links()}}

    {{--    <a--}}
{{--        @if($pagination->has_prev_page())--}}
{{--            href="?pagenumber={$pagination->prev_page()}"--}}
{{--        @else--}}
{{--            href="?pagenumber={$pagination->current_page()}"--}}
{{--        @endif--}}
{{--        class="btn-list-back btn">Back--}}
{{--    </a>--}}

{{--    <span class="btn-list-page_number">--}}
{{--        @if($pagination->min_limit != 1)--}}
{{--            <a href="?pagenumber=1" class="btn-list-page_number-link">--}}
{{--                1--}}
{{--            </a>--}}
{{--            @if($pagination->current_page() != 3)--}}
{{--                <span class="btn-list-page_number-link-min">---</span>--}}
{{--            @endif--}}
{{--        @endif--}}
{{--        @for($i=$pagination->min_limit ;$i <= $pagination->max_limit;$i++){--}}
{{--            @if($i == $pagination->current_page()){--}}
{{--                <span class="btn-list-page_number-selected">{$i}</span>--}}
{{--            @else--}}
{{--                <a href="?pagenumber={$i}" class="btn-list-page_number-link">--}}
{{--                    {$i}--}}
{{--                </a>--}}
{{--            @endif--}}
{{--        @endfor--}}
{{--        @if($pagination->max_limit != $pagination->total_pages())--}}
{{--            @if( ($pagination->current_page()+2)  != ($pagination->total_pages()) )--}}
{{--                <span class="btn-list-page_number-link-max">---</span>--}}
{{--            @endif--}}
{{--                <a href="?pagenumber={$pagination->total_pages()}"  class="btn-list-page_number-link">--}}
{{--                    {$pagination->total_pages()}--}}
{{--                </a>--}}
{{--        @endif--}}
{{--    </span>--}}


{{--    --}}
{{--        <a--}}
{{--            @if($pagination->has_next_page())--}}
{{--                href="?pagenumber={$pagination->next_page()}"--}}
{{--            @else--}}
{{--                href="?pagenumber={$pagination->current_page()}"--}}
{{--            @endif--}}
{{--            class="btn-list-next btn">Next--}}
{{--        </a>--}}
{{--    --}}

</div>
@endsection
