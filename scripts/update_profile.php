<?php
    session_start();

    $pass = file_get_contents('../../pass.txt', true);
    
    if(isset($_POST['namePHP'])) {
        //connect to database
        $connection = new mysqli('localhost', 'pbsclp', $pass, 'pbsclp_pbsclp');

        //check db connection
        if ($connection->connect_error) {
            exit("Connection failed: " . $connection->connect_error);
        }

        //retrieve title, content and author for the new post
        $firstname = $_POST['firstnamePHP'];
        $lastname = $_POST['lastnamePHP'];
        $email = $_POST['emailPHP'];
        $contact_number = $_POST['contact_numberPHP'];
        $organisation = $_POST['organisationPHP'];

        // query database and insert the new announcement into the announcements table
        $query = "UPDATE users SET firstname='" . $firstname. "', lastname='" . $lastname . "', email='" . $email . "', organisation='" . $organisation . "', contact_number='" . $contact_number . "' WHERE user_id='" . $_SESSION['user_id'] . "'";

        
        //check to see if the insert was successful
        // if ($connection->query($query) === TRUE) {
        //     exit('success');
        // } else {
        //     exit('Error: ' . $connection->error);
        // }

        exit('success');

        $connection->close();
    }

?>