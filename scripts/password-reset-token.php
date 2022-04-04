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

    if(isset($_POST['emailPHP'])) {

        $pass = file_get_contents('../../pass.txt', true);
        $e_pass = file_get_contents('../../e-pass.txt', true);

        //connect to database
        try {
            $connectionPDO = new PDO('mysql:host=localhost;dbname=pbsclp_pbsclp', 'pbsclp', $pass);
            $connectionPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            exit('*database_connection_error*');
        }

        $email = $_POST['emailPHP'];

        $sql = "SELECT user_id, firstname, lastname FROM users WHERE email=?";
        $stmt = $connectionPDO->prepare($sql);
        $stmt->execute([$email]);
        $data = $stmt->fetch();
        
        if($data)
        {
            $name = $data['firstname'] . $data['lastname'];

            $token = md5($email).rand(10,9999);
            $expFormat = mktime(date("H"), date("i"), date("s"), date("m")+20 ,date("d"), date("Y"));
            $expDate = date("Y-m-d H:i:s",$expFormat);

            $sql = "UPDATE users set reset_link_token=:token, exp_date=:expDate WHERE email=:email";
            $stmt = $connectionPDO->prepare($sql);
            
            //check to see if the insert was successful
            if ($stmt->execute(['token' => $token, 'expDate' => $expDate, 'email' => $email])) {
                echo 'success';
            } else {
                echo 'Error: ' . $connectionPDO->error;
            }
            
            $link = "<a href='https://pbsclp.info/pages/change_password.php?key=".$email."&token=".$token."'>Click To Reset password</a>";

            $mail = new PHPMailer();
            $mail->CharSet =  "utf-8";
            $mail->IsSMTP();
            
            $mail->SMTPAuth = true; // enable SMTP authentication

            $mail->Username = "passwordreset@pbsclp.info";
            $mail->Password = $e_pass;

            $mail->SMTPSecure = "ssl";
            
            $mail->Host = "mail.pbsclp.info"; // sets pbsclp mail server as the SMTP server
            $mail->Port = "465"; // set the SMTP port for the pbsclp server

            $mail->From='passwordreset@pbsclp.info';
            $mail->FromName='PBSCLP Password Reset System';

            $mail->AddAddress($email, $name);
            $mail->Subject  =  'Reset Password';
            $mail->IsHTML(true);

            $mail->Body    = '<h1> Password Reset Email </h1> <br><br>Click On This Link to reset your password: ' . $link . '';

            $mail->AltBody = 'Click On This Link to Reset Password https://pbsclp.info/pages/change_password.php?key='.$email.'&token='.$token.'';

            if($mail->Send())
            {
                exit("*email_sent_successfully*");
            }
            else
            {
                echo "Mail Error - >".$mail->ErrorInfo;
            }
            
        }else{
            exit("*no_email_address_found*");
        }

        

        // close connection to db
        $stmt = null;
        $connectionPDO = null;
    }
?>