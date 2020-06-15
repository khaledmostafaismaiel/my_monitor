<?php



class Log{

    private $log_file = SITE_ROOT.DS.'..'.DS.'logs'.DS.'log_file.txt';
    private $log_dir = 'logs';


    public static function write_in_log($message){
        $object = new self ;

        if($object->check_for_dir_existance()){
            if(! $object->check_for_file_existance('logs')){
                $handel=fopen($object->log_file,'w');
                fclose($handel);
            }
        }else{
            mkdir("logs",0777);

            $object->create_log_file();
        }


        if($handel=fopen($object->log_file,'a')){
            fwrite($handel,$message);
            fclose($handel);
            return true ;
        }else{
            return false ;
        }
    }

    private function check_for_dir_existance(){

        chdir ('..') ;

        if(is_dir($this->log_dir)){
            return true ;
        }else{

            return false ;
        }    
    }


    private function check_for_file_existance(){
        $object = new self ;

        $handel_dir = opendir($this->log_dir);
            
        if($handel=fopen($object->log_file,'r')){
            fclose($handel);
            return true ;
        }else{
            fclose($handel);
            return false ;
        }
        closedir($handel_dir);   
    }


    private function create_log_file(){
        $object = new self ;

        $handel=fopen($object->log_file,'w');
        fclose($handel);  
    }

    
}


// if($_GET["clear"] == true){
//     file_put_contents($log_file,""); //كده هو مسح الفايل القديم وعمل فايل جديد وسابه فاضى
//     log_action("logs cleared","by user id {$session->user_id}") ;
//     redirect_to("sign_in.php") ;
// }


