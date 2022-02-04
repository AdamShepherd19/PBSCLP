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

    $pass = file_get_contents('../../pass.txt', true);
    
    if(isset($_POST['titlePHP'])) {
        //connect to database
        $connection = new mysqli('localhost', 'pbsclp', $pass, 'pbsclp_pbsclp');

        //check db connection
        if ($connection->connect_error) {
            exit("Connection failed: " . $connection->connect_error);
        }

        //retrieve title, content and author for the new post
        $title = $_POST['titlePHP'];
        $content = $_POST['contentPHP'];
        $author = $_SESSION['firstname'] . " " . $_SESSION['lastname'];

        // query database and insert the new announcement into the announcements table
        $query = "INSERT INTO announcements (title, content, author) VALUES ('" . $title . "', '" . $content . "', '" . $author . "');";
        
        //check to see if the insert was successful
        if ($connection->query($query) === TRUE) {
            exit('success');
        } else {
            exit('Error: ' . $connection->error);
        }

        $connection->close();

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
        <link rel="stylesheet" href="../stylesheets/new_announcement.css">

        <title>New Announcement</title>
        
    </head>

    <body>

        <div id="pbs-nav-bar">
            <?php
                include "../common/nav-bar.php";
            ?>
        </div>

        <div class="page-header">
            <h1>New Announcement</h1>
        </div>

        <div class="main-content">
            <div class="form-wrapper">
                <form action="login.php" method="post">
                    <label for="title">Title: </label><br />
                    <input type="text" id="title" class="pbs-form-text-box" placeholder="Enter post title..."><br /><br />
                    <label for="content">Content: </label><br />
                    <textarea id="content" class="pbs-form-text-box" placeholder="Enter post content..."></textarea><br />
                    
                    <div class="button-wrapper">
                        <input type="button" id="announcement-cancel" class="pbs-button pbs-button-red" value="Cancel"> 
                        <input type="button" id="announcement-post" class="pbs-button pbs-button-green" value="Post">
                    </div>
                </form>
            </div>

        </div>

        
        <script type="text/javascript">
            $(document).ready(function () {
                
                //onclick function for the cancel button
                $("#announcement-cancel").on('click', function(){
                    window.location.replace('landing.php');
                });

                // onclick function for the post announcement button
                $("#announcement-post").on('click', function(){
                    //retrieve data from form
                    var title = $("#title").val();
                    var content = $('#content').val();

                    //check data not empty
                    if(title == "" || content == ""){
                        //prompt user to fill in all data
                        alert("Please fill out the information in the form");
                    } else {
                        //send data to php
                        $.ajax({
                            method: 'POST',
                            url: "new_announcement.php",
                            data: {
                                titlePHP: title,
                                contentPHP: content
                            },
                            success: function (response) {
                                //check if the php execution was successful and the data was added to the db
                                if (response.includes("success")){
                                    //replace html with success message and button to return to landing page
                                    var successHTML = "<h3>Your post was created succesfully. Please click the button below to return to the landing page.</h3><br> " +
                                        "<input type='button' id='return' class='pbs-button pbs-button-green' value='Confirm'>";

                                    $('.main-content').html(successHTML);

                                    // onclick function for new button to return to landing page
                                    $("#return").on('click', function(){
                                        window.location.replace('landing.php');
                                    });

                                } else {
                                    //display error message if the php could not be executed
                                    $('.main-content').html("<h3> There was an error processing your request. Please try again </h3><br>Error" + response);
                                }
                            },
                            datatype: 'text'
                        });
                    };
                });

                // only show administrator content if an admin logged in
                var accountType = '<?php echo $_SESSION['account_type']; ?>';
                if (accountType != 'administrator') {
                    $('.admin-only').hide();
                } else {
                    $('.admin-only').show();
                }
            });
        </script>
        
    </body>
</html>