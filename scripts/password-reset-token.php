<?php

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    

    require '../includes/PHPMailer/src/Exception.php';
    require '../includes/PHPMailer/src/PHPMailer.php';
    require '../includes/PHPMailer/src/SMTP.php';

    if(isset($_POST['emailPHP'])) {

        $pass = file_get_contents('../../pass.txt', true);

        //connect to database
        try {
            $connectionPDO = new PDO('mysql:host=localhost;dbname=pbsclp_pbsclp', 'pbsclp', $pass);
            $connectionPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            exit('*database_connection_error*');
        }

        $email = $_POST['emailPHP'];

        $sql = "SELECT user_id FROM users WHERE email=?";
        $stmt = $connectionPDO->prepare($sql);
        $stmt->execute([$email]);
        $data = $stmt->fetch();
        
        if($data)
        {
            $token = md5($email).rand(10,9999);
            $expFormat = mktime(date("H"), date("i"), date("s"), date("m") ,date("d")+1, date("Y"));
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
            // enable SMTP authentication
            $mail->SMTPAuth = true;                  
            // GMAIL username
            $mail->Username = "passwordreset@pbsclp.info";
            // GMAIL password
            $mail->Password = "T!nOCQ3@sg!a";
            $mail->SMTPSecure = "ssl";  
            // sets GMAIL as the SMTP server
            $mail->Host = "mail.pbsclp.info";
            // set the SMTP port for the GMAIL server
            $mail->Port = "465";
            $mail->From='passwordreset@pbsclp.info';
            $mail->FromName='Adam';
            $mail->AddAddress('passwordreset@pbsclp.info', 'receiver');
            $mail->Subject  =  'Reset Password';
            $mail->IsHTML(true);
            // $mail->Body    = 'Click On This Link to Reset Password '.$link.'';
            $mail->Body    = 'This is an email';
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