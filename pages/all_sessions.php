<?php
    // ============================================
    //     - PBSCLP | all_sessions
    //     - Adam Shepherd
    //     - PBSCLP
    //     - April 2022

    //     This file contains the page which shows
    //     a list of all the sessions within a 
    //     course within the resource bank. Users
    //     can select a course and view the material
    //     within
    // ============================================

    session_start();

    //check for logged in user
    if(!isset($_SESSION['logged_in'])){
        header('Location: localhost/PBSCLP/');
        exit();
    }

    function getCourseName($courseId) {

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
        $sql = "SELECT name FROM courses WHERE course_id=? LIMIT 1";
        $stmt = $connectionPDO->prepare($sql);
        $stmt->execute([$courseId]);
        $result = $stmt->fetch();


        if ($result){
            $courseName = $result[0];
        }


        // close connection to db
        $stmt = null;
        $connectionPDO = null;

        return $courseName;
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

        <!-- links to stylesheets -->
        <link rel="stylesheet" href="../stylesheets/style.css">
        <link rel="stylesheet" href="../stylesheets/resource_bank.css">

        <title>Resource Bank</title>
        
    </head>

    <body>

        <!-- import nav bar -->
        <div id="pbs-nav-bar">
            <?php
                include "../common/nav-bar.php";
            ?>
        </div>

        <!-- page heading -->
        <div class="page-header">
            <h1>Resource Bank</h1> <br>
        </div>

        <!-- page subheading (course name) -->
        <div class="page-subheader">
            <h3 id='course-subheading'></h3>
        </div>

        <div class="main-content">

            <!-- add new session button if user is an admin -->
            <div class="button-wrapper">
                <input type="button" id="new-session-button" class="pbs-button pbs-button-green admin-only" value="New Session">
            </div>

            <div class="inner-wrapper">

                <!-- session class structure -->
                <!-- <div class="course-card card">
                    <div class="card-header">Course Name Here</div>
                    <div class="card-body">
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                    </div>
                </div> -->

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

                // fetch course id from PHP and return to javascript variable
                var course_id = "<?php echo $_GET['cid']; ?>";
                var course_name = "<?php echo getCourseName($_GET['cid']); ?>";

                // navigate to new session page on button click
                $("#new-session-button").on('click', function() {
                    window.location.href = 'add_new_session.php?cid=' + course_id;
                });

                // set page subheading to course id (change to course name)
                $("#course-subheading").html(course_name);

                // get list of all sessions and display as cards
                $.ajax({
                    url: '../scripts/get_session_summary.php',
                    type: 'post',
                    dataType: 'JSON',
                    data: {
                        //pass through course id to select sessions for that course
                        course_idPHP: course_id
                    },
                    success: function(response) {
                        //display message if no sessions found
                        if (response.includes("*warning_no_sessions_found*")) {
                            var message = "<div class='card'><h4 class='card-header'> There is no course content yet!</div>"

                            $(".inner-wrapper").html(message);
                        } else if(response.includes("*user_not_authorised_on_this_course*")) {
                            //display error message if user not assigned the relevant course to view this session
                            var message = "<div class='card'><h4 class='card-header'> You are not authorised to access this course material.</div>"

                            $(".inner-wrapper").html(message);
                        } else {
                            //display a list of courses to the DOM and display on page in card form
                            for(var x = 0; x < response.length; x++) {

                                var message = '<div class="session-card card" id=sid-' + response[x].session_id + '>' +
                                    '<div class="card-header">' + response[x].name + '</div>' +
                                    '<div class="card-body">' +
                                        '<p>' + response[x].description + '</p>' +
                                    '</div></div>';

                                $(".inner-wrapper").append(message);
                            }

                            //functionality to click on a session card and navigate to individual session page to view material
                            $(document).on("click", ".session-card" , function() {
                                var contentPanelId = jQuery(this).attr("id");
                                var session_id = contentPanelId.split(/[-]+/).pop();
                                window.location.href = 'individual_session.php?sid=' + session_id;
                            });
                        }

                    }
                });
                
            });
        </script>
        
    </body>
</html>