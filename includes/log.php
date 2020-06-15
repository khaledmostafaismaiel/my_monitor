<?php



class Log{

    private $log_file = SITE_ROOT.DS.'..'.DS.'logs'.DS.'log_file.txt';


    public static function write_in_log($message){
        $object = new self ;

        if($handel=fopen($object->log_file,'a')){
            fwrite($handel,$message);
            fclose($handel);
            return true ;
        }else{
            return false ;
        }
    }

    private function check_for_dir_existance($dir){
        $object = new self ;

        
        chdir ('..') ;

        if(is_dir($dir)){
            $object->check_for_file_existance();
            closedir($handel_dir);
        }else{
            mkdir("logs",0777);

            $handel=fopen($object->log_file,'w');
            fclose($handel);
        }
    
    }


    private function check_for_file_existance($dir){
        $object = new self ;

        $handel_dir = opendir($dir);
            
        if($handel=fopen($object->log_file,'r')){
            fclose($handel);
            return true ;
        }else{
            fclose($handel);
            $handel=fopen($object->log_file,'w');
            fclose($handel);

            return false ;
        }

        closedir($handel_dir);
            
    
        }
    
    }


// if($_GET["clear"] == true){
//     file_put_contents($log_file,""); //كده هو مسح الفايل القديم وعمل فايل جديد وسابه فاضى
//     log_action("logs cleared","by user id {$session->user_id}") ;
//     redirect_to("sign_in.php") ;
// }


