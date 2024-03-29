<?php
    // ============================================
    //     - PBSCLP | upload_file
    //     - Adam Shepherd
    //     - PBSCLP
    //     - April 2022

    //     This script uploads a new file to the
    //     file server within the session specified
    //     and holds a record in the database of
    //     what the filename is and which session it
    //     belongs to
    // ============================================


    session_start();
    if(isset($_POST['filename'])) {
        
        $pass = file_get_contents('../../pass.txt', true);

        // connect to database
        try {
            $connectionPDO = new PDO('mysql:host=localhost;dbname=pbsclp_pbsclp', 'pbsclp', $pass);
            $connectionPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            exit('*database_connection_error*');
        }

        /* Getting file name */
        $session_id = $_POST['session_id'];
        $existing_filename = $_FILES['file']['name'];
        $new_file_name = $_POST['filename'];
        // convert spaces in filename to underscore
        $new_file_name = strtolower(str_replace(' ', '_', $new_file_name));
        
        /* Extension */
        $file_extension = pathinfo($existing_filename, PATHINFO_EXTENSION);
        $file_extension = strtolower($file_extension);
        $new_file_name = $new_file_name . "." . $file_extension;

        $sql = "SELECT course_id, directory_name FROM sessions WHERE session_id=? LIMIT 1";
        $stmt = $connectionPDO->prepare($sql);
        $stmt->execute([$session_id]);
        $result = $stmt->fetchAll();

        if ($result){
            // output data of each row
            foreach($result as $row) {
                $session_directory_name = $row['directory_name'];
                $course_id = $row['course_id'];
            }
        } else {
            exit("*warning_no_session_found*");
        }

        $sql = "SELECT directory_name FROM courses WHERE course_id=? LIMIT 1";
        $stmt = $connectionPDO->prepare($sql);
        $stmt->execute([$course_id]);
        $result = $stmt->fetchAll();

        if ($result){
            // output data of each row
            foreach($result as $row) {
                $course_directory_name = $row['directory_name'];
            }
        } else {
            exit("*warning_no_course_found*");
        }

        // Location
        $location = "../../resource_bank/" . $course_directory_name . "/" . $session_directory_name . "/";
        
        if(file_exists($location . $new_file_name)) {
            exit('*warning_file_already_exists*');
        }

        /* Valid extensions */
        $valid_extensions = array("jpg","jpeg","png", "gif", "svg", "pdf", "doc", "docx", "txt", "ppt", "pptx");
        
        /* Check file extension */
        if(in_array(strtolower($file_extension), $valid_extensions)) {
            /* Upload file */
            if(move_uploaded_file($_FILES['file']['tmp_name'],$location.$new_file_name)){
                echo "*file_uploaded_successfully*";
            } else {
                exit("*file_upload_failed*");
            }
        } else {
            exit("*filetype_not_supported*");
        }

        $sql = "INSERT INTO files (filename, session_id, course_id ) values (:filename, :session_id, :course_id)";
        $stmt = $connectionPDO->prepare($sql);

        try {
            $stmt->execute(['filename' => $new_file_name, 'session_id' => $session_id, 'course_id' => $course_id]);
            exit('*file_uploaded_successfully*');
        } catch (Exception $e) {
            unlink($location . $new_file_name);
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }

        $stmt = null;
        $connectionPDO = null;
    }
?>