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
    $sql = "SELECT * FROM sessions WHERE course_id=? ORDER BY session_id ASC";
    $stmt = $connectionPDO->prepare($sql);
    $stmt->execute([$_POST['course_idPHP']]);
    $result = $stmt->fetchAll();
    
    if ($result){
        //initialise array
        $data = array();

        // output data of each row
        foreach($result as $row) {

            //retrieve data from query
            $session_id = $row['session_id'];
            $name = $row['name'];
            $description = $row['description'];
                
            //add data into array
            $data[] = array(
                "session_id" => $session_id,
                "name" => $name,
                "description" => $description,
            );
        }
        

        //encode the array into jason
        echo json_encode($data);
    } else {
        echo json_encode("*warning_no_sessions_found*");
    }

    // close connection to db
    $stmt = null;
    $connectionPDO = null;

?>