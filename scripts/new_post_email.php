<?php
    // ============================================
    //     - PBSCLP | new_post_email
    //     - Adam Shepherd
    //     - PBSCLP
    //     - April 2022

    //     This script sends an email alerting an
    //     admin there is a new post to be reviewed
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


    function sendEmail($post_title, $post_content) {
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

        $mail->AddAddress('forumposts@pbsclp.info', 'PBSCLP Forum Post System');
        $mail->Subject  =  'Forum Post Update';
        $mail->IsHTML(true);

        $message = file_get_contents('../email_templates/new_post_email_template.html');
        $message = str_replace('%post_title%', $post_title, $message);
        $message = str_replace('%post_content%', $post_content, $message);
        
        $mail->MsgHTML($message);

        $mail->AddEmbeddedImage('../images/pbslogo.png', 'pbslogo');

        $mail->AltBody = 'New Forum Post. A new post has been submitted for approval. Please log in to the PBSCLP platform to review the new post. Post Title: ' . $post_title . 'Post Content: ' . $post_content;

        

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