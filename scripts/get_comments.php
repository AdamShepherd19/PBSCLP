<?php
    // ============================================
    //     - PBSCLP | get_comments
    //     - Adam Shepherd
    //     - PBSCLP
    //     - April 2022

    //     This script queries and returns all the
    //     comments for a specific forum post
    // ============================================
    
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
    $sql = "SELECT * FROM comments WHERE thread_id=? ORDER BY date DESC";
    $stmt = $connectionPDO->prepare($sql);
    $stmt->execute([$thread_id]);
    $result = $stmt->fetchAll();


    if ($result){
        //initialise array
        $data = array();

        // output data of each row
        foreach($result as $row) {
            $sql = "SELECT firstname, lastname FROM users WHERE user_id=?";
            $stmt = $connectionPDO->prepare($sql);
            $stmt->execute([$row['user_id']]);
            $names = $stmt->fetchAll();

            //retrieve data from query
            $content = $row['content'];
            $firstname = $names[0]['firstname'];
            $lastname = $names[0]['lastname'];
            $date = $row['date'];

            $date = date_create($date);
            $date = date_format($date, 'd-m-Y H:i:s');
                
            //add data into array
            $data[] = array(
                "content" => $content,
                "firstname" => $firstname,
                "lastname" => $lastname,
                "date" => $date
            );
        }
        //encode the array into jason
        echo json_encode($data);
    } else {
        echo json_encode("*warning_no_comments_found*");
    }


    // close connection to db
    $stmt = null;
    $connectionPDO = null;
?>