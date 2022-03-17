<?php
    session_start();

    if(!isset($_SESSION['logged_in'])){
        header('Location: https://pbsclp.info');
        exit();
    }

    if($_SESSION['account_type'] != 'administrator'){
        header('Location: landing.php');
        exit();
    }

    if (isset($_POST['confirm_deletion'])){
        $user_id_to_delete = $_POST['deleting_user_id'];
        
        $filename = "../account_deletion_logs/" . $_POST['deleting_user_id'];
        
        $myfile = fopen($filename, "w") or die("*error_creating_log*");

        $txt = "User ID: " . $_SESSION['user_id'] . "\n";
        fwrite($myfile, $txt);

        // $txt = "Name: " . $_SESSION['firstname'] . $_SESSION['lastname'] . "\n";
        // fwrite($myfile, $txt);

        // $txt = "Email Address: " . $_SESSION['email'] . "\n";
        // fwrite($myfile, $txt);

        // $txt = "Account Type: " . $_SESSION['account_type'] . "\n";
        // fwrite($myfile, $txt);

        fclose($myfile);
    }

    $pass = file_get_contents('../../pass.txt', true);
    
    // if(isset($_POST['userIDPHP'])) {
    //     //connect to database
    //     try {
    //         $connectionPDO = new PDO('mysql:host=localhost;dbname=pbsclp_pbsclp', 'pbsclp', $pass);
    //         $connectionPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //     } catch(PDOException $e) {
    //         exit('*database_connection_error*');
    //     }

    //     // query database and insert the new announcement into the announcements table
    //     $sql = "DELETE FROM users WHERE user_id=?";
    //     $stmt = $connectionPDO->prepare($sql);
        
    //     //check to see if the insert was successful
    //     if ($stmt->execute([$_POST['userIDPHP']]) > 0) {
    //         exit('*account_removed_succesfully*');
    //     } else {
    //         exit('Error: ' . $connection->error);
    //     }

    //     //close connection to db
    //     $stmt = null;
    //     $connectionPDO = null;
    // }

?>