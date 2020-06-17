<?php 

    require_once("database.php");
    require_once("pagination.php");
    require_once("helper.php");


class Expense extends Database_object{ 

    protected static $table_name = "expenses";
    protected static $db_fields = array("id","user_id","expense_name","price",
        "category","comment","created_at");

    public $id ;
    public $user_id ;
    public $expense_name ;
    public $price ;
    public $category ;
    public $comment ;
    public $created_at ;


 
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

    
	/*<------------------------------------------------------------------------------>*/
	
	
	// Common Database Methods

	public static function find_all(){
            
        return self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE user_id = {$_SESSION['user_id']} ORDER BY id DESC");
	}
		
	public static function get_from_url($string){

		if(isset($_GET[$string])){
			$value = htmlentities($_GET[$string]) ;
		}else{
			$value = null;
		}

		return $value ;
	}

	public static function find_by_id($id=0){
		global $database ;

		$result_array = self::find_by_sql("SELECT * FROM ".self::$table_name ." WHERE id={$database->escaped_value($id)} AND user_id = {$_SESSION['user_id']} LIMIT 1");
	
		return !empty($result_array)? array_shift($result_array) : false;
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
			$attribute_pairs[] = "{$key} = '{$value}'" ;
		}
		$sql  ="UPDATE ".self::$table_name." SET " ;
		$sql .=join(", ",$attribute_pairs);
		$sql .=" WHERE id=".$database->escaped_value($this->id) ;

        $database->query($sql) ;

        
		if($database->affected_rows() == 1){
			return true ;
		}else{
			return false ;
		}
	}
    public function create(){
        global $database ;
        //Don't forget your sql syntax and good habits
        //- INSERT INTO table (Key,Key) VALUES (value,value)
        //- single-quotes around all values
        //- escape all values to prevent sql injection

        $attributes = $this->sanitized_attributes();

        array_shift($attributes);


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

	protected function sanitized_attributes(){
		global $database ;
		$clean_attributes = array();
		foreach($this->attrributes() as $key=>$value){
			$clean_attributes[$key] = $database->escaped_value($value);
		}
		return $clean_attributes ;
	}


	public static function count_all($result_Set){
		new $database ;

		$sql = "SELECT COUNT(*) FROM ".self::$table_name;
		$sql .= " WHERE user_id = {$_SESSION['user_id']}"  ;

		$result_Set = $database->query($sql);
		$row = $database->fetch_array($result_Set);
		return array_shift($row);
	}


    public function check_before_save(){

        return  (($this->expense_name != null)  && ($this->price != null))? true : false ; 
    }

}
