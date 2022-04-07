<?php

    // function sendEmail() {
    //     $e_pass = file_get_contents('../../f-e-pass.txt', true);

    //     $mail = new PHPMailer();
    //     $mail->CharSet =  "utf-8";
    //     $mail->IsSMTP();
        
    //     $mail->SMTPAuth = true; // enable SMTP authentication

    //     $mail->Username = "forumposts@pbsclp.info";
    //     $mail->Password = $e_pass;

    //     $mail->SMTPSecure = "ssl";
        
    //     $mail->Host = "mail.pbsclp.info"; // sets pbsclp mail server as the SMTP server
    //     $mail->Port = "465"; // set the SMTP port for the pbsclp server

    //     $mail->From='forumposts@pbsclp.info';
    //     $mail->FromName='PBSCLP Password Reset System';

    //     $mail->AddAddress($email, $name);
    //     $mail->Subject  =  'Reset Password';
    //     $mail->IsHTML(true);

    //     $mail->Body    = '<h1> Password Reset Email </h1> <br><br>Click On This Link to reset your password: ' . $link . '';

    //     $mail->AltBody = 'Click On This Link to Reset Password https://pbsclp.info/pages/change_password.php?key='.$email.'&token='.$token.'';

    //     if($mail->Send())
    //     {
    //         exit("*email_sent_successfully*");
    //     }
    //     else
    //     {
    //         echo "Mail Error - >".$mail->ErrorInfo;
    //     }
    // }

?>