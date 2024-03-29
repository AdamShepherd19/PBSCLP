<?php
// ============================================
//     - PBSCLP | login
//     - Adam Shepherd
//     - PBSCLP
//     - April 2022

//     This file contains the login page which
//     allows practitioners to acces the
//     platform or the password reset system
// ============================================

session_start();

// if already logged in
if (isset($_SESSION['logged_in'])) {
    header('Location: https://pbsclp.info/pages/landing.php');
    exit();
}

$pass = file_get_contents('../../pass.txt', true);

if (isset($_POST['login'])) {
    //connect to database
    try {
        $connectionPDO = new PDO('mysql:host=localhost;dbname=pbsclp_pbsclp', 'pbsclp', $pass);
        $connectionPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        exit('*database_connection_error*');
    }

    try {

        $email = $_POST['emailPHP'];
        $password = $_POST['passwordPHP'];

        $sqlUser = "SELECT users.user_id, users.account_type, users.firstname, users.lastname, users.password, users.admin_locked, users.password_attempts, users.password_locked FROM users WHERE users.email=? LIMIT 1";
        $stmt = $connectionPDO->prepare($sqlUser);
        $stmt->execute([$email]);
        $dataUser = $stmt->fetch();

        if ($dataUser != null) {

            $sqlOrganisation = "SELECT users_in_organisation.organisation_id, organisations.organisation_name FROM users_in_organisation, organisations WHERE users_in_organisation.user_id = ? AND organisations.organisation_id = users_in_organisation.organisation_id LIMIT 1";
            $stmt = $connectionPDO->prepare($sqlOrganisation);
            $stmt->execute([$dataUser['user_id']]);
            $dataOrganisation = $stmt->fetch();

            //check if login details provided match a user profile in the db
            if ($dataUser['password_locked'] == 1) {
                exit("*account_password_locked*");
            } else if ($dataUser && password_verify($password, $dataUser['password'])) {
                if ($dataUser['admin_locked'] == 1) {
                    exit("*account_locked_by_administrator*");
                } else {
                    $loginTimestamp = date("Y-m-d H:i:s");
                    $loginTimeSql = "UPDATE users SET last_login=:login_timestamp, password_attempts='0' WHERE email=:email";
                    $stmt = $connectionPDO->prepare($loginTimeSql);
                    if ($stmt->execute(['login_timestamp' => $loginTimestamp, 'email' => $email])) {

                        if ($dataOrganisation['organisation_id'] > 0) {
                            $_SESSION['organisation_id'] = $dataOrganisation['organisation_id'];
                            $_SESSION['organisation_name'] = $dataOrganisation['orgnisation_name'];
                        } else {
                            $_SESSION['organisation_id'] = null;
                            $_SESSION['organisation_name'] = null;
                        }
                        //store session variables
                        $_SESSION['logged_in'] = True;
                        $_SESSION['user_id'] = $dataUser['user_id'];
                        $_SESSION['email'] = $email;
                        $_SESSION['account_type'] = $dataUser['account_type'];
                        $_SESSION['firstname'] = $dataUser['firstname'];
                        $_SESSION['lastname'] = $dataUser['lastname'];

                        exit('*login_success*');
                    } else {
                        exit('*issue_creating_timestamp*');
                    }
                }
            } else {
                $account_locked = 0;
                if ($dataUser['password_attempts'] == 0) {
                    $attempts = 1;
                } else if ($dataUser['password_attempts'] == 1) {
                    $attempts = 2;
                } else if ($dataUser['password_attempts'] == 2) {
                    $attempts = 3;
                    $account_locked = 1;
                }

                // query database and insert the new announcement into the announcements table
                $sql = "UPDATE users SET password_attempts=:attempts, password_locked=:locked WHERE email=:email";
                $stmt = $connectionPDO->prepare($sql);

                //check to see if the insert was successful
                if (!$stmt->execute(['attempts' => $attempts, 'locked' => $account_locked, 'email' => $email])) {
                    exit('Error: ' . $connection->error);
                }

                exit('*login_failed*');
            }
        }
    } catch (PDOException $e) {
        exit($e);
    }

    // close connection to db
    $stmt = null;
    $connectionPDO = null;
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">

    <!-- bootstrap metadata -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <!-- Bootstrap javascript include links -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>

    <!-- include jQuery -->
    <script src="../includes/jquery.js"></script>

    <link rel="stylesheet" href="../stylesheets/style.css">
    <link rel="stylesheet" href="../stylesheets/login.css">

    <title>Login</title>

</head>

<body>
    <div class="logo-banner">
        <img src="../images/pbslogo.png" alt="PBSuk Logo">
    </div>

    <div class="login-container">

        <div class="login-header">
            <h1>PBSuk Collaboration and Learning Hub</h1>
        </div>

        <div class="login-form-container">
            <h2>Login</h2>

            <form action="login.php" method="post">
                <label for="email">Email Address: </label>
                <input type="text" id="email" class="pbs-form-text-box" placeholder="Email..."> <br />
                <label for="password">Password: </label>
                <input type="password" id="password" class="pbs-form-text-box" placeholder="Password..."> <br />
                <a href="password_reset.php" id="forgot-password">Reset password</a>
                <input type="button" id="login" class="pbs-button pbs-button-white" value="Log In"> <br />
            </form>

            <p id="login-response"></p>

        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function () {

            $("#login").on('click', function () {
                var email = $("#email").val();
                var password = $('#password').val();

                if (email == "" || password == "") {
                    alert("Please enter an email and password");
                } else {
                    $.ajax({
                        method: 'POST',
                        url: "login.php",
                        data: {
                            login: 1,
                            emailPHP: email,
                            passwordPHP: password
                        },
                        success: function (response) {
                            if (response.includes("*login_success*")) {
                                window.location.href = 'landing.php';
                            } else if (response.includes("*account_locked_by_administrator*")) {
                                $('#login-response').html("Your account has been suspended. Please contact an adminstrator.");
                            } else if (response.includes("*account_password_locked*")) {
                                $('#login-response').html("You have entered your password incorrectly too many times. Please use the password reset tool to change your password and unlock your account.");
                            } else if (response.includes("*login_failed*")) {
                                $('#login-response').html("Login Failed. Please try again.");
                            } else if (response.includes("*database_connection_error*")) {
                                alert("There was an issue connecting to the server. Please try again or contact a system administrator.");
                            } else if (response.includes("*issue_creating_timestamp*")) {
                                alert("There was an issue connecting to the time server. Please try again or contact a system administrator.");
                            }
                        },
                        datatype: 'text'
                    });
                };
            });
        });
    </script>
</body>

</html>