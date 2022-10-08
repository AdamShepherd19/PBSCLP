<?php
    // ============================================
    //     - PBSCLP | get_profile
    //     - Adam Shepherd
    //     - PBSCLP
    //     - April 2022

    //     This script queries and returns the info
    //     about a specified user
    // ============================================

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

    if(isset($_POST['user_id_PHP'])) {
        $user_id = $_POST['user_id_PHP'];
    } else {
        $user_id = $_SESSION['user_id'];
    }

    //perform query and sort into newest first
    $sql = "SELECT users.firstname, users.lastname, users.email, organisations.organisation_name, organisations.organisation_id, users.contact_number FROM users, organisations, users_in_organisation WHERE users.user_id=? AND users.user_id = users_in_organisation.user_id AND users_in_organisation.organisation_id = organisations.organisation_id LIMIT 1";
    $stmt = $connectionPDO->prepare($sql);
    $stmt->execute([$user_id]);
    $result = $stmt->fetch();

    if ($result) {
        
        $sql = "SELECT course_id FROM users_on_courses WHERE user_id=? ORDER BY course_id ASC";
        $stmt = $connectionPDO->prepare($sql);
        $stmt->execute([$user_id]);
        $courses_result = $stmt->fetchAll();

        if ($courses_result){
            $listOfCourseID = array();
            
            foreach($courses_result as $row) {
                array_push($listOfCourseID, $row['course_id']);
                $courses_as_string .= $row['course_id'] .= ",";
            }
        
            $courses_as_string = substr($courses_as_string, 0, -1);

            $sql = "SELECT name FROM courses WHERE course_id IN (" . $courses_as_string . ") ORDER BY course_id ASC";
            $stmt = $connectionPDO->prepare($sql);
            $stmt->execute();
            $course_name_result = $stmt->fetchAll();

            if ($course_name_result){
                //initialise array
                $list_of_course_name = array();
        
                // output data of each row
                foreach($course_name_result as $row) {
                    array_push($list_of_course_name, $row['name']);
                }
            }
        }

        $data = array();

        //retrieve data from query
        $name = $result['firstname'] . " " . $result['lastname'];
        $email = $result['email'];
        $organisation_name = $result['organisation_name'];
        $organisation_id = $result['organisation_id'];
        $contact_number = $result['contact_number'];
        
        //add data into array
        $data[] = array(
            "name" => $name,
            "email" => $email,
            "organisation_name" => $organisation_name,
            "organisation_id" => $organisation_id,
            "contact_number" => $contact_number,
            "list_of_course_id" => $listOfCourseID,
            "list_of_course_names" => $list_of_course_name
        );

        //encode the array into jason
        echo json_encode($data);

    } else {
        echo json_encode("*warning_no_user_found*");
    }

    // close connection to db
    $stmt = null;
    $connectionPDO = null;

?>