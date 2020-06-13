<?php 

    require_once("../includes/initialize.php") ;

    global $database;

    $id=Expense::get_expense_id_from_url();
    if(!Expense::get_expense_data_by_id($id)){
        rediret_to("not_available.php");
    }
    if(Expense::delete_expense_from_database($id) && $database->affected_rows($database->connection ) >= 1){
        //success
        $_SESSION["message"] = "Delete success" ;
    }else{
        //failed
        $_SESSION["message"] = "Delete Didn't success" ;
    }

    redirect_to("expenses.php?pagenumber=1");