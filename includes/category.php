<?php 

    require_once("database.php");


    class Category extends Database_object{


        protected static $table_name = "categories";
        protected static $db_fields = array("category_id","category_name");

        public $category_id ;
        public $category_name ;


    




        //common methods for classes
        public static function find_all(){
            
            return self::find_by_sql("SELECT * FROM " .self::$table_name ." WHERE user_id = {$_SESSION['user_id']}");
        }
    


        public static function find_by_sql($sql = ""){

            global $database ;
            $result_Set = $database->query($sql);

            $object_array = array() ;
            while($row = $database->fetch_array($result_Set)){

                $object_array[] = self::instantiate($row) ;
            }


            return $object_array ;
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
            foreach($user_data as $attribute=>$value):
                if($object->has_attribute($attribute)){
                    $object->$attribute = $value ;
                }
            endforeach;
            return $object ;
        } 

        private function has_attribute($attribute){

            $object_vars = $this->attrributes();

            return array_key_exists($attribute , $object_vars) ;
        }


        protected function attrributes(){
            $attributes =array();
            foreach(self::$db_fields as $field):
                if(property_exists($this,$field)){
                    $attributes[$field] = $this->$field ; 
                }
            endforeach;
            return $attributes  ;
        }

    


    }