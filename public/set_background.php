<?php require_once("../includes/initialize.php")?>

<?php
    
        global $database;

        $background=Background::find_by_id(Background::get_from_url('id'));

        $query = "UPDATE admins SET " ;
        $query .= " backgroud_image = '{$background->file_name}' " ;
        $query .= " WHERE id = {$_SESSION['user_id']} " ;
        $query .= " LIMIT 1 " ;



        if($result = mysqli_query($database->connection ,$query)){
            $_SESSION["message"] = "background set" ;

            redirect_to("index.php");
        }else{
            $_SESSION["message"] = "didn't set" ;
            // var_dump($result);
            // die ;
            redirect_to("backgrounds.php?pagenumber=1");
        }   