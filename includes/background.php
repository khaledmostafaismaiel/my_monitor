<?php
// If it's going to need the database, then it's 
// probably smart to require it before we start.
require_once('database.php');

class Background extends Database_object {
	
	protected static $table_name="backgrounds";
	protected static $db_fields=array('id','user_id','file_name', 'type' , 'size', 'caption');
	public $id;
	public $user_id;
	public $file_name;
	public $type;
	public $size;
	public $caption;
	
	private $temp_path;
    //protected$upload_dir =LIB_PATH.DS."uploads";
    protected$upload_dir =SITE_ROOT.DS."uploads";
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
		global $database ;

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
		  $this->temp_path  = $database->sql_sanitize($file['tmp_name']);
		  $this->file_name   = strtolower($database->sql_sanitize(basename($file['name'])));
		  $this->type       = strtolower($database->sql_sanitize($file['type']));
		  $this->size       = $database->sql_sanitize($file['size']);
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



	public static function count_all($result_Set){
		new $database ;

		$sql = "SELECT COUNT(*) FROM ".self::$table_name;
		$sql .= " WHERE user_id = {$_SESSION['user_id']}"  ;

		$result_Set = $database->query($sql);
		$row = $database->fetch_array($result_Set);
		return array_shift($row);
	}

	
    public function check_before_save(){
		global $database ;

		if($this->caption != null){
			$this->caption = $database->sql_sanitize($this->caption);
	        return  true  ; 

		}else{

		}
    }
	


}

?>