<?php
    session_start();
    
    if(isset($_POST['course_namePHP'])) {
        
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

        // echo $course_name;

        $directory_name = str_replace(' ', '_', $course_name);
        echo $directory_name;
        if(file_exists('../../resource_bank/' . $directory_name)) {
            exit('*warning_course_already_exists*');
        }

        //generate directory name
        //create directory in resource bank
        //add new course to database
        

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