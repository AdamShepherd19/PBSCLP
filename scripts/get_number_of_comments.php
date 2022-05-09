<?php
    // ============================================
    //     - PBSCLP | get_comments
    //     - Adam Shepherd
    //     - PBSCLP
    //     - April 2022

    //     This script queries and returns the count
    //     of all comments for a specific forum post
    // ============================================
    
    $thread_id = $_GET['thread_idPHP'];

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
    $sql = "SELECT COUNT(*) FROM comments WHERE thread_id=?";
    $stmt = $connectionPDO->prepare($sql);
    $stmt->execute([$thread_id]);
    $result = $stmt->fetch();


    if ($result){
        exit($result[0]);
    } else {
        exit("no_comments_found*");
    }


    // close connection to db
    $stmt = null;
    $connectionPDO = null;
?>