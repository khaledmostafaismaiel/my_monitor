<?php

    function add_expense_in_database($name_field,$price_field,$category_field
        ,$comment_field,$created_at_field){

        global $connection ;

        //prcess the form
        //escape all strings to prevent sql injection with mysqli_prep
        $name = mysqli_prep(strtolower(htmlentities($_POST[$name_field]))) ;
        $price = (float)urlencode($_POST[$price_field]) ;
        $category = mysqli_prep(strtolower(htmlentities($_POST[$category_field]))) ;
        $comment = mysqli_prep(strtolower(htmlentities($_POST[$comment_field]))) ;
        $created_at =mysqli_prep(htmlentities($_POST[$created_at_field])) ;


        $query = " INSERT INTO expenses ( ";
        $query .= " expense_name , price , category , comment , created_at ) " ;  
        $query .= " VALUES ( '{$name}' , {$price} , '{$category}' , '{$comment}' , '{$created_at}' )";

        return mysqli_query($connection,$query)  ;
    }


    function delete_expense_from_database($expense_id){

        global $connection ;
        
        $query = "DELETE FROM expenses WHERE id = {$expense_id} LIMIT 1" ;

        return mysqli_query($connection,$query)  ;
    }


    function update_expense_in_database($id,$name_field,$price_field,$category_field
        ,$comment_field,$created_at_field){

        global $connection ;

        //prcess the form
        //escape all strings to prevent sql injection with mysqli_prep
        //prcess the form
        //escape all strings to prevent sql injection with mysqli_prep
        $name = mysqli_prep($_POST[$name_field]) ;
        $price = $_POST[$price_field] ;
        $category = $_POST[$category_field] ;
        $comment = mysqli_prep($_POST[$comment_field]) ;
        $created_at = $_POST[$created_at_field] ;



        $query = "UPDATE expenses SET " ;
        $query .= "expense_name = '{$name}', " ;
        $query .= "price = {$price}, " ;
        $query .= "category = '{$category}', " ;  
        $query .= "comment = '{$comment}', " ;
        $query .= "created_at = '{$created_at}' " ;
        // $query .= "updated_at = 'date' " ;
        $query .= "WHERE id = {$id} " ;
        $query .= "LIMIT 1" ;


        return mysqli_query($connection,$query)  ;
    }


    function search_by_expense_name($expense_name){

        global $connection ;


        $safe_expense_name = mysqli_prep($expense_name);


        $query = "SELECT * FROM expenses WHERE expense_name = '{$safe_expense_name}' ORDER BY id DESC" ;

        $result_set= mysqli_query($connection , $query);
    
        confirm_query($result_set);
    
        $number_of_expenses = mysqli_num_rows($result_set)  ;
    
        if(!($number_of_expenses > 0)){
            $_SESSION["message"] = "No Matching" ;
            redirect_to("index.php");
        }

        // $query .="ORDER BY id DESC";
        
        return $result_set;
    }


    function check_before_sign_up($first_name_field,$second_name_field,  
        $email_field,$password_field,$confirm_password_field,$not_robot_field,$terms_of_conditions_field){

        global $errors ;

        validate_has_presence(array($first_name_field ,$second_name_field ,
        $email_field,$password_field ,$confirm_password_field
        ,$not_robot_field,$terms_of_conditions_field));

        validate_max_lengths( array($first_name_field=> 30 ,$second_name_field =>30,
        $email_field => 30 ,$password_field => 30 ,$confirm_password_field => 30
        ,$not_robot_field => 1,$terms_of_conditions_field => 1) );

        validate_min_lengths( array($password_field => 8 ,$confirm_password_field => 8) );


        check_password_and_confirm_similarity($password_field,$confirm_password_field);

        ckeck_for_user_existance($email_field);


        if(empty($errors)){
            //success
            insert_admin_in_database($first_name_field ,$second_name_field,$email_field,
            $password_field );
        }else{
            //fail
            redirect_to("sign_up.php") ;
        }

    }



    function check_before_sign_in($user_name_field,$password_field,$remember_me_field){

        if(!attempt_sign_in($user_name_field,$password_field)){
            //fail
            redirect_to("sign_in.php?");
        }
        return true ;
    }

?>