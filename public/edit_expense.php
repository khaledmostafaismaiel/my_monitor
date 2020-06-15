<?php require_once("../includes/initialize.php")?>

<?php
    
    if(isset($_POST['submit_edit_expense'])){
        global $database;

        $id=Expense::get_expense_id_from_url();
        if(!Expense::get_expense_data_by_id($id)){
            rediret_to("not_available.php");
        }
        if(Expense::update_expense_in_database($id,"expense_name","price","category","comment","created_at")){
            //success
            Log::write_in_log("{$_SESSION['user_id']} edit expense \n");

            $_SESSION["message"] = "Edit success" ;
            redirect_to("expenses.php?pagenumber=1");
        }else{
            //failed
            $_SESSION["message"] = "Edit DIDN'T success" ;
            redirect_to("edit_expense.php?expenseid={$id}");
        }

    }else{
        //this is probably $_GET request
        //i will check if user is active or not
    }

    $expense_data = Expense::get_expense_data_by_id(Expense::get_expense_id_from_url());
    $category_set = Category::get_all_categories();
?>


<form method = "post">

    <fieldset class="form_edit_expense">
        <legend> 
            <h2>
                Edit Expense ...

            </h2>   
        </legend>

        <div class="form_edit_expense-name">
            <label>Name:</label> 

            <input type="text" name="expense_name" value="<?php echo $expense_data["expense_name"]?>" placeholder="Expense Name ?">  
        </div>

        <div class="form_edit_expense-price">
            <label>Price:</label> 

            <input type="text" name="price" value="<?php echo $expense_data["price"]?>" placeholder="Expense Price ?">  
        </div>

        <div class="form_edit_expense-category">
            <label>Category:</label> 

            <select name="category"  size="4" class="form_edit_expense-category-menu">
                <?php
                    while($category = mysqli_fetch_assoc($category_set)){
                        $out_put  = "<option ";
                            if(ucfirst($category["category_name"]) == ucfirst($expense_data["category"])){
                                $out_put .= "selected" ;
                            }
                        $out_put .= ">" ;
                        $out_put .= ucfirst($category["category_name"]) ;
                        $out_put .= "</option>" ;                        
                        echo $out_put ;
                    }
                    $database->free_result($category_set); 
                ?>
            </select>
        </div>

        <div class="form_edit_expense-comment">
            <label>Comment:</label> 
            <textarea id="" cols="20" name="comment" value="" rows="3" placeholder="Like,place..."><?php echo $expense_data["comment"]?></textarea>
        </div>

        <div class="form_edit_expense-date">
            <label>Date:</label> 
            <input name="created_at" type="date" value="<?php echo $expense_data["created_at"]?>">  
        </div>


        <div class="form_edit_expense-cancel">
                <a href="index.php" class="btn">
                    cancel
                </a>
        </div>

        <div class="form_edit_expense-submit">
            <input type="submit" name="submit_edit_expense" value="edit" class="form-sign_in-animated btn">  
        </div>
        

    </fieldset>
    <?php $database->free_result($expense_data) ?>
</form>

<?php include("layouts/footer.php")?>