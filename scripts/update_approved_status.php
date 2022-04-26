<?php
    // ============================================
    //     - PBSCLP | update_approved_status
    //     - Adam Shepherd
    //     - PBSCLP
    //     - April 2022

    //     This script updates the status of a forum
    //     post to say it has been approved by an admin
    // ============================================

    include_once "../scripts/post_approved_email.php";

    if(isset($_POST['threadIDPHP'])) {
        
        $pass = file_get_contents('../../pass.txt', true);

        //connect to database
        try {
            $connectionPDO = new PDO('mysql:host=localhost;dbname=pbsclp_pbsclp', 'pbsclp', $pass);
            $connectionPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            exit('*database_connection_error*');
        }

        //retrieve title, content and author for the new post
        $threadID = $_POST['threadIDPHP'];

        // query database and insert the new announcement into the announcements table
        $sql = "UPDATE threads SET approved=1 WHERE thread_id=?;";
        $stmt = $connectionPDO->prepare($sql);

        

        try {
            $stmt->execute([$threadID]);
            
            $sql = "SELECT firstname, lastname, email FROM users WHERE user_id IN (SELECT user_id FROM threads WHERE thread_id=?);";
            $stmt = $connectionPDO->prepare($sql);
            $stmt->execute([$threadID]);
            $result = $stmt->fetch();

            if ($result) {
                $email_to = $result['email'];
                $name_to = $result['firstname'] . " " . $result['lastname'];

                if(sendEmail($email_to, $name_to) == "*email_sent_successfully*") {
                    exit('*post_approved_succesfully*');
                } else {
                    exit('*error_sending_email*');
                }
            } else {
                exit("*error_fetching_user_details*");
            }

        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }

        $stmt = null;
        $connectionPDO = null;
    }
?>