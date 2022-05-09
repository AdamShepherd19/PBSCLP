<?php
    // ============================================
    //     - PBSCLP | upload_video
    //     - Adam Shepherd
    //     - PBSCLP
    //     - April 2022

    //     This script uploads a new video name and
    //     youtube url to the database
    // ============================================


    session_start();
    if(isset($_POST['video_namePHP'])) {
        
        $pass = file_get_contents('../../pass.txt', true);

        // connect to database
        try {
            $connectionPDO = new PDO('mysql:host=localhost;dbname=pbsclp_pbsclp', 'pbsclp', $pass);
            $connectionPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            exit('*database_connection_error*');
        }

        /* Getting file name */
        $session_id = $_POST['session_idPHP'];
        $video_name = $_POST['video_namePHP'];
        $video_link = $_POST['video_linkPHP'];

        $sql = "SELECT course_id FROM sessions WHERE session_id=? LIMIT 1";
        $stmt = $connectionPDO->prepare($sql);
        $stmt->execute([$session_id]);
        $result = $stmt->fetchAll();

        if ($result){
            // output data of each row
            foreach($result as $row) {
                $course_id = $row['course_id'];
            }
        } else {
            exit("*warning_no_session_found*");
        }

        $sql = "INSERT INTO videos (session_id, course_id, name, link) values (:session_id, :course_id, :name, :link)";
        $stmt = $connectionPDO->prepare($sql);

        try {
            $stmt->execute(['session_id' => $session_id, 'course_id' => $course_id, 'name' => $video_name, 'link' => $video_link]);
            exit('*video_uploaded_successfully*');
        } catch (Exception $e) {
            unlink($location . $new_file_name);
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }

        $stmt = null;
        $connectionPDO = null;
    }
?>