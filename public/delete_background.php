<?php 

    require_once("../includes/initialize.php") ;

    global $database;


    $photo = background::find_by_id($_GET['id']);
    if($photo && $photo->destroy() && $database->affected_rows() >= 1) {
        Log::write_in_log("{$_SESSION['user_id']} delete background ".date("d-m-Y")." ".date("h:i:sa")."\n");
        $_SESSION["message"] = "Delete success" ;
    } else {
        $_SESSION["message"] = "Delete Didn't success" ;
    }
    redirect_to("backgrounds.php?pagenumber=1");