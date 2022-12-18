<?php
// ============================================
//     - PBSCLP | update_profile
//     - Adam Shepherd
//     - PBSCLP
//     - April 2022

//     This script updates the profile info
//     in the database after it has been edited
// ============================================


session_start();

$pass = file_get_contents('../../pass.txt', true);

if (isset($_POST['firstnamePHP'])) {
    //connect to database
    try {
        $connectionPDO = new PDO('mysql:host=localhost;dbname=pbsclp_pbsclp', 'pbsclp', $pass);
        $connectionPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        exit('*database_connection_error*');
    }

    if (isset($_POST['user_idPHP'])) {
        $user_id = $_POST['user_idPHP'];
    } else {
        $user_id = $_SESSION['user_id'];
    }

    //retrieve title, content and author for the new post
    $firstname = $_POST['firstnamePHP'];
    $lastname = $_POST['lastnamePHP'];
    $email = $_POST['emailPHP'];
    $organisation = $_POST['organisationPHP'];
    $contact_number = $_POST['contact_numberPHP'];
    if (isset($_POST['organisationIdPHP'])) {
        $organisation_id = $_POST['organisationIdPHP'];
    }

    //check if email exists
    $checkEmailQuery = "SELECT user_id FROM users WHERE email=? LIMIT 1";
    $stmt = $connectionPDO->prepare($checkEmailQuery);
    $stmt->execute([$email]);
    $email_result = $stmt->fetch();

    if ($email_result && $email_result['user_id'] != $user_id) {
        exit("*email_already_exists*");
    }

    if (isset($organisation_id)) {
        if ($organisation_id == -1) {
            // query database and insert the new announcement into the announcements table
            $sql = "BEGIN; UPDATE users SET firstname=:firstname, lastname=:lastname, email=:email, contact_number=:contact_number, organisation=:organisation WHERE user_id=:u_user_id; DELETE FROM users_in_organisation WHERE user_id = :o_user_id; COMMIT;";
            $stmt = $connectionPDO->prepare($sql);

            //check to see if the insert was successful
            if ($stmt->execute(['firstname' => $firstname, 'lastname' => $lastname, 'email' => $email, 'contact_number' => $contact_number, 'organisation' => $organisation, 'u_user_id' => $user_id, 'o_user_id' => $user_id])) {
                echo 'profile updated';
                $_SESSION['firstname'] = $firstname;
                $_SESSION['lastname'] = $lastname;
                $_SESSION['email'] = $email;
            } else {
                exit('Error: ' . $connection->error);
            }
        } else {

            if(isset($_POST['noMemberOrganisationPHP']) && $_POST['noMemberOrganisationPHP'] == 1){
                $sql = "BEGIN; UPDATE users SET firstname=:firstname, lastname=:lastname, email=:email, contact_number=:contact_number, organisation=:organisation WHERE user_id=:u_user_id; INSERT INTO users_in_organisation (organisation_id, user_id) VALUES (:organisation_id, :o_user_id); COMMIT;";
            } else {
                $sql = "BEGIN; UPDATE users SET firstname=:firstname, lastname=:lastname, email=:email, contact_number=:contact_number, organisation=:organisation WHERE user_id=:u_user_id; UPDATE users_in_organisation SET organisation_id = :organisation_id WHERE user_id = :o_user_id; COMMIT;";
            }
            // query database and insert the new announcement into the announcements table
            
            $stmt = $connectionPDO->prepare($sql);

            //check to see if the insert was successful
            if ($stmt->execute(['firstname' => $firstname, 'lastname' => $lastname, 'email' => $email, 'contact_number' => $contact_number, 'organisation' => $organisation, 'u_user_id' => $user_id, 'organisation_id' => $organisation_id, 'o_user_id' => $user_id])) {
                echo 'profile updated';
                $_SESSION['firstname'] = $firstname;
                $_SESSION['lastname'] = $lastname;
                $_SESSION['email'] = $email;
            } else {
                exit('Error: ' . $connection->error);
            }
        }

    } else {
        // query database and insert the new announcement into the announcements table
        $sql = "UPDATE users SET firstname=:firstname, lastname=:lastname, email=:email, contact_number=:contact_number WHERE user_id=:u_user_id;";
        $stmt = $connectionPDO->prepare($sql);

        //check to see if the insert was successful
        if ($stmt->execute(['firstname' => $firstname, 'lastname' => $lastname, 'email' => $email, 'contact_number' => $contact_number, 'u_user_id' => $user_id])) {
            echo 'profile updated';
            $_SESSION['firstname'] = $firstname;
            $_SESSION['lastname'] = $lastname;
            $_SESSION['email'] = $email;
        } else {
            exit('Error: ' . $connection->error);
        }
    }


    if (isset($_POST['new_list_of_coursesPHP']) || isset($_POST['old_list_of_coursesPHP'])) {
        // difference between old and new = to be removed
        // difference between new and old = to be added

        $new_list_of_courses = $_POST['new_list_of_coursesPHP'];
        $old_list_of_courses = $_POST['old_list_of_coursesPHP'];

        if ($old_list_of_courses != null) {
            $courses_to_add = array_values(array_diff($new_list_of_courses, $old_list_of_courses));
        } else {
            $courses_to_add = $new_list_of_courses;
        }

        if ($new_list_of_courses != null) {
            $courses_to_remove = array_values(array_diff($old_list_of_courses, $new_list_of_courses));
        } else {
            $courses_to_remove = $old_list_of_courses;
        }

        if (count($courses_to_remove) > 0) {
            $courses_to_remove_string = "";
            for ($x = 0; $x < count($courses_to_remove); $x++) {
                $courses_to_remove_string .= $courses_to_remove[$x];
                if ($x < (count($courses_to_remove) - 1)) {
                    $courses_to_remove_string .= ", ";
                }
            }

            $remove_query = "DELETE FROM users_on_courses WHERE user_id=? AND course_id IN (" . $courses_to_remove_string . ");";
            echo $remove_query;
            $stmt = $connectionPDO->prepare($remove_query);
            if (!$stmt->execute([$user_id])) {
                exit('Error: ' . $connection->error);
            }
        }

        if (count($courses_to_add) > 0) {
            $insertquery = "INSERT INTO users_on_courses (user_id, course_id) VALUES ";
            for ($y = 0; $y < count($courses_to_add); $y++) {
                $insertquery .= "(" . $user_id . ", " . $courses_to_add[$y] . ")";
                if ($y < (count($courses_to_add) - 1)) {
                    $insertquery .= ", ";
                } else {
                    $insertquery .= ";";
                }
            }

            echo $insertquery;

            $stmt = $connectionPDO->prepare($insertquery);

            if (!$stmt->execute()) {
                exit('Error: ' . $connection->error);
            }
        }
    }

    //close connection to db
    $stmt = null;
    $connectionPDO = null;
    exit('*account_updated_successfully*');
}

?>