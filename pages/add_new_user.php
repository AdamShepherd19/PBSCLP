<?php
    session_start();

    if(!isset($_SESSION['logged_in'])){
        header('Location: login.php');
        exit();
    }

    if($_SESSION['account_type'] != 'administrator'){
        header('Location: landing.php');
        exit();
    }

    $pass = file_get_contents('../../pass.txt', true);

    //connect to database
    try {
        $connectionPDO = new PDO('mysql:host=localhost;dbname=pbsclp_pbsclp', 'pbsclp', $pass);
        $connectionPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        exit('*database_connection_error*');
    }

    $new_email = $_POST['email'];

    //perform query
    $sql = "SELECT email FROM users";
    $stmt = $connectionPDO->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();

    if ($result){
        //initialise array
        $data = array();

        // output data of each row
        foreach($result as $row) {
            //retrieve data from query
            if ($new_email == $row['email']){
                exit("*account_already_exists*");
            }
        }
    } else {
        exit("*no_existing_accounts*");
    }

    // close connection to db
    $stmt = null;
    $connectionPDO = null;
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
        <link rel="stylesheet" href="../stylesheets/add_new_user.css">

        <title>Add New User</title>
        
    </head>

    <body>
        <div id="pbs-nav-bar">
            <?php
                include "../common/nav-bar.php";
            ?>
        </div>

        <div class="page-header">
            <h1>Add New User</h1>
        </div>

        <div class="main-content">
            <div class="form-wrapper">
                <table>
                    <tr>
                        <td class="caption">First Name(s):</td>
                        <td><input type="text" id="firstname" class="pbs-form-text-box" placeholder="First Name(s)..."></td>
                    </tr>
                    
                    <tr>
                        <td class="caption">Last Name:</td>
                        <td><input type="text" id="lastname" class="pbs-form-text-box" placeholder="Last Name..."></td>
                    </tr>
            
                    <tr>
                        <td class="caption">Email Address:</td>
                        <td><input type="text" id="email" class="pbs-form-text-box" placeholder="Email Address..."></td>
                    </tr>
                    
                    <tr>
                        <td class="caption">Contact Number:</td>
                        <td><input type="text" id="contact-number" class="pbs-form-text-box" placeholder="Contact Number..."></td>
                    </tr>
                    
                    <tr>
                        <td class="caption">Organisation:</td>
                        <td><input type="text" id="organisation" class="pbs-form-text-box" placeholder="Organisation..."></td>
                    </tr>
            
                    <tr>
                        <td class="caption">Account Type:</td>
                        <td>
                            <select id="account-type" name="account-type" class="pbs-form-text-box">
                                <option value="practitioner">Practitioner</option>
                                <option value="administrator">Administrator</option>
                            </select>
                        </td>
                    </tr>

                    <tr id="course-list">
                        <td class="caption">Courses:</td>
                        <td>
                            <input type="checkbox" id="course1" class="pbs-form-check-box" value="course 1">
                            <label for="course1">Course 1</label><br>

                            <input type="checkbox" id="course2" class="pbs-form-check-box" value="course 2">
                            <label for="course2">Course 2</label><br>

                            <input type="checkbox" id="course3" class="pbs-form-check-box" value="course 3">
                            <label for="course3">Course 3</label><br>
                        </td>
                        <input type="button" id="test-button" class="pbs-button pbs-button-orange" value="Test">
                    </tr>
                </table>
        
                <div class="button-wrapper">
                    <input type="button" id="cancel" class="pbs-button pbs-button-red" value="Cancel">
                    <input type="button" id="add-new-user" class="pbs-button pbs-button-green" value="Add">
                </div>
            </div>

        </div>

        
        <script type="text/javascript">
            $(document).ready(function () {
                $("#cancel").on('click', function(){
                    window.location.replace('manage_users.php');
                });
                
                $('#account-type').change( function () {
                    if($('#account-type').val() == "administrator") {
                        $('#course-list').hide();
                    } else {
                        $('#course-list').show();
                    }
                });

                $("#test-button").click(function(event){
                    event.preventDefault();
                    var searchIDs = $("#course-list input:checkbox:checked").map(function(){
                        return $(this).val();
                    }).get(); // <----
                    console.log(searchIDs);
                });

                $("#add-new-user").on('click', function(){
                    //retrieve data from form
                    var firstname = $("#firstname").val();
                    var lastname = $('#lastname').val();
                    var email = $('#email').val();
                    var contact_number = $('#contact-number').val();
                    var organisation = $('#organisation').val();
                    var account_type = $('#account-type').val(); 

                    //check data not empty
                    if(firstname == "" || lastname == "" || email == "" || contact_number == "" || organisation == ""){
                        //prompt user to fill in all data
                        alert("Please fill out all the fields in the form.");
                    } else {

                        //check if new email exists in db
                        $.ajax({
                            method: 'POST',
                            url: "add_new_user.php",
                            data: {
                                email: email,
                            },
                            success: function (response) {
                                //check if the php execution was successful and the data was added to the db
                                if (response.includes("*no_existing_accounts*")){
                                    $.ajax({
                                        method: 'POST',
                                        url: "../scripts/insert_new_user.php",
                                        data: {
                                            firstnamePHP: firstname,
                                            lastnamePHP: lastname,
                                            emailPHP: email,
                                            contact_numberPHP: contact_number,
                                            organisationPHP: organisation,
                                            account_typePHP: account_type
                                        },
                                        success: function (response) {
                                            //check if the php execution was successful and the data was added to the db
                                            if (response.includes("*user_added_successfully*")){
                                                //replace html with success message and button to return to landing page
                                                var successHTML = "<h3>The new user was added succesfully. Please click the button below to return to the landing page.</h3><br> " +
                                                    "<input type='button' id='return' class='pbs-button pbs-button-green' value='Confirm'>";

                                                $('.main-content').html(successHTML);

                                            } else {
                                                //display error message if the php could not be executed
                                                $('.main-content').html("<h3> There was an error processing your request. Please try again </h3><br>Error" + response +
                                                    "<br><input type='button' id='return' class='pbs-button pbs-button-green' value='Confirm'>");
                                            }

                                            // onclick function for new button to return to landing page
                                            $("#return").on('click', function(){
                                                window.location.replace('manage_users.php');
                                            });
                                        },
                                        datatype: 'text'
                                    });

                                } else {
                                    alert("That email address is already in use. Please enter another.");
                                }
                            },
                            datatype: 'text'
                        });
                    };
                });
            });
        </script>
        
    </body>
</html>