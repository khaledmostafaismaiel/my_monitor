<?php
    require_once("database.php");

    class Database_object
    {


        //common methods for classes
        //common methods for classes
        public static function find_all(){
            
            return self::find_by_sql("SELECT * FROM ".static::$table_name." WHERE user_id = {$_SESSION['user_id']} ORDER BY id DESC");
        }
            
    	public static function find_by_id($id=0){
            global $database ;
    
            $result_array = self::find_by_sql("SELECT * FROM ".static::$table_name ." WHERE id={$database->sql_sanitize($id)} AND user_id = {$_SESSION['user_id']} LIMIT 1");
        
            return !empty($result_array)? array_shift($result_array) : false;
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
            $object = new static ;
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
            foreach(static::$db_fields as $field):
                if(property_exists($this,$field)){
                    $attributes[$field] = $this->$field ; 
                }
            endforeach;
            return $attributes  ;
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
            foreach($attributes as $key => $value):
                $attribute_pairs[] = "{$key} = '{$value}'" ;
                
            endforeach;


            $sql  ="UPDATE " .static::$table_name ." SET " ;
            $sql .=join(", ",$attribute_pairs);
            $sql .=" WHERE id=" .$database->sql_sanitize($this->id) ;
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
            foreach($this->attrributes() as $key=>$value):
                $clean_attributes[$key] = $database->sql_sanitize($value);
            endforeach;
            return $clean_attributes ;
        }
        
        public function create(){
            global $database ;
            //Don't forget your sql syntax and good habits
            //- INSERT INTO table (Key,Key) VALUES (value,value)
            //- single-quotes around all values
            //- escape all values to prevent sql injection
    
            $attributes = $this->sanitized_attributes();
    
            array_shift($attributes);
    
            $sql  ="INSERT INTO ".static::$table_name ." (";
            $sql .=join(",",array_keys($attributes)) ;
            $sql .=") VALUES ('" ;
            $sql .=join("','",array_values($attributes)) ;
            $sql .= "')";
    
    
            if($database->query($sql)){
                //$this->id = $database->inserted_id();
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
    
            $sql = "DELETE FROM ".static::$table_name ;
            $sql .= " WHERE id =".$database->sql_sanitize($this->id) ;
            $sql .= " AND user_id = {$_SESSION['user_id']}"  ;
            $sql .= " LIMIT 1" ;
        
            $database->query($sql) ;
            if($database->affected_rows() == 1){
                return true ;
            }else{
                return false ;
            }
        
        }

















    }

    