<?php 


$errors = array();   



class Helper{



    public static function redirect_to($new_location){

        header("Location: ".$new_location);
        exit;
    }

    public static function get_from_url($string){
        global $database;
		if(isset($_GET[$string])){
			$value = $database->html_sanitize($database->sql_sanitize($_GET[$string])) ;
		}else{
			$value = null;
		}

		return $value ;
    }
    
    public static function field_name_as_text($field_name){

        $field_name = str_replace("_","",$field_name);
        $field_name = ucfirst($field_name);

        return $field_name ;
    }

    
    function has_presence($value){
        return isset($value) && $value != "" ;
    }

    function validate_has_presence($strings){
        $object = new self ;
        global $errors ;

        foreach($strings as $string):
            $value = trim($string) ;
            if(! $object->has_presence($value)){
                $errors[$string] = Helper::field_name_as_text($string)." isn't exist";
                return false ;
            }

        endforeach;

        return true ;
    }

    private function has_max_length($value,$max){
        return strlen($value) <= $max ;
    }

    public static function validate_max_lengths($strings){
        $object = new self ;
        
        global $errors ;

        foreach($strings as $string => $max):
            $value = trim(htmlentities($string)) ;
            if(! $object->has_max_length($value,$max)){
                $errors[$string] = Helper::field_name_as_text($string)." is to long";
            }

        endforeach;

    }

    
    private function has_min_length($value,$max){
        return strlen($value) >= $max ;
    }

    public static function validate_min_lengths($strings){
        $object = new self ;
        global $errors ;

        foreach($strings as $string => $max):
            $value = trim(htmlentities($string)) ;
            if(! $object->has_min_length($value,$max)){
                $errors[$string] = Helper::field_name_as_text($string)." is to short";
            }

        endforeach;

    }

    public static function get_script_name(){

        $script_name = str_replace(".php","",$_SERVER['SCRIPT_NAME']) ;
        $script_name = str_replace("/","",$script_name) ;
        $script_name = str_replace("_"," ",$script_name) ;

        if($script_name == "index"){
            $script_name = "home" ;
        }

        return $script_name ;
    }



    public static function include_layout_template($template){

        include(LAYOUTS_PATH.DS.$template);
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




    
    function __autoload($class_name){

        $class_name = strtolower($class_name);

        $path = LIB_PATH .DS."{$class_name}.php";
        if(file_exists($path)){
            require_once($path);
        }else{
            die("the file {$class_name}.php couldn't be found. ") ;
        }
    }