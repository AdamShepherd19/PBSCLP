<?php
    session_start();

    if(!isset($_SESSION['logged_in'])){
        header('Location: login.php');
        exit();
    }

    $pass = file_get_contents('../pass.txt', true);

    //connect to database
    $connection = new mysqli('localhost', 'pbsclp', $pass, 'pbsclp_pbsclp');

    //check db connection
    if ($connection->connect_error) {
        exit("Connection failed: " . $connection->connect_error);
    }

    //perform query
    $query = "SELECT * FROM `announcements`";
    $result = $connection->query($query);

    $data = array();

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            echo "- Title: " . $row["title"]. "<br>- Content: " . $row["content"]. "<br>- Author" . $row["author"]. "<br><br>";
            $data[] = $row;
            echo json_encode($data));
        }
    } else {
        echo "0 results";
    }

    $connection->close();

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
        <script src="includes/jquery.js"></script>

        <link rel="stylesheet" href="stylesheets/style.css">
        <link rel="stylesheet" href="stylesheets/landing.css">

        <title>Landing Page</title>
        
    </head>

    <body>

        <div id="pbs-nav-bar"></div>

        <h1 class="page-header">Announcements</h1>

        <div class="main-content">
            <div class="left-space"></div>

            <div class="announcement-wrapper" id="announcement-wrapper">

                <div class="card">
                    <div class="card-header">
                        <span class="announcement-header">Announcement 1 </span>
                    </div>
                    <div class="card-body">
                        <blockquote class="blockquote mb-0">
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                        <footer class="blockquote-footer">Firstname Surname</footer>
                        </blockquote>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <span class="announcement-header">Announcement 2 </span>
                    </div>
                    <div class="card-body">
                        <blockquote class="blockquote mb-0">
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                        <footer class="blockquote-footer">Firstname Surname</footer>
                        </blockquote>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <span class="announcement-header">Announcement 3 </span>
                    </div>
                    <div class="card-body">
                        <blockquote class="blockquote mb-0">
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                        <footer class="blockquote-footer">Firstname Surname</footer>
                        </blockquote>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                    <span class="announcement-header">Announcement 4 </span>
                    </div>
                    <div class="card-body">
                        <blockquote class="blockquote mb-0">
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                        <footer class="blockquote-footer">Firstname Surname</footer>
                        </blockquote>
                    </div>
                </div>
            </div>

            <div class="landing-nav-box-wrapper">
                <input type="button" id="forum" class="pbs-button pbs-button-white" value="Forum"> <br />
                <input type="button" id="resource-bank" class="pbs-button pbs-button-blue" value="Resource Bank"> <br />
                <input type="button" id="profile" class="pbs-button pbs-button-grey" value="Profile"> <br />
            </div>

        </div>
        

        <script type="text/javascript">
            $(document).ready(function () {

                // var current_title = $(document).attr('title');
                // $(".current-nav").removeClass("current-nav");
                // if(current_title == "Landing Page") {
                //     $("#nav-landing").addClass("current-nav");
                // }

                $(function(){
                    $("#pbs-nav-bar").load("nav-bar.html"); 
                });

                $("#forum").on('click', function(){
                    window.location.replace('forum.php');
                });

                $("#resource-bank").on('click', function(){
                    window.location.replace('resource_bank.php');
                });

                $("#profile").on('click', function(){
                    window.location.replace('profile.php');
                });

                // ==================================================
                // https://www.youtube.com/watch?v=crtwSmleWMA&t=367s

                var ajax = new XMLHttpRequest();
                var method = "GET";
                var url = "landing.php";
                var asynchronous = true;

                ajax.open(method, url, asynchronous);
                ajax.send();

                ajax.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200){
                        // var data = JSON.parse(this.responseText);
                        // console.log(data);
                        console.log(this.responseText);
                    }
                }

                // ==================================================


            });
        </script>
            
    </body>
    
</html>