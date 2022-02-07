<?php
    session_start();

    $pass = file_get_contents('../../pass.txt', true);
    
    if(isset($_POST['firstnamePHP'])) {
        //connect to database
        try {
            $connectionPDO = new PDO('mysql:host=localhost;dbname=pbsclp_pbsclp', 'pbsclp', $pass);
            $connectionPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            exit('*database_connection_error*');
        }

        //retrieve title, content and author for the new post
        $firstname = $_POST['firstnamePHP'];
        $lastname = $_POST['lastnamePHP'];
        $email = $_POST['emailPHP'];
        $contact_number = $_POST['contact_numberPHP'];
        $organisation = $_POST['organisationPHP'];

        // query database and insert the new announcement into the announcements table
        $sql = "UPDATE users SET firstname=:firstname, lastname=:lastname, email=:email, organisation=:organisation, contact_number=:contact_number WHERE user_id=:user_id";
        $stmt = $connectionPDO->prepare($sql);
        
        //check to see if the insert was successful
        if ($stmt->execute(['firstname' => $firstname, 'lastname' => $lastname, 'email' => $email, 'organisation' => $organisation, 'contact_number' => $contact_number, 'user_id' => $user_id])) {
            exit('success');
        } else {
            exit('Error: ' . $connection->error);
        }

        //close connection to db
        $stmt = null;
        $connectionPDO = null;
    }

?>