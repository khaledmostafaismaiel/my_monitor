<?php 

    require_once("database.php");


    class Category{


        protected static $table_name = "categories";
        protected static $db_fields = array("category_id","category_name");

        public $category_id ;
        public $category_name ;


        public static function get_all_categories(){
            global $database;
    
            //2. perform database query
            $query = "SELECT * ";
            $query .="FROM ".self::$table_name;
            $query .=" ORDER BY category_id ASC ";
            // $query .="LIMIT 1";
    
            $result_set= mysqli_query($database->connection , $query);
    
            //test if there was a query error
            $database->confirm_query($result_set);
            
            return $result_set;
        }
    
        public static function get_category_data_by_id($category_id){
    
            global $database;
    
            $safe_category_id = mysqli_real_escape_string($database->connection ,$category_id);
            
            //2. perform database query
            $query = "SELECT * ";
            $query .="FROM ".self::$table_name;
            $query .=" WHERE category_id={$safe_category_id} ";
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
    


    }