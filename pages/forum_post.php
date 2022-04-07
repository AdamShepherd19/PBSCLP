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
        <link rel="stylesheet" href="../stylesheets/forum_post.css">

        <title>Forum Post</title>
        
    </head>

    <body>

        <div id="pbs-nav-bar">
            <?php
                include "../common/nav-bar.php";
            ?>
        </div>

        <div class="page-header">
            <h1>Forum Post</h1>
        </div>

        <div class="main-content">
            <div id='post-section'>
                <!-- post here -->
            </div>

            <div id='new-comment-section'>
                <!-- new comment section here -->
                <form>
                    <h4>New Comment: </h4><br />
                    <textarea id="comment-box" class="pbs-form-text-box" placeholder="Enter comment..."></textarea><br />
                    
                    <div class="button-wrapper new-comment-button-wrapper">
                        <input type="button" id="new-comment-submit" class="pbs-button pbs-button-green" value="Submit">
                    </div>
                </form>
            </div>

            <br>

            <div id='comment-section'>
                <!-- comments here -->
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

                var comments = false;

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
                            $('#comment-section').hide();
                        } else {
                            for(var x = 0; x < response.length; x++) {
                                var post = '<div class="forum-post card" id="thread-id-' + response[x].thread_id + '">' +
                                    '<div class="card-header">' + response[x].title + '<br><span class="post-name"><i> - ' + response[x].firstname + ' ' + response[x].lastname + '</i></span>' + '</div>' +
                                    '<div class="card-body">' +
                                        '<p>' + response[x].content + '</p>' +
                                    '</div></div><br>';

                                $('#post-section').html(post);
                            }

                            $.ajax({
                                url: '../scripts/get_comments.php',
                                type: 'get',
                                dataType: 'JSON',
                                data: {
                                    threadIDPHP: thread_id
                                },
                                success: function(response) {
                                    if (response.includes("*warning_no_comments_found*")) {
                                        var announcement = "<br><h2>There are no comments on this post yet!</h2>";

                                        $('#comment-section').html(announcement);
                                    } else {
                                        for(var x = 0; x < response.length; x++) {
                                            var comment = '<div class="card">' +
                                                '<div class="card-body post-comment">' +
                                                    '<p class="comment-text">' + response[x].content + '</p>' +
                                                    '<span class="comment-subtext"><i>' + response[x].firstname  + ' ' + 
                                                    response[x].lastname + ' - ' + response[x].date + '</i></span>' +
                                                '</div></div><br>';

                                            $('#comment-section').append(comment);
                                            comments = true;
                                        }
                                    }

                                }
                            });
                        }
                    }
                });


                $("#new-comment-submit").on('click', function(){
                    var comment = $("#comment-box").val();
                    if(comment.length > 0) {
                        $.ajax({
                            url: '../scripts/new_comment.php',
                            type: 'POST',
                            data: {
                                threadIDPHP: thread_id,
                                commentPHP: comment
                            },
                            success: function(response) {
                                if (response.includes("*comment_created_succesfully*")) {
                                    var new_comment = '<div class="card">' +
                                        '<div class="card-body post-comment">' +
                                            '<p class="comment-text">' + comment + '</p>' +
                                            '<span class="comment-subtext"><i>' + '<?php echo $_SESSION['firstname']; ?>' + ' ' + 
                                            '<?php echo $_SESSION['lastname']; ?>' + ' - now' + '</i></span>' +
                                        '</div></div><br>';

                                    if (comments) {
                                        $('#comment-section').prepend(new_comment);
                                    } else {
                                        $('#comment-section').html(new_comment);
                                    }
                                    
                                } else {
                                    alert("There was an error creating your comment. Please try again.<br>" + response);
                                }

                            }
                        });
                    }
                });


            });
        </script>
        
    </body>
</html>