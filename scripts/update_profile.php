<?php
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

        // query database and insert the new announcement into the announcements table
        $sql = "UPDATE users SET firstname=:firstname, lastname=:lastname, email=:email, organisation=:organisation, contact_number=:contact_number WHERE user_id=:user_id";
        $stmt = $connectionPDO->prepare($sql);
        
        //check to see if the insert was successful
        if ($stmt->execute(['firstname' => $firstname, 'lastname' => $lastname, 'email' => $email, 'organisation' => $organisation, 'contact_number' => $contact_number, 'user_id' => $user_id])) {
            echo 'profile updated';
        } else {
            exit('Error: ' . $connection->error);
        }

        if (isset($_POST['new_list_of_coursesPHP'])) {
            // difference between old and new = to be removed
            // difference between new and old = to be added

            $new_list_of_courses = $_POST['new_list_of_coursesPHP'];
            $old_list_of_courses = $_POST['new_list_of_coursesPHP'];

            $courses_to_add = array_diff($new_list_of_courses, $old_list_of_courses);
            $courses_to_remove = array_diff($old_list_of_courses, $new_list_of_courses);

            
            $courses_to_remove_string = "";
            for ($x = 0; $x < count($courses_to_remove); $x++) {
                $courses_to_remove_string .= $user_id;
                if ($x < (count($list_of_courses) - 1)) {
                    $courses_to_remove_string .= ", ";
                }
            }
            $remove_query = "DELETE FROM users_on_courses WHERE user_id=? AND course_id IN (" . $courses_to_remove_string . ");";
            $stmt = $connectionPDO->prepare($remove_query);
            
            if (!$stmt->execute([$user_id]) {
                exit('Error: ' . $connection->error);
            }


            $insertquery = "INSERT INTO users_on_courses (user_id, course_id) VALUES ";
            for ($y = 0; $y < count($courses_to_add); $y++) {
                $insertquery .= "(" .= $user_id . ", " .= $courses_to_add[$y] .= ")";
                if ($y < (count($courses_to_add) - 1)) {
                    $insertquery .= ", ";
                } else {
                    $insertquery .= ";";
                }
            }

            $stmt = $connectionPDO->prepare($insertquery);
            
            if (!$stmt->execute()) {
                exit('Error: ' . $connection->error);
            }

            exit('success');


        }

        //close connection to db
        $stmt = null;
        $connectionPDO = null;
    }

?>