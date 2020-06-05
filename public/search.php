<?php require_once("../includes/session.php")?>
<?php require_once("../includes/db_connection.php")?>
<?php require_once("../includes/functions.php")?>
<?php confirm_sign_in()?>
<?php include("../includes/layout/header.php")?>




<?php
    if(isset($_POST['edit_delete_submit'])){
            //prcess the form
            //escape all strings to prevent sql injection with mysqli_prep
            if($_POST["edit_delete"] === "edit"){
                redirect_to("edit_expense.php") ;
            }elseif($_POST["edit_delete"] === "delete"){

                //perform deleteing method
            }else{

            }

        }else{
            //this is probably $_GET request
            //i will check if user is active or not

        }

?>





<?php 
    $number_of_expenses = 13 ;
    $number_of_expenses_per_page = 5 ;
    if($number_of_expenses <= 5){
        $number_of_pages = 1 ;
    }else if(($number_of_expenses % $number_of_expenses_per_page) == 0){
        $number_of_pages = $number_of_expenses / $number_of_expenses_per_page ;
    }else{
        $number_of_pages = $number_of_expenses / $number_of_expenses_per_page ;
        $number_of_pages += 1 ;
    }
?>


<?php 

    $page_number = get_page_number() ;
    if($page_number > $number_of_pages){
        //redirect to expenses.php ;
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
                $id=1;
                while($expense_data=get_expense_data_by_id($id++)){ 

                // $expenses_set = get_all_expenses();
                // while($expense = mysqli_fetch_object($expenses_set)){
                        
            ?>

                    <tr class="table-expenses-body-raw">
<!-- 
                        <td><?php echo $expense["expense_name"] ?></td>
                        <td><?php echo $expense["price"] ?></td>
                        <td><?php echo $expense["category"] ?></td>
                        <td><?php echo $expense["comment"] ?></td>
                        <td><?php echo $expense["created_at"] ?></td> -->


                        <td><?php echo $expense_data["expense_name"] ?></td>
                        <td><?php echo $expense_data["price"] ?></td>
                        <td><?php echo $expense_data["category"] ?></td>
                        <td><?php echo $expense_data["comment"] ?></td>
                        <td><?php echo $expense_data["created_at"] ?></td>
                        
                        <td>
                            <div class="row">
                                <div class="btn-action">
                                    <a href="" class="">
                                        <i class="" aria-hidden="true"></i>
                                    </a>
                                </div>
                                
                                <div class="btn-action">
                                    <form class="form-expenses" name="submit" method="POST">
                                        <input class="form-expenses-edit" type="radio" name="edit_delete" value="edit">
                                        <input class="form-expenses-delete" type="radio" name="edit_delete" value="delete">
                                        <button class="form_expenses-submit" name="edit_delete_submit" type="submit"  title="Delete">
                                        <i class="" aria-hidden="true"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>

            <?php
            
                if($id == 7 ){
                    break;
                }
            }?>
            
        </tbody>
    </table>
</div>



<div class="btn-list">

    <?php
        echo "<a";
        if($page_number > 1){
            echo " href=\"" ;
            echo " ?pagenumber=" ;
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
        if($page_number < ($number_of_pages - 1 )){
            echo " href=\"" ;
            echo " ?pagenumber=" ;
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


<?php
    //4. release the returned data
    mysqli_free_result($result);            
?>

<?php include("../includes/layout/footer.php")?>

<?php
    //5. close database connection
    mysqli_close($connection);
?>