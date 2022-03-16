<?php
    session_start();

    $pass = file_get_contents('../../pass.txt', true);
    
    if(isset($_POST['userIDPHP'])) {
        //connect to database
        try {
            $connectionPDO = new PDO('mysql:host=localhost;dbname=pbsclp_pbsclp', 'pbsclp', $pass);
            $connectionPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            exit('*database_connection_error*');
        }

        // query database and insert the new announcement into the announcements table
        $sql = "DELETE FROM users WHERE user_id=?";
        $stmt = $connectionPDO->prepare($sql);
        
        //check to see if the insert was successful
        if ($stmt->execute([$_POST['userIDPHP']]) > 0) {
            exit('*account_removed_succesfully*');
        } else {
            exit('Error: ' . $connection->error);
        }

        //close connection to db
        $stmt = null;
        $connectionPDO = null;
    }

?>