<?php
    session_start();

    // if already logged in
    if (isset($_SESSION['logged_in'] && $_SESSION['logged_in'] == True)){
        header('Location: landing.php');
        exit();
    }

    $pass = file_get_contents('../pass.txt', true);

    if(isset($_POST['login'])) {
        //connect to database
        $connection = new mysqli('localhost', 'pbsclp', $pass, 'pbsclp_pbsclp');

        //check db connection
        if ($conn->connect_error) {
            exit("Connection failed: " . $conn->connect_error);
        }

        //retrieve email and password from form
        $email = $_POST['emailPHP'];
        $password = md5($_POST['passwordPHP']);
        
        //query db for user login details provided
        $query = "SELECT user_id FROM users WHERE email='" . $email . "' AND password='" . $password . "'";
        $data = $connection->query($query);

        //check if login details provided match a user profile in the db
        if ($data->num_rows > 0) {
            //store session variables
            $_SESSION['logged_in'] = True;
            $_SESSION['email'] = $email;
            $_SESSION['user_id'] = $data[0];

            exit('Login success');
        } else {
            exit('Login failed');
        }
    }
?>

<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8">

        <!-- bootstrap metadata -->
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- bootstrap -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

        <!-- Bootstrap javascript include links -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

        <!-- include jQuery -->
        <script src="includes/jquery.js"></script>


        <title>Login</title>
        
    </head>

    <body>

        <div>
            <h1>PBSCLP Login</h1>
        </div>

        <form action="login.php" method="post">
            <input type="text" id="email" placeholder="Email...">
            <input type="text" id="password" placeholder="Password...">
            <input type="button" id="login" value="Log In">
        </form>

        <p id="login-response"></p>
            
        <script type="text/javascript">
            $(document).ready(function () {

                $("#login").on('click', function(){
                    var email = $("#email").val();
                    var password = $('#password').val();

                    if(email == "" || password == ""){
                        alert("Please enter a username and password");
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
                                $('#login-response').html(response);

                                // if (response.indexOf('success') >= 0){
                                //     window.location = 'landing.php';
                                // }
                            },
                            datatype: 'text'
                        });
                    };
                });
            });
        </script>
    </body>
    
</html>