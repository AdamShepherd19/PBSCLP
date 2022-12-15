
<?php
    // ============================================
    //     - PBSCLP | resource_bank_home
    //     - Adam Shepherd
    //     - PBSCLP
    //     - April 2022

    //     This file contains the page for a
    //     practitioner to view the courses available
    //     to them on the resource bank. They can
    //     select one which takes them to a list
    //     of sessions within
    // ============================================
    session_start();

    if(!isset($_SESSION['logged_in'])){
        header('Location: https://pbsclp.info/');
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
        <link rel="stylesheet" href="../stylesheets/resource_bank.css">

        <title>Resource Bank</title>
        
    </head>

    <body>

        <div id="pbs-nav-bar">
            <?php
                include "../common/nav-bar.php";
            ?>
        </div>

        <div class="page-header">
            <h1>Resource Bank</h1>
        </div>

        <div class="main-content">
            <div class="button-wrapper">
                <input type="button" id="new-course-button" class="pbs-button pbs-button-green admin-only" value="New Course">
            </div>

            <div class="inner-wrapper">

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

                $("#new-course-button").on('click', function() {
                    window.location.href = 'add_new_course.php';
                });
                
                $.ajax({
                    url: '../scripts/get_course_summary.php',
                    type: 'get',
                    dataType: 'JSON',
                    success: function(response) {
                        if (response.includes("*warning_no_courses_found*")) {
                            var message = "<div class='card'><h4 class='card-header'> There is no course content yet!</div>"

                            $(".inner-wrapper").html(message);
                        } else if(response.includes("*no_courses_assigned_to_user*")) {
                            var message = "<div class='card'><h4 class='card-header'> You have not been assigned any courses yet.</div>"

                            $(".inner-wrapper").html(message);
                        } else {
                            for(var x = 0; x < response.length; x++) {

                                var message = '<div class="course-card card" id=cid-' + response[x].course_id + '>' +
                                    '<div class="card-header">' + response[x].name + '</div>' +
                                    '<div class="card-body">' +
                                        '<p>' + response[x].description + '</p>' +
                                    '</div></div>';

                                $(".inner-wrapper").append(message);
                            }
                            $(document).on("click", ".course-card" , function() {
                                var contentPanelId = jQuery(this).attr("id");
                                var course_id = contentPanelId.split(/[-]+/).pop();
                                window.location.href = 'all_sessions.php?cid=' + course_id;
                                // alert(contentPanelId);
                            });
                        }

                    }
                });
                
            });
        </script>
        
    </body>
</html>