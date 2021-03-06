<?php
    // ============================================
    //     - PBSCLP | new_comment
    //     - Adam Shepherd
    //     - PBSCLP
    //     - April 2022

    //     This script inserts a new comment to
    //     the database
    // ============================================

    session_start();
    
    if(isset($_POST['commentPHP'])) {
        
        $pass = file_get_contents('../../pass.txt', true);

        //connect to database
        try {
            $connectionPDO = new PDO('mysql:host=localhost;dbname=pbsclp_pbsclp', 'pbsclp', $pass);
            $connectionPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            exit('*database_connection_error*');
        }

        //retrieve title, content and author for the new post
        $thread_id = $_POST['threadIDPHP'];
        $comment = $_POST['commentPHP'];

        // query database and insert the new announcement into the announcements table
        $sql = "INSERT INTO comments (content, thread_id, user_id) values (:comment, :thread_id, :user_id)";
        $stmt = $connectionPDO->prepare($sql);
        
        //check to see if the insert was successful
        // if ($stmt->execute(['threadID' => $threadID])) {
        //     echo '*post_approved_succesfully*';
        // } else {
        //     echo 'Error: ' . $connectionPDO->error;
        // }

        try {
            $stmt->execute(['comment' => $comment, 'thread_id' => $thread_id, 'user_id' => $_SESSION['user_id']]);
            echo '*comment_created_succesfully*';
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }

        $stmt = null;
        $connectionPDO = null;
    }
?>