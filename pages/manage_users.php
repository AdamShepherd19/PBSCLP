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
        <link rel="stylesheet" href="../stylesheets/manage_users.css">

        <title>Manage Users</title>
        
    </head>

    <body>

        <div id="pbs-nav-bar">
            <?php
                include "../common/nav-bar.php";
            ?>
        </div>

        <div class="page-header">
            <h1>Manage Users</h1>
        </div>

        <div class="main-content">

            <div class="search-wrapper">
                <input type="search" class="pbs-form-text-box" id="search-input" placeholder="search">
                <input type="button" id="search-button" class="pbs-button pbs-search-button pbs-button-green" value="Search">
                <input type="button" id="new-user-button" class="pbs-button pbs-button-green" value="New User">
            </div>
        
            <div class="account-wrapper">
                <!-- <div class="card">
                    <h4 class="card-header">Firstname Surname</h4>
                    <div class="card-body">
                        <div class="text-wrapper">
                            <table>
                                <tr>
                                    <td class="table-labels">Email Address:</td>
                                    <td>email@email.com</td>
                                </tr>
                        
                                <tr>
                                    <td class="table-labels">Contact Number:</td>
                                    <td>071234567890</td>
                                </tr>
                        
                                <tr>
                                    <td class="table-labels">Organisation:</td>
                                    <td>Organisation</td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="button-wrapper">
                            <input type="button" id="edit-profile" class="pbs-button pbs-button-orange table-button" value="Edit"> <br />
                            <input type="button" id="lock-profile" class="pbs-button pbs-button-yellow table-button" value="Lock">
                            <input type="button" id="remove-profile" class="pbs-button pbs-button-red table-button" value="Remove">
                        </div>
                    </div>
                </div> -->

            </div>
        </div>
        
        <script type="text/javascript">
            $(document).ready(function () {

                $("#new-user-button").on('click', function(){
                    window.location.replace('add_new_user.php');
                });

                $.ajax({
                    url: '../scripts/get_user_accounts.php',
                    type: 'get',
                    dataType: 'JSON',
                    success: function(response) {
                        if (response.includes("*warning_no_users_found*")) {
                            var message = "<div class='card'><h4 class='card-header'> There are no user accounts!</div>"

                            $(".account-wrapper").append(message);
                        } else {
                            for(var x = 0; x < response.length; x++) {
                                if (response[x].admin_locked == true){
                                    $lock_unlock_button = '<input type="button" id="lock-profile-id-' + response[x].user_id + '" class="pbs-button pbs-button-yellow table-button lock-profile" value="Un-Lock">';
                                } else {
                                    $lock_unlock_button = '<input type="button" id="lock-profile-id-' + response[x].user_id + '" class="pbs-button pbs-button-yellow table-button lock-profile" value="Lock">'
                                }

                                var message = '<div class="card">'+
                                '<h4 class="card-header">' + response[x].firstname + ' ' + response[x].lastname + '</h4>'+
                                '<div class="card-body">'+
                                    '<div class="text-wrapper">'+
                                        '<table>'+
                                            '<td class="table-labels">Email Address:</td><td id="email-content">' + response[x].email + '</td></tr>'+

                                            '<tr><td class="table-labels">Contact Number:</td><td id="contact-number-content">' + response[x].contact_number + '</td></tr>'+
                                    
                                            '<tr><td class="table-labels">Organisation:</td><td id="organisation-content">' + response[x].organisation + '</td></tr>'+
                                        '</table>'+
                                    '</div>'+
                                    
                                    '<div class="button-wrapper">'+
                                        '<input type="button" id="edit-profile-' + response[x].user_id + '" class="pbs-button pbs-button-orange table-button edit-profile" value="Edit"> <br />'+
                                        $lock_unlock_button +
                                        '<input type="button" id="remove-profile-' + response[x].user_id + '" class="pbs-button pbs-button-red table-button remove-profile" value="Remove">'+
                                '</div></div></div>';

                                $(".account-wrapper").append(message);
                            }

                            $(document).on("click", ".edit-profile" , function() {
                                var contentPanelId = jQuery(this).attr("id");
                                var thread_id = contentPanelId.split(/[-]+/).pop();
                                alert(contentPanelId);
                                // window.location.href = 'forum_post.php?threadId=' + thread_id;
                            });

                            $(document).on("click", ".remove-profile" , function() {
                                var contentPanelId = jQuery(this).attr("id");
                                var thread_id = contentPanelId.split(/[-]+/).pop();
                                alert(contentPanelId);
                                // window.location.href = 'forum_post.php?threadId=' + thread_id;
                            });

                            $(document).on("click", ".lock-profile" , function() {
                                var contentPanelId = jQuery(this).attr("id");
                                var thread_id = contentPanelId.split(/[-]+/).pop();
                                // alert(contentPanelId);
                                // window.location.href = 'forum_post.php?threadId=' + thread_id;
                                $.ajax({
                                    url: '../scripts/toggle_profile_lock.php',
                                    type: 'post',
                                    dataType: 'text',
                                    data: {
                                        toggle_lock: true;
                                    },
                                    success: function(response) {
                                        if (response.includes("*account_successfully_locked*")){
                                            $(contentPanelId).prop("value", "Unlock");
                                        } else if (response.includes("*account_successfully_unlocked*") {
                                            $(contentPanelId).prop("value", "Lock");
                                        } else {
                                            alert("There was an error processing your request. Please try again.");
                                        }
                                    }
                                });
                            });
                        }

                    }
                });

                // search bar functionality that toggles visibility of user accounts based on the value
                // entered into the search bar. This looks through any field of the user's information
                // and displays all the user accounts that contain the search term
                $("#search-input").on("keyup", function() {
                    var value = $(this).val().toLowerCase();
                    $(".card").filter(function() {
                        $(this).find('*').toggle($(this).text().toLowerCase().indexOf(value) > -1)
                    });
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