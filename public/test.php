<?php
    require_once("../includes/database.php");
    require_once("../includes/user.php");

    if(isset($database_object)){

        echo "true" ;
    }else{

        echo "false" ;
    }


    $sql = "SELECT * FROM admins WHERE id = 1" ;
    $result_set = $database_object->query($sql) ;
    $first_user = mysql_fetch_array($result_set);

    echo $first_user["first_name"];

    $user = user::find_by_id(1);




    $user_set = user::find_all();
    while($user = $database_object->fetch_array($user_set)){
        echo "first name: ".$user["firrst_name"]."<br />" ;
        echo "last name: ".$user["last_name"]."<br />" ;

    }












?>