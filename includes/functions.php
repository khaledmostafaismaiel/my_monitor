<?php 
    $errors = array();   



    function redirect_to($new_location){

        header("Location: ".$new_location);
        exit;
    }


    function confirm_query($result_set){

        if(!$result_set){
            die("Database query failed.");
        }

    }



    function get_all_admins(){

        global $connection ;

        
        //2. perform database query
        $query = "SELECT * ";
        $query .="FROM admins ";
        $query .="ORDER BY id ASC ";
        // $query .="LIMIT 1";

        $result_set= mysqli_query($connection , $query);

        //test if there was a query error
        confirm_query($result_set);
        
        return $result_set;
    }

    function get_admin_data_by_id($admin_id){

        global $connection ;

        $safe_admin_id = mysqli_prep($admin_id);
        
        //2. perform database query
        $query = "SELECT * ";
        $query .="FROM admins ";
        $query .="WHERE id = {$safe_admin_id} ";
        $query .="LIMIT 1";

        $result_set= mysqli_query($connection , $query);

        //test if there was a query error
        confirm_query($result_set);

        if($result=mysqli_fetch_assoc($result_set)){
            return "amro" ;

        }else{
            return null ;
        }
    }


    function get_admin_data_by_user_name($user_name_field){

        global $connection ;

        $safe_user_name_field = mysqli_prep($user_name_field);
        $user_name = htmlentities($_POST[$safe_user_name_field]);

        //2. perform database query
        $query = "SELECT * ";
        $query .="FROM admins ";
        $query .="WHERE user_name = '{$user_name}' ";
        $query .="LIMIT 1";

        $result_set= mysqli_query($connection , $query);

        //test if there was a query error
        confirm_query($result_set);

        if($result=mysqli_fetch_assoc($result_set)){
            return $result ;

        }else{
            return null ;
        }
    }

    function get_all_expenses(){

        global $connection ;

        
        //2. perform database query
        $query = "SELECT * ";
        $query .="FROM expenses ";
        $query .="ORDER BY id DESC ";
        // $query .="LIMIT 1";

        $result_set= mysqli_query($connection , $query);

        //test if there was a query error
        confirm_query($result_set);
        
        return $result_set;
    }


    function get_all_month_expenses(){

        global $connection ;
        
        $month = date('Y-m-00');

        $query = "SELECT * FROM `expenses` WHERE `created_at` > '{$month}' ORDER BY id DESC";
        $result_set = mysqli_query($connection, $query);
    
        //test if there was a query error
        confirm_query($result_set);
        
        return $result_set;
    }



    function get_all_month_prices(){

        global $connection ;
        
        $month = date('Y-m-00');
        $query = "SELECT SUM(`price`) AS 'total' FROM `expenses` WHERE `created_at` > '{$month}'";
        $result_set = mysqli_query($connection, $query);
        
        //test if there was a query error
        confirm_query($result_set);
        
        return $monthTotal = mysqli_fetch_object($result_set)->total;
    }


    function get_expense_data_by_id($expense_id){

        global $connection ;

        $safe_expense_id = mysqli_prep($expense_id);
        
        //2. perform database query
        $query = "SELECT * ";
        $query .="FROM expenses ";
        $query .="WHERE id={$safe_expense_id} ";
        $query .="LIMIT 1";

        $result_set= mysqli_query($connection , $query);

        //test if there was a query error
        confirm_query($result_set);

        if($result=mysqli_fetch_assoc($result_set)){
            return $result ;

        }else{
            return null ;
        }
    }



    function get_all_categories(){

        global $connection ;

        
        //2. perform database query
        $query = "SELECT * ";
        $query .="FROM categories ";
        $query .="ORDER BY category_id ASC ";
        // $query .="LIMIT 1";

        $result_set= mysqli_query($connection , $query);

        //test if there was a query error
        confirm_query($result_set);
        
        return $result_set;
    }

    function get_category_data_by_id($category_id){

        global $connection ;

        $safe_category_id = mysqli_real_escape_string($connection,$category_id);
        
        //2. perform database query
        $query = "SELECT * ";
        $query .="FROM categories ";
        $query .="WHERE category_id={$safe_category_id} ";
        $query .="LIMIT 1";

        $result_set= mysqli_query($connection , $query);

        //test if there was a query error
        confirm_query($result_set);

        if($result=mysqli_fetch_assoc($result_set)){
            return $result ;

        }else{
            return null ;
        }
    }







    function get_page_number(){

        if(isset($_GET["pagenumber"])){
            $page_number = htmlentities($_GET["pagenumber"]) ;
        }else{
            $page_number = null ;
        }

        return $page_number ;

    }

    function get_expense_id_from_url(){

        if(isset($_GET["expenseid"])){
            $expense_id = htmlentities($_GET["expenseid"]) ;
        }else{
            $expense_id = null;
        }

        return $expense_id ;

    }



    function mysqli_prep($string){
        //escape all strings to prevent sql injection
        global $connection ;    
        return mysqli_real_escape_string($connection,$string);
    }

    // mysqli_num_rows($data_set)




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
            $value = trim(htmlentities($_POST[$field])) ;
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
            $value = trim(htmlentities($_POST[$field])) ;
            if(!has_max_length($value,$max)){
                $errors[$field] = field_name_as_text($field)." is to long";
            }

        }

    }


    function has_min_length($value,$max){
        return strlen($value) >= $max ;
    }

    function validate_min_lengths($fields){
        /**/
        global $errors ;

        foreach($fields as $field => $max){
            $value = trim(htmlentities($_POST[$field])) ;
            if(!has_min_length($value,$max)){
                $errors[$field] = field_name_as_text($field)." is to short";
            }

        }

    }



    function insert_admin_in_database($first_name_field,$second_name_field,  
        $email_field,$password_field){

        global $connection ;

        //prcess the form
        //escape all strings to prevent sql injection with mysqli_prep
        $first_name = strtolower(mysqli_prep(htmlentities($_POST[$first_name_field]))) ;
        $second_name = strtolower(mysqli_prep(htmlentities($_POST[$second_name_field]))) ;
        $email = strtolower(mysqli_prep(htmlentities($_POST[$email_field]))) ;
        $hashed_password = password_hash($_POST[$password_field],PASSWORD_BCRYPT,['cost=>10']) ;

        
        $query = "INSERT INTO admins (";
        $query .= " first_name,second_name,user_name,hashed_password";
        $query .= ") VALUES (" ;
        $query .= " '{$first_name}','{$second_name}','{$email}','{$hashed_password}'";
        $query .= ")";

        ;

        if(!$result = mysqli_query($connection,$query)){
            // failed
            $_SESSION["message"] = "Try Again";
            redirect_to("sign_up.php?");
        }
    }

    function get_admin_id_by_user_name($email_field){
        global $errors ;
        $email = strtolower($_POST[$email_field]) ;
     
        $admins_set=get_all_admins();
        while($admin=mysqli_fetch_assoc($admins_set)){
            if($admin["user_name"] == $email){
                
            return $admin["id"];
            }
        }
        return null;
    }


    function ckeck_for_user_existance($email_field){
        global $errors ;

        $email = strtolower(htmlentities($_POST[$email_field])) ;
     
        $admins_set=get_all_admins();
        while($admin=mysqli_fetch_assoc($admins_set)){
            if($admin["user_name"] == $email){
                $errors[$email_field] = field_name_as_text($email_field) ." user is arready exist.";
            return;
            }
        }
        return;
    }


    function check_password_and_confirm_similarity($password_field,$confirm_password_field){
        global $errors ;

        if(htmlentities($_POST[$password_field]) !== htmlentities($_POST[$confirm_password_field])){
            $errors[$password_field] = field_name_as_text($email_field) ."password and confirmation password didn't not match.";
        }
    }


    function password_check($existing_hashed_password , $password_field){

        if(password_verify(htmlentities($_POST[$password_field]),$existing_hashed_password)){
            return true ;
        }else{
            return false ;
        }
    }


    function generate_salte($salt_length){

        //not 100% unique.not 100% random but good enough for a salt
        $unique_random_string = md5(uniqid(mt_rand(),true));

        //valid characters for a salt are a[a-zA-Z0-9./]
        $base_64_string = base64_encode($unique_random_string);

        //but not '+' which is valid in base64 encoding 
        $modified_base64_string = str_replace('+','.',$base_64_string);

        //trancate string into the correct length
        $salt = substr($modified_base64_string,0,$salt_length);

        return $salte ;

    }

    function password_encrypt($password){

        $hash_format = "$2y$10$" ; //tell php to use Blowfish with a cost of 10
        $salt_length = 22 ; //Blowfish should be 22 or more
        $salt = generate_salte($salt_length);
        $format_and_salt= $hash_format . $salt ;
        $hashed_password=crypt($password,$format_and_salt);

        return $hashed_password ;
    }


 


    function attempt_sign_in($user_name_field,$password_field){

            
        if($admin = get_admin_data_by_user_name($user_name_field)){              
                
                if(password_check($admin["hashed_password"] , $password_field))
                    $_SESSION["user_id"] = $admin["id"];
                    $_SESSION["first_name"] = $admin["first_name"];
                    return  true ;
            
        }

        return  false ;
    }


    function signed_in(){
        return isset($_SESSION["user_id"]) ;
    }

    function confirm_sign_in(){

        if(!signed_in()){
            redirect_to("sign_in.php");
        }
    }
?>