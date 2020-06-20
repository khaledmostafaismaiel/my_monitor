<?php

    require_once("database.php");

    class User extends Database_object{

        protected static $table_name = "admins" ;
        protected static $db_fields = array(
            'id','first_name','second_name','user_name','hashed_password','background_image') ;

        public $id ;
        public $first_name ;
        public $second_name ;
        public $user_name ;
        public $hashed_password ;
        public $background_image ;

        public $password ;
        public $confirm_password ;
        public $not_robot ;
        public $terms_of_conditions ;

        public $remember_me ;


        //very unique methods for this class
        public function full_name(){

            if((isset($this->first_name)) && (isset($this->second_name)) ){

                return $this->first_name." ".$this->second_name ;
            }else{
                return "" ;
            }
        }

        public static function find_by_id($id=0){
            global $database ;
    
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name ." WHERE id={$database->sql_sanitize($id)} LIMIT 1");
        
            return !empty($result_array)? array_shift($result_array) : false;
        }
        
        public  function check_before_sign_up(){


            global $database ;
            global $errors ;

            $this->first_name = strtolower($database->sql_sanitize($this->first_name));
            $this->second_name = strtolower($database->sql_sanitize($this->second_name));
            $this->user_name = strtolower($database->sql_sanitize($this->user_name));
            $this->password = $database->sql_sanitize($this->password);
            $this->confirm_password = $database->sql_sanitize($this->confirm_password);
            $this->not_robot = $database->sql_sanitize($this->not_robot);
            $this->terms_of_conditions = $database->sql_sanitize($this->terms_of_conditions);


            Helper::validate_has_presence(array($this->first_name ,$this->second_name ,
                $this->user_name,$this->password ,$this->confirm_password,
                $this->not_robot ,$this->terms_of_conditions));

            Helper::validate_max_lengths( array($this->first_name=> 15 ,$this->second_name =>15,
                $this->user_name => 30 ,$this->password => 30 ,$this->confirm_password => 30) );

            Helper::validate_min_lengths( array($this->password => 8 ,$this->confirm_password => 8) );

            $this->check_password_and_confirm_similarity($this->password,$this->confirm_password);

            if($this->get_by_user_name()){
                $errors[$this->user_name] = Helper::field_name_as_text($this->user_name)." is alredy exist";
            }


            if(empty($errors)){
                $this->hashed_password = password_hash($this->password,PASSWORD_BCRYPT,['cost=>10']) ;
                return true ;
            }else{
                return false ;
            }

            
        }

        private function check_password_and_confirm_similarity($password,$confirm_password){
            global $errors ;
    
            if($this->password !== $this->confirm_password){
                $errors[$password_field] = Helper::field_name_as_text($password_field) ."password and confirmation password didn't not match.";
            }
        }

        public function get_by_user_name(){
            global $errors ;
            global $database ;

        
            $sql = "SELECT * ";
            $sql .="FROM ".self::$table_name;
            $sql .=" WHERE user_name = '{$this->user_name}' ";
            $sql .="LIMIT 1";


            return self::find_by_sql($sql) ;
        }


        public function check_before_sign_in(){
            global $database ;

            $object = new self;

            $this->user_name = strtolower($database->sql_sanitize($this->user_name));
            $this->password = $database->sql_sanitize($this->password);

            $this->hashed_password = password_hash($this->password,PASSWORD_BCRYPT,['cost=>10']) ;


            if($this->authenticate()){
                //sucses
                return true ;
            }

            //fail
            return false ;
        }


        private function authenticate(){

            foreach($this->get_by_user_name() as $admin):          
                if(password_verify($this->password , $admin->hashed_password )){ 
                    $_SESSION["user_id"] = $admin->id;
                    $_SESSION["first_name"] = $admin->first_name;
                    $_SESSION["background_image"] = $admin->background_image;
                    return  true ;
                }
            endforeach;
            return  false ;
        }
               
        private function signed_in(){
            return isset($_SESSION["user_id"]) ;
        }
    
        public static function confirm_sign_in(){
            $object = new self ;

            if(!$object->signed_in()){
                Helper::redirect_to("sign_in.php");
            }
        }
        /*;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;*/
        
        private function generate_salte($salt_length){
    
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
    
        private function password_encrypt($password){
            $object = new self ;

            $hash_format = "$2y$10$" ; //tell php to use Blowfish with a cost of 10
            $salt_length = 22 ; //Blowfish should be 22 or more
            $salt = $object->generate_salte($salt_length);
            $format_and_salt= $hash_format . $salt ;
            $hashed_password=crypt($password,$format_and_salt);
    
            return $hashed_password ;
        }



    
    
    }


    $user = new User() ;



    /*
        if(){
            throw new Exception("Khaled"); 
        }
    */

