<?php
    $pass = file_get_contents('../../pass.txt', true);
    
    if(isset($_POST['firstnamePHP'])) {
        //connect to database
        try {
            $connectionPDO = new PDO('mysql:host=localhost;dbname=pbsclp_pbsclp', 'pbsclp', $pass);
            $connectionPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            exit('*database_connection_error*');
        }

        //retrieve title, content and author for the new post
        $firstname = $_POST['firstnamePHP'];
        $lastname = $_POST['lastnamePHP'];
        $email = $_POST['emailPHP'];
        $contact_number = $_POST['contact_numberPHP'];
        $organisation = $_POST['organisationPHP'];
        $account_type = $_POST['account_typePHP'];
        $list_of_courses = $_POST['list_of_coursesPHP'];


        // query database and insert the new announcement into the announcements table
        $sql = "INSERT INTO users (firstname, lastname, email, contact_number, organisation, account_type) VALUES (:firstname, :lastname, :email, :contact_number, :organisation, :account_type);";
        // INSERT INTO users (firstname, lastname, email, contact_number, organisation, account_type) VALUES ('adam', 'ldf', 'email', '1232', 'ardasf', 'practitioner')
        $stmt = $connectionPDO->prepare($sql);
        
        //check to see if the insert was successful
        if ($stmt->execute(['firstname' => $firstname, 'lastname' => $lastname, 'email' => $email, 'contact_number' => $contact_number, 'organisation' => $organisation, 'account_type' => $account_type])) {
            $sql = "SELECT user_id FROM users WHERE email=? LIMIT 1";
            $stmt = $connectionPDO->prepare($sql);
            $stmt->execute([$email]);
            $data = $stmt->fetch();

            if ($data) {
                $user_id = $data['user_id'];
                $query = "INSERT INTO users_on_courses (user_id, course_id) VALUES ";
                for ($x = 0; $x < count($list_of_courses); $x++) {
                    $query .= "(" . $user_id . ", " . $list_of_courses[$x] . ")";
                    if ($x < (count($list_of_courses) - 1)) {
                        $query .= ", ";
                    } else {
                        $query .= ";";
                    }
                }

                $stmt = $connectionPDO->prepare($sql);
                if ($stmt->execute()) {
                    exit('*user_added_successfully*');
                } else {
                    exit('Error: ' . $connectionPDO->error);
                }
            } else {
                exit('Error: ' . $connectionPDO->error);
            }
            
            
        } else {
            exit('Error: ' . $connectionPDO->error);
        }

        $stmt = null;
        $connectionPDO = null;

    }

?>