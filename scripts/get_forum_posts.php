<?php

    session_start();

    // https://makitweb.com/return-json-response-ajax-using-jquery-php
    $pass = file_get_contents('../../pass.txt', true);

    //connect to database
    try {
        $connectionPDO = new PDO('mysql:host=localhost;dbname=pbsclp_pbsclp', 'pbsclp', $pass);
        $connectionPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        exit('*database_connection_error*');
    }

    if(isset($_GET['approvedPHP']) && isset($_GET['feedback_providedPHP'])) {
        
        $approved = $_GET['approvedPHP'];
        $feedback_provided = $_GET['feedback_providedPHP'];

        $sql = "SELECT * FROM threads WHERE approved=:approved AND feedback_provided=:feedback_provided ORDER BY post_time DESC";

        $stmt = $connectionPDO->prepare($sql);
        $stmt->execute(['approved' => $approved, 'feedback_provided' => $feedback_provided]);
        $result = $stmt->fetchAll();

    } else {
        
        if (isset($_GET['approvedPHP'])) {
            $approved = $_GET['approvedPHP'];
            $sql = "SELECT * FROM threads WHERE approved=? ORDER BY post_time DESC";
        } else {
            $approved = '0';
            $sql = "SELECT * FROM threads WHERE approved=? ORDER BY post_time DESC";
        }

        $stmt = $connectionPDO->prepare($sql);
        $stmt->execute([$approved]);
        $result = $stmt->fetchAll();
    }

    //check that there were announcements to show
    if ($result) {

        //initialise array
        $data = array();

        // output data of each row
        foreach($result as $row) {
            $sql = "SELECT firstname, lastname FROM users WHERE user_id=?";
            $stmt = $connectionPDO->prepare($sql);
            $stmt->execute([$row['user_id']]);
            $names = $stmt->fetchAll();

            //retrieve data from query
            $thread_id = $row['thread_id'];
            $title = $row['title'];
            $content = $row['content'];
            $firstname = $names[0]['firstname'];
            $lastname = $names[0]['lastname'];
            
            //add data into array
            $data[] = array(
                "thread_id" => $thread_id,
                "title" => $title,
                "content" => $content,
                "firstname" => $firstname,
                "lastname" => $lastname
            );
        }

        //encode the array into jason
        echo json_encode($data);

    } else {
        echo json_encode("*warning_no_posts_found*");
    }

    // close connection to db
    $stmt = null;
    $connectionPDO = null;

?>