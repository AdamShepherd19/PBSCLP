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
        <link rel="stylesheet" href="../stylesheets/new_announcement.css">

        <title>New File</title>
        
    </head>

    <body>

        <div id="pbs-nav-bar">
            <?php
                include "../common/nav-bar.php";
            ?>
        </div>

        <div class="page-header">
            <h1>New File</h1>
        </div>

        <div class="main-content">
            <div class="form-wrapper">
                <form>
                    <label for="name">File Name: </label><br />
                    <input type="text" id="name" class="pbs-form-text-box" placeholder="Enter the course name..."><br /><br />
                    <!-- <label for="description">Description: </label><br />
                    <textarea id="description" class="pbs-form-text-box" placeholder="Enter course description..."></textarea><br /> -->
                        <!-- <form action="upload.php" method="post" enctype="multipart/form-data"> -->
                    <label for="file-to-upload"> Select file to upload: </label> <br>
                    <input type="file" name="file-to-upload" id="file-to-upload">
                        <!-- <input type="submit" value="Upload Image" name="submit"> -->
                        <!-- </form> -->
                    <div class="button-wrapper">
                        <input type="button" id="file-cancel" class="pbs-button pbs-button-red" value="Cancel"> 
                        <input type="button" id="file-create" class="pbs-button pbs-button-green" value="Create">
                    </div>
                </form>
            </div>

        </div>

        
        <script type="text/javascript">
            $(document).ready(function () {

                var session_id = "<?php echo $_GET['sid']; ?>"
                
                //onclick function for the cancel button
                $("#file-cancel").on('click', function(){
                    window.location.replace('resource_bank_home.php');
                });

                // onclick function for the post announcement button
                $("#file-create").on('click', function(){

                    var files = $('#file-to-upload')[0].files;
                    var file_name = $("#name").val();
                    var form_data = new FormData();                  
                    form_data.append('file',files[0]);
                    form_data.append('filename', file_name);
                    form_data.append('session_id', session_id);


                    //check data not empty
                    if(file_name == ""){
                        //prompt user to fill in all data
                        alert("Please fill out the information in the form");
                    } else {
                        //send data to php
                        $.ajax({
                            method: 'POST',
                            url: "../scripts/upload_file.php",
                            processData: false,
                            contentType: false,
                            data: form_data,
                            success: function (response) {
                                //check if the php execution was successful and the data was added to the db
                                if (response.includes("*file_uploaded_successfully*")){
                                    //replace html with success message and button to return to landing page
                                    var successHTML = "<h3>Your file has been uploaded succesfully. Please click the button below to return to the resource bank.</h3><br> " +
                                        "<input type='button' id='return' class='pbs-button pbs-button-green' value='Confirm'>";

                                    $('.main-content').html(successHTML);
                                } else if (response.includes("*warning_file_already_exists*")){
                                    alert("A file with this name already exists. Please enter a different name.");
                                } else if (response.includes("*filetype_not_supported*")){
                                    alert("This filetype is not supported at the moment. Please ensure the file extension is in the name and if the error persists, contact a system administrator.");
                                } else if (response.includes("*file_upload_failed*")){
                                    alert("There was an error uploading your file. Please try again or contact a system administrator.");
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