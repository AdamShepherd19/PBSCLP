<?php
    session_start();

    include_once "../scripts/post_feedback_email.php";
    
    if(isset($_POST['feedbackPHP'])) {
        
        $pass = file_get_contents('../../pass.txt', true);

        //connect to database
        try {
            $connectionPDO = new PDO('mysql:host=localhost;dbname=pbsclp_pbsclp', 'pbsclp', $pass);
            $connectionPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            exit('*database_connection_error*');
        }

        $thread_id = $_POST['thread_idPHP'];
        $feedback = $_POST['feedbackPHP'];

        // query database and insert the new announcement into the announcements table
        $sql = "UPDATE threads SET feedback_provided=1, current_feedback=:feedback WHERE thread_id=:thread_id;";
        $stmt = $connectionPDO->prepare($sql);
        

        try {
            $stmt->execute(['feedback' => $feedback, 'thread_id' => $thread_id]);

            $sql = "SELECT firstname, lastname, email FROM users WHERE user_id IN (SELECT user_id FROM threads WHERE thread_id=?);";
            $stmt = $connectionPDO->prepare($sql);
            $stmt->execute([$thread_id]);
            $result = $stmt->fetch();

            if ($result) {
                $email_to = $result['email'];
                $name_to = $result['firstname'] . " " . $result['lastname'];

                if(sendEmail($email_to, $name_to) == "*email_sent_successfully*") {
                    exit('*feedback_sent_successfully*');
                } else {
                    exit('*error_sending_email*');
                }
            } else {
                exit("*error_fetching_user_details*");
            }

        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }

        // sendEmail();

        // close connection to db
        $stmt = null;
        $connectionPDO = null;

    }
?>