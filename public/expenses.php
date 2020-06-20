<?php 
    require_once("../includes/initialize.php");

    global $database;

    $month = date('Y-m-00');
    $sql = "SELECT * FROM expenses " ;
    $sql .= " WHERE `created_at` > '{$month}' AND user_id = {$_SESSION['user_id']}  ORDER BY id DESC";
    $expenses_set  = Expense::find_by_sql($sql);


    // $expenses = Expense::forMonth('June')->all();
    $number_of_expenses = $database->num_rows($expenses_set)  ;
    $number_of_expenses_per_page = 6 ;
    $number_of_pages= ceil((float)$number_of_expenses/(float)$number_of_expenses_per_page);

    $page_number = Helper::get_from_url("pagenumber") ;
    if(($page_number > $number_of_pages) || ($page_number < 1)){
        if($number_of_pages != 0){ 
            Helper::redirect_to("not_available.php");
        }
    }

    $pagination = new Pagination($page_number,$number_of_expenses_per_page,$number_of_expenses);

    $sql = "SELECT * FROM expenses " ;
    $sql .= " WHERE `created_at` > '{$month}' AND user_id = {$_SESSION['user_id']}  ORDER BY id DESC";
    $sql .= " LIMIT ".$pagination->per_page ;
    $sql .= " OFFSET ".$pagination->offset() ;
    
    $expenses_set  = Expense::find_by_sql($sql);

?>



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



            <?php
                if($expenses_set != null){ 
                    foreach($expenses_set as $expense):
            ?>
                    <tr class="table-expenses-body-raw">

                        <td class="table-expenses-td"><?php echo $database->html_sanitize($expense->expense_name) ?></td>
                        <td class="table-expenses-td"><?php echo $database->html_sanitize($expense->price)?></td>
                        <td class="table-expenses-td"><?php echo ucfirst($database->html_sanitize($expense->category)) ?></td>
                        <td class="table-expenses-td"><?php echo $database->html_sanitize($expense->comment) ?></td>
                        <td class="table-expenses-td"><?= $database->html_sanitize(date( 'D d-M-Y', strtotime( $expense->created_at ) )) ?></td>
                        
                        <td class="table-expenses-td">
                            <div class="btn-action">
                                    <a class= "btn-action-edit" href="edit_expense.php?expenseid=<?php echo $database->encode_url($expense->id) ?>"  value="edit">
                                            <img src="images/edit.png" class="btn-action-edit-image" alt="edit"></a>
                                    <a class= "btn-action-delete" href="delete_expense.php?expenseid=<?php echo $database->encode_url($expense->id) ?>"  value="delete" onclick="return confirm('Are you sure?');">
                                        <img src="images/delete.png" class="btn-action-delete-image" alt="delete"></a>
                                    </a>
                            </div>
                        </td>
                    </tr>

            <?php 
                    endforeach;
                }
                $database->free_result($expenses_set);
            ?>
            
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="btn-list">

    <?php
        echo "<a";
        if($pagination->has_prev_page()){
            echo " href=\"" ;
            echo " ?pagenumber=" ;
            echo  "{$pagination->prev_page()}"  ;
            echo "\"" ; 
        }else{
            echo " href=\"?pagenumber={$pagination->current_page()}\"" ;
        }
        echo "class=\"btn-list-back btn\"" ;
        echo ">";
        echo "Back";
        echo "</a> " ;
    ?>

    <span class="btn-list-page_number">
        <?php
            if($pagination->min_limit != 1){
                echo "<span class=\"btn-list-page_number-selected-min\">1</span>" ;
                echo "<span class=\"btn-list-page_number-link-min\">...</span>" ;
            }
            for($i=$pagination->min_limit ;$i <= $pagination->max_limit;$i++){
                if($i == $pagination->current_page()){
                    echo "<span class=\"btn-list-page_number-selected\">{$i}</span>" ;
                }else{
                    echo "<a href=\"?pagenumber={$i}\"  class=\"btn-list-page_number-link\">{$i}</a>" ;
                }
            }
            if($pagination->max_limit != $pagination->total_pages()){
                echo "<span class=\"btn-list-page_number-link-max\">...</span>" ;
                echo "<span class=\"btn-list-page_number-selected-max\">{$pagination->total_pages()}</span>" ;
            }

        ?>
    </span>


    <?php
        echo "<a";
        if($pagination->has_next_page()){
            echo " href=\"" ;
            echo " ?pagenumber=" ;
            echo  "{$pagination->next_page()}"  ;
            echo "\"" ;
        }else{
            echo " href=\"?pagenumber={$pagination->current_page()}\"" ;
        }
        echo "class=\"btn-list-next btn\"" ;
        echo ">";
        echo "Next";
        echo "</a> " ;
    ?>

</div>
<?php Helper::include_layout_template("footer.php")?>