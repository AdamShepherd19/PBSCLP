<?php
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
        $course_name = $_POST['course_namePHP'];

        echo $course_name;

        // query database and insert the new announcement into the announcements table
        // $sql = "INSERT INTO comments (content, thread_id, user_id) values (:comment, :thread_id, :user_id)";
        // $stmt = $connectionPDO->prepare($sql);

        // try {
        //     $stmt->execute(['comment' => $comment, 'thread_id' => $thread_id, 'user_id' => $_SESSION['user_id']]);
        //     echo '*comment_created_succesfully*';
        // } catch (Exception $e) {
        //     echo 'Caught exception: ',  $e->getMessage(), "\n";
        // }

        // $stmt = null;
        $connectionPDO = null;
    }
?>