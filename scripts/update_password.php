<?php
    session_start();

    // if already logged in
    if (isset($_SESSION['logged_in'])){
        header('Location: https://pbsclp.info');
        exit();
    }

    if(isset($_POST['new_passwordPHP'])){
        $pass = file_get_contents('../../pass.txt', true);

        //connect to database
        try {
            $connectionPDO = new PDO('mysql:host=localhost;dbname=pbsclp_pbsclp', 'pbsclp', $pass);
            $connectionPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            exit('*database_connection_error*');
        }

        $new_password = $_POST['new_passwordPHP'];
        $email = $_POST['emailPHP'];

        $new_password_enc = password_hash($new_password, PASSWORD_DEFAULT);

        // query database and insert the new announcement into the announcements table
        $sql = "UPDATE users SET password=:new_pass_enc, reset_link_token=NULL, exp_date=NULL, password_attempts=0, password_locked=0 WHERE email=:email";
        $stmt = $connectionPDO->prepare($sql);
        
        //check to see if the insert was successful
        if ($stmt->execute(['new_pass_enc' => $new_password_enc, 'email' => $email])) {
            exit('*password_updated_successfully*');
        } else {
            exit('Error: ' . $connection->error);
        }

        // close connection to db
        $stmt = null;
        $connectionPDO = null;
    }
?>