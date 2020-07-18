<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Background extends Model
{

    protected $fillable = [
        'user_id' ,
        'file_name' ,
        'type' ,
        'size' ,
        'caption' ,
        'temp_name'
    ] ;

    protected $upload_dir ="/storage/uploads";


    public function user(){//return Backgrounds which belongs to that user
        return $this->belongsTo(User::class);
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
    public function attach_file($file)
    {

        // Perform error checking on the form parameters
        if (!$file || empty($file) || !is_array($file)) {
            // error: nothing uploaded or wrong argument usage
            $errors = "No file was uploaded.";
            return false;

        } elseif ($file['error'] != 0) {
            // error: report what PHP says went wrong
            $errors = $this->upload_errors[$file['error']];
            return false;

        } else {

//            // Set object attributes to the form parameters.
//            $this->temp_path  = $file['tmp_name']);
//            $this->file_name   = strtolower(basename($file['name']));
//            $this->type       = strtolower($file['type']);
//            $this->size       = $file['size'];
//            $this->temp_name  =  $this->generateRandomString() ;
//            // Don't worry about saving anything to the database yet.
//            return true;
        }
    }

    public function generateRandomString($length = 10) {

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randstring = '';
        for ($i = 0; $i < $length; $i++) {
            $randstring .= $characters[rand(0, strlen($characters))];
        }

        $randdate = date("YmdHis");

        $temp_token = $randstring.$randdate ;

        return$temp_token ;
    }

    public function get_uploads_path(){
        return $this->upload_dir;
    }


}








