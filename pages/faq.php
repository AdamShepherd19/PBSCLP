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
        <link rel="stylesheet" href="../stylesheets/faq.css">

        <title>FAQ</title>
        
    </head>

    <body>

        <div id="pbs-nav-bar">
            <?php
                include "../common/nav-bar.php";
            ?>
        </div>

        <div class="page-header">
            <h1>FAQ</h1>
        </div>

        <div class="main-content">
            <div class="button-wrapper admin-only">
                <input type="button" id="new-faq-button" class="pbs-button pbs-button-green" value="New FAQ">
            </div>

            <div class="faq-wrapper" id="faq-wrapper">

                <!-- <div class="card">
                    <div class="card-header">
                        <h3>Q - Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam?</h3>
                    </div>
                    <div class="card-body">
                        <p>A - Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                    </div>
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

                $.ajax({
                    url: '../scripts/get_faqs.php',
                    type: 'get',
                    dataType: 'JSON',
                    success: function(response) {
                        if (response.includes("*warning_no_faqs_found*")) {
                            var announcement = "<div class='card'>" +
                                "<div class='card-header'>" +
                                    "<h3> There are no FAQ's yet! </h3>" +
                                "</div>";

                                $("#faq-wrapper").append(announcement);
                        } else {
                            for(var x = 0; x < response.length; x++) {
                                var faq = '<div class="card faq-card">' +
                                    '<div class="card-header">' +
                                        '<h3> <strong>Q</strong> -' + response[x].question + '</h3>' +
                                    '</div>' +
                                    '<div class="card-body">' +
                                        '<p> <strong>A</strong> -' + response[x].answer + '</p>' +
                                    '</div>';

                                $("#faq-wrapper").append(faq);
                            }
                        }

                    }
                });

                $('#new-faq-button').on("click", function() {
                    window.location.replace('new_faq_post.php');
                });
                
            });
        </script>
        
    </body>
</html>