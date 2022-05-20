<?php
    // ============================================
    //     - PBSCLP | create_password
    //     - Adam Shepherd
    //     - PBSCLP
    //     - April 2022

    //     This script handles the initial stages
    //     of resetting a user password and sends
    //     them an email with a link with a unique
    //     key in order to reset their password with
    //     a 20 minute timer
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

    if(isset($_POST['emailPHP'])) {

        $pass = file_get_contents('../../pass.txt', true);
        $e_pass = file_get_contents('../../p-e-pass.txt', true);

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
            $expFormat = mktime(date("H"), date("i"), date("s"), date("m") ,date("d")+2, date("Y"));
            $expDate = date("Y-m-d H:i:s",$expFormat);

            $sql = "UPDATE users set reset_link_token=:token, exp_date=:expDate WHERE email=:email";
            $stmt = $connectionPDO->prepare($sql);
            
            //check to see if the insert was successful
            if ($stmt->execute(['token' => $token, 'expDate' => $expDate, 'email' => $email])) {
                echo 'success';
            } else {
                exit("*failed_to_create_token*");
            }
            
            $link = "<a href='https://pbsclp.info/pages/change_password.php?key=".$email."&token=".$token."'>Click To Set Password</a>";

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
            $mail->Subject  =  'Set Password';
            $mail->IsHTML(true);


            $message = file_get_contents('../email_templates/create_password_email_template.html');
            $message = str_replace('%link%', $link, $message);
            $mail->MsgHTML($message);

            $mail->AltBody = 'Click on this link to choose the password for your new account https://pbsclp.info/pages/change_password.php?key='.$email.'&token='.$token.'';

            $mail->AddEmbeddedImage('../images/pbslogo.png', 'pbslogo');

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