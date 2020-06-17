<?php 

    require_once("../includes/initialize.php") ;

    global $database;

    $id=Helper::get_from_url("expenseid");

    $expense = new Expense();

    if(! $expense = $expense::find_by_id($id)){
        Helper::redirect_to("not_available.php");
    }

    if($expense->delete() && $database->affected_rows($database->connection ) >= 1){
        //success
        Log::write_in_log("{$_SESSION['user_id']} delete expense ".date("d-m-Y")." ".date("h:i:sa")."\n");

        $_SESSION["message"] = "Delete success" ;
    }else{
        //failed
        $_SESSION["message"] = "Delete Didn't success" ;
    }

    if(isset($database)){ 
        $database->close_connection(); 
    }

    Helper::redirect_to("expenses.php?pagenumber=1");