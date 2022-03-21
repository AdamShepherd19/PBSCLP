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

    //perform query and sort into newest first
    $sql = "SELECT firstname, lastname, email, organisation, contact_number FROM users WHERE user_id=?";
    $stmt = $connectionPDO->prepare($sql);

    if(isset($_POST['user_id_PHP'])) {
        $stmt->execute([$_POST['user_id_PHP']]);
    } else {
        $stmt->execute([$_SESSION['user_id']]);
    }
    
    $result = $stmt->fetch();

    
    if ($result) {
        //initialise array
        $data = array();

        //retrieve data from query
        $name = $result['firstname'] . " " . $result['lastname'];;
        $email = $result['email'];
        $organisation = $result['organisation'];
        $contact_number = $result['contact_number'];
        
        //add data into array
        $data[] = array(
            "name" => $name,
            "email" => $email,
            "organisation" => $organisation,
            "contact_number" => $contact_number
        );

        //encode the array into jason
        echo json_encode($data);

    } else {
        echo json_encode("*warning_no_user_found*");
    }

    // close connection to db
    $stmt = null;
    $connectionPDO = null;

?>