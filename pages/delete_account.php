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

    if (isset($_POST['userIDPHP'])) {
        // https://makitweb.com/return-json-response-ajax-using-jquery-php
        $pass = file_get_contents('../../pass.txt', true);

        //connect to database
        try {
            $connectionPDO = new PDO('mysql:host=localhost;dbname=pbsclp_pbsclp', 'pbsclp', $pass);
            $connectionPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            exit('*database_connection_error*');
        }

        //perform query and sort into newest first
        $sql = "SELECT firstname, lastname FROM users WHERE user_id=? LIMIT 1";
        $stmt = $connectionPDO->prepare($sql);
        $stmt->execute([$_POST['userIDPHP']]);
        $result = $stmt->fetchAll();

        if ($result){
            // output data of each row
            foreach($result as $row) {
                $name = $row['firstname'] . " " . $row['lastname'];
                exit($name);
            }
        } else {
            exit("*warning_no_user_found*");
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
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

        <!-- Bootstrap javascript include links -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

        <!-- include jQuery -->
        <script src="../includes/jquery.js"></script>

        <link rel="stylesheet" href="../stylesheets/style.css">
        <link rel="stylesheet" href="../stylesheets/delete_account.css">

        <title>Remove Account</title>
        
    </head>

    <body>

        <div id="pbs-nav-bar">
            <?php
                include "../common/nav-bar.php";
            ?>
        </div>

        <div class="page-header">
            <h1>Remove Account</h1>
        </div>

        <div class="main-content">
            <div id="info-wrapper">
                
                <h3>Are you sure you wish to remove the account belonging to <span id='account-name'> </span>? All items relating to the account (such as forum posts created by the user and comments posted by the user) will be permanently removed.</h3>
                <form>
                    <br>
                    <h5>Please enter the reason you are deleting this account below:</h5>
                    <textarea id="delete-reason" class="pbs-form-text-box" placeholder="Please enter the reason for deletion..."></textarea><br />
                </form>
            </div>

            <div class="button-wrapper">
                <input type="button" id="remove-account-cancel" class="pbs-button pbs-button-green" value="Cancel"> 
                <input type="button" id="remove-account-submit" class="pbs-button pbs-button-red" value="Delete">
            </div>
        </div>

        
        <script type="text/javascript">
            $(document).ready(function () {

                // only show administrator content if an admin logged in
                var accountType = '<?php echo $_SESSION['account_type']; ?>';
                if (accountType != 'administrator') {
                    $('.admin-only').hide();
                } else {
                    $('.admin-only').show();
                }

                var user_id = "<?php echo $_GET['userId']; ?>";



                $.ajax({
                    method: 'POST',
                    url: "delete_account.php",
                    data: {
                        userIDPHP: user_id
                    },
                    success: function (response) {
                        if (response.includes("*warning_no_user_found*")) {
                            $("#info-wrapper").html("<h3> No user found. </h3>");
                        } else {
                            $("#account-name").html(response);
                        }
                    },
                    datatype: 'text'
                });

                $("#remove-account-cancel").on('click', function(){
                    window.location.replace('manage_users.php');
                });

                $("#remove-account-submit").on('click', function(){
                    //send data to php
                    $.ajax({
                        method: 'POST',
                        url: "../scripts/remove_account.php",
                        data: {
                            userIDPHP: user_id
                        },
                        success: function (response) {
                            //check if the php execution was successful and the data was added to the db
                            if (response.includes("*account_removed_succesfully*")){
                                //replace html with success message and button to return to landing page
                                var successHTML = "<h3>The account was removed succesfully. Please click the button below to return to the user management page.</h3><br> " +
                                    "<input type='button' id='return' class='pbs-button pbs-button-green' value='Confirm'>";

                                $('.main-content').html(successHTML);

                            } else {
                                //display error message if the php could not be executed
                                $('.main-content').html("<h3> There was an error processing your request. Please try again </h3><br>Error" + response +
                                    "<br><input type='button' id='return' class='pbs-button pbs-button-green' value='Confirm'>");
                            }

                            // onclick function for new button to return to landing page
                            $("#return").on('click', function(){
                                window.location.replace('manage_users.php');
                            });
                        },
                        datatype: 'text'
                    });
                });
            });
        </script>
        
    </body>
</html>