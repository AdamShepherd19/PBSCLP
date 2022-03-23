<?php
    session_start();
    echo "test";
    // if(isset($_POST['file_namePHP'])) {
        
        // $pass = file_get_contents('../../pass.txt', true);

        //connect to database
        // try {
        //     $connectionPDO = new PDO('mysql:host=localhost;dbname=pbsclp_pbsclp', 'pbsclp', $pass);
        //     $connectionPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // } catch(PDOException $e) {
        //     exit('*database_connection_error*');
        // }

    if(isset($_FILES['file']['name'])){
        echo "test 2";
        /* Getting file name */
        $filename = $_FILES['file']['name'];
        
        /* Location */
        $location = "../../resource_bank/".$filename;
        $imageFileType = pathinfo($location,PATHINFO_EXTENSION);
        $imageFileType = strtolower($imageFileType);
        
        /* Valid extensions */
        $valid_extensions = array("jpg","jpeg","png");
        
        $response = 0;
        /* Check file extension */
        if(in_array(strtolower($imageFileType), $valid_extensions)) {
            /* Upload file */
            if(move_uploaded_file($_FILES['file']['tmp_name'],$location)){
                $response = $location;
            }
        }
        
        echo $response;
        exit;
    }
    echo "test 3";
        
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
        // $connectionPDO = null;
    // }
?>