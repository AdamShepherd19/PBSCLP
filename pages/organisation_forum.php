<?php
// ============================================
//     - PBSCLP | forum
//     - Adam Shepherd
//     - PBSCLP
//     - April 2022

//     This file contains the page for a
//     practitioner to view the forum system.
//     They will be presented with a list of all
//     approved forum posts and they can select
//     one to view the individual page for it
//     and view the comments
// ============================================

session_start();

if (!isset($_SESSION['logged_in'])) {
    header('Location: https://pbsclp.info/');
    exit();
}

if (($_SESSION['account_type'] != 'administrator') && ($_GET['orgid'] != $_SESSION['organisation_id'])) {
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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <!-- Bootstrap javascript include links -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>

    <!-- include jQuery -->
    <script src="../includes/jquery.js"></script>

    <link rel="stylesheet" href="../stylesheets/style.css">
    <link rel="stylesheet" href="../stylesheets/forum.css">

    <title>Forum</title>

</head>

<body>

    <div id="pbs-nav-bar">
        <?php
        include "../common/nav-bar.php";
        ?>
    </div>

    <div class="page-header">
        <h1 id='page-title'>Forum</h1>
    </div>

    <div class="main-content">
        <div class="search-wrapper">
            <input type="search" class="pbs-form-text-box" id="search-input" placeholder="search">
            <input type="button" id="search-button" class="pbs-button pbs-search-button pbs-button-green"
                value="Search">
            <input type="button" id="new-post-button" class="pbs-button pbs-button-green" value="New Post">
        </div>

        <div class="forum-wrapper">

            <!-- <div class="forum-post card">
                    <div class="card-header">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</div>
                    <div class="card-body">
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
                        </p>
                        <span><i>Comments (x)</i></span>
                    </div>
                </div> -->

        </div>
    </div>


    <script type="text/javascript">
        $(document).ready(function () {

            // only show administrator content if an admin logged in
            var accountType = '<?php echo $_SESSION['account_type']; ?>';
            var organisationID = '<?php echo $_GET['orgid']; ?>';

            if (accountType != 'administrator') {
                $('.admin-only').hide();
            } else {
                $('.admin-only').show();
            }

            $('#new-post-button').on("click", function () {
                window.location.replace('new_forum_post.php?orgid=' + organisationID);
            });

            $.ajax({
                url: '../scripts/get_forum_posts.php',
                type: 'get',
                dataType: 'JSON',
                data: {
                    approvedPHP: '1',
                    organisationPHP: organisationID
                },
                success: function (response) {
                    if (response.includes("*warning_no_posts_found*")) {
                        var message = "<div class='card'><h4 class='card-header'> There are no posts yet!</div>"

                        $(".forum-wrapper").append(message);
                    } else {
                        for (var x = 0; x < response.length; x++) {

                            var number_of_comments = function () {
                                var tmp = null;
                                $.ajax({
                                    url: '../scripts/get_number_of_comments.php',
                                    async: false,
                                    type: 'get',
                                    dataType: 'text',
                                    data: {
                                        thread_idPHP: response[x].thread_id
                                    },
                                    success: function (response) {
                                        tmp = response;
                                        console.log(tmp);
                                    }
                                });
                                return tmp;
                            }();

                            var message = '<div class="forum-post card" id="thread-id-' + response[x].thread_id + '">' +
                                '<div class="card-header">' + response[x].title + '<br><span><i> - ' + response[x].firstname + ' ' + response[x].lastname + '</i></span>' + '</div>' +
                                '<div class="card-body">' +
                                '<p>' + response[x].content + '</p>' +
                                '<span><i>Comments (' + number_of_comments + ')</i></span>' +
                                '</div></div><br>';

                            $(".forum-wrapper").append(message);
                        }

                        $(document).on("click", ".forum-post", function () {
                            var contentPanelId = jQuery(this).attr("id");
                            var thread_id = contentPanelId.split(/[-]+/).pop();
                            window.location.href = 'forum_post.php?threadId=' + thread_id;
                        });
                    }

                }
            });


            var list_of_all_organisations = function () {
                var temp = null;

                $.ajax({
                    url: '../scripts/get_all_organisations.php',
                    async: false,
                    type: 'get',
                    dataType: 'JSON',
                    success: function (response) {
                        //check if there are no courses
                        if (response.includes("*warning_no_organisations_found*")) {
                            console.log("no organisations found");
                        } else {
                            temp = response;
                        }
                    }
                });

                return temp;
            }();

            for (let y = 0; y < list_of_all_organisations.length; y++) {
                if (list_of_all_organisations[y].organisation_id == organisationID) {
                    organisationName = list_of_all_organisations[y].organisation_name;
                    break;
                }
            }

            $("#page-title").text(organisationName + " Forum");


            // search bar functionality that toggles visibility of user accounts based on the value
            // entered into the search bar. This looks through any field of the user's information
            // and displays all the user accounts that contain the search term
            $("#search-input").on("keyup", function () {
                var value = $(this).val().toLowerCase();
                $(".card").filter(function () {
                    $(this).find('*').toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });

        });
    </script>

</body>

</html>