<?php
    // ============================================
    //     - PBSCLP | update_forum_post
    //     - Adam Shepherd
    //     - PBSCLP
    //     - April 2022

    //     This script updates a forum post in the
    //     database after it has been amended by the
    //     author after receiving admin feedback
    // ============================================


    session_start();
    include_once "../scripts/post_amended_email.php";

    if(!isset($_SESSION['logged_in'])){
        header('Location: https://pbsclp.info/');
        exit();
    }

    $pass = file_get_contents('../../pass.txt', true);
    
    if(isset($_POST['new_titlePHP'])) {
        //connect to database
        try {
            $connectionPDO = new PDO('mysql:host=localhost;dbname=pbsclp_pbsclp', 'pbsclp', $pass);
            $connectionPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            exit('*database_connection_error*');
        }

        //retrieve title, content and author for the new post
        $new_title = $_POST['new_titlePHP'];
        $new_content = $_POST['new_contentPHP'];
        $thread_id = $_POST['thread_idPHP'];

        // query database and insert the new announcement into the announcements table
        $sql = "UPDATE threads SET title=:title, content=:content, feedback_provided=0, current_feedback=NULL WHERE thread_id=:thread_id;";
        $stmt = $connectionPDO->prepare($sql);
        
        //check to see if the insert was successful
        if ($stmt->execute(['title' => $new_title, 'content' => $new_content, 'thread_id' => $thread_id])) {
            try{
                if(sendEmail($new_title, $new_content) == "*email_sent_successfully*") {
                    exit('*post_updated_successfully*');
                } else {
                    exit('*error_sending_email*');
                }
            } catch (Exception $e) {
                echo 'Caught exception: ',  $e->getMessage(), "\n";
            }
        } else {
            exit('Error: ' . $connectionPDO->error);
        }

        $stmt = null;
        $connectionPDO = null;

    }

?>