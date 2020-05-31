<?php
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
            $value = trim($_POST[$field]) ;
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
            $value = trim($_POST[$field]) ;
            if(!has_max_length($value,$max)){
                $errors[$field] = field_name_as_text($field)." is to long";
            }

        }

    }

    function has_inclusion_in(){
        /**/

        return in_array($value,$set);
    }


    function form_errors($errors=array()){
        /**/

        $out_put = ""  ;
        if(!empty($errors)){
            $out_put .="<div class=\"error\">" ;
            $out_put .="please fix the folloing errors" ;
            $out_put .="<ul>" ;
            foreach($errors as $key => $error){
                $out_put .= "<li>{$error}</li>" ;
            }
            $out_put .="</ul>" ;
            $out_put .="</div>" ;

        }

        return $out_put;
    }
?>