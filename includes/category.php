<?php 

    require_once("database.php");


    class Category extends Database_object{


        protected static $table_name = "categories";
        protected static $db_fields = array("category_id","category_name");

        public $category_id ;
        public $category_name ;

        public static function find_all(){
            
            return self::find_by_sql("SELECT * FROM " .static::$table_name);
        }
    }