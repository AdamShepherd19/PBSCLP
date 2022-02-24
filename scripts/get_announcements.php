<?php
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
            $stmt = null;
            $sql = "SELECT firstname, lastname FROM users WHERE user_id=?";
            $stmt = $connectionPDO->prepare($sql);
            $stmt->execute([$row['user_id']]);
            $names = $stmt->fetchAll();

            //retrieve data from query
            $id = $row['announcement_id'];
            $title = $row['title'];
            $content = $row['content'];
            $firstname = $names['firstname'];
            $lastname = $names['lastname'];
                
            //add data into array
            $data[] = array(
                "id" => $id,
                "title" => $title,
                "content" => $content,
                "firstname" => $firstname,
                "lastname" => $lastname,
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