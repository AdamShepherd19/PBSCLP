<?php
    session_start();

    if(!isset($_SESSION['logged_in'])){
        header('Location: https://pbsclp.info');
        exit();
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
        <link rel="stylesheet" href="../stylesheets/landing.css">

        <title>Landing Page</title>
        
    </head>

    <body>

        <div id="pbs-nav-bar"></div>

        <h1 class="page-header">Announcements</h1>

        <div class="main-content">
            <div class="left-space"></div>

            <div class="announcement-wrapper" id="announcement-wrapper">
                <!-- 
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
                </div> -->
            </div>

            <div class="landing-nav-box-wrapper">
                <input type="button" id="new-announcement" class="pbs-button pbs-button-blue" value="New Announcement"> <br/>
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
                    $("#pbs-nav-bar").load("../common/nav-bar.html"); 
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

                $("#new-announcement").on('click', function(){
                    window.location.replace('new_announcement.php');
                });

                //https://makitweb.com/return-json-response-ajax-using-jquery-php/
                $.ajax({
                    url: '../scripts/get_announcements.php',
                    type: 'get',
                    dataType: 'JSON',
                    success: function(response) {
                        if (response.includes("no announcements")) {
                            $("#announcement-wrapper").hide();
                            $(".left-space").after("<h3 style='display: flex; flex-direction: row; align-items: center;'> There are no announcements yet! </h3>");
                            // $("#announcement-wrapper").html("<h3> There are no announcements yet! </h3>");
                        } else {
                            for(var x = 0; x < response.length; x++) {
                                var announcement = "<div class='card'>" +
                                "<div class='card-header'>" +
                                    "<span class='announcement-header'>" + response[x].title + "</span></div>" +
                                "<div class='card-body'>" +
                                    "<blockquote class='blockquote mb-0'>" +
                                        "<p>" + response[x].content + "</p>" +
                                        "<footer class='blockquote-footer'>" + response[x].author + "</footer>" +
                                    "</blockquote>" +
                                "</div></div>";

                                $("#announcement-wrapper").append(announcement);
                            }
                        }

                    }
                });

                var accountType = '<?php echo $_SESSION['account_type']; ?>';
                if (accountType != 'administrator') {
                    $('#new-announcement').hide();
                } else {
                    $('#new-announcement').show();
                }
            });
        </script>
            
    </body>
    
</html>