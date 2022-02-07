<?php
    session_start();

    if(!isset($_SESSION['logged_in'])){
        header('Location: login.php');
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

        <title>Add New User</title>
        
    </head>

    <body style="background-image:url(../images/dog.jpg); background-size:cover; background-repeat: no-repeat;">

        <div id="pbs-nav-bar">
            <?php
                include "../common/nav-bar.php";
            ?>
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
                </table>
        
                <div class="button-wrapper">
                    <input type="button" id="announcement-cancel" class="pbs-button pbs-button-red" value="Cancel">
                    <input type="button" id="announcement-post" class="pbs-button pbs-button-green" value="Add">
                </div>
            </div>

        </div>

        
        <script type="text/javascript">
            $(document).ready(function () {
                
            });
        </script>
        
    </body>
</html>