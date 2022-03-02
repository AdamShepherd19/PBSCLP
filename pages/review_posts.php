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
        <link rel="stylesheet" href="../stylesheets/forum.css">

        <title>New Posts</title>
        
    </head>

    <body>

        <div id="pbs-nav-bar">
            <?php
                include "../common/nav-bar.php";
            ?>
        </div>

        <div class="page-header">
            <h1>New Posts</h1>
        </div>

        <div class="main-content">
            <div class="forum-wrapper">

                <!-- <div class="forum-post card">
                    <div class="card-header">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</div>
                    <div class="card-body">
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
                        </p>
                        <span><i>Comments (x)</i></span>
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

                $.ajax({
                    url: '../scripts/get_forum_posts.php',
                    type: 'get',
                    dataType: 'JSON',
                    data: {
                        approvedPHP: '0'
                    },
                    success: function(response) {
                        if (response.includes("*warning_no_posts_found*")) {
                            var message = "<div class='card'><h4 class='card-header'> There are no posts that need reviewed!</div>"

                            $(".forum-wrapper").append(message);
                        } else {
                            for(var x = 0; x < response.length; x++) {
                                var message = '<div class="forum-post no-pointer-change card" id="thread-id-' + response[x].thread_id + '">' +
                                    '<div class="card-header">' + response[x].title + '<br><span><i> - ' + response[x].firstname + ' ' + response[x].lastname + '</i></span>' + '</div>' +
                                    '<div class="card-body">' +
                                        '<p>' + response[x].content + '</p>' +
                                        '<input type="button" id="review-post-' + response[x].thread_id + '" class="pbs-button pbs-button-orange review-button" value="Review">' +
                                        '<input type="button" id="approve-post-' + response[x].thread_id + '" class="pbs-button pbs-button-green approve-button" value="Approve">' +
                                    '</div></div><br>';

                                $(".forum-wrapper").append(message);
                            }
                            $(document).on("click", ".approve-button" , function() {
                                var contentPanelId = jQuery(this).attr("id");
                                var thread_id = contentPanelId.split(/[-]+/).pop();
                                // window.location.href = 'forum-post.php?threadId=' + thread_id;
                                prompt(contentPanelId);
                            });

                            $(document).on("click", ".review-button" , function() {
                                var contentPanelId = jQuery(this).attr("id");
                                var thread_id = contentPanelId.split(/[-]+/).pop();
                                // window.location.href = 'forum-post.php?threadId=' + thread_id;
                                prompt(contentPanelId);
                            });
                        }

                    }
                });
            });
        </script>
        
    </body>
</html>