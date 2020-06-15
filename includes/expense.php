<?php 

    require_once("database.php");
    require_once("pagination.php");


class Expense extends Database_object{ 

    protected static $table_name = "expenses";
    protected static $db_fields = array("id","user_id","expense_name","price",
        "category","comment","created_at","updated_at");

    public $id ;
    public $user_id ;
    public $expense_name ;
    public $price ;
    public $category ;
    public $comment ;
    public $created_at ;
    public $updated_at ;


    public static function get_all_expenses(){

        global $database;
        
        //2. perform database query
        $query = "SELECT * ";
        $query .="FROM ".self::$table_name;
        $query .=" WHERE user_id = {$_SESSION['user_id']} ";
        $query .="ORDER BY id DESC ";
        // $query .="LIMIT 1";

        $result_set= mysqli_query($database->connection , $query);

        //test if there was a query error
        $database->confirm_query($result_set);
        
        return $result_set;
    }



    public static function get_expense_data_by_id($expense_id){
        global $database;

        $safe_expense_id = $database->escaped_value($expense_id);
        
        //2. perform database query
        $query = "SELECT * ";
        $query .="FROM ".self::$table_name;
        $query .=" WHERE id={$safe_expense_id} AND user_id = {$_SESSION['user_id']} ";
        $query .="LIMIT 1";

        $result_set= mysqli_query($database->connection , $query);

        //test if there was a query error
        $database->confirm_query($result_set);

        if($result=mysqli_fetch_assoc($result_set)){
            return $result ;

        }else{
            return null ;
        }
    }


    public static function get_expense_id_from_url(){

        if(isset($_GET["expenseid"])){
            $expense_id = htmlentities($_GET["expenseid"]) ;
        }else{
            $expense_id = null;
        }

        return $expense_id ;

    }



    public static function add_expense_in_database($name_field,$price_field,$category_field
        ,$comment_field,$created_at_field){

        global $database;

        //prcess the form
        //escape all strings to prevent sql injection with escaped_value
        $name = $database->escaped_value(strtolower(htmlentities($_POST[$name_field]))) ;
        $price = urlencode($_POST[$price_field]) ;
        $category = $database->escaped_value(strtolower(htmlentities($_POST[$category_field]))) ;
        $comment = $database->escaped_value(strtolower(htmlentities($_POST[$comment_field]))) ;
        $created_at =$database->escaped_value(htmlentities($_POST[$created_at_field]));
        // $created_at .= date("s:i:H") ;

        if(!is_numeric($price)){
            return false ;
        }else{ 
        $query = " INSERT INTO ".self::$table_name." ( ";
        $query .= " expense_name , price , category , comment , created_at , user_id ) " ;  
        $query .= " VALUES ( '{$name}' , {$price} , '{$category}' , '{$comment}' , '{$created_at}' , {$_SESSION['user_id']} )";
        
        return mysqli_query($database->connection ,$query)  ;
        }
    }


    public static function delete_expense_from_database($expense_id){

        global $database;
        
        $query = "DELETE FROM ".self::$table_name." WHERE id = {$expense_id} AND user_id = {$_SESSION['user_id']} LIMIT 1" ;

        return mysqli_query($database->connection ,$query)  ;
    }


    public static function update_expense_in_database($id,$name_field,$price_field,$category_field
        ,$comment_field,$created_at_field){

        global $database;

        //prcess the form
        //escape all strings to prevent sql injection with escaped_value
        //prcess the form
        //escape all strings to prevent sql injection with escaped_value
        $name = $database->escaped_value($_POST[$name_field]) ;
        $price = urlencode($_POST[$price_field]) ;
        $category = $_POST[$category_field] ;
        $comment = $database->escaped_value($_POST[$comment_field]) ;
        $created_at = $database->escaped_value($_POST[$created_at_field]) ;


        if(!is_numeric($price)){
            return false ;
        }else{ 
        $query = "UPDATE ".self::$table_name." SET " ;
        $query .= "expense_name = '{$name}', " ;
        $query .= "price = {$price}, " ;
        $query .= "category = '{$category}', " ;  
        $query .= "comment = '{$comment}', " ;
        $query .= "created_at = '{$created_at}' " ;
        // $query .= "updated_at = 'date' " ;
        $query .= "WHERE id = {$id} " ;
        $query .= " AND user_id = {$_SESSION['user_id']} " ;
        $query .= "LIMIT 1" ;

        return mysqli_query($database->connection ,$query)  ;
        }
    }


    public static function get_all_month_expenses(){
        global $database;
        
        $month = date('Y-m-00');

        $query = "SELECT * FROM ".self::$table_name." WHERE `created_at` > '{$month}' AND user_id = {$_SESSION['user_id']}  ORDER BY id DESC";
        $result_set = mysqli_query($database->connection , $query);
    
        //test if there was a query error
        $database->confirm_query($result_set);
        
        return $result_set;
    }


    public static function get_all_month_prices(){

        global $database;


        $month = date('Y-m-00');
        $query = "SELECT SUM(`price`) AS 'total' FROM ".self::$table_name." WHERE `created_at` > '{$month}' AND user_id = {$_SESSION['user_id']} ";
        $result_set = mysqli_query($database->connection , $query);
        
        //test if there was a query error
        $database->confirm_query($result_set);
        
        return $monthTotal = mysqli_fetch_object($result_set)->total;
    }

    
    public static function search_by_expense_name($expense_name){

        global $database;

        $safe_expense_name = $database->escaped_value($expense_name);


        $query = "SELECT * FROM ".self::$table_name." WHERE expense_name = '{$safe_expense_name}' AND user_id = {$_SESSION['user_id']}  ORDER BY id DESC" ;

        $result_set= mysqli_query($database->connection  , $query);
    
        $database->confirm_query($result_set);

        // $query .="ORDER BY id DESC";
        
        return $result_set;
    }




}
