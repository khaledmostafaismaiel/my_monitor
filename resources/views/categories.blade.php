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
            </tr>
        </thead>

        <tbody class="table-expenses-body">

            @foreach($categories as $category)

            <tr class="table-expenses-body-raw">

                        <td class="table-expenses-td">{{ $category->name }}</td>

                        <td class="table-expenses-td">
                            <div class="btn-action">
                                    <a class= "btn-action-edit" href="/categories/{{ $category->id }}/edit"  value="edit">
                                            <img src="/images/edit.png" class="btn-action-edit-image" alt="edit"></a>
                                    <a class= "btn-action-delete" href="/categories/{{ $category->id }}/delete"  value="delete" onclick="return confirm('Are you sure?');">
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

    {{$categories->links()}}

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
