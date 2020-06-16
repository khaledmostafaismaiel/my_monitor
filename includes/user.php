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


        //very unique methods for this class
        public function full_name(){

            if((isset($this->first_name)) && (isset($this->second_name)) ){

                return $this->first_name." ".$this->second_name ;
            }else{
                return "" ;
            }
        }

        public static function check_before_sign_up($first_name_field,$second_name_field,  
            $user_name_filed,$password_field,$confirm_password_field,$not_robot_field,$terms_of_conditions_field){


            global $database ;
            global $errors ;
            $object = new self ;


            $first_name_field = $database->escaped_value($first_name_field);
            $second_name_field = $database->escaped_value($second_name_field);
            $user_name_filed = $database->escaped_value($user_name_filed);
            $password_field = $database->escaped_value($password_field);
            $confirm_password_field = $database->escaped_value($confirm_password_field);


            Helper::validate_has_presence(array($first_name_field ,$second_name_field ,
                $user_name_filed,$password_field ,$confirm_password_field
                ,$not_robot_field,$terms_of_conditions_field));

            Helper::validate_max_lengths( array($first_name_field=> 15 ,$second_name_field =>15,
                $user_name_filed => 30 ,$password_field => 30 ,$confirm_password_field => 30
                ,$not_robot_field => 1,$terms_of_conditions_field => 1) );

            Helper::validate_min_lengths( array($password_field => 8 ,$confirm_password_field => 8) );

            $object->check_password_and_confirm_similarity($password_field,$confirm_password_field);

            if(self::get_by_user_name($_POST[$user_name_filed])){
                $errors[$user_name_filed] = Helper::field_name_as_text($user_name_filed)." is alredy exist";
            }

            if(empty($errors)){
                //success
                if(self::insert_in_database($first_name_field ,$second_name_field,$user_name_filed,
                    $password_field )){ 
                        //Success
                        $_SESSION["message"] = "Success";
                        /* i need to send email here for admin */
                        Helper::redirect_to("sign_in.php?");
                }
            }
            //fail
            $_SESSION["message"] = "Try Again";
            Helper::redirect_to("sign_up.php") ;
            
        }

        private function check_password_and_confirm_similarity($password_field,$confirm_password_field){
            global $errors ;
    
            if(htmlentities($_POST[$password_field]) !== htmlentities($_POST[$confirm_password_field])){
                $errors[$password_field] = Helper::field_name_as_text($password_field) ."password and confirmation password didn't not match.";
            }
        }

        public static function get_by_user_name($user_name){
            global $errors ;
            global $database ;
        
            $sql = "SELECT * ";
            $sql .="FROM ".self::$table_name;
            $sql .=" WHERE user_name = '{$user_name}' ";
            $sql .="LIMIT 1";

            return self::find_by_sql($sql) ;
        }


        public static function find_by_sql($sql = ""){

            global $database ;
            $result_set = $database->query($sql);
    
            $object_array = array() ;
            while($row = $database->fetch_array($result_set)){
    
                $object_array[] = self::instantiate($row) ;
            }

            return $object_array ;
        }
    
    
        private static function instantiate($row){
            $object = new self ;
            //very long approch spatially if the record have lots of attributs 
            // $object->id = $row["id"];
            // $object->first_name = $row["first_name"];
            // $object->second_name = $row["second_named"];
            // $object->user_name = $row["user_name"];
            // $object->password = $row["password"];
    
            //very short approch
            foreach($row as $attribute=>$value){
                if($object->has_attribute($attribute)){
                    $object->$attribute = $value ;
                }


            }
            return $object ;
        } 

        private function has_attribute($attribute){

            $object_vars = $this->attrributes();
    
            return array_key_exists($attribute , $object_vars) ;
        }
    
    
        protected function attrributes(){
            $attributes =array();
            foreach(self::$db_fields as $field){
                if(property_exists($this,$field)){
                    $attributes[$field] = $this->$field ; 
                }
            }
            return $attributes  ;
        }

        public static function insert_in_database($first_name_field,$second_name_field,  
            $email_field,$password_field){

            global $database;

            //prcess the form
            //escape all strings to prevent sql injection with mysqli_prep
            $first_name = strtolower($database->escaped_value(htmlentities($_POST[$first_name_field]))) ;
            $second_name = strtolower($database->escaped_value(htmlentities($_POST[$second_name_field]))) ;
            $email = strtolower($database->escaped_value(htmlentities($_POST[$email_field]))) ;
            $hashed_password = password_hash($_POST[$password_field],PASSWORD_BCRYPT,['cost=>10']) ;

            
            $query = "INSERT INTO ".self::$table_name ." (";
            $query .= " first_name,second_name,user_name,hashed_password";
            $query .= ") VALUES (" ;
            $query .= " '{$first_name}','{$second_name}','{$email}','{$hashed_password}'";
            $query .= ")";


            if($result = mysqli_query($database->connection,$query)){
                return true ;
            }
            // failed
            return false ;
        }


        public static function check_before_sign_in($user_name_field,$password_field,$remember_me_field){

            $object = new self;

            if(! $object->authenticate($user_name_field,$password_field)){
                //fail
                Helper::redirect_to("sign_in.php?");
            }

            Log::write_in_log("{$_SESSION['user_id']} signed in ".date("d-m-Y")." ".date("h:i:sa")."\n");
            return true ;
        }


        public static function authenticate($user_name_field="",$password_field=""){
            $object = new self ;

            foreach(self::get_by_user_name($_POST[$user_name_field]) as $admin){              
                if(password_verify($_POST[$password_field] , $admin->hashed_password )){ 
                    $_SESSION["user_id"] = $admin->id;
                    $_SESSION["first_name"] = $admin->first_name;
                    $_SESSION["background_image"] = $admin->background_image;
                    return  true ;
                }
            }
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


        public function save(){

            return isset($this->id) ? $this->update() : $this->create() ;
        }

        public function update(){
            global $database ;

            //Don't forget your sql syntax and good habits
            //- UPDATE table SET Key = 'value' WHERE condition
            //- single-quotes around all values
            //- escape all values to prevent sql injection
    
            $attributes = $this->sanitized_attributes();
            $attribute_pairs = array();
            foreach($attributes as $key => $value){
                $attribute_pairs[] = "{$key} = {$value}" ;
                
            }


            $sql  ="UPDATE " .self::$table_name ." SET " ;
            $sql .=join(", ",$attribute_pairs);
            $sql .=" WHERE id=" .$database->escaped_value($this->id) ;
            $sql .=" LIMIT 1" ;


            $database->query($sql) ;
            if($database->affected_rows() == 1){
                return true ;
            }else{
                return false ;
            }
        }
        protected function sanitized_attributes(){
            global $database ;
            $clean_attributes = array();
            foreach($this->attrributes() as $key=>$value){
                $clean_attributes[$key] = $database->escaped_value($value);
            }
            return $clean_attributes ;
        }
        public function create(){
            global $database ;
            //Don't forget your sql syntax and good habits
            //- INSERT INTO table (Key,Key) VALUES (value,value)
            //- single-quotes around all values
            //- escape all values to prevent sql injection
    
            $attributes = $this->sanitized_attributes();
    
            $sql  ="INSERT INTO ".self::$table_name ." (";
            $sql .=join(",",array_keys($attributes)) ;
            $sql .=") VALUES ('" ;
            $sql .=join("','",array_values($attributes)) ;
            $sql .= "')";
    
            if($database->query($sql)){
                //$this->id = $database->insert_id();
                return true ;
            }else{
                return false ;
            }
        }
        
        public function delete(){
            global $database ;
            //Don't forget your sql syntax and good habits
            //- DELETE FROM table WHERE condition LIMIT 1
            //- single-quotes around all values
            //- escape all values to prevent sql injection
            //- user LIMIT 1
    
            $sql = "DELETE FROM ".self::$table_name ;
            $sql .= " WHERE id =".$database->escaped_value($this->id) ;
            $sql .= " AND user_id = {$_SESSION['user_id']}"  ;
            $sql .= " LIMIT 1" ;
        
            $database->query($sql) ;
            if($database->affected_rows() == 1){
                return true ;
            }else{
                return false ;
            }
        
        }

        public static function find_by_id($id=0){
            global $database ;
    
            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name ." WHERE id={$database->escaped_value($id)} LIMIT 1");
        
            return !empty($result_array)? array_shift($result_array) : false;
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

