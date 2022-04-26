<!--
    ============================================
        - PBSCLP | logout
        - Adam Shepherd
        - PBSCLP
        - April 2022

        This script unsets and destroys all 
        session variables, loggin the user out
        and taking them back to the login page
    ============================================
-->

<?php
    session_start();

    session_unset();
    session_destroy();
    header('Location: ../pages/login.php');
    exit();
?>