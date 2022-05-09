<?php
    // ============================================
    //     - PBSCLP | get_feedback
    //     - Adam Shepherd
    //     - PBSCLP
    //     - April 2022

    //     This script queries and returns the
    //     feedback provided by an admin for a 
    //     specific forum post
    // ============================================
    if(isset($_GET['threadIDPHP'])){
        $thread_id = $_GET['threadIDPHP'];

        // https://makitweb.com/return-json-response-ajax-using-jquery-php
        $pass = file_get_contents('../../pass.txt', true);

        //connect to database
        try {
            $connectionPDO = new PDO('mysql:host=localhost;dbname=pbsclp_pbsclp', 'pbsclp', $pass);
            $connectionPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            exit('*database_connection_error*');
        }

        //perform query and sort into newest first
        $sql = "SELECT current_feedback FROM threads WHERE thread_id=? LIMIT 1";
        $stmt = $connectionPDO->prepare($sql);
        $stmt->execute([$thread_id]);
        $result = $stmt->fetch();

        if ($result == ""){
            exit("*warning_no_feedback_found*");
        } else {
            exit($result['current_feedback']);
        }

        // close connection to db
        $stmt = null;
        $connectionPDO = null;
    }
?>