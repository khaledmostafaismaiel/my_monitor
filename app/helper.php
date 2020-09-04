<?php

    function sql_sanitize($value){

        if(function_exists("mysqli_real_escape_string")){
            // PHP >= V 4.3.0
            if(get_magic_quotes_gpc()){
                $value = stripslashes($value);
                $value = mysqli_real_escape_string($value);//to prevent sql injection
            }
        }else{
            if(!get_magic_quotes_gpc()){
                $value = addslashes($value);
            }
        }

        return $value ;
    }

    function html_sanitize($string){

        return $string = htmlentities($string) ;
    }

    function encode_url($string){

        return $string = urlencode($string) ;
    }

    function decode_url($string){

        return $string = urldecode($string) ;
    }
