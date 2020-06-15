<?php 

    require_once("../includes/initialize.php") ;

    global $database;

    $id=Expense::get_expense_id_from_url();
    if(!Expense::get_expense_data_by_id($id)){
        rediret_to("not_available.php");
    }
    if(Expense::delete_expense_from_database($id) && $database->affected_rows($database->connection ) >= 1){
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

    redirect_to("expenses.php?pagenumber=1");