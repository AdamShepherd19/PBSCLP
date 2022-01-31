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
        <link rel="stylesheet" href="../stylesheets/profile.css">

        <title>Profile</title>
        
    </head>

    <body>

        <div id="pbs-nav-bar"></div>

        <h1 class="page-header">Profile</h1>

        <div class="main-content">
            <div class="profile-wrapper">
              
                <table>
                    <tr>
                        <td class="caption">Name:</td>
                        <td id="name"></td>
                    </tr>
                    
                    <tr>
                        <td class="caption">Email Address:</td>
                        <td id="email-address"></td>
                    </tr>
                    
                    <tr>
                        <td class="caption">Contact Number:</td>
                        <td id="contact-number"></td>
                    </tr>
                    
                    <tr>
                        <td class="caption">Organisation:</td>
                        <td id="organisation"></td>
                    </tr>
                    
                    <tr>
                        <td></td>
                        <td id="buttons">
                            <input type="button" id="edit-profile" class="pbs-button pbs-button-green table-button" value="Edit">
                            <input type="button" id="cancel-profile" class="pbs-button pbs-button-red" value="Cancel">
                            <input type="button" id="save-profile" class="pbs-button pbs-button-green" value="Save">
                        </td>
                    </tr>
                </table>
              
            </div>
        </div>



        <script type="text/javascript">
            $(document).ready(function () {
                var name, email, contact_number, organisation;

                $(function(){
                    $("#pbs-nav-bar").load("../common/nav-bar.html"); 
                });

                // hide save and cancel edit profile buttons
                $("#cancel-profile").hide();
                $("#save-profile").hide();

                $("#edit-profile").on('click', function(){
                    $("#edit-profile").hide();
                    $("#cancel-profile").show();
                    $("#save-profile").show();
                    
                    $("#name").html('<input type="text" id="new-name" class="pbs-form-text-box" value="' + name + '"/>');
                    $("#email-address").html('<input type="text" id="new-email" class="pbs-form-text-box" value="' + email + '"/>');
                    $("#contact-number").html('<input type="text" id="new-contact-number" class="pbs-form-text-box" value="' + contact_number + '"/>');
                    $("#organisation").html('<input type="text" id="new-organisation" class="pbs-form-text-box" value="' + organisation + '"/>');
                });

                $("#cancel-profile").on('click', function() {
                    $("#cancel-profile").hide();
                    $("#save-profile").hide();
                    $("#edit-profile").show();

                    $("#name").html(name);
                    $("#email-address").html(email);
                    $("#contact-number").html(contact_number);
                    $("#organisation").html(organisation);

                });

                $("#save-profile").on('click', function() {
                    var new_name = $("#new-name").text();
                    var new_email = $("#new-email-address").text();
                    var new_contact_number = $("#new-contact-number").text();
                    var new_organisation = $("#new-organisation").text();


                    $.ajax({
                        method: 'POST',
                        url: "../scripts/update_profile.php",
                        data: {
                            namePHP: new_name,
                            emailPHP: new_email,
                            contact_numberPHP: new_contact_number,
                            organisationPHP: new_organisation
                        },
                        success: function (response) {
                            //check if the php execution was successful and the data was added to the db
                            if (response.includes("success")){
                                //replace html with success message and button to return to landing page
                                var successHTML = "<h3>Your post was created succesfully. Please click the button below to return to the landing page.</h3><br> " +
                                    "<input type='button' id='return' class='pbs-button pbs-button-green' value='Confirm'>";

                                $('.main-content').html(successHTML);

                                // onclick function for new button to return to landing page
                                $("#return").on('click', function(){
                                    window.location.replace('profile.php');
                                });

                            } else {
                                //display error message if the php could not be executed
                                $('.main-content').html("<h3> There was an error processing your request. Please try again </h3><br>Error" + response);
                            }
                        },
                        datatype: 'text'
                    });
                });

                

                $.ajax({
                    url: '../scripts/get_profile.php',
                    type: 'get',
                    dataType: 'JSON',
                    success: function(response) {
                        if (response.includes("*warning_no_user_found*")) {
                            console.log('No user found...')
                        } else {
                            name = response[0].name;
                            email = response[0].email;
                            contact_number = response[0].contact_number;
                            organisation = response[0].organisation;

                            $('#name').text(name);
                            $('#email-address').text(email);
                            $('#contact-number').text(contact_number);
                            $('#organisation').text(organisation);
                        }

                    }
                });
            });
        </script>
        
    </body>
</html>