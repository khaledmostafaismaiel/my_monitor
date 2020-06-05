<?php

    session_start();

    function session_message(){

        if(isset($_SESSION["message"])){

            $out_put = "<div class=\"message-session\">" ;
            $out_put .= "<span class = \"message-session-span\">" ;
            $out_put .= htmlentities($_SESSION["message"]) ; 
            $out_put .= "</span>" ;
            $out_put .= "</div>" ;

            //clear message after filterating it with htmlentities and echoing it 
            $_SESSION["message"] = null ;
            
        }
        elseif(isset($_SESSION["errors"])){

            $out_put = "<div class=\"message-session\">" ;
            $out_put .= "<span class = \"message-session-span\">" ;
            $out_put .= $_SESSION["errors"]; 
            $out_put .= "</span>" ;
            $out_put .= "</div>" ;

            //clear errors after echoing it 
            $_SESSION["errors"] = null ;
            
        }
        elseif(isset($_SESSION["user_id"])){

            $out_put = "<div class=\"message-session\">" ;
            $out_put .= "<span class = \"message-session-span\">" ;
            $out_put .= $_SESSION["user_id"]; 
            $out_put .= "</span>" ;
            $out_put .= "</div>" ;
        }else{
            $out_put = null ; 
        }

        return $out_put ;
    }


?> 
