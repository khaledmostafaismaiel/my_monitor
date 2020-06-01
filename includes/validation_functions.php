<?php


    function check_before_sign_up($first_name_field,$second_name_field,  
        $email_field,$password_field,$confirm_password_field,$not_robot_field,$terms_of_conditions_field){

        global $errors ;

        validate_has_presence(array($first_name_field ,$second_name_field ,
        $email_field,$password_field ,$confirm_password_field
        ,$not_robot_field,$terms_of_conditions_field));

        validate_max_lengths( array($first_name_field=> 30 ,$second_name_field =>30,
        $email_field => 30 ,$password_field => 30 ,$confirm_password_field => 30
        ,$not_robot_field => 1,$terms_of_conditions_field => 1) );

        check_password_and_confirm_similarity($password_field,$confirm_password_field);

        ckeck_for_user_existance($email_field);



        if(empty($errors)){
            //success
            insert_admin_in_database($first_name_field ,$second_name_field,$email_field,
            $password_field );
            $_SESSION["user_id"] = get_admin_id_by_user_name($email_field); /* $errors */ ;
        }else{
            //fail
            $_SESSION["errors"] = "Sign up failed" /* $errors */ ;
            redirect_to("sign_up.php") ;
        }

    }

?>