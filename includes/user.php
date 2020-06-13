<?php

    require_once("database.php");
    require_once("functions.php");

    class User extends Database_object{

        protected static $table_name = "admins" ;
        protected static $db_fields = array('id',
            'first_name','second_name','user_name','password') ;

        public $id ;
        public $first_name ;
        public $second_name ;
        public $user_name ;
        public $password ;


        //very unique methods for this class
        public function full_name(){

            if((isset($this->first_name)) && (isset($this->second_name)) ){

                return $this->first_name." ".$this->second_name ;
            }else{
                return "" ;
            }
        }



        public static function authenticate($user_name="",$password=""){
            global $database ;
            $user_name = $database->escaped_value($user_name);
            $password = $database->escaped_value($password);

            $sql  = "SELECT * FROM admins WHERE " ;
            $sql .= " user_name = {$user_name} AND ";
            $sql .= "password = {$password} ";
            $sql .= "LIMIT 1";

            $result_array = self::find_by_sql($sql);
        
            return !empty($result_array)? array_shift($result_array) : false;

        }


        //common methods for classes
        public static function find_all(){
            
            return self::find_by_sql("SELECT * FROM .self::$table_name");
        }


        public static function find_by_id($id=0){
            global $database ;

            $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE id={$id} LIMIT 1");
        
            return !empty($result_array)? array_shift($result_array) : false;
        }


        public static function find_by_sql($sql = ""){

            global $database ;
            $result_Set = $database->query($sql);

            $object_array = array() ;
            while($row = $database->fetch_array($result_set)){

                $object_array[] = self::instantiate($row) ;
            }


            return $object_array ;
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

        protected function sanitized_attributes(){
            global $database ;
            $clean_attributes = array();
            foreach($this->attrributes() as $key=>$value){
                $clean_attributes[$key] = $database->escaped_value($value);
            }
            return $clean_attributes ;
        }

        private static function instantiate($user_data){
            $object = new self ;
            //very long approch spatially if the record have lots of attributs 
            // $object->id = $user_data["id"];
            // $object->first_name = $user_data["first_name"];
            // $object->second_name = $user_data["second_named"];
            // $object->user_name = $user_data["user_name"];
            // $object->password = $user_data["password"];

            //very short approch
            foreach($user_data as $attribute=>$value){
                if($object->has_attribute($attribute)){
                    $object->$attribute = $value ;
                }
            }
            return $object ;
        } 





        public function save(){

            return isset($this->id) ? $this->update() : $this->create() ;
        }

        public function create(){
            global $database ;
            //Don't forget your sql syntax and good habits
            //- INSERT INTO table (Key,Key) VALUES (value,value)
            //- single-quotes around all values
            //- escape all values to prevent sql injection

            $attrributes = $this->sanitized_attributes();

            $sql  ="INSERT INTO ".self::$table_name ." (";
            $sql .=join(",",array_keys($attributes)) ;
            $sql .=") VALUES ('" ;
            $sql .=join("','",array_values($attributes)) ;
            $sql .= "')";

            if($database->query($sql)){
                $this->id = $database->insert_id();
                return true ;
            }else{
                return false ;
            }
        }

        public function update(){
            global $database ;
            //Don't forget your sql syntax and good habits
            //- UPDATE table SET Key = 'value' WHERE condition
            //- single-quotes around all values
            //- escape all values to prevent sql injection

            $attrributes = $this->sanitized_attributes();
            $attribute_pairs = array();
            foreach($attributes as $key => $value){
                $attribute_pairs[] = "{$key} = {$value}" ;
            }
            $sql  ="UPDATE " .self::$table_name ." SET " ;
            $sql .=join(", ",$attribute_pairs);
            $sql .=" WHERE id=" .$database->escaped_value($this->id) ;

            $database->query($sql) ;
            if($database->affected_rows() == 1){
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
            $sql .= " LIMIT 1" ;
        
            $database->query($sql) ;
            if($database->affected_rows() == 1){
                return true ;
            }else{
                return false ;
            }
        
        
        
        }


        public static function count_all($result_Set){
            new $database ;

            $sql = "SELECT COUNT(*) FROM ".self::$table_name;
            $result_Set = $database->query($sql);
            $row = $database->fetch_array($result_Set);
            return array_shift($row);
        }
        /*;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;*/
        public static function get_admin_data_by_user_name($user_name_field){

            global $database;
    
            $safe_user_name_field = $database->escaped_value($user_name_field);
            $user_name = htmlentities($_POST[$safe_user_name_field]);
    
            //2. perform database query
            $query = "SELECT * ";
            $query .="FROM ".self::$table_name;
            $query .=" WHERE user_name = '{$user_name}' ";
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


        public static function insert_admin_in_database($first_name_field,$second_name_field,  
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

            ;

            if(!$result = mysqli_query($database->connection,$query)){
                // failed
                $_SESSION["message"] = "Try Again";
                redirect_to("sign_up.php?");
            }
        }

        public static function get_admin_id_by_user_name($email_field){
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

        public function get_all_admins(){

            global $database;

            
            //2. perform database query
            $query = "SELECT * ";
            $query .="FROM " .self::$table_name;
            $query .=" ORDER BY id ASC ";

            $result_set= mysqli_query($database->connection , $query);

            //test if there was a query error
            $database->confirm_query($result_set);
            
            return $result_set;
        }

        public static function ckeck_for_user_existance($email_field){
            global $errors ;

            $email = strtolower(htmlentities($_POST[$email_field])) ;
        
            $admins_set= self::get_all_admins();
            while($admin=mysqli_fetch_assoc($admins_set)){
                if($admin["user_name"] == $email){
                    $errors[$email_field] = field_name_as_text($email_field) ." user is arready exist.";
                return;
                }
            }
            return;
        }





        public function get_admin_data_by_id($admin_id){

            global $database;

            $safe_admin_id = $database->escaped_value($admin_id);
            
            //2. perform database query
            $query = "SELECT * ";
            $query .="FROM admins ";
            $query .="WHERE id = {$safe_admin_id} ";
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




        public static function check_before_sign_up($first_name_field,$second_name_field,  
            $email_field,$password_field,$confirm_password_field,$not_robot_field,$terms_of_conditions_field){

            global $errors ;
            $object = new self ;

            validate_has_presence(array($first_name_field ,$second_name_field ,
            $email_field,$password_field ,$confirm_password_field
            ,$not_robot_field,$terms_of_conditions_field));

            validate_max_lengths( array($first_name_field=> 30 ,$second_name_field =>30,
            $email_field => 30 ,$password_field => 30 ,$confirm_password_field => 30
            ,$not_robot_field => 1,$terms_of_conditions_field => 1) );

            validate_min_lengths( array($password_field => 8 ,$confirm_password_field => 8) );


            $object->check_password_and_confirm_similarity($password_field,$confirm_password_field);

            user::ckeck_for_user_existance($email_field);


            if(empty($errors)){
                //success
                user::insert_admin_in_database($first_name_field ,$second_name_field,$email_field,
                $password_field );
            }else{
                //fail
                redirect_to("sign_up.php") ;
            }

        }


        public static function check_before_sign_in($user_name_field,$password_field,$remember_me_field){

            $object = new self;

            if(!$object->attempt_sign_in($user_name_field,$password_field)){
                //fail
                redirect_to("sign_in.php?");
            }
            return true ;
        }
    
        
        private function signed_in(){
            return isset($_SESSION["user_id"]) ;
        }
    
        public static function confirm_sign_in(){
            $object = new self ;

            if(!$object->signed_in()){
                redirect_to("sign_in.php");
            }
        }
    
    
    
    
    

        private function check_password_and_confirm_similarity($password_field,$confirm_password_field){
            global $errors ;
    
            if(htmlentities($_POST[$password_field]) !== htmlentities($_POST[$confirm_password_field])){
                $errors[$password_field] = field_name_as_text($email_field) ."password and confirmation password didn't not match.";
            }
        }
    
    
        private function password_check($existing_hashed_password , $password_field){
    
            if(password_verify(htmlentities($_POST[$password_field]),$existing_hashed_password)){
                return true ;
            }else{
                return false ;
            }
        }
    
    
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
    
    
     
    
    
        private function attempt_sign_in($user_name_field,$password_field){
    
            $object = new self ;
                
            if($admin = user::get_admin_data_by_user_name($user_name_field)){              
                    
                    if($object->password_check($admin["hashed_password"] , $password_field))
                        $_SESSION["user_id"] = $admin["id"];
                        $_SESSION["first_name"] = $admin["first_name"];
                        return  true ;
            }
    
            return  false ;
        }
    
    
    
    
    
    
    
    
    
    
    
    
    
    }

    //لو عندك ميثود نوعها كذه بتنادى عليها فى نفس الكلاس  بالطريقه دى
    ////////////////public  $object = new self ;
    ////////////////private  $object = new self ;
    /////////////////protected   $this->
    ///////////////static self::

    //لو عندك فاريبل نوعه كذه بتنادى عليه فى نفس الكلاس  بالطريقه دى
    ////////////////public  $this-> ;
    ////////////////private  $this-> ;
    ////////////////protected  $this-> ;
    ////////////////static self::  ;

    /*
        if(){
            throw new Exception("Khaled"); 
        }
    */
?>


