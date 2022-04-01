<?php
    function get_file_path($fid) {
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
        $stmt->execute([$fid]);
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

                                $file_path = $course_dir_name . "/" . $session_dir_name . "/" . $filename;

                                return($file_path);
                            }
                        } else {
                            exit("*warning_error_opening_file*");
                        }
                    }
                } else {
                    exit("*warning_error_opening_file*");
                }
            }
        } else {
            exit("*warning_error_opening_file*");
        }

        // close connection to db
        $stmt = null;
        $connectionPDO = null;
    }

    session_start();

    $file_id = $_GET['file_idPHP'];

    $file_path = get_file_path($file_id);

    $filename = array_pop(explode('/', $file_path));
    $file_extension = array_pop(explode('.', $filename));

    $directory = "../../resource_bank/";

    if ($file_id === null || !file_exists($directory.$file_path)) {
        exit("*warning_error_opening_file*");
    }

    if (!isset($_SESSION['logged_in'])) {
        exit("*not_authorised_to_view_content*");
    }

    switch($file_extension) {
        case ".pdf":
            $content_type = "application/pdf";
            break;
        case ".txt":
            $content_type = "text/plain";
            break;
        case ".doc":
            $content_type = "application/msword";
            break;
        case ".docx":
            $content_type = "application/vnd.openxmlformats-officedocument.wordprocessingml.document";
            break;
        case ".jpg":
            $content_type = "image/jpeg";
            break;
        case ".jpeg":
            $content_type = "image/jpeg";
            break;
        case ".png":
            $content_type = "image/png";
            break;
        case ".gif":
            $content_type = "image/gif";
            break;
        case ".svg":
            $content_type = "image/svg+xml";
            break;
        

    }

    header("Content-Type: " . $content_type);
    header('Content-Disposition: inline; filename="' . $file_path . '"');

    @readfile($directory . $file_path);

    exit;
?>