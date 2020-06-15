<?php

    require_once('PHPMailer/src/PHPMailer.php');
    require_once('PHPMailer/src/SMTP.php');
    require_once('PHPMailer/language/phpmailer.lang-en.php');

    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = gethostname();
    $mail->SMTPAuth = true;
    $mail->Username = 'khaledmostafa297@gmail.com';
    $mail->Password = '01143325016';
    $mail->setFrom('khaledmostafa297@gmail.com');
    $mail->addAddress('khaledmostafa297@gmail.com');
    $mail->Subject = 'Here is the subject';
    $mail->Body    = 'This is the body.';
    $mail->send();

?>



<?php

    function try_to_send_mail($message=""){

        $to = "khaledmostafa297@gmail.com";
        $to_name = "amr mostafa";
        
        $subject = "New sign up ".strftime("%T",time());
        
        $message = wordwrap($message,70);
        
        $from = "khaled_mostafa900@yahoo.com";
        $from_name = "my_monitor";
        
        $mail = new PHPMailer();

        // $mail->IsSMTP();
        // $mail->Host = "your_host.com" ;
        // $mail->Port = "25" ;
        // $mail->SMTPAuth = false ;
        // $mail->User_name = "user_name";
        // $mail->Password = "password";

        $mail->From_name = $from_name ;
        $mail->From = $from ;
        $mail->AddAddress($to,$to_name) ;
        $mail->Subject = $subject ;
        //$mail->Body = $message ;

        $mail->Body =<<<EMAILBODY
a new sign up has been submitted from {$message}
EMAILBODY;
        $result_mail = $mail->Send() ;
        $_SESSION["message"] = $result_mail? 'Sent' : 'Error';
    }

?>