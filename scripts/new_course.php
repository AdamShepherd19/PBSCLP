<?php
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

        //retrieve title, content and author for the new post
        $course_name = $_POST['course_namePHP'];

        // echo $course_name;

        $directory_name = strtolower(str_replace(' ', '_', $course_name));
        // echo $directory_name;
        if(file_exists('../../resource_bank/' . $directory_name)) {
            exit('*warning_course_already_exists*');
        }

        if (!mkdir("../../resource_bank/" . $directory_name)) {
            exit("*error_creating_directory*");
        }

        // query database and insert the new announcement into the announcements table
        $sql = "INSERT INTO courses (name, description, directory_name ) values (:name, :description, :directory_name)";
        $stmt = $connectionPDO->prepare($sql);



        //generate directory name X
        //create directory in resource bank X
        //add new course to database
        
        try {
            $stmt->execute(['name' => $course_name, 'description' => $_POST['descriptionPHP'], 'directory_name' => $directory_name]);
            exit('*course_created_successfully*');
        } catch (Exception $e) {
            rmdir("../../resource_bank/" . $directory_name);
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }

        // $stmt = null;
        $connectionPDO = null;
    }
?>