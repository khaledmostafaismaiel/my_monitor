<?php require_once("../includes/session.php")?>
<?php require_once("../includes/db_connection.php")?>
<?php require_once("../includes/functions.php")?>
<?php require_once("../includes/main_functions.php")?>
<?php confirm_sign_in()?>
<?php include("../includes/layout/header.php")?>







<?php

    if(isset($_POST['submit_delete_expense'])){

        $id=get_expense_id_from_url();
        if(!get_expense_data_by_id($id)){
            rediret_to("not_available.php");
        }
        if(delete_expense_from_database($id) && mysqli_affected_rows($connection) >= 1){
            //success
            $_SESSION["message"] = "Delete success" ;
            redirect_to("expenses.php?pagenumber=1");
        }else{
            //failed
            $_SESSION["message"] = "Delete Didn't success" ;
            redirect_to("delete_expense.php?expenseid={$id}");
        }

    }else{
        //this is probably $_GET request
        //i will check if user is active or not

    }

?>





<form method = "post" form_delete_expense">
    <?php $expense_data = get_expense_data_by_id(get_expense_id_from_url()) ?>


    <fieldset class="form_delete_expense">
        <legend> 
            <h2>
                Delete ...
                <!-- <?php if(!empty($message)){
                            echo $message ;
                        }
                ?> -->
            </h2>   
        </legend>

        <div class="form_add_expense-name">
            <label>Name:</label> 

            <input type="text" name="expense_name" value="<?php echo $expense_data["expense_name"]?>" placeholder="Expense Name ?">  
        </div>

        <div class="form_add_expense-price">
            <label>Price:</label> 

            <input type="text" name="price" value="<?php echo $expense_data["price"]?>" placeholder="Expense Price ?">  
        </div>


        
        <div class="form_add_expense-category">
            <label>Category:</label> 

            <select name="category"  value="<?php echo $expense_data["category"]?>"  size="1" class="form_delete_expense-category-menu">
                <?php
                    $category_set = get_all_categories();
                    while($category = mysqli_fetch_assoc($category_set)){
                        $out_put  = "<option>";
                        $out_put .= $category["category_name"] ;
                        $out_put .= "</option>" ;                        
                        echo $out_put ;
                    }
                ?>
            </select>
        </div>

        <div class="form_add_expense-comment">
            <label>Comment:</label> 
            <textarea id="" cols="20" name="comment" rows="3" placeholder="Like,place..."> <?php echo $expense_data["comment"]?> </textarea>
        </div>

        <div class="form_add_expense-date">
            <label>Date:</label> 
            <input name="created_at" type="date" value="<?php echo $expense_data["created_at"]?>">  
        </div>


        <div class="form_delete_expense-cancel">
                <a href="index.php?" class="btn">
                    cancel
                </a>
        </div>

        <div class="form_delete_expense-submit">
            <input type="submit" name="submit_delete_expense" value="delete" class="form-sign_in-animated  btn">  
        </div>
        

    </fieldset>
</form>



<?php include("../includes/layout/footer.php")?>
