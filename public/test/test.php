<?php
    // require_once("../includes/database.php");
    // require_once("../includes/user.php");

    // if(isset($database)){

    //     echo "true" ;
    // }else{

    //     echo "false" ;
    // }


    // $sql = "SELECT * FROM admins WHERE id = 1" ;
    // $result_set = $database->query($sql) ;
    // $first_user = mysql_fetch_array($result_set);

    // echo $first_user["first_name"];

    // $user = user::find_by_id(1);




    // $user_set = user::find_all();
    // while($user = $database->fetch_array($user_set)){
    //     echo "first name: ".$user["firrst_name"]."<br />" ;
    //     echo "last name: ".$user["last_name"]."<br />" ;

    // }





    // //include session.php then type this to redirect you if you are not logggin
    // if(!$session->is_logged_in()){

    //     redirect_to("sign_in.php") ;

    // }else{

    //     echo "false" ;
    // }

        // $user = new user();

        // $user->first_name = "eslam" ;
        // $user->second_name = "dahy" ;
        // $user->user_name = "eslam_dahy" ;
        // $user->password = "123456789" ;
        // $user->create() ;


        // $user =  user::find_by_id(3);

        // $user->first_name = "sameh" ;
        // $user->second_name = "dahy" ;
        // $user->user_name = "sameh_dahy" ;
        // $user->password = "1234056789" ;
        // $user->update() ;

        $user =  user::find_by_id(3);
        $user->delete();

?>