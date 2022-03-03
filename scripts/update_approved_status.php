<?php
    if(isset($_POST['threadIDPHP'])) {
        
        $pass = file_get_contents('../../pass.txt', true);

        //connect to database
        try {
            $connectionPDO = new PDO('mysql:host=localhost;dbname=pbsclp_pbsclp', 'pbsclp', $pass);
            $connectionPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            exit('*database_connection_error*');
        }

        //retrieve title, content and author for the new post
        $threadID = $_POST['threadIDPHP'];

        // query database and insert the new announcement into the announcements table
        $sql = "UPDATE threads SET approved='1' WHERE thread_id=?;";
        $stmt = $connectionPDO->prepare($sql);
        
        //check to see if the insert was successful
        if ($stmt->execute(['threadID' => $threadID])) {
            echo '*post_approved_succesfully*';
        } else {
            echo 'Error: ' . $connectionPDO->error;
        }

        $stmt = null;
        $connectionPDO = null;
    }
?>