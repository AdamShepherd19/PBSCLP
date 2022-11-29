<?php
    // ============================================
    //     - PBSCLP | get_announcements
    //     - Adam Shepherd
    //     - PBSCLP
    //     - April 2022

    //     This script queries and returns all the
    //     announcements available
    // ============================================

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
    $sql = "SELECT * FROM `announcements` ORDER BY announcement_id DESC";
    $stmt = $connectionPDO->prepare($sql);
    $stmt->execute();
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
            $id = $row['announcement_id'];
            $title = $row['title'];
            $content = $row['content'];
            $firstname = $names[0]['firstname'];
            $lastname = $names[0]['lastname'];
            $link = $row['link'];
                
            //add data into array
            $data[] = array(
                "id" => $id,
                "title" => $title,
                "content" => $content,
                "firstname" => $firstname,
                "lastname" => $lastname,
                "link" => $link
            );
        }
        

        //encode the array into jason
        echo json_encode($data);
    } else {
        echo json_encode("*warning_no_announcements_found*");
    }

    // close connection to db
    $stmt = null;
    $connectionPDO = null;

?>