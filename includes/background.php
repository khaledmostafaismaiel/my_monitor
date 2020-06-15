<?php
// If it's going to need the database, then it's 
// probably smart to require it before we start.
require_once('database.php');

class Background extends Database_object {
	
	protected static $table_name="backgrounds";
	protected static $db_fields=array('id','user_id','file_name', 'type' , 'size', 'caption');
	protected static $num_db_fields = /*sizeof(self::$db_fields)*/6 ;
	public $id;
	public $user_id;
	public $file_name;
	public $type;
	public $size;
	public $caption;
	
	private $temp_path;
    protected$upload_dir =LIB_PATH.DS."uploads";
    public $errors=array();
  
    protected $upload_errors = array(
		// http://www.php.net/manual/en/features.file-upload.errors.php
		UPLOAD_ERR_OK 				=> "No errors.",
	    UPLOAD_ERR_INI_SIZE  	=> "Larger than upload_max_filesize.",
	    UPLOAD_ERR_FORM_SIZE 	=> "Larger than form MAX_FILE_SIZE.",
	    UPLOAD_ERR_PARTIAL 		=> "Partial upload.",
	    UPLOAD_ERR_NO_FILE 		=> "No file.",
	    UPLOAD_ERR_NO_TMP_DIR => "No temporary directory.",
	    UPLOAD_ERR_CANT_WRITE => "Can't write to disk.",
	    UPLOAD_ERR_EXTENSION 	=> "File upload stopped by extension."
	);

	// Pass in $_FILE(['uploaded_file']) as an argument
    public function attach_file($file) {
		// Perform error checking on the form parameters
		if(!$file || empty($file) || !is_array($file)) {
		  // error: nothing uploaded or wrong argument usage
		  $this->errors[] = "No file was uploaded.";
		  return false;

		} elseif($file['error'] != 0) {
		  // error: report what PHP says went wrong
		  $this->errors[] = $this->upload_errors[$file['error']];
		  return false;

		} else {
			// Set object attributes to the form parameters.
		  $this->temp_path  = $file['tmp_name'];
		  $this->file_name   = basename($file['name']);
		  $this->type       = $file['type'];
		  $this->size       = $file['size'];
			// Don't worry about saving anything to the database yet.
			return true;
		}
	}
  
	public function save() {
		// A new record won't have an id yet.
		if(isset($this->id)) {
			// Really just to update the caption
			$this->update();
		} else {
            
            // Make sure the caption is not too long for the DB
            if(strlen($this->caption) > 255) {
                $this->errors[] = "The caption can only be 255 characters long.";
                return false;
            }

            // Can't save without file_name and temp location
            if(empty($this->file_name) || empty($this->temp_path)) {
                $this->errors[] = "The file location was not available.";
                return false;
            }

            // Determine the target_path
            $target_path = $this->upload_dir .DS. $this->file_name;

            // Make sure a file doesn't already exist in the target location
            if(file_exists($target_path)) {
                $this->errors[] = "The file {$this->file_name} already exists.";
                return false;
            }
		
            // Attempt to move the file 
            if(move_uploaded_file($this->temp_path, $target_path)) {
            // Success
                // Save a corresponding entry to the database
                if($this->create()) {
                    // We are done with temp_path, the file isn't there anymore
                    unset($this->temp_path);
                    return true;
                }
            } else {
                // File was not moved.
                $this->errors[] = "The file upload failed, possibly due to incorrect permissions on the upload folder.";
                return false;
            }
		}
	}
	
	// replaced with a custom save()
	// public function save() {
	//   // A new record won't have an id yet.
	//   return isset($this->id) ? $this->update() : $this->create();
	// }
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
		$sql .=join("','",array_values(array_pad($attributes,-self::$num_db_fields,0))) ;
		$sql .= "')";

		if($database->query($sql)){
			//$this->id = $database->insert_id();
			return true ;
		}else{
			return false ;
		}
	}


	public function get_size_text(){
		if($this->size < 1024){

			return "{$this->size} bytes";
		}elseif($this->size < 1048576){
			$size_kb = round($this->size / 1024);
			return "{$size_kb} KB";
		}else{
			$size_mb = round($this->size / 1048576 , 1);
			return "{$size_mb} MB";
		}
		return ;
	}

	public function destroy() {
		// First remove the database entry
		if($this->delete()) {
			// then remove the file
		  // Note that even though the database entry is gone, this object 
			// is still around (which lets us use $this->image_path()).
			$target_path = $this->get_uploads_path();
			return unlink($target_path) ? true : false;
		} else {
			// database delete failed
			return false;
		}
	}

	public function get_uploads_path(){
		return $this->upload_dir.DS.$this->file_name;
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

	

	


}

?>