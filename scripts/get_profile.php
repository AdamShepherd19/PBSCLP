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
} catch (PDOException $e) {
    exit('*database_connection_error*');
}

if (isset($_POST['user_id_PHP'])) {
    $user_id = $_POST['user_id_PHP'];
} else {
    $user_id = $_SESSION['user_id'];
}

//perform query and sort into newest first

$sql = "SELECT users.firstname, users.lastname, users.email, users.organisation, users.contact_number, users.last_login FROM users WHERE users.user_id=? LIMIT 1";

$stmt = $connectionPDO->prepare($sql);
$stmt->execute([$user_id]);
$result = $stmt->fetch();

if ($result) {

    try {
        $sql = "SELECT users_in_organisation.organisation_id, organisations.organisation_name FROM users_in_organisation, organisations WHERE users_in_organisation.user_id=? AND users_in_organisation.organisation_id = organisations.organisation_id LIMIT 1";
        $stmt = $connectionPDO->prepare($sql);
        $stmt->execute([$user_id]);
        $member_org_result = $stmt->fetch();

        if ($member_org_result) {
            $member_organisation_name = $member_org_result['organisation_name'];
            $member_organisation_id = $member_org_result['organisation_id'];
        } else {
            $member_organisation_name = null;
            $member_organisation_id = null;
        }

        $sql = "SELECT course_id FROM users_on_courses WHERE user_id=? ORDER BY course_id ASC";
        $stmt = $connectionPDO->prepare($sql);
        $stmt->execute([$user_id]);
        $courses_result = $stmt->fetchAll();

        if ($courses_result) {
            $listOfCourseID = array();

            foreach ($courses_result as $row) {
                array_push($listOfCourseID, $row['course_id']);
                $courses_as_string .= $row['course_id'] .= ",";
            }

            $courses_as_string = substr($courses_as_string, 0, -1);

            $sql = "SELECT name FROM courses WHERE course_id IN (" . $courses_as_string . ") ORDER BY course_id ASC";
            $stmt = $connectionPDO->prepare($sql);
            $stmt->execute();
            $course_name_result = $stmt->fetchAll();

            if ($course_name_result) {
                //initialise array
                $list_of_course_name = array();

                // output data of each row
                foreach ($course_name_result as $row) {
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
        $organisation = $result['organisation'];
        $contact_number = $result['contact_number'];
        $last_login = $result['last_login'];

        //add data into array
        $data[] = array(
            "name" => $name,
            "email" => $email,
            "organisation" => $organisation,
            "organisation_name" => $member_organisation_name,
            "organisation_id" => $member_organisation_id,
            "contact_number" => $contact_number,
            "list_of_course_id" => $listOfCourseID,
            "list_of_course_names" => $list_of_course_name,
            "last_login" => $last_login
        );
    } catch (PDOException $e) {
        exit($e);
    }

    //encode the array into jason
    echo json_encode($data);

} else {
    echo json_encode("*warning_no_user_found*");
}

// close connection to db
$stmt = null;
$connectionPDO = null;

?>