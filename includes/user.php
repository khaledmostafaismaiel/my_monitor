<?php

    require_once("database.php");

    class user {

        
        public $id ;
        public $first_name ;
        public $second_name ;
        public $user_name ;
        public $password ;



        public static function find_all(){
            
            return self::find_by_sql("SELECT * FROM admins");
        }


        public static function find_by_id($id=0){
            global $database_object ;

            $result_array = self::find_by_sql("SELECT * FROM admins WHERE id={$id} LIMIT 1");
        
            return !empty($result_array)? array_shift($result_array) : false;
        }


        public static function find_by_sql($sql = ""){

            global $database_object ;
            $result_Set = $database_object->query($sql);

            $object_array = array() ;
            while($row = $database_object->fetch_array($result_set)){

                $object_array[] = self::instantiate($row) ;
            }


            return $object_array ;
        }


        public function full_name(){

            if((isset($this->first_name)) && (isset($this->second_name)) ){

                return $this->first_name." ".$this->second_name ;
            }else{
                return "" ;
            }
        }


        private function has_attribute($attribute){

            $object_vars = get_object_vars($this);

            return array_key_exists($attribute , $object_vars) ;
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




    }

?>