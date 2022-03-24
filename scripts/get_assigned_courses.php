<?php
    session_start();

    // https://makitweb.com/return-json-response-ajax-using-jquery-php
    $pass = file_get_contents('../../pass.txt', true);

    //connect to database
    try {
        $connectionPDO = new PDO('mysql:host=localhost;dbname=pbsclp_pbsclp', 'pbsclp', $pass);
        $connectionPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        exit('*database_connection_error*');
    }

    $user_id = $_POST['user_id_PHP'];

    $sql = "SELECT course_id FROM users_on_courses WHERE user_id=? ORDER BY course_id ASC";
    $stmt = $connectionPDO->prepare($sql);
    $stmt->execute([$user_id]);
    $courses_result = $stmt->fetchAll();

    if ($courses_result){
        $listOfCourseID = array();
            
        foreach($courses_result as $row) {
            array_push($listOfCourseID, $row['course_id']);
        }

        //encode the array into jason
        echo json_encode($listOfCourseID);
    } else {
        echo json_encode("*warning_no_courses_found*");
    }

    

    // close connection to db
    $stmt = null;
    $connectionPDO = null;

?>