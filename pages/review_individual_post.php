<?php
    // ============================================
    //     - PBSCLP | review_individual_post
    //     - Adam Shepherd
    //     - PBSCLP
    //     - April 2022

    //     This file contains the page for an admin
    //     to review and leave feedback on a
    //     specific forum post, sending it back to
    //     the author to amend
    // ============================================

    session_start();

    if(!isset($_SESSION['logged_in'])){
        header('Location: https://pbsclp.info/');
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
        <link rel="stylesheet" href="../stylesheets/forum_post.css">

        <title>Review Forum Post</title>
        
    </head>

    <body>

        <div id="pbs-nav-bar">
            <?php
                include "../common/nav-bar.php";
            ?>
        </div>

        <div class="page-header">
            <h1>Review Forum Post</h1>
        </div>

        <div class="main-content">
            <div id='post-section'>
                <!-- post here -->
            </div>

            <div id='feedback-section'>
                <form>
                    <label for="feedback">Feedback: </label><br />
                    <textarea id="feedback" class="pbs-form-text-box text-area-large" placeholder="Enter feedback for author..."></textarea><br />
                </form>
            </div>

            <div class="button-wrapper">
                <input type="button" id="review-post-cancel" class="pbs-button pbs-button-red" value="Cancel"> 
                <input type="button" id="review-post-submit" class="pbs-button pbs-button-green" value="Submit">
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

                var thread_id = "<?php echo $_GET['threadId']; ?>";

                $.ajax({
                    url: '../scripts/get_single_post.php',
                    type: 'get',
                    dataType: 'JSON',
                    data: {
                        threadIDPHP: thread_id
                    },
                    success: function(response) {
                        if (response.includes("*warning_no_post_found*")) {
                            var announcement = "<br><h2>This post does not exist.</h2>";

                            $('#post-section').html(announcement);
                            $("#review-post-submit").hide();
                            $("#feedback-section").hide();
                        } else {
                            if (response[0].approved == '0'){
                                var post = '<div class="forum-post card" id="thread-id-' + response[0].thread_id + '">' +
                                    '<div class="card-header">' + response[0].title + '<br><span class="post-name"><i> - ' + response[0].firstname + ' ' + response[0].lastname + '</i></span>' + '</div>' +
                                    '<div class="card-body">' +
                                        '<p>' + response[0].content + '</p>' +
                                    '</div></div><br>';

                                $('#post-section').html(post);
                            } else {
                                $('#post-section').html("<br><h2>Warning: This post has already been approved.</h2>");
                                $("#feedback-section").hide();
                                $("#review-post-submit").hide();
                            }
                        }
                    }
                });

                $("#review-post-cancel").on('click', function(){
                    window.location.replace('review_posts.php');
                });

                $("#review-post-submit").on('click', function(){
                    var feedback = $('#feedback').val();

                    //send data to php
                    $.ajax({
                        method: 'POST',
                        url: "../scripts/send_feedback.php",
                        data: {
                            thread_idPHP: thread_id,
                            feedbackPHP: feedback
                        },
                        success: function (response) {
                            if(response.includes("*feedback_sent_successfully*")) {
                                $('#post-section').html("<br><h2>Feedback has been successfully sent back to the author for review. Please press the button below to return to the post reviewal page.</h2>");
                            } else {
                                $('#post-section').html("<br><h2>There was a problem sending your feedback. Please try again or contact a system administrator if the issue persists.</h2>");
                            }

                            $("#feedback-section").hide();
                            $("#review-post-submit").hide();
                            $("#review-post-cancel").val("Return");
                            $("#review-post-cancel").removeClass("pbs-button-red");
                            $("#review-post-cancel").addClass("pbs-button-green");
                        },
                        datatype: 'text'
                    });
                });
            });
        </script>
        
    </body>
</html>