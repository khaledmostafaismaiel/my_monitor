<?php require_once("../includes/db_connection.php")?>
<?php require_once("../includes/functions.php")?>
<?php include("../includes/layout/header.php")?>






<form  action="index.php">
    
    <fieldset class="form_add_expense">
        <legend> 
            <h2>
                Edit Expense ...
            </h2>   
        </legend>

        <div class="form_add_expense-price">
            <label>Price:</label> 

            <input type="text" placeholder="Item Price ?">  
        </div>

        <div class="form_add_expense-category">
            <label>Category:</label> 

            <select name="" id=""  size="4" class="form_add_expense-category-menu">
                    <option value="1">Food</option>
                    <option value="2">Drink</option>
                    <option value="3">Mobile</option>
                    <option value="3">Computer</option>
                    <option value="3">Other</option>
            </select>
        </div>

        <div class="form_add_expense-comment">
            <label>Comment:</label> 

            <textarea id="" cols="20" rows="3" placeholder="Like Item Name,..."></textarea>
        </div>

        <div class="form_add_expense-date">
            <label>Date:</label> 

            <input type="date">  
        </div>


        <div class="form_add_expense-submit">
            <input type="submit" value="add" class="form-sign_in-animated btn">  
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