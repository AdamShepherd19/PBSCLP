<?php
    session_start();
    
    if(isset($_POST['session_namePHP'])) {
        
        $pass = file_get_contents('../../pass.txt', true);

        //connect to database
        try {
            $connectionPDO = new PDO('mysql:host=localhost;dbname=pbsclp_pbsclp', 'pbsclp', $pass);
            $connectionPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            exit('*database_connection_error*');
        }

        $session_name = $_POST['session_namePHP'];
        $course_id = $_POST['course_idPHP'];

        $session_directory_name = strtolower(str_replace(' ', '_', $session_name));

        // $course_directory_name = ;
        $sql = "SELECT directory_name FROM courses WHERE course_id=? LIMIT 1";
        $stmt = $connectionPDO->prepare($sql);
        $stmt->execute([$course_id]);
        $result = $stmt->fetchAll();

        if ($result){
            // output data of each row
            foreach($result as $row) {
                $course_directory_name = $row['directory_name'];
            }
        } else {
            exit("*warning_no_course_found*");
        }
        
        if(file_exists('../../resource_bank/' . $course_directory_name . '/' . $session_directory_name)) {
            exit('*warning_session_already_exists*');
        }

        if (!mkdir("../../resource_bank/" . $course_directory_name . '/' .  $session_directory_name)) {
            exit("*error_creating_directory*");
        }

        $sql = "INSERT INTO sessions (name, description, directory_name ) values (:name, :description, :directory_name)";
        $stmt = $connectionPDO->prepare($sql);

        try {
            $stmt->execute(['name' => $session_name, 'description' => $_POST['descriptionPHP'], 'directory_name' => $session_directory_name]);
            exit('*course_created_successfully*');
        } catch (Exception $e) {
            rmdir("../../resource_bank/" . $course_directory_name . '/' . $session_directory_name);
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }

        $stmt = null;
        $connectionPDO = null;
    }
?>