<?php 


    $errors = array();   



    function redirect_to($new_location){

        header("Location: ".$new_location);
        exit;
    }


    function get_page_number(){

        if(isset($_GET["pagenumber"])){
            $page_number = htmlentities($_GET["pagenumber"]) ;
        }else{
            $page_number = null ;
        }

        return $page_number ;

    }


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


    
    function __autoload($class_name){

        $class_name = strtolower($class_name);

        $path = "../includes/{$class_name}.php";
        if(file_exists($path)){
            require_once($path);
        }else{
            die("the file {$class_name}.php couldn't be found. ") ;
        }
    }

    function include_layout_template($template){

        include(LIB_PATH.DS.'layout'.DS.$template);
    }

    function log_action($action ,$message=""){

        $log_file = SITE_ROOT.DS.'logs'.DS.'log.txt' ;
        $new = file_exists($log_file) ? false : true ;

        if($handel = fopen($log_file , 'a')){
            $time_stamp = strtotime("%Y-%m-%d %H:&M:%S" ,time()) ;
            $conntent = "{$time_stamp} | {$action} : {$message}\n" ;
            fwrite($handel,$conntent);
            fclose($handel);
            if($new){
                chmod($log_file,0755);
            }
        }else{
            echo "couldn't open log file to write" ;
        }

    }

