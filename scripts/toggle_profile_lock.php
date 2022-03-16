<?php
    if(isset($_POST['user_idPHP'])) {
        
        $pass = file_get_contents('../../pass.txt', true);

        //connect to database
        try {
            $connectionPDO = new PDO('mysql:host=localhost;dbname=pbsclp_pbsclp', 'pbsclp', $pass);
            $connectionPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            exit('*database_connection_error*');
        }

        //retrieve title, content and author for the new post
        $user_id = $_POST['user_idPHP'];

        // query database and insert the new announcement into the announcements table
        $sql = "SELECT admin_locked FROM users WHERE user_id=?;";
        $stmt = $connectionPDO->prepare($sql);
        $stmt->execute([$user_id]);
        $result = $stmt->fetchAll();
        
        if ($result) {
            foreach($result as $row){
                if ($row['admin_locked'] == 1){
                    $sql = "UPDATE users SET admin_locked=0 WHERE user_id=?;";
                    $stmt = $connectionPDO->prepare($sql);
                    try {
                        $stmt->execute([$user_id]);
                        exit("*account_successfully_unlocked*");
                    } catch (Exception $e) {
                        exit('Caught exception: ',  $e->getMessage(), "\n");
                    }
                } else if ($row['admin_locked'] == 0){
                    $sql = "UPDATE users SET admin_locked=1 WHERE user_id=?;";
                    $stmt = $connectionPDO->prepare($sql);
                    try {
                        $stmt->execute([$user_id]);
                        exit("*account_successfully_locked*");
                    } catch (Exception $e) {
                        exit('Caught exception: ',  $e->getMessage(), "\n");
                    }
                }
            }
        } else {
            exit("*error_processing_request*");
        }

        $stmt = null;
        $connectionPDO = null;
    }
?>