<?php
    if(isset($_GET['threadIDPHP'])){
        $thread_id = $_GET['threadIDPHP'];

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
        $sql = "SELECT current_feedback FROM threads WHERE thread_id=? LIMIT 1";
        $stmt = $connectionPDO->prepare($sql);
        $stmt->execute([$thread_id]);
        $result = $stmt->fetch();

        if ($result){
            exit($result['current_feedback']);
        } else {
            exit("*warning_no_feedback_found*");
        }

        // close connection to db
        $stmt = null;
        $connectionPDO = null;
    }
?>