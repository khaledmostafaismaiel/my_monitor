<?php
//    require_once("../includes/initialize.php");
//
//    global $database;
//
//    $background=Background::find_by_id(Helper::get_from_url('id'));
//
//    $query = "UPDATE admins SET " ;
//    $query .= " background_image = '{$background->file_name}' " ;
//    $query .= " WHERE id = {$_SESSION['user_id']} " ;
//    $query .= " LIMIT 1 " ;
//
//    if($result = mysqli_query($database->get_connection() ,$query)){
//        $_SESSION["background_image"] = $background->file_name ;
//        $_SESSION["message"] = "background set" ;
//    }else{
//        $_SESSION["message"] = "didn't set" ;
//
//    }
//    Helper::redirect_to("backgrounds.php?pagenumber=1");
