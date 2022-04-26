<!--
    ============================================
        - PBSCLP | amend_posts
        - Adam Shepherd
        - PBSCLP
        - April 2022

        This file contains the page that displays
        a list of posts that are ready to be
        amended (feedback has been given)
    ============================================
-->

<?php
    session_start();

    // check user us logged in
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

        <!-- links to stylesheets -->
        <link rel="stylesheet" href="../stylesheets/style.css">
        <link rel="stylesheet" href="../stylesheets/forum.css">

        <title>Amend Posts</title>
        
    </head>

    <body>

        <!-- import nav bar -->
        <div id="pbs-nav-bar">
            <?php
                include "../common/nav-bar.php";
            ?>
        </div>

        <!-- page header -->
        <div class="page-header">
            <h1>Amend Posts</h1>
        </div>

        <div class="main-content">
            <div class="inner-wrapper">
                <!-- list of posts here -->
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

                // get list of forum posts that have had feedback provided and are ready to be amended
                $.ajax({
                    url: '../scripts/get_forum_posts.php',
                    type: 'get',
                    dataType: 'JSON',
                    data: {
                        approvedPHP: '0', //post not already reviewed
                        feedback_providedPHP: '1', //feedback provided: true
                        review_posts: '1' //review posts: true
                    },
                    success: function(response) {
                        if (response.includes("*warning_no_posts_found*")) {
                            // message if no posts need amended
                            var message = "<div class='card'><h4 class='card-header'> There are no posts that need reviewed!</div>"

                            $(".inner-wrapper").append(message);
                        } else {
                            // display posts that are available to be amended
                            for(var x = 0; x < response.length; x++) {
                                var message = '<div class="forum-post card" id="thread-id-' + response[x].thread_id + '">' +
                                    '<div class="card-header">' + response[x].title + '<br><span><i> - ' + response[x].firstname + ' ' + response[x].lastname + '</i></span>' + '</div>' +
                                    '<div class="card-body">' +
                                        '<p>' + response[x].content + '</p>' +
                                    '</div></div><br>';

                                $(".inner-wrapper").append(message);
                            }

                            // navigate to individual amend post page if post is clicked on
                            $(document).on("click", ".forum-post" , function() {
                                var contentPanelId = jQuery(this).attr("id");
                                var thread_id = contentPanelId.split(/[-]+/).pop();
                                window.location.replace('amend_individual_post.php?threadId=' + thread_id);
                            });
                        }

                    }
                });
            });
        </script>
        
    </body>
</html>