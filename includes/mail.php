<?php

    require_once(__DIR__ . '/../PHPMailer1/src/PHPMailer.php');
    require_once(__DIR__ . '/../PHPMailer1/src/SMTP.php');
    require_once(__DIR__ . '/../PHPMailer1/src/Exception.php');

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    // require_once(__DIR__ . '/../PHPMailer1/language/phpmailer.lang-en.php');

    function try_to_send_mail($message=""){

        $from = "khaledmostafa297@gmail.com";
        $from_name = "amr mostafa";
        
        $subject = "New sign up ".strftime("%T",time());
        
        $message = wordwrap($message,70);
        
        $mail = new PHPMailer(true);

        $mail->IsSMTP();
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = $from;                     // SMTP username
        $mail->Password   = '01143325016';                               // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = 587;

        $mail->setFrom($from, 'my_monitor');
        $mail->addAddress("khaledmostafa297@gmail.com", "Khaled");     // Add a recipient

        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'New SignUp';
        $mail->Body =<<<EMAILBODY
a new sign up has been submitted from {$message} in my monitor APP
EMAILBODY;
//        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        try {
            $mail->send();
            $_SESSION["message"] = 'Message sent';
        } catch (Exception $e) {
            $_SESSION["message"] = "Message Error"/*: {$mail->ErrorInfo}"*/;
        }

    }

