<?php
    // ============================================
    //     - PBSCLP | post_approved_email
    //     - Adam Shepherd
    //     - PBSCLP
    //     - April 2022

    //     This script sends an email to the user
    //     alerting them their post has been approved
    // ============================================


    // Reference Links:
    // https://laratutorials.com/php-send-reset-password-link-email/
    // https://github.com/PHPMailer/PHPMailer

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    

    require '../includes/PHPMailer/src/Exception.php';
    require '../includes/PHPMailer/src/PHPMailer.php';
    require '../includes/PHPMailer/src/SMTP.php';


    function sendEmail($email_to, $name_to) {
        $e_pass = file_get_contents('../../e-pass.txt', true);

        $mail = new PHPMailer();
        $mail->CharSet =  "utf-8";
        $mail->IsSMTP();
        
        $mail->SMTPAuth = true; // enable SMTP authentication

        $mail->Username = "info@pbsclp.info";
        $mail->Password = $e_pass;

        $mail->SMTPSecure = "tls";
        
        $mail->Host = "smtp-relay.sendinblue.com"; // sets pbsclp mail server as the SMTP server
        $mail->Port = "587"; // set the SMTP port for the pbsclp server

        $mail->From='forumposts@pbsclp.info';
        $mail->FromName='PBSCLP Forum Post System';

        $mail->AddAddress($email_to, $name_to);
        $mail->Subject  =  'Forum Post Approved';
        $mail->IsHTML(true);

        $message = file_get_contents('../email_templates/post_approved_email_template.html');
        $mail->MsgHTML($message);
        $mail->AddEmbeddedImage('../images/pbslogo.png', 'pbslogo');

        $mail->AltBody = 'Post Approved. A forum post you submitted has been approved. Please log into the PBSCLP platform to view the post..';

        if($mail->Send())
        {
            return "*email_sent_successfully*";
        }
        else
        {
            $msg = "Mail Error - >" . $mail->ErrorInfo;
            return $msg;
        }
    }

?>