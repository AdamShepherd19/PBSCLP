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
            <h1>Resource Bank</h1> <br>
        </div>

        <div class="page-subheader">
            <h3 id='session-subheading'></h3>
        </div>

        <div class="main-content">

            <div class="inner-wrapper">

                <!-- <div class="file-card card">
                    <div class="card-header">Course Name Here</div>
                    <a href="../resource_bank/course_1/session_1/handout_1_1.txt">
                        <div class="card-body">
                            <h5>filename.pdf</h5>
                        </div>
                    </a>
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

                var session_id = "<?php echo $_GET['sid']; ?>";

                $("#session-subheading").html(session_id);

                $.ajax({
                    url: '../scripts/get_files.php',
                    type: 'post',
                    dataType: 'JSON',
                    data: {
                        session_idPHP: session_id
                    },
                    success: function(response) {
                        if (response.includes("*warning_no_files_found*")) {
                            var message = "<div class='card'><h4 class='card-header'> There are no supporting files relating to this session yet!</div>"

                            $(".inner-wrapper").html(message);
                        } else {
                            for(var x = 0; x < response.length; x++) {

                                var message = '<div class="file-card card" id="fid-' + response[x].file_id + '">' +
                                    '<div class="card-body">' +
                                        '<h5>' + response[x].filename + '</h5>' +
                                    '</div></div>';

                                $(".inner-wrapper").append(message);
                            }
                            $(document).on("click", ".file-card" , function() {
                                var contentPanelId = jQuery(this).attr("id");
                                var file_id = contentPanelId.split(/[-]+/).pop();
                                // window.location.href = 'all_courses.php?courseId=' + course_id;
                                // alert(contentPanelId);

                                $.ajax({
                                    url: '../scripts/open_file.php',
                                    type: 'post',
                                    dataType: 'JSON',
                                    data: {
                                        file_idPHP: file_id
                                    },
                                    success: function(response) {
                                        if (response.includes("*warning_error_opening_file*")) {
                                            // var message = "<div class='card'><h4 class='card-header'> There are no supporting files relating to this session yet!</div>"
                                            alert("There was an error opening the file. Please try again or contact a system administrator.");
                                            // $(".inner-wrapper").html(message);
                                        } 
                                        // else {
                                        //     for(var x = 0; x < response.length; x++) {

                                        //         var message = '<div class="file-card card" id="fid-' + response[x].file_id + '">' +
                                        //             '<div class="card-body">' +
                                        //                 '<h5>' + response[x].filename + '</h5>' +
                                        //             '</div></div>';

                                        //         $(".inner-wrapper").append(message);
                                        //     }
                                        // }
                                    }
                                });
                            });
                        }

                    }
                });
                
            });
        </script>
        
    </body>
</html>