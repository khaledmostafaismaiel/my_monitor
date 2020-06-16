<?php 



class Helper{



    public static function redirect_to($new_location){

        header("Location: ".$new_location);
        exit;
    }

    public static function get_from_url($string){

		if(isset($_GET[$string])){
			$value = htmlentities($_GET[$string]) ;
		}else{
			$value = null;
		}

		return $value ;
    }
    

    


}
    $errors = array();   






    function field_name_as_text($field_name){

        $field_name = str_replace("_","",$field_name);
        $field_name = ucfirst($field_name);

        return $field_name ;
    }


    function has_presence($value){
        return isset($value) && $value != "" ;
    }

    function validate_has_presence($fields){
        /**/
        global $errors ;

        foreach($fields as $field){
            $value = trim(htmlentities($_POST[$field])) ;
            if(!has_presence($value)){
                $errors[$field] = field_name_as_text($field)." can't be blank";
            }

        }


    }


    function has_max_length($value,$max){
        return strlen($value) <= $max ;
    }

    function validate_max_lengths($fields){
        /**/
        global $errors ;

        foreach($fields as $field => $max){
            $value = trim(htmlentities($_POST[$field])) ;
            if(!has_max_length($value,$max)){
                $errors[$field] = field_name_as_text($field)." is to long";
            }

        }

    }

    

    function has_min_length($value,$max){
        return strlen($value) >= $max ;
    }

    function validate_min_lengths($fields){
        /**/
        global $errors ;

        foreach($fields as $field => $max){
            $value = trim(htmlentities($_POST[$field])) ;
            if(!has_min_length($value,$max)){
                $errors[$field] = field_name_as_text($field)." is to short";
            }

        }

    }


    function include_layout_template($template){

        include(LAYOUTS_PATH.DS.$template);
    }


    
    function __autoload($class_name){

        $class_name = strtolower($class_name);

        $path = LIB_PATH .DS."{$class_name}.php";
        if(file_exists($path)){
            require_once($path);
        }else{
            die("the file {$class_name}.php couldn't be found. ") ;
        }
    }