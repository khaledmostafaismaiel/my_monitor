<?php
    $errors = array();   

    function field_name_as_text($field_name){

        $field_name = str_replace("_","",$field_name);
        $field_name = ucfirst($field_name);

        return $field_name ;
    }


    function has_presence($value){
        return isset($value) && $value != "" ;
    }

    function validate_has_presence($fields){
        /**/
        global $errors ;

        foreach($fields as $field){
            $value = trim($_POST[$field]) ;
            if(!has_presence($value)){
                $errors[$field] = field_name_as_text($field)." can't be blank";
            }

        }


    }


    function has_max_length($value,$max){
        return strlen($value) <= $max ;
    }

    function validate_max_lengths($fields){
        /**/
        global $errors ;

        foreach($fields as $field => $max){
            $value = trim($_POST[$field]) ;
            if(!has_max_length($value,$max)){
                $errors[$field] = field_name_as_text($field)." is to long";
            }

        }

    }

    function has_inclusion_in(){
        /**/

        return in_array($value,$set);
    }


    function ckeck_for_user_existance($field){

        $email = $_POST[$field] ;
        $admins_set=get_all_admins();
        while($admin=mysqli_fetch_assoc($admins_set)){
            if($admin[$field] == $email){
                $errors[$field] = field_name_as_text($field) ." user is arready exist.";
            return;
            }
        }
        return;
    }



function check_before_sign_up($first_name,$second_name,
    
    $email,$password,$confirm_password,$not_robot,$terms_of_conditions){

    validate_has_presence(array($first_name ,$second_name ,
    $email,$password ,$confirm_password
    ,$not_robot,$terms_of_conditions));

    
    validate_max_lengths( array($first_name=> 30 ,$second_name =>30,
    $email => 30 ,$password => 30 ,$confirm_password => 30
    ,$not_robot => 30,$terms_of_conditions => 30) );


    ckeck_for_user_existance($email);

    
    if(!empty($errors)){
        $_SESSION["errors"] = "Sign up failed" /* $errors */ ;
        redirect_to("sign_up.php") ;
    }

    if($password !== $confirm_password){
        $_SESSION["errors"] = "Sign up failed" /* $errors */ ;
        redirect_to("sign_up.php") ;
    }


}
?>