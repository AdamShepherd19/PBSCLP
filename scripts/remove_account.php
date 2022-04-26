<?php
    // ============================================
    //     - PBSCLP | remove_account
    //     - Adam Shepherd
    //     - PBSCLP
    //     - April 2022

    //     This script removes a user account from
    //     the database
    // ============================================


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
        //get post variables
        $user_id_to_delete = $_POST['deleting_user_id'];
        $reason = $_POST['delete_reason'];

        $pass = file_get_contents('../../pass.txt', true);
        //connect to database
        try {
            $connectionPDO = new PDO('mysql:host=localhost;dbname=pbsclp_pbsclp', 'pbsclp', $pass);
            $connectionPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            exit('*database_connection_error*');
        }

        // query database and insert the new announcement into the announcements table
        $sql = "SELECT firstname, lastname, email, account_type FROM users WHERE user_id=? LIMIT 1";
        $stmt = $connectionPDO->prepare($sql);
        $stmt->execute([$user_id_to_delete]);
        $result = $stmt->fetchAll();

        if ($result){

            // store data of each row
            foreach($result as $row) {
                // QUERY FOR INFO
                $firstname_to_delete = $row['firstname'];
                $lastname_to_delete = $row['lastname'];
                $email_to_delete = $row['email'];
                $account_type_to_delete = $row['account_type'];
            }

            $filename = "../account_deletion_logs/" . $_POST['deleting_user_id'] . "_" . $firstname_to_delete . $lastname_to_delete;
        
            $myfile = fopen($filename, "w") or die("*error_creating_log*");

            $expFormat = mktime(date("H"), date("i")+20, date("s"), date("m") ,date("d"), date("Y"));
            $date = "Date of deletion: " . date("d-m-Y H:i:s",$expFormat) . "\n";
            $uid = "User ID: " . $user_id_to_delete . "\n";
            $name = "Name: " . $firstname_to_delete . " " . $lastname_to_delete . "\n";
            $email = "Email Address: " . $email_to_delete . "\n";
            $acctype = "Account Type: " . $account_type_to_delete . "\n";
            $separator = "\n===================================\n\n";
            $deluserinfo = "Deleting User: \n";
            $adminid = "User ID: " . $_SESSION['user_id'] ."\n";
            $adminname = "Name: " . $_SESSION['firstname'] . " " . $_SESSION['lastname'] ."\n";
            $adminreason = "\nReason: " . $reason ."\n";

            $total_log = $date.= $uid .= $name .= $email .= $acctype .= $separator .= $deluserinfo .= $adminid .= $adminname .= $adminreason;
            
            if (fwrite($myfile, $total_log) == false) {
                exit("*error_creating_log*");
            } else {
                $sql = "DELETE FROM users WHERE user_id=?";
                $stmt = $connectionPDO->prepare($sql);
                
                //check to see if the insert was successful
                if ($stmt->execute([$user_id_to_delete]) > 0) {
                    exit('*account_removed_succesfully*');
                } else {
                    exit('Error: ' . $connection->error);
                }
            }

            fclose($myfile);
        }

        //close connection to db
        $stmt = null;
        $connectionPDO = null;
    }

?>