<?php
    session_start();

    if(!isset($_SESSION['logged_in'])){
        header('Location: https://pbsclp.info');
        exit();
    }

    $pass = file_get_contents('../../pass.txt', true);
    echo "test 1";
    if(isset($_POST['posted'])) {
        //connect to database
        $connection = new mysqli('localhost', 'pbsclp', $pass, 'pbsclp_pbsclp');

        //check db connection
        if ($connection->connect_error) {
            exit("Connection failed: " . $connection->connect_error);
        }

        echo "test2";

        //retrieve email and password from form
        $title = $_POST['titlePHP'];
        $content = $_POST['contentPHP'];
        $author = "adam shep";

        echo $title;
        echo $content;
        
        //query db for user login details provided
        // $query = "INSERT INTO `announcements` (`announcement_id`, `title`, `content`, `author`) VALUES (NULL, `" . $title . "`, `" . $content . "`, `" . $author . "`);";
        $query = "INSERT INTO announcements (title, content, author) VALUES ('testTitle2', 'testContent2', 'testAuthor2');";
        //check if login details provided match a user profile in the db
        if ($connection->query($query) === TRUE) {
            echo "test3";
            exit('success');
        } else {
            echo "test4";
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

        <title>TITLE</title>
        
    </head>

    <body>

        <div id="pbs-nav-bar"></div>

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
                $(function(){
                    $("#pbs-nav-bar").load("../common/nav-bar.html"); 
                });

                $("#announcement-cancel").on('click', function(){
                    window.location.replace('landing.php');
                });

                $("#announcement-post").on('click', function(){
                    var title = $("#title").val();
                    var content = $('#content').val();

                    if(title == "" || content == ""){
                        alert("Please fill out the information in the form");
                    } else {
                        alert("This works 1");
                        $.ajax({
                            method: 'POST',
                            url: "new_announcement.php",
                            data: {
                                posted: 1,
                                titlePHP: title,
                                contentPHP: content
                            },
                            success: function (response) {
                                alert("This works");
                                alert(response);
                                // $('#main-content').html(response);

                                // if (response.includes("success")){
                                //     var successHTML = "<h3>Your post was created succesfully. Please click the button below to return to the landing page.<br> " +
                                //         '<input type="button" id="return" class="pbs-button pbs-button-green" value="Confirm">';

                                //     $('#main-content').html(successHTML);
                                // } else {
                                //     $('#main-content').html("<h3> There was an error processing your request. Please try again </h3><br>Error" + response);
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