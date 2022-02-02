<?php
    // https://makitweb.com/return-json-response-ajax-using-jquery-php
    $pass = file_get_contents('../../pass.txt', true);

    //connect to database
    $connection = new mysqli('localhost', 'pbsclp', $pass, 'pbsclp_pbsclp');

    //check db connection
    if ($connection->connect_error) {
        exit("Connection failed: " . $connection->connect_error);
    }

    //perform query and sort into newest first
    $query = "SELECT firstname, lastname, email, contact_number, organisation FROM `users` WHERE user_id != " . $_SESSION['user_id'] . "ORDER BY firstname ASC";
    $result = $connection->query($query);

    //check that there were announcements to show
    if ($result->num_rows > 0) {

        //initialise array
        $data = array();

        // output data of each row
        while($row = $result->fetch_assoc()) {
            //retrieve data from query
            $firstname = $row['firstname'];
            $lastname = $row['lastname'];
            $email = $row['email'];
            $contact_number = $row['contact_number'];
            $organisation = $row['organisation'];
            
            //add data into array
            $data[] = array(
                "firstname" => $firstname,
                "lastname" => $lastname,
                "email" => $email,
                "contact_number" => $contact_number,
                "organisation" => $organisation
            );
        }

        //encode the array into jason
        echo json_encode($data);

    } else {
        echo json_encode("*warning_no_users_found*");
    }

    $connection->close();

?>