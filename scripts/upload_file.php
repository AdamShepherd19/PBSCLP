<?php
    session_start();
    
    if(isset($_POST['file_namePHP'])) {
        
        $pass = file_get_contents('../../pass.txt', true);

        //connect to database
        try {
            $connectionPDO = new PDO('mysql:host=localhost;dbname=pbsclp_pbsclp', 'pbsclp', $pass);
            $connectionPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            exit('*database_connection_error*');
        }

        $file_name = $_POST['file_namePHP'];

        // $directory_name = strtolower(str_replace(' ', '_', $course_name));
        $directory_name = "../../resource_bank/";
        // $file_to_upload = $_POST['file'];

        // get details of the uploaded file
        // $fileTmpPath = $_FILES['filePHP']['tmp_name'];
        // $fileName = $_FILES['filePHP']['name'];
        
        // $uploadFileDir = '../../resource_bank/';
        // $dest_path = $uploadFileDir . $fileName;
        
        if ( 0 < $_FILES['file']['error'] ) {
            echo 'Error: ' . $_FILES['file']['error'] . '<br>';
        }
        else {
            move_uploaded_file($_FILES['file']['tmp_name'], '../../resource_bank/' . $_FILES['file']['name']);
        }
        
        // if(file_exists('../../resource_bank/' . $directory_name)) {
        //     exit('*warning_course_already_exists*');
        // }

        // if (!mkdir("../../resource_bank/" . $directory_name)) {
        //     exit("*error_creating_directory*");
        // }

        // $sql = "INSERT INTO courses (name, description, directory_name ) values (:name, :description, :directory_name)";
        // $stmt = $connectionPDO->prepare($sql);

        // try {
        //     $stmt->execute(['name' => $course_name, 'description' => $_POST['descriptionPHP'], 'directory_name' => $directory_name]);
        //     exit('*course_created_successfully*');
        // } catch (Exception $e) {
        //     rmdir("../../resource_bank/" . $directory_name);
        //     echo 'Caught exception: ',  $e->getMessage(), "\n";
        // }

        // $stmt = null;
        $connectionPDO = null;
    }
?>