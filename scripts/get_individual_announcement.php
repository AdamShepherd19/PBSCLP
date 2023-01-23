<?php
    // ============================================
    //     - PBSCLP | get_individual_announcement
    //     - Adam Shepherd
    //     - PBSCLP
    //     - January 2023

    //     This script queries and returns data for
    //     a specific announcement by ID
    // ============================================

    try{
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
        $sql = "SELECT * FROM `announcements` WHERE announcement_id=? ORDER BY announcement_id DESC";
        $stmt = $connectionPDO->prepare($sql);
        $stmt->execute([$_GET['announcement_idPHP']]);
        $result = $stmt->fetchAll();

        if ($result){
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
    } catch(PDOException $e) {
        exit('*query_error* - ' . $e);
    }
    

?>