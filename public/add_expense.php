<?php 
    require_once("../includes/initialize.php");

    if(isset($_POST['submit_add_expense'])){

        if(Expense::add_expense_in_database("expense_name","price","category","comment","created_at")){
            //success
            Log::write_in_log("{$_SESSION['user_id']} add expense ".date("d-m-Y")." ".date("h:i:sa")."\n");

            $_SESSION["message"] = "add success" ;
            Helper::redirect_to("index.php?");
        }else{
            //failed
            $_SESSION["message"] = "add DIDN'T success" ;
            Helper::redirect_to("add_expense.php?");
        }

    }else{
        //this is probably $_GET request
        //i will check if user is active or not

    }
    $category_set = Category::find_all();
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
                    foreach($category_set as $category){
                        $out_put  = "<option>";
                        $out_put .= ucfirst($category->category_name) ;
                        $out_put .= "</option>" ;                        
                        echo $out_put ;
                    }
                    //4. release the returned data
                    $database->free_result($category_set); 
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

<?php include(LAYOUTS_PATH.DS."footer.php")?>
<?php /*include_layout_template("footer")*/?>