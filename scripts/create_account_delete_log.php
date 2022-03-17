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
?>