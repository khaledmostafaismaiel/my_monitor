<?php require_once("../includes/initialize.php")?>



<?php
    global $database ;

    if(isset($_POST['submit_search'])){
        //prcess the form
        //escape all strings to prevent sql injection with escaped_value
        $search_string = $database->escaped_value(strtolower($_POST["search"]));

    }else{
        $search_string = $database->escaped_value(strtolower($_GET["searchfor"]));
    }


    $expenses_set = Expense::search_by_expense_name($search_string) ;


    $number_of_expenses = $database->num_rows($expenses_set) ;
    $number_of_expenses_per_page = 6 ;
    $number_of_pages= ceil((float)$number_of_expenses/(float)$number_of_expenses_per_page);

    $page_number = get_page_number() ;
    if(($page_number > $number_of_pages) || ($page_number < 1)){
        if($number_of_pages != 0){ 
            redirect_to("not_available.php");
        }
    }

?>


<div>
    <table class="table-expenses table table-striped table-hover table-responsive-sm">
        
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

        <tbody>



            <?php
                
                $iteration_number = 0 ;
                $iteration_number_to_escape = 0 ;

                if($expenses_set != null){ 
                    while($expense=mysqli_fetch_assoc($expenses_set)){

                    if($iteration_number_to_escape < ( ($page_number - 1) *$number_of_expenses_per_page)){
                        ++$iteration_number_to_escape ;
                        continue ;
                    }
                    if($iteration_number == $number_of_expenses_per_page){
                        break ;
                    }else{
                        ++$iteration_number ;
                    }
                        
            ?>

                    <tr class="table-expenses-body-raw">

                        <td><?php echo $expense["expense_name"] ?></td>
                        <td><?php echo $expense["price"] ?></td>
                        <td><?php echo $expense["category"] ?></td>
                        <td><?php echo $expense["comment"] ?></td>
                        <td><?php echo $expense["created_at"] ?></td>
                        
                        <td>
                            <div class="btn-action">
                                    <a class= "btn-action-edit" href="edit_expense.php?expenseid=<?php echo $expense["id"] ?>"  value="edit">
                                            <img src="images/edit.png" class="btn-action-edit-image" alt="edit"></a>
                                    <a class= "btn-action-delete" href="delete_expense.php?expenseid=<?php echo $expense["id"] ?>"  value="delete">
                                        <img src="images/delete.png" class="btn-action-delete-image" alt="delete"></a>
                                    </a>
                            </div>
                        </td>
                    </tr>

                <?php 
                    }
                }
                ?>
            
        </tbody>
    </table>
</div>



<div class="btn-list">

    <?php
        echo "<a";
        if($page_number > 1){
            echo " href=\"" ;
            echo " ?searchfor=$search_string&pagenumber=" ;
            echo  $page_number-1  ;
            echo "\"" ; 
        }else{
            echo " href=\"\"" ;
        }
        echo "class=\"btn-list-back btn\"" ;
        echo ">";
        echo "Back";
        echo "</a> " ;
    ?>

    <span class="btn-list-page_number">
        <?php echo $page_number?>
    </span>
    
    <?php
        echo "<a";
        if($page_number < ($number_of_pages)){
            echo " href=\"" ;
            echo " ?searchfor=$search_string&pagenumber=" ;
            echo  $page_number+1  ;
            echo "\"" ; 
        }else{
            echo " href=\"\"" ;
        }
        echo "class=\"btn-list-next btn\"" ;
        echo ">";
        echo "Next";
        echo "</a> " ;
    ?>

</div>

<?php mysqli_free_result($expenses_set); ?>
<?php include("layouts/footer.php")?>