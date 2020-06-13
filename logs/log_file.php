<?php require_once("initialize");?>
<?php if(!$session->is_logged_in()){
        redirect_to("sign_in.php");
        } 
?>

<?php
    $log_file = SITE_ROOT.DS.'logs'.DS.'log.txt';
    if($_GET["clear"] == true){
        file_put_contents($log_file,""); //كده هو مسح الفايل القديم وعمل فايل جديد وسابه فاضى
        log_action("logs cleared","by user id {$session->user_id}") ;
        redirect_to("sign_in.php") ;
    }
?>

<?php
    if(file_exists($log_file) && is_readable($log_file) && $handel=fopen($log_file,'r')){

        echo "<ul class=\"log-entries\">" ;
        while(!feof($handel)){
            $entry = fgets($handel);
            if(trim($entry) != ""){
                echo "<li>{$entry}</li>";
            }
        }
        echo "</ul>" ;
        fclose($handel);
    }else{

        echo "couldn't read from {$log_file}" ;
    }
?>










