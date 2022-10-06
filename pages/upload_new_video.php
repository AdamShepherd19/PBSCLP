<?php
    // ============================================
    //     - PBSCLP | upload_new_video
    //     - Adam Shepherd
    //     - PBSCLP
    //     - April 2022

    //     This file contains the page for an admin
    //     to upload and name a new video (from 
    //     youtube) that will be added to the
    //     resource bank
    // ============================================
    session_start();

    if(!isset($_SESSION['logged_in'])){
        header('Location: localhost/PBSCLP/');
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
        <link rel="stylesheet" href="../stylesheets/new_announcement.css">

        <title>New Video</title>
        
    </head>

    <body>

        <div id="pbs-nav-bar">
            <?php
                include "../common/nav-bar.php";
            ?>
        </div>

        <div class="page-header">
            <h1>New Video</h1>
        </div>

        <div class="main-content">
            <div class="form-wrapper">
                <form>
                    <label for="name">Video Name: </label><br />
                    <input type="text" id="name" class="pbs-form-text-box" placeholder="Enter the video name..."><br /><br />

                    <label for="video-link">Video Link (url): </label><br />
                    <input type="text" id="video-link" class="pbs-form-text-box" placeholder="Enter the url of the video..."><br /><br />
                    
                    <div class="button-wrapper">
                        <input type="button" id="video-cancel" class="pbs-button pbs-button-red" value="Cancel"> 
                        <input type="button" id="video-create" class="pbs-button pbs-button-green" value="Create">
                    </div>

                </form>
            </div>

        </div>

        
        <script type="text/javascript">
            $(document).ready(function () {

                var session_id = "<?php echo $_GET['sid']; ?>"
                
                //onclick function for the cancel button
                $("#video-cancel").on('click', function(){
                    window.location.replace('resource_bank_home.php');
                });

                // onclick function for the post announcement button
                $("#video-create").on('click', function(){

                    var video_name = $("#name").val();
                    var video_link = $("#video-link").val();

                    //check data not empty
                    if(video_name == "" || video_link == ""){
                        //prompt user to fill in all data
                        alert("Please fill out the information in the form");
                    } else {
                        //send data to php
                        $.ajax({
                            method: 'POST',
                            url: "../scripts/upload_video.php",
                            data: {
                                video_namePHP: video_name,
                                video_linkPHP: video_link,
                                session_idPHP: session_id
                            },
                            success: function (response) {
                                //check if the php execution was successful and the data was added to the db
                                if (response.includes("*video_uploaded_successfully*")){
                                    //replace html with success message and button to return to landing page
                                    var successHTML = "<h3>Your video has been uploaded succesfully. Please click the button below to return to the resource bank.</h3><br> " +
                                        "<input type='button' id='return' class='pbs-button pbs-button-green' value='Confirm'>";

                                    $('.main-content').html(successHTML);
                                } else if (response.includes("*warning_video_already_exists*")){
                                    alert("A file with this name already exists. Please enter a different name.");
                                } else if (response.includes("*video_upload_failed*")){
                                    alert("There was an error uploading your video. Please try again or contact a system administrator.");
                                } else {
                                    //display error message if the php could not be executed
                                    $('.main-content').html("<h3> There was an error processing your request. Please try again </h3><br>Error: " + response +
                                        "<br><input type='button' id='return' class='pbs-button pbs-button-green' value='Confirm'>");
                                }

                                // onclick function for new button to return to landing page
                                $("#return").on('click', function(){
                                    window.location.replace('resource_bank_home.php');
                                });
                            },
                            datatype: 'text'
                        });
                    };
                });

                // only show administrator content if an admin logged in
                var accountType = '<?php echo $_SESSION['account_type']; ?>';
                if (accountType != 'administrator') {
                    $('.admin-only').hide();
                } else {
                    $('.admin-only').show();
                }
            });
        </script>
        
    </body>
</html>