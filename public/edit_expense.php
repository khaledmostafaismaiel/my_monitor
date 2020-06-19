<?php 
    require_once("../includes/initialize.php") ;
    
    if(isset($_POST['submit_edit_expense'])){
        global $database;

        $id=Helper::get_from_url("expenseid");
        $expense = new Expense() ;
        if(! $expense = $expense::find_by_id($id)){
            Helper::redirect_to("not_available.php");
        }
        $expense->expense_name = $_POST['expense_name'] ;
        $expense->price = $_POST['price'] ;
        $expense->category = $_POST['category'] ;
        $expense->comment = $_POST['comment'] ;
        if($_POST['created_at'] == ''){
            $expense->created_at = date("Y-m-d H:i:s") ;

        }else{
            $expense->created_at = $_POST['created_at'] ;
        }
        

        if($expense->save()){
            //success
            Log::write_in_log("{$_SESSION['user_id']} edit expense ".date("d-m-Y")." ".date("h:i:sa")."\n");

            $_SESSION["message"] = "Edit success" ;
            Helper::redirect_to("expenses.php?pagenumber=1");
        }else{
            //failed
            $_SESSION["message"] = "Edit DIDN'T success" ;
            Helper::redirect_to("edit_expense.php?expenseid={$id}");
        }

    }else{
        //this is probably $_GET request
        //i will check if user is active or not
    }

    if(! $expense_data = Expense::find_by_id(Helper::get_from_url("expenseid"))){
        Helper::redirect_to("not_available.php");
    }
    $category_set = Category::find_all();
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

            <input type="text" name="expense_name" value="<?php echo $expense_data->expense_name?>" placeholder="Expense Name ?">  
        </div>

        <div class="form_edit_expense-price">
            <label>Price:</label> 

            <input type="text" name="price" value="<?php echo $expense_data->price?>" placeholder="Expense Price ?">  
        </div>

        <div class="form_edit_expense-category">
            <label>Category:</label> 

            <select name="category"  size="4" class="form_edit_expense-category-menu">
                <?php
                    foreach($category_set as $category):
                        $out_put  = "<option ";
                            if(ucfirst($category->category_name) == ucfirst($expense_data->category)){
                                $out_put .= "selected" ;
                            }
                        $out_put .= ">" ;
                        $out_put .= ucfirst($category->category_name) ;
                        $out_put .= "</option>" ;                        
                        echo $out_put ;
                    endforeach;
                    $database->free_result($category_set); 
                ?>
            </select>
        </div>

        <div class="form_edit_expense-comment">
            <label>Comment:</label> 
            <textarea id="" cols="20" name="comment" value="" rows="3" placeholder="Like,place..."><?php echo $expense_data->comment?></textarea>
        </div>

        <div class="form_edit_expense-date">
            <label>Date:</label> 
            <input name="created_at" type="date" value="<?= date( 'Y-m-d', strtotime( $expense_data->created_at ) ) ?>">  
        </div>


        <div class="form_edit_expense-cancel">
                <a href="expenses.php?pagenumber=1" class="btn">
                    cancel
                </a>
        </div>

        <div class="form_edit_expense-submit">
            <input type="submit" name="submit_edit_expense" value="edit" class="form-sign_in-animated btn">  
        </div>
        

    </fieldset>
    <?php $database->free_result($expense_data) ?>
</form>

<?php include_layout_template("footer.php")?>