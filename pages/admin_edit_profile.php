<?php
    // ============================================
    //     - PBSCLP | admin_edit_profile
    //     - Adam Shepherd
    //     - PBSCLP
    //     - April 2022

    //     This file contains the page for an admin
    //     to edit the details of a practitioners
    //     account and assign them courses.
    // ============================================

    session_start();

    // ensure user is logged in
    if(!isset($_SESSION['logged_in'])){
        header('Location: https://pbsclp.info');
        exit();
    }

    // ensure logged in user is an admin
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

        <!-- links to stylesheets -->
        <link rel="stylesheet" href="../stylesheets/style.css">
        <link rel="stylesheet" href="../stylesheets/profile.css">

        <title>Admin Edit Profile</title>
        
    </head>

    <body>

        <!-- import nav bar -->
        <div id="pbs-nav-bar">
            <?php
                include "../common/nav-bar.php";
            ?>
        </div>

        <!-- page header -->
        <h1 class="page-header">Edit Practitioner Profile</h1>

        <div class="main-content">
            <div class="profile-wrapper">
              
                <!-- table to display the details of the pracititoner account -->
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
                    
                    <!-- buttons for edit profile page -->
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
                // retrieve user id from get request and return to javascript
                var user_id_to_edit = "<?php echo $_GET['userId']; ?>";

                // initialise variables
                var name, email, contact_number, organisation;
                
                // hide save and cancel edit profile buttons
                $("#cancel-edit").hide();
                $("#save-profile").hide();

                // action for cancel button
                $("#cancel-profile").on('click', function() {
                    window.location.href = 'manage_users.php';
                });

                // action for edit button
                $("#edit-profile").on('click', function(){
                    // swap visibility of buttons
                    $("#edit-profile").hide();
                    $("#cancel-profile").hide();
                    $("#cancel-edit").show();
                    $("#save-profile").show();
                    
                    // retrieve list of courses
                    $.ajax({
                        url: '../scripts/get_all_courses.php',
                        type: 'get',
                        dataType: 'JSON',
                        success: function(response) {
                            //check if there are no courses
                            if(response.includes("*warning_no_courses_found*")){
                                console.log("no courses found");
                            } else {
                                //add courses to dom if found
                                $("#courses").html("");
                                for (let i = 0; i < response.length; i++) {
                                    let output = '<input type="checkbox" id="edit-cid-' + response[i].course_id + '" class="pbs-form-check-box" value="' + response[i].course_name + '"><label for="edit-cid-' + response[i].course_id + '">' + response[i].course_name + '</label><br>';

                                    $("#courses").append(output);
                                }
                                
                                //get list of assigned courses
                                $.ajax({
                                    url: '../scripts/get_assigned_courses.php',
                                    type: 'post',
                                    dataType: 'JSON',
                                    data: {
                                        user_id_PHP: user_id_to_edit
                                    },
                                    success: function(response) {
                                        if(response.includes("*warning_no_courses_found*")){
                                            console.log("temp");
                                        } else {
                                            //checks courses that are already assigned
                                            for (let j = 0; j < response.length; j++) {
                                                let temp_id = "#edit-cid-" + response[j];
                                                $(temp_id).prop('checked', true);
                                            }
                                        }
                                    }
                                });

                                
                            }
                        }
                    });
                    
                    // creat form for user details
                    $("#name").html('<input type="text" id="new-name" class="pbs-form-text-box" value="' + name + '"/>');
                    $("#email-address").html('<input type="text" id="new-email" class="pbs-form-text-box" value="' + email + '"/>');
                    $("#contact-number").html('<input type="text" id="new-contact-number" class="pbs-form-text-box" value="' + contact_number + '"/>');
                    $("#organisation").html('<input type="text" id="new-organisation" class="pbs-form-text-box" value="' + organisation + '"/>');
                });

                //cancel button action
                $("#cancel-edit").on('click', function() {
                    //toggle visibility of buttons
                    $("#cancel-edit").hide();
                    $("#save-profile").hide();
                    $("#edit-profile").show();
                    $("#cancel-profile").show();

                    //set profile details to original values
                    $("#name").html(name);
                    $("#email-address").html(email);
                    $("#contact-number").html(contact_number);
                    $("#organisation").html(organisation);
                    $("#courses").html('<ul id="course-list"></ul>');
                    //display list of courses
                    if (list_of_course_id != null) {
                        for (let x = 0; x < list_of_course_id.length; x++){
                            let output = "<li id='cid-" + list_of_course_id[x] + "'>" + list_of_course_names[x] + "</li>";
                            $('#course-list').append(output);
                        }
                    }
                });

                //save button action
                $("#save-profile").on('click', function() {
                    //retrieve forum values after updated
                    var new_name = $("#new-name").val();
                    var new_email = $("#new-email").val();
                    var new_contact_number = $("#new-contact-number").val();
                    var new_organisation = $("#new-organisation").val();

                    //retrieve list of checked courses
                    var new_list_of_courses = $("#courses input:checkbox:checked").map(function(){
                        return $(this).attr('id').split(/[-]+/).pop();
                    }).get();

                    //name string handling to separate first and last names
                    var index = new_name.lastIndexOf(" ");
                    var lastname = new_name.slice(index + 1);
                    var firstname = new_name.substring(0, index);

                    //function to update database with new profile details
                    $.ajax({
                        method: 'POST',
                        url: "../scripts/update_profile.php",
                        data: {
                            //new profile information passed to PHP script
                            user_idPHP: user_id_to_edit,
                            firstnamePHP: firstname,
                            lastnamePHP: lastname,
                            emailPHP: new_email,
                            contact_numberPHP: new_contact_number,
                            organisationPHP: new_organisation,
                            old_list_of_coursesPHP: list_of_course_id,
                            new_list_of_coursesPHP: new_list_of_courses
                        },
                        success: function (response) {
                            //check if the php execution was successful and the data was added to the db
                            if (response.includes("*account_updated_successfully*")){
                                //replace html with success message and button to return to landing page
                                var successHTML = "<h3>Your profile was updated succesfully. Please click the button below to return to the user management page.</h3><br> " + "<br><input type='button' id='return' class='pbs-button pbs-button-green' value='Confirm'>";

                                $('.main-content').html(successHTML);

                            } else {
                                //display error message if the php could not be executed
                                $('.main-content').html("<h3> There was an error processing your request. Please try again </h3><br>Error" + response +"<br><input type='button' id='return' class='pbs-button pbs-button-green' value='Confirm'>");
                            }

                            // onclick function for new button to return to landing page
                            $("#return").on('click', function(){
                                window.location.replace('manage_users.php');
                            });

                        },
                        datatype: 'text'
                    });
                });

                //fetch user profile details from database
                $.ajax({
                    url: '../scripts/get_profile.php',
                    type: 'post',
                    dataType: 'JSON',
                    data: {
                        //id of user to fetch
                        user_id_PHP: user_id_to_edit
                    },
                    success: function(response) {
                        //message if no user found
                        if (response.includes("*warning_no_user_found*")) {
                            var message = "<h3>That user does not exist</h3><br> ";

                                $('.main-content').html(message);
                        } else {
                            //retrieve user detauls from response and store in variables
                            name = response[0].name;
                            email = response[0].email;
                            contact_number = response[0].contact_number;
                            organisation = response[0].organisation;
                            list_of_course_id = response[0].list_of_course_id;
                            list_of_course_names = response[0].list_of_course_names;

                            //set profile detauls to DOM elements to display on page
                            $('#name').text(name);
                            $('#email-address').text(email);
                            $('#contact-number').text(contact_number);
                            $('#organisation').text(organisation);
                            if (list_of_course_id != null) {
                                for (let x = 0; x < list_of_course_id.length; x++){
                                    let output = "<li id='cid-" + list_of_course_id[x] + "'>" + list_of_course_names[x] + "</li>";
                                    $('#course-list').append(output);
                                }
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