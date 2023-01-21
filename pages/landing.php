<?php
// ============================================
//     - PBSCLP | landing
//     - Adam Shepherd
//     - PBSCLP
//     - April 2022

//     This file contains the landing page of
//     the platform. This is the home page where
//     the rest of the platform can be navigated
//     to and this page shows a list of
//     announcements to the practitioners
// ============================================

session_start();

if (!isset($_SESSION['logged_in'])) {
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
    <link rel="stylesheet" href="../stylesheets/landing.css">

    <title>Landing Page</title>

</head>

<body>

    <div id="pbs-nav-bar">
        <?php
        include "../common/nav-bar.php";
        ?>
    </div>

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
            <input type="button" id="review-forum-posts"
                class="pbs-button pbs-button-orange admin-only landing-nav-box-button" value="New Forum Posts">
            <input type="button" id="amend-posts" class="pbs-button pbs-button-orange landing-nav-box-button"
                value="Amend Posts">
            <input type="button" id="new-announcement"
                class="pbs-button pbs-button-blue admin-only landing-nav-box-button" value="New Announcement">
            <input type="button" id="forum" class="pbs-button pbs-button-white landing-nav-box-button" value="Forum">
            <input type="button" id="resource-bank" class="pbs-button pbs-button-blue landing-nav-box-button"
                value="Resource Bank">
            <input type="button" id="profile" class="pbs-button pbs-button-grey landing-nav-box-button" value="Profile">
        </div>

    </div>


    <script type="text/javascript">

        $(document).ready(function () {
            var title_size;

            $("#review-forum-posts").on('click', function () {
                window.location.href = 'review_posts.php';
            });

            $("#amend-posts").on('click', function () {
                window.location.href = 'amend_posts.php';
            });

            $("#forum").on('click', function () {
                window.location.href = 'forum.php';
            });

            $("#resource-bank").on('click', function () {
                window.location.href = 'resource_bank_home.php';
            });

            $("#profile").on('click', function () {
                window.location.href = 'profile.php';
            });

            $("#new-announcement").on('click', function () {
                window.location.href = 'new_announcement.php';
            });

            var account_type = "<?php print($_SESSION['account_type']); ?>";

            //https://makitweb.com/return-json-response-ajax-using-jquery-php/
            $.ajax({
                url: '../scripts/get_announcements.php',
                type: 'get',
                dataType: 'JSON',
                success: function (response) {
                    if (response.includes("*warning_no_announcements_found*")) {
                        var announcement = "<div class='card'>" +
                            "<div class='card-header'>" +
                            "<span class='announcement-header'> There are no announcements! </span></div>" +
                            "</div>";

                        $("#announcement-wrapper").append(announcement);
                    } else {
                        for (var x = 0; x < response.length; x++) {
                            var announcement;

                            if (response[x].link != null) {
                                announcement = "<div class='card'>" +
                                    "<div class='card-header'>" +
                                    " <span class='announcement-header'><div class='row'>" +
                                    "<div class='col-" + title_size + "'>" + response[x].title + " </div>" +
                                    "<div class='col-2'>" +
                                    "<a href='" + response[x].link + "' target='_blank' rel='noopener noreferrer'>" +
                                    "<input type='button' id='view-announcement-link' class='pbs-button pbs-button-blue' value='View Link'></a>" +
                                    "</div>" +
                                    "<div class='col-2 admin-only'> <input type='button' id='edit-announcement-" + response[x].id + "' class='pbs-button pbs-button-yellow edit-announcement' value='Edit'> </div>" +
                                    "</div></span>" +
                                    "</div>" +
                                    "<div class='card-body'>" +
                                    "<blockquote class='blockquote mb-0'>" +
                                    "<p>" + response[x].content + "</p>" +
                                    "<footer class='blockquote-footer'>" + response[x].firstname + " " + response[x].lastname + "</footer>" +
                                    "</blockquote>" +
                                    "</div></div>";
                            } else {
                                announcement = "<div class='card'>" +
                                    "<div class='card-header'>" +
                                    " <span class='announcement-header'><div class='row'>" +
                                    "<div class='col-" + (title_size+2) + "'>" + response[x].title + " </div>" +
                                    "<div class='col-2 admin-only'> <input type='button' id='edit-announcement-" + response[x].id + "' class='pbs-button pbs-button-yellow edit-announcement ' value='Edit'> </div>" +
                                    "</div></span>" +
                                    "</div>" +
                                    "<div class='card-body'>" +
                                    "<blockquote class='blockquote mb-0'>" +
                                    "<p>" + response[x].content + "</p>" +
                                    "<footer class='blockquote-footer'>" + response[x].firstname + " " + response[x].lastname + "</footer>" +
                                    "</blockquote>" +
                                    "</div></div>";

                            }
                            $("#announcement-wrapper").append(announcement);
                        }
                    }

                }
            }).then(() => {
                // only show administrator content if an admin logged in
                var accountType = '<?php echo $_SESSION['account_type']; ?>';
                if (accountType != 'administrator') {
                    $('.admin-only').hide();
                } else {
                    $('.admin-only').show();
                }
            });

            $.ajax({
                url: '../scripts/get_forum_posts.php',
                type: 'get',
                dataType: 'JSON',
                data: {
                    approvedPHP: '0',
                    feedback_providedPHP: '0'
                },
                success: function (response) {
                    if (response.includes("*warning_no_posts_found*")) {
                        $('#review-forum-posts').hide();
                    } else {
                        var number_of_new_posts = response.length;
                        $('#review-forum-posts').val('New Posts (' + number_of_new_posts + ')');
                    }
                }
            });

            $.ajax({
                url: '../scripts/get_forum_posts.php',
                type: 'get',
                dataType: 'JSON',
                data: {
                    approvedPHP: '0',
                    feedback_providedPHP: '1',
                    review_posts: '1'
                },
                success: function (response) {
                    if (response.includes("*warning_no_posts_found*")) {
                        $('#amend-posts').hide();
                    } else {
                        var number_of_new_posts = response.length;
                        $('#amend-posts').val('Amend Posts (' + number_of_new_posts + ')');
                    }
                }
            });

            $(document).on("click", ".edit-announcement", function () {
                var contentPanelId = jQuery(this).attr("id");
                var announcement_id = contentPanelId.split(/[-]+/).pop();
                // alert(contentPanelId);
                window.location.href = 'new_announcement.php?aid=' + announcement_id;
            })

            // only show administrator content if an admin logged in
            var accountType = '<?php echo $_SESSION['account_type']; ?>';
            if (accountType != 'administrator') {
                $('.admin-only').hide();
                title_size=9;
            } else {
                $('.admin-only').show();
                title_size=7;
            }
        });
    </script>

</body>

</html>