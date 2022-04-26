<!--
    ============================================
        - PBSCLP | get_course_summary
        - Adam Shepherd
        - PBSCLP
        - April 2022

        This script queries and returns all the
        information regarding a specific list
        of courses that a user is enrolled on
    ============================================
-->

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

    //query users_on_courses for course_id's for $_SESSION['user_id']
    //apply this query to select courses query
    $sql = "SELECT course_id FROM users_on_courses WHERE user_id=? ORDER BY course_id ASC";
    $stmt = $connectionPDO->prepare($sql);
    $stmt->execute([$_SESSION['user_id']]);
    $result = $stmt->fetchAll();

    if ($result){
        // output data of each row
        foreach($result as $row) {
            //add data into array
            $valid_courses .= $row['course_id'] .= ",";
        }
    } else {
        echo json_encode("*no_courses_assigned_to_user*");
    }

    $valid_courses = substr($valid_courses, 0, -1);

    //perform query and sort into newest first
    $sql = "SELECT * FROM courses WHERE course_id IN (" . $valid_courses . ") ORDER BY course_id ASC";
    $stmt = $connectionPDO->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();
    
    if ($result){
        //initialise array
        $data = array();

        // output data of each row
        foreach($result as $row) {

            //retrieve data from query
            $course_id = $row['course_id'];
            $name = $row['name'];
            $description = $row['description'];
                
            //add data into array
            $data[] = array(
                "course_id" => $course_id,
                "name" => $name,
                "description" => $description,
            );
        }
        

        //encode the array into jason
        echo json_encode($data);
    } else {
        echo json_encode("*warning_no_courses_found*");
    }

    // close connection to db
    $stmt = null;
    $connectionPDO = null;

?>