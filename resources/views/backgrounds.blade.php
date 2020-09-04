@extends('layouts.master_layout')
@section('content')
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

    <div>
    <table class="table-backgrounds table table-hover">

        <thead>
            <tr>
                <th>Image</th>
                <th>Filename</th>
                <th>Caption</th>
                <th>Size</th>
                <th>Type</th>
                <th>Options</th>
            </tr>
        </thead>

        <tbody class="table-backgrounds-body">

            @foreach( $backgrounds as $background ):

                    <tr class="table-backgrounds-body-raw">

                        <td class="table-backgrounds-td"><img src="/storage/uploads/{{$background->temp_name}}" jpg;base64 width="80" alt="{{ $background->file_name }}"></td>
                        <td class="table-backgrounds-td">{{ $background->file_name }}</td>
                        <td class="table-backgrounds-td">{{ $background->caption }}</td>
                        <td class="table-backgrounds-td">{{ $background->get_size_text() }}</td>
                        <td class="table-backgrounds-td">{{ $background->type }}</td>

                        <td class="table-backgrounds-td">
                            <div class="btn-action">
                                    <a class= "btn-action-edit" href="/expenses/{{ $background->id }}/set"  value="set">
                                            <img src="/images/set.png" class="btn-action-edit-image" alt="set"></a>
                                    <a class= "btn-action-delete" href="/backgrounds/{{ $background->id }}/delete"  value="delete" onclick="return confirm('Are you sure?');">
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

    {{$backgrounds->links()}}

        <?php
//        echo "<a";
//        if($pagination->has_prev_page()){
//            echo " href=\"" ;
//            echo " ?pagenumber=" ;
//            echo  "{$pagination->prev_page()}"  ;
//            echo "\"" ;
//        }else{
//            echo " href=\"?pagenumber={$pagination->current_page()}\"" ;
//        }
//        echo "class=\"btn-list-back btn\"" ;
//        echo ">";
//        echo "Back";
//        echo "</a> " ;
        ?>

        <span class="btn-list-page_number">
        <?php
//        if($pagination->min_limit != 1){
//            echo "<a href=\"?pagenumber=1\"  class=\"btn-list-page_number-link\">1</a>" ;
//            if($pagination->current_page() != 3){
//                echo "<span class=\"btn-list-page_number-link-min\">...</span>" ;
//            }
//        }
//        for($i=$pagination->min_limit ;$i <= $pagination->max_limit;$i++){
//            if($i == $pagination->current_page()){
//                echo "<span class=\"btn-list-page_number-selected\">{$i}</span>" ;
//            }else{
//                echo "<a href=\"?pagenumber={$i}\"  class=\"btn-list-page_number-link\">{$i}</a>" ;
//            }
//        }
//        if($pagination->max_limit != $pagination->total_pages()){
//            if( ($pagination->current_page()+2)  != ($pagination->total_pages()) ){
//                echo "<span class=\"btn-list-page_number-link-max\">...</span>" ;
//            }
//            echo "<a href=\"?pagenumber={$pagination->total_pages()}\"  class=\"btn-list-page_number-link\">{$pagination->total_pages()}</a>" ;
//        }

        ?>
    </span>


        <?php
//        echo "<a";
//        if($pagination->has_next_page()){
//            echo " href=\"" ;
//            echo " ?pagenumber=" ;
//            echo  "{$pagination->next_page()}"  ;
//            echo "\"" ;
//        }else{
//            echo " href=\"?pagenumber={$pagination->current_page()}\"" ;
//        }
//        echo "class=\"btn-list-next btn\"" ;
//        echo ">";
//        echo "Next";
//        echo "</a> " ;
        ?>

    </div>
@endsection
