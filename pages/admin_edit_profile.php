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
        <link rel="stylesheet" href="../stylesheets/profile.css">

        <title>Admin Edit Profile</title>
        
    </head>

    <body>

        <div id="pbs-nav-bar">
            <?php
                include "../common/nav-bar.php";
            ?>
        </div>

        <h1 class="page-header">Edit Practitioner Profile</h1>

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
                        <td class="caption">Courses:</td>
                        <td id="courses"><ul id="course-list"></ul></td>
                    </tr>
                    
                    <tr>
                        <td></td>
                        <td id="buttons">
                            <input type="button" id="cancel-profile" class="pbs-button pbs-button-red" value="Cancel">
                            <input type="button" id="edit-profile" class="pbs-button pbs-button-green table-button" value="Edit">
                            <input type="button" id="cancel-edit" class="pbs-button pbs-button-red" value="Cancel">
                            <input type="button" id="save-profile" class="pbs-button pbs-button-green" value="Save">
                        </td>
                    </tr>
                </table>
              
            </div>
        </div>



        <script type="text/javascript">
            $(document).ready(function () {
                var name, email, contact_number, organisation;
                
                // hide save and cancel edit profile buttons
                $("#cancel-edit").hide();
                $("#save-profile").hide();

                $("#cancel-profile").on('click', function() {
                    window.location.href = 'manage_users.php';
                });

                $("#edit-profile").on('click', function(){
                    $("#edit-profile").hide();
                    $("#cancel-profile").hide();
                    $("#cancel-edit").show();
                    $("#save-profile").show();
                    
                    $("#name").html('<input type="text" id="new-name" class="pbs-form-text-box" value="' + name + '"/>');
                    $("#email-address").html('<input type="text" id="new-email" class="pbs-form-text-box" value="' + email + '"/>');
                    $("#contact-number").html('<input type="text" id="new-contact-number" class="pbs-form-text-box" value="' + contact_number + '"/>');
                    $("#organisation").html('<input type="text" id="new-organisation" class="pbs-form-text-box" value="' + organisation + '"/>');
                });

                $("#cancel-edit").on('click', function() {
                    $("#cancel-edit").hide();
                    $("#save-profile").hide();
                    $("#edit-profile").show();
                    $("#cancel-profile").show();

                    $("#name").html(name);
                    $("#email-address").html(email);
                    $("#contact-number").html(contact_number);
                    $("#organisation").html(organisation);

                });

                $("#save-profile").on('click', function() {
                    var new_name = $("#new-name").val();
                    var new_email = $("#new-email").val();
                    var new_contact_number = $("#new-contact-number").val();
                    var new_organisation = $("#new-organisation").val();
                    

                    var index = new_name.lastIndexOf(" ");
                    var lastname = new_name.slice(index + 1);
                    var firstname = new_name.substring(0, index);

                    console.log(firstname + " " + lastname);

                    $.ajax({
                        method: 'POST',
                        url: "../scripts/update_profile.php",
                        data: {
                            firstnamePHP: firstname,
                            lastnamePHP: lastname,
                            emailPHP: new_email,
                            contact_numberPHP: new_contact_number,
                            organisationPHP: new_organisation
                        },
                        success: function (response) {
                            //check if the php execution was successful and the data was added to the db
                            if (response.includes("success")){
                                //replace html with success message and button to return to landing page
                                var successHTML = "<h3>Your profile was updated succesfully. Please click the button below to return to the landing page.</h3><br> " +
                                    "<br><input type='button' id='return' class='pbs-button pbs-button-green' value='Confirm'>";

                                $('.main-content').html(successHTML);

                            } else {
                                //display error message if the php could not be executed
                                $('.main-content').html("<h3> There was an error processing your request. Please try again </h3><br>Error" + response +
                                        "<br><input type='button' id='return' class='pbs-button pbs-button-green' value='Confirm'>");
                            }

                            // onclick function for new button to return to landing page
                            $("#return").on('click', function(){
                                window.location.replace('profile.php');
                            });

                        },
                        datatype: 'text'
                    });
                });

                
                var user_id_to_edit = "<?php echo $_GET['userId']; ?>";

                $.ajax({
                    url: '../scripts/get_profile.php',
                    type: 'post',
                    dataType: 'JSON',
                    data: {
                        user_id_PHP: user_id_to_edit
                    },
                    success: function(response) {
                        if (response.includes("*warning_no_user_found*")) {
                            console.log('No user found...')
                        } else {
                            name = response[0].name;
                            email = response[0].email;
                            contact_number = response[0].contact_number;
                            organisation = response[0].organisation;
                            list_of_course_id = response[0].list_of_course_id;
                            list_of_course_names = response[0].list_of_course_names;

                            $('#name').text(name);
                            $('#email-address').text(email);
                            $('#contact-number').text(contact_number);
                            $('#organisation').text(organisation);
                            for (let x = 0; x < list_of_courses.length; x++){
                                $('#course-list').append("<li id='cid=" + list_of_course_id[x] + "'>" + list_of_course_names[x] + "</li>");
                            }
                        }

                    }
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