<?php
    // ============================================
    //     - PBSCLP | get_user_accounts
    //     - Adam Shepherd
    //     - PBSCLP
    //     - April 2022

    //     This script queries and returns a list
    //     of all user accounts bar the signed
    //     in user
    // ============================================

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

    //perform query and sort alphabetically by first name
    $sql = "SELECT user_id, firstname, lastname, email, contact_number, organisation, admin_locked, last_login FROM `users` WHERE user_id<>? ORDER BY firstname ASC";
    
    $stmt = $connectionPDO->prepare($sql); //prepare query
    $stmt->execute([$_SESSION['user_id']]); //execute query using data provided
    $result = $stmt->fetchAll(); //fetch results from query

    //check that there were announcements to show
    if ($result) {

        //initialise array
        $data = array();

        // output data of each row
        foreach($result as $row) {
            //retrieve data from query
            $user_id = $row['user_id'];
            $firstname = $row['firstname'];
            $lastname = $row['lastname'];
            $email = $row['email'];
            $contact_number = $row['contact_number'];
            $organisation = $row['organisation'];
            $admin_locked = $row['admin_locked'];
            $last_login = $row['last_login'];
            
            //add data into array
            $data[] = array(
                "user_id" => $user_id,
                "firstname" => $firstname,
                "lastname" => $lastname,
                "email" => $email,
                "contact_number" => $contact_number,
                "organisation" => $organisation,
                "admin_locked" => $admin_locked,
                "last_login" => $last_login
            );
        }

        //encode the array into jason
        echo json_encode($data);

    } else {
        echo json_encode("*warning_no_users_found*");
    }

    // close connection to db
    $stmt = null;
    $connectionPDO = null;

?>