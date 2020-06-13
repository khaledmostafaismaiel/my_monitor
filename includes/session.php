<?php

    require_once("config.php");
    
    class Session{

        private $logged_in=false ;
        public $user_id ;

        function __construct(){
            session_start();
            $this->check_login();
            if($this->logged_in){

            }else{

            }  
        }

        private function check_login(){

            if(isset($_SESSION["user_id"])){
                $this->logged_in = true ;
                $this->user_id = $_SESSION["user_id"] ;
            }else{
                $this->logged_in = false ;
                unset($this->user_id ); 
            }
        }

        
        public function is_logged_in(){
            return $this->logged_in ;
        }

        public function login($user){

            if($user){
                $this->user_id = $_SESSION["user_id"] = $user->id ;
                $this->logged_in = true ;
            }
        }

        public function logout(){

            if($user){
                unset($this->user_id);
                unset($_SESSION["user_id"]) ;
                $this->logged_in = false ;
            }
        }
        
        public static function session_message(){

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
            // elseif(isset($_SESSION["user_id"])){
    
            //     $out_put = "<div class=\"message-session\">" ;
            //     $out_put .= "<span class = \"message-session-span\">" ;
            //     $out_put .= $_SESSION["user_id"]; 
            //     $out_put .= "</span>" ;
            //     $out_put .= "</div>" ;
            // }
            else{
                $out_put = null ; 
            }
    
            return $out_put ;
        }
        
    }

    $sesstion =  new Session();
