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
        $name = $_POST['namePHP'];
        $email = $_POST['emailPHP'];
        $contact_number = $_POST['contact_numberPHP'];
        $organisation = $_POST['organisationPHP'];


        // $pieces = preg_split(' ', $name);
        // $firstname = $pieces[0];
        // $lastname = $pieces[1];
        // print_r($pieces);

        // query database and insert the new announcement into the announcements table
        // $query = "UPDATE users SET firstname='" . $firstname. "', lastname='" . $lastname . "', email='" . $email . "', organisation='" . $organisation . "', contact_number='" . $contact_number . "' WHERE user_id='" . $_SESSION['user_id'] . "'";
        $query = "UPDATE users SET firstname='jeff' WHERE user_id='" . $_SESSION['user_id'] . "'";
        
        //check to see if the insert was successful
        if ($connection->query($query) === TRUE) {
            exit('success');
        } else {
            exit('Error: ' . $connection->error);
        }

        $connection->close();
    }

?>