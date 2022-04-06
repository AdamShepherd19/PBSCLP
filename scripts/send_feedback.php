<?php
    session_start();
    
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
        $sql = "ALTER threads SET feedback_provided=0, current_feedback=:feedback WHERE thread_id=:thread_id;";
        $stmt = $connectionPDO->prepare($sql);

        if($stmt->execute(['feedback' => $feedback, 'thread_id' => $thread_id]));
            echo '*feedback_sent_successfully*';
        } else {
            $error_msg = 'Caught exception: ',  $e->getMessage(), "\n";
            exit($error_msg);
        }

        // sendEmail();


    }

    // close connection to db
    $stmt = null;
    $connectionPDO = null;
?>