<?php require_once("../includes/session.php")?>
<?php require_once("../includes/db_connection.php")?>
<?php require_once("../includes/functions.php")?>
<?php include("../includes/layout/header.php")?>







<?php

    if(isset($_POST['submit_add_expense'])){
        //prcess the form
        //escape all strings to prevent sql injection with mysqli_prep
        $name = mysqli_prep($_POST["expense_name"]) ;
        $price = $_POST["price"] ;
        $category = $_POST["category"] ;
        $comment = mysqli_prep($_POST["comment"]) ;
        $created_at = $_POST["created_at"] ;

        $query = " INSERT INTO expenses ( ";
        $query .= " expense_name , price , category , comment , created_at ) " ;  
        $query .= " VALUES ( '{$name}' , {$price} , '{$category}' , {$comment} , '{$created_at}' )";

        $result = mysqli_query($connection,$query) ;

        if($result){
            //success
            $_SESSION["message"] = "add success" ;
            redirect_to("index.php?currentpage=home");
        }else{
            //failed
            $_SESSION["message"] = "add DIDN'T success" ;
            redirect_to("add_expense.php?currentpage=addexpense");
        }

    }else{
        //this is probably $_GET request
        //i will check if user is active or not

        // if(1){

        // }else{
        //     redirect_to("sign_up.php");
        // }
    }

?>





<form method = "post">
    
    <fieldset class="form_add_expense">
        <legend> 
            <h2>
                EDIT Expense ...
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

                    $id = 1 ;
                    while($category_data=get_category_data_by_id($id++)){
                        $out_put = "<option>";
                        $out_put.= $category_data["category_name"] ;
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
                <a href="index.php?currentpage=home" class="btn">
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