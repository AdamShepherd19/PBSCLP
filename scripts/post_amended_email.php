<?php
    // ============================================
    //     - PBSCLP | post_amended_email
    //     - Adam Shepherd
    //     - PBSCLP
    //     - April 2022

    //     This script alerts the practitioner email
    //     that a post has been amended after receiving
    //     feedback
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
        $e_pass = file_get_contents('../../f-e-pass.txt', true);

        $mail = new PHPMailer();
        $mail->CharSet =  "utf-8";
        $mail->IsSMTP();
        
        $mail->SMTPAuth = true; // enable SMTP authentication

        $mail->Username = "forumposts@pbsclp.info";
        $mail->Password = $e_pass;

        $mail->SMTPSecure = "ssl";
        
        $mail->Host = "mail.pbsclp.info"; // sets pbsclp mail server as the SMTP server
        $mail->Port = "465"; // set the SMTP port for the pbsclp server

        $mail->From='forumposts@pbsclp.info';
        $mail->FromName='PBSCLP Forum Post System';

        $mail->AddAddress('forumposts@pbsclp.info', 'PBSCLP Forum Post System');
        $mail->Subject  =  'Forum Post Update';
        $mail->IsHTML(true);

        $mail->Body    = '<h1> Amended Forum Post </h1> <br> A post has been amended based on administrator feedback. Please log in to the PBSCLP platform to review the amended post. <br><br> <h4> New Title </h4>' . $post_title . '<br><br> <h4> New Content </h4>' . $post_content;

        $message = file_get_contents('../email_templates/post_amended_email_template.html');
        $message = str_replace('%post_title%', $post_title, $message);
        $message = str_replace('%post_content%', $post_content, $message);
        $mail->MsgHTML($message);
        $mail->AddEmbeddedImage('../images/pbslogo.png', 'pbslogo');

        $mail->AltBody = 'Amended Forum Post. A post has been amended based on administrator feedback. Please log in to the PBSCLP platform to review the amended post. New Title: ' . $post_title . 'New Content: ' . $post_content;

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