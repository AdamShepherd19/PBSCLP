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


    function sendEmail($email_to, $name_to) {
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

        $mail->AddAddress($email_to, $name_to);
        $mail->Subject  =  'Forum Post Feedback';
        $mail->IsHTML(true);

        $mail->Body    = '<h1> Forum Post Feedback </h1> <br> A forum post you submitted has received some feedback. Please log into the PBSCLP platform to review the post. <br><br> <h4> Post Title </h4>' . $post_title . '<br><br> <h4> Post Content </h4>' . $post_content . '<br><br> <h4> Post Feedback </h4>' . $post_feedback;

        $mail->AltBody = 'New Forum Post. A new post has been submitted for approval. Please log in to the PBSCLP platform to review the new post. Post Title: ' . $post_title . 'Post Content: ' . $post_content . 'Post Feedback: ' . $post_feedback;

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