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

    $file_id = $_POST['file_idPHP'];

    $file_path = get_file_path($file_id);

    $proxiedDirectory = "../../resource_bank/"; //Whatever the directory you blocked access to is.

    // exit($proxiedDirectory . $file_path);

    // $filename = isset($_GET["fn"])?$_GET["fn"]:null;

    if ($file_id === null || !file_exists($proxiedDirectory.$file_path)) {
        // http_response_code(404);
        exit("*warning_error_opening_file*");
    }

    if (!isset($_SESSION['logged_in'])) { //Not a real method, use your own check
        // http_response_code(403);
        exit("*not_authorised_to_view_content*");
    }

    // header("Location:" . $proxiedDirectory . $file_path);

    $fp = fopen($proxiedDirectory.$file_path, 'rb');

    header("Content-Type: application/pdf"); //May need to determine mime type somehow
    // header("Content-Length: " . filesize($proxiedDirectory.$file_path));
    header('Content-Disposition: inline; filename="' . $proxiedDirectory.$file_path . '"');  
    // header($proxiedDirectory.$file_path);
    @readfile($proxiedDirectory . $file_path);
    // fpassthru($fp);
    exit;
?>