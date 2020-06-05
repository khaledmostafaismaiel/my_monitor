<?php require_once("../includes/session.php")?>
<?php require_once("../includes/db_connection.php")?>
<?php require_once("../includes/functions.php")?>
<?php confirm_sign_in()?>
<?php include("../includes/layout/header.php")?>







<?php

    if(isset($_POST['submit_add_expense'])){
        //prcess the form
        //escape all strings to prevent sql injection with mysqli_prep
        $name = mysqli_prep($_POST["expense_name"]) ;
        $price = $_POST["price"] ;
        $category = $_POST["category"] ;
        $comment = mysqli_prep($_POST["comment"]) ;
        $created_at = $_POST["updated_at"] ;

        $id=get_expense_id_from_url();
        
        $query = "UPDATE expenses SET " ;
        $query .= "expense_name ='{$name}', " ;
        $query .= "price = {$price}, " ;
        $query .= "category = '{$category}', " ;  
        $query .= "comment = {$comment}, " ;
        $query .= "updated_at {$created_at} " ;
        $query .= "WHERE id = {$id} " ;
        $query .= "LIMIT 1" ;

        $result = mysqli_query($connection,$query) ;

        if($result && mysqli_affected_rows($connection) >= 1){
            //success
            $_SESSION["message"] = "Edit success" ;
            redirect_to("index.php?");
        }else{
            //failed
            $message = "Edit DIDN'T success" ;
            redirect_to("edit_expense.php?");
        }

    }else{
        //this is probably $_GET request
        //i will check if user is active or not

    }

?>





<form method = "post">
    <?php $expense_data = get_expense_data_by_id(get_expense_id_from_url()) ?>
    <fieldset class="form_add_expense">
        <legend> 
            <h2>
                Edit Expense ...
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

            <select name="category"  value="<?php echo $expense_data["category"]?>"  size="4" class="form_add_expense-category-menu">
                <?php
                    $category_set = get_all_categories();
                    while($category = mysqli_fetch_assoc($category_set)){
                        $out_put  = "<option>";
                        $out_put .= $category["category_name"] ;
                        $out_put .= "</option>" ;                        
                        echo $out_put ."khaled" ;
                    }
                ?>
            </select>
        </div>

        <div class="form_add_expense-comment">
            <label>Comment:</label> 
            <textarea id="" cols="20" name="comment" value="" rows="3" placeholder="Like,place..."><?php echo $expense_data["comment"]?></textarea>
        </div>

        <div class="form_add_expense-date">
            <label>Date:</label> 
            <input name="created_at" type="date" value="<?php echo $expense_data["created_at"]?>">  
        </div>


        <div class="form_add_expense-cancel">
                <a href="index.php?" class="btn">
                    cancel
                </a>
        </div>

        <div class="form_add_expense-submit">
            <input type="submit" name="submit_add_expense" value="+ add" class="form-sign_in-animated btn">  
        </div>
        

    </fieldset>
</form>



<?php
    //4. release the returned data
    mysqli_free_result($result);            
?>

<?php include("../includes/layout/footer.php")?>

<?php
    //5. close database connection
    mysqli_close($connection);     
?>