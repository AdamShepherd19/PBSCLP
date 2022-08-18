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
    
    if(isset($_POST['firstnamePHP'])) {
        //connect to database
        try {
            $connectionPDO = new PDO('mysql:host=localhost;dbname=pbsclp_pbsclp', 'pbsclp', $pass);
            $connectionPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            exit('*database_connection_error*');
        }

        if(isset($_POST['user_idPHP'])) {
            $user_id = $_POST['user_idPHP'];
        } else {
            $user_id = $_SESSION['user_id'];
        }

        //retrieve title, content and author for the new post
        $firstname = $_POST['firstnamePHP'];
        $lastname = $_POST['lastnamePHP'];
        $email = $_POST['emailPHP'];
        $contact_number = $_POST['contact_numberPHP'];
        $organisation = $_POST['organisationPHP'];

        //check if email exists
        $checkEmailQuery = "SELECT user_id FROM users WHERE email=? LIMIT 1";
        $stmt = $connectionPDO->prepare($checkEmailQuery);
        $stmt->execute([$email]);
        $email_result = $stmt->fetch();
        print_r($email_result);
        if ($email_result && $email_result['user_id'] != $user_id) {
            exit("*email_already_exists*");
        }


        // query database and insert the new announcement into the announcements table
        $sql = "UPDATE users SET firstname=:firstname, lastname=:lastname, email=:email, organisation=:organisation, contact_number=:contact_number WHERE user_id=:user_id";
        $stmt = $connectionPDO->prepare($sql);
        
        //check to see if the insert was successful
        if ($stmt->execute(['firstname' => $firstname, 'lastname' => $lastname, 'email' => $email, 'organisation' => $organisation, 'contact_number' => $contact_number, 'user_id' => $user_id])) {
            echo 'profile updated';
            $_SESSION['firstname'] = $firstname;
            $_SESSION['lastname'] = $lastname;
            $_SESSION['email'] = $email;
        } else {
            exit('Error: ' . $connection->error);
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

            print_r($courses_to_remove);
            
            if(count($courses_to_remove) > 0){
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