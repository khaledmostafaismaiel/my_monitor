<?php require_once("../includes/session.php")?>
<?php require_once("../includes/db_connection.php")?>
<?php require_once("../includes/functions.php")?>
<?php require_once("../includes/main_functions.php")?>
<?php confirm_sign_in()?>
<?php include("../includes/layout/header.php")?>


<?php

    if(isset($_POST['submit_add_expense'])){

        if(add_expense_in_database("expense_name","price","category","comment","created_at")){
            //success
            $_SESSION["message"] = "add success" ;
            redirect_to("index.php?");
        }else{
            //failed
            $_SESSION["message"] = "add DIDN'T success" ;
            redirect_to("add_expense.php?");
        }

    }else{
        //this is probably $_GET request
        //i will check if user is active or not

    }

?>





<form method = "post">
    
    <fieldset class="form_add_expense">
        <legend> 
            <h2>
                Add Expense ...
            </h2>   
        </legend>

        <div class="form_add_expense-name">
            <label>Name:</label> 

            <input type="text" name="expense_name" placeholder="Expense Name ?">  
        </div>

        <div class="form_add_expense-price">
            <label>Price:</label> 

            <input type="text" name="price" placeholder="Expense Price ?">  
        </div>


        
        <div class="form_add_expense-category">
            <label>Category:</label> 

            <select name="category" id=""  size="4" class="form_add_expense-category-menu">
                <?php
                    // $category_set = get_all_categories();
                    // while($category = mysqli_fetch_object($category_set)){
                    //     $out_put  = "<option>";
                    //     $out_put .= $category["category_name"] ;
                    //     $out_put .= "</option>" ;                        
                    //     echo $out_put ."khaled" ;
                    // }

                    $category_set = get_all_categories();
                    while($category=mysqli_fetch_assoc($category_set)){
                        $out_put = "<option>";
                        $out_put.= $category["category_name"] ;
                        $out_put .= "</option>" ;                        
                        echo $out_put ;
                    }
                ?>
            </select>
        </div>

        <div class="form_add_expense-comment">
            <label>Comment:</label> 
            <textarea id="" cols="20" name="comment" rows="3" placeholder="Like,place..."></textarea>
        </div>

        <div class="form_add_expense-date">
            <label>Date:</label> 
            <input name="created_at" type="date">  
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



<?php include("../includes/layout/footer.php")?>