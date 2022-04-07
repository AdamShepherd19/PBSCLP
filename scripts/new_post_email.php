<?php
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

        $mail->Body    = '<h1> New Forum Post </h1> <br> A new post has been submitted for approval. Please log in to the PBSCLP platform to review the new post. <br> <h4> Post Title </h4> <br>' . $post_title . '<br><br> <h4> Post Content </h4> <br><br>' . $post_content;

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