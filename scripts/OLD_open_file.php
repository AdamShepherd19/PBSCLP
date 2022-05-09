<?php
    // ============================================
    //     - PBSCLP | old_open_file
    //     - Adam Shepherd
    //     - PBSCLP
    //     - April 2022

    //     This script is an old version of the open
    //     file code that was not secure
    // ============================================


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
    $sql = "SELECT course_id, session_id, filename FROM files WHERE file_id=? LIMIT 1";
    $stmt = $connectionPDO->prepare($sql);
    $stmt->execute([$_POST['file_idPHP']]);
    $result = $stmt->fetchAll();
    
    if ($result){
        // output data of each row
        foreach($result as $row) {
            $course_id = $row['course_id'];
            $session_id = $row['session_id'];
            $filename = $row['filename'];

            //perform query and sort into newest first
            $sql = "SELECT directory_name FROM sessions WHERE session_id=? AND course_id=? LIMIT 1";
            $stmt = $connectionPDO->prepare($sql);
            $stmt->execute([$session_id, $course_id]);
            $result = $stmt->fetchAll();

            if ($result){
                // output data of each row
                foreach($result as $row) {
                    $session_dir_name = $row['directory_name'];

                    //perform query and sort into newest first
                    $sql = "SELECT directory_name FROM courses WHERE course_id=? LIMIT 1";
                    $stmt = $connectionPDO->prepare($sql);
                    $stmt->execute([$course_id]);
                    $result = $stmt->fetchAll();

                    if ($result){
                        // output data of each row
                        foreach($result as $row) {
                            $course_dir_name = $row['directory_name'];

                            $file_path = "../resource_bank/" . $course_dir_name . "/" . $session_dir_name . "/" . $filename;

                            exit($file_path);
                        }
                    } else {
                        echo "*warning_error_opening_file*";
                    }
                }
            } else {
                echo "*warning_error_opening_file*";
            }
        }
    } else {
        echo "*warning_error_opening_file*";
    }

    // close connection to db
    $stmt = null;
    $connectionPDO = null;

?>