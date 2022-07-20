<?php
    // ============================================
    //     - PBSCLP | new_course
    //     - Adam Shepherd
    //     - PBSCLP
    //     - April 2022

    //     This script adds a new course to the
    //     database
    // ============================================

    session_start();
    
    if(isset($_POST['course_namePHP'])) {
        
        $pass = file_get_contents('../../pass.txt', true);

        //connect to database
        try {
            $connectionPDO = new PDO('mysql:host=localhost;dbname=pbsclp_pbsclp', 'pbsclp', $pass);
            $connectionPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            exit('*database_connection_error*');
        }

        $course_name = $_POST['course_namePHP'];

        $directory_name = strtolower(str_replace(' ', '_', $course_name));
        
        if(file_exists('../../resource_bank/' . $directory_name)) {
            exit('*warning_course_already_exists*');
        }

        if (!mkdir("../../resource_bank/" . $directory_name)) {
            exit("*error_creating_directory*");
        }

        $sql = "INSERT INTO courses (name, description, directory_name ) values (:name, :description, :directory_name)";
        $stmt = $connectionPDO->prepare($sql);

        try {
            $stmt->execute(['name' => $course_name, 'description' => $_POST['descriptionPHP'], 'directory_name' => $directory_name]);

            //get course id
            $get_course_id_query = "SELECT * FROM courses ORDER BY course_id DESC LIMIT 1";
            $stmt = $connectionPDO->prepare($get_course_id_query);
            $stmt->execute();
            $course_id_result = $stmt->fetch();
            $course_id = $course_id_result['course_id'];

            //get list of admins
            $get_list_of_admins_query = "SELECT * FROM users WHERE account_type='administrator' ORDER BY user_id ASC";
            $stmt = $connectionPDO->prepare($get_list_of_admins_query);
            $stmt->execute();
            $list_of_admins_result = $stmt->fetchAll();
            for ($z = 0; $z < count($list_of_admins_result); $z++) {
                $list_of_admins[] = $list_of_admins_result[$z]['user_id'];
            }

            $insertquery = "INSERT INTO users_on_courses (user_id, course_id) VALUES ";
            for ($y = 0; $y < count($list_of_admins); $y++) {
                $insertquery .= "(" . $$list_of_admins[$y] . ", " . $course_id . ")";
                if ($y < (count($list_of_admins) - 1)) {
                    $insertquery .= ", ";
                } else {
                    $insertquery .= ";";
                }
            }

            echo $insertquery;

            // $stmt = $connectionPDO->prepare($insertquery);
            
            // if (!$stmt->execute()) {
            //     exit('Error: ' . $connection->error);
            // }


            exit('*course_created_successfully*');
        } catch (Exception $e) {
            rmdir("../../resource_bank/" . $directory_name);
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
        
        
        

        


        

        $stmt = null;
        $connectionPDO = null;
    }
?>