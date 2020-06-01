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
            redirect_to("index.php?");
        }else{
            //failed
            $_SESSION["message"] = "add DIDN'T success" ;
            redirect_to("add_expense.php?");
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


<div class="not_available">
    <img src="/images/not_available.jpg" class="not_available-image" alt="not_available">
</div>


<?php include("../includes/layout/footer.php")?>

