<?php
    session_start();

    if(!isset($_SESSION['logged_in'])){
        header('Location: https://pbsclp.info');
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
        $user_id = $_SESSION['user_id'];

        // query database and insert the new announcement into the announcements table
        $sql = "UPDATE threads (title, content, feedback_provided, current_feedback) VALUES (:title, :content, 0, NULL) WHERE user_id=:user_id;";
        $stmt = $connectionPDO->prepare($sql);
        
        //check to see if the insert was successful
        if ($stmt->execute(['title' => $title, 'content' => $content, 'user_id' => $user_id])) {
            exit('*post_updated_successfully*');
        } else {
            exit('Error: ' . $connectionPDO->error);
        }

        $stmt = null;
        $connectionPDO = null;

    }

?>