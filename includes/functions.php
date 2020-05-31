<?php 

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

        $safe_admin_id = mysqli_real_escape_string($connection,$admin_id);
        
        //2. perform database query
        $query = "SELECT * ";
        $query .="FROM admins ";
        $query .="WHERE id={$safe_admin_id} ";
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
        $query .="ORDER BY id ASC ";
        // $query .="LIMIT 1";

        $result_set= mysqli_query($connection , $query);

        //test if there was a query error
        confirm_query($result_set);
        
        return $result_set;
    }

    function get_expense_data_by_id($expense_id){

        global $connection ;

        $safe_expense_id = mysqli_real_escape_string($connection,$expense_id);
        
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


    function search_by_expense_name($expense_name){

        global $connection ;

        $safe_expense_name = mysqli_real_escape_string($connection,$expense_name);
        
        //2. perform database query
        $query = "SELECT * ";
        $query .="FROM expenses ";
        $query .="WHERE expense_name={$safe_expense_name} ";
        $query .="ORDER BY id DESC";
        // $query .="LIMIT 1";

        $result_set= mysqli_query($connection , $query);

        //test if there was a query error
        confirm_query($result_set);
        
        return $result_set;
    }


    





    function get_page_number(){

        if(isset($_GET["pagenumber"])){
            $page_number = $_GET["pagenumber"] ;
        }else{
            $page_number = 1 ;
        }

        return $page_number ;

    }
    
    function edit_by_expense_id($expense_id){

    }

    function mysqli_prep($string){
        //escape all strings to prevent sql injection
        global $connection ;    

        return mysqli_real_escape_string($connection,$string);
    }

    // mysqli_num_rows($data_set)


    

?>