<?php
    session_start();
    // https://makitweb.com/return-json-response-ajax-using-jquery-php
    $pass = file_get_contents('../../pass.txt', true);

    $session_id = $_POST['session_idPHP'];

    //connect to database
    try {
        $connectionPDO = new PDO('mysql:host=localhost;dbname=pbsclp_pbsclp', 'pbsclp', $pass);
        $connectionPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        exit('*database_connection_error*');
    }

    $sql = "SELECT course_id FROM sessions WHERE session_id=? LIMIT 1";
    $stmt = $connectionPDO->prepare($sql);
    $stmt->execute([$session_id]);
    $result = $stmt->fetchAll();

    if ($result){
        foreach($result as $row) {
            $course_id = $row['course_id'];
        }
    } else {
        $error_msg = json_encode("*user_not_authorised_on_this_course*");
        exit($error_msg);
    }

    //query users_on_courses for course_id's for $_SESSION['user_id']
    //apply this query to select courses query
    $sql = "SELECT course_id FROM users_on_courses WHERE user_id=:user_id AND course_id=:course_id LIMIT 1";
    $stmt = $connectionPDO->prepare($sql);
    $stmt->execute(["user_id" => $_SESSION['user_id'], "course_id" => $course_id]);
    $result = $stmt->fetchAll();

    if (!$result){
        $error_msg = json_encode("*user_not_authorised_on_this_course*");
        exit($error_msg);
    }

    //perform query and sort into newest first
    $sql = "SELECT video_id, name, link FROM videos WHERE session_id=? ORDER BY video_id ASC";
    $stmt = $connectionPDO->prepare($sql);
    $stmt->execute([$session_id]);
    $result = $stmt->fetchAll();
    
    if ($result){
        //initialise array
        $data = array();

        // output data of each row
        foreach($result as $row) {

            //retrieve data from query
            $video_id = $row['video_id'];
            $video_name = $row['name'];
            $video_link = $row['link'];
                
            //add data into array
            $data[] = array(
                "video_id" => $video_id,
                "video_name" => $video_name,
                "link" => $video_link
            );
        }
        

        //encode the array into jason
        echo json_encode($data);
    } else {
        echo json_encode("*warning_no_videos_found*");
    }

    // close connection to db
    $stmt = null;
    $connectionPDO = null;

?>