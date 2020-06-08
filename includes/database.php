<?php

    require_once("config.php") ;

    class MySqlDatabase{

        private $connection ;
        public $last_query ;
        private $magic_quotes_active ;
        private $real_escape_string_exists ;

        function __construct(){

            $this->open_connection();
            $this->magic_quotes_active = get_magic_quotes_gpc();
            $this->real_escape_string_exists = function_exists("mysql_real_escape_string") ;

        }

        public function open_connection(){
            //1. create database connection
            $this->connection = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);

            if(!$this->connection){
                die("Database connection failed: ".mysqli_connect_error()
                    ."(" .mysqli_connect_errno() .")" ) ;
            }else{
  
                //2. select database to use
                $database_select = mysqli_select_db(DB_NAME,$this->connection);
                if($database_select){
                    die("Database selection failed: ".mysqli_connect_error()
                    ."(" .mysqli_connect_errno() .")" ) ;
                }

            }
        }


        public function close_connection(){
            //5. close database connection

            if(isset($this->connection)){
                mysql_close($this->connection);
                unset($this->connection);
            }
        }




        private function confirm_query($result){

            if(!$result){

                $out_put = ("Database confirm query failed: ".mysql_connect_error()
                    ."(" .mysql_connect_errno() .")" ) ;
            
                $out_put .= "LAST SQL QUERY: ".$this->last_query ;

                die ($out_put) ;
            }
    
        }


        public function escaped_value($value){
            
            if($this->real_escape_string_exists){
                // PHP >= V 4.3.0
                if($this->magic_quotes_active){
                    $value = stripslashes($value);
                    $value = mysql_real_escape_string($value);
                }
            }else{
                if(!$this->magic_quotes_active){
                    $value = addslashes($value);
                }
            }
            
            return $value ;
        }


        public function query($sql_query){
            $this->$last_query =$sql_query;

            $result_set = mysql_query($sql_query , $this->connection);

            $this->confirm_query($result_set);
            
            return $result_set ;
            
        }


        public function fech_array($result_set){
            return mysql_fetch_array($result_set) ;
        }

        public function num_rows($result_set){
            return mysql_num_rows($result_set) ;
        }

        public function insert_id($result_set){
            // get the last id inserted over the current database connection
            return mysql_insert_id($this->connection) ;
        }

        public function affected_rows($result_set){
            return mysql_affected_rows($this->connection) ;
        }

    }


    $database_object = new MySqlDatabase() ;

    $db =& $database_object ;




?>