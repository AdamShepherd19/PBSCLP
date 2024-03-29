<?php
// ============================================
//     - PBSCLP | new_announcement
//     - Adam Shepherd
//     - PBSCLP
//     - April 2022

//     This file contains the page for an admin
//     to create a new announcement for the
//     landing page
// ============================================

session_start();

if (!isset($_SESSION['logged_in'])) {
    header('Location: https://pbsclp.info');
    exit();
}

if ($_SESSION['account_type'] != 'administrator') {
    header('Location: landing.php');
    exit();
}

$pass = file_get_contents('../../pass.txt', true);
//connect to database
try {
    $connectionPDO = new PDO('mysql:host=localhost;dbname=pbsclp_pbsclp', 'pbsclp', $pass);
    $connectionPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    exit('*database_connection_error*');
}

if (isset($_POST['delete_announcementPHP']) && $_POST['delete_announcementPHP'] == 1) {
    $sql = "DELETE FROM announcements WHERE announcement_id=:announcement_id";
    $stmt = $connectionPDO->prepare($sql);

    //check to see if the insert was successful
    if ($stmt->execute(['announcement_id' => $_POST['announcement_idPHP']])) {
        exit('*announcement_deleted_successfully*');
    } else {
        exit('Error: ' . $connectionPDO->error);
    }
} else {
    if (isset($_POST['titlePHP'])) {
        //retrieve title, content and author for the new post
        $title = $_POST['titlePHP'];
        $content = $_POST['contentPHP'];
        $link = $_POST['linkPHP'];
        $user_id = $_SESSION['user_id'];

        try {
            // query database and insert the new announcement into the announcements table
            if (isset($_POST['announcement_idPHP']) && $_POST['announcement_idPHP'] != -1) {
                $sql = "UPDATE announcements SET title=:title, content=:content, user_id=:user_id, link=:link WHERE announcement_id=:announcement_id;";
                $stmt = $connectionPDO->prepare($sql);

                //check to see if the insert was successful
                if ($stmt->execute(['title' => $title, 'content' => $content, 'user_id' => $user_id, 'link' => $link, 'announcement_id' => $_POST['announcement_idPHP']])) {
                    exit('*announcement_updated_successfully*');
                } else {
                    exit('Error: ' . $connectionPDO->error);
                }
            } else {
                $sql = "INSERT INTO announcements (title, content, user_id, link) VALUES (:title, :content, :user_id, :link);";
                $stmt = $connectionPDO->prepare($sql);

                //check to see if the insert was successful
                if ($stmt->execute(['title' => $title, 'content' => $content, 'user_id' => $user_id, 'link' => $link])) {
                    exit('*announcement_created_successfully*');
                } else {
                    exit('Error: ' . $connectionPDO->error);
                }
            }
        } catch (PDOException $e) {
            exit('*query_error* - ' . $e);
        }

        // $stmt = $connectionPDO->prepare($sql);

        // //check to see if the insert was successful
        // if ($stmt->execute(['title' => $title, 'content' => $content, 'user_id' => $user_id, 'link' => $link])) {
        //     exit('success');
        // } else {
        //     exit('Error: ' . $connectionPDO->error);
        // }



    }
}

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
    <link rel="stylesheet" href="../stylesheets/new_announcement.css">

    <title>Announcement</title>

</head>

<body>

    <div id="pbs-nav-bar">
        <?php
        include "../common/nav-bar.php";
        ?>
    </div>

    <div class="page-header">
        <h1 id='page-title'>New Announcement</h1>
    </div>

    <div class="main-content">
        <div class="form-wrapper">
            <form>
                <label for="title">*Title: </label><br />
                <input type="text" id="title" class="pbs-form-text-box" placeholder="Enter post title..."><br /><br />

                <label for="content">*Content: </label><br />
                <textarea id="content" class="pbs-form-text-box text-area-large"
                    placeholder="Enter post content..."></textarea><br />

                <label for="link">Link (optional): </label><br />
                <input type="text" id="link" class="pbs-form-text-box" placeholder="Enter optional link..."><br /><br />

                <div class="button-wrapper">
                    <input type="button" id="announcement-cancel" class="pbs-button pbs-button-yellow" value="Cancel">
                    <input type="button" id="announcement-delete" class="pbs-button pbs-button-red" value="Delete">
                    <input type="button" id="announcement-post" class="pbs-button pbs-button-green" value="Post">
                </div>
            </form>
        </div>

    </div>


    <script type="text/javascript">
        $(document).ready(function () {

            var announcement_id = "<?php print($_GET['aid']); ?>";
            var title, content, link;

            if (announcement_id) {
                $("#page-title").html("Edit Announcement");
                console.log(announcement_id);

                $.ajax({
                    method: 'GET',
                    url: "../scripts/get_individual_announcement.php",
                    data: {
                        announcement_idPHP: announcement_id
                    },
                    success: function (response) {
                        let data = JSON.parse(response)
                        console.log(data);
                        title = data[0].title;
                        content = data[0].content;
                        link = data[0].link;
                        $("#title").val(title);
                        $('#content').val(content);
                        $('#link').val(link);
                    }
                });
            } else {
                announcement_id = -1;
            }

            //onclick function for the cancel button
            $("#announcement-cancel").on('click', function () {
                window.location.replace('landing.php');
            });

            //onclick function for the delete button
            $("#announcement-delete").on('click', function () {
                $.ajax({
                    method: 'POST',
                    url: "new_announcement.php",
                    data: {
                        announcement_idPHP: announcement_id,
                        delete_announcementPHP: 1
                    },
                    success: function (response) {
                        console.log(response);
                        if (response.includes("*announcement_deleted_successfully*")) {
                            var successHTML = "<h3>Your post has been deleted succesfully. Please click the button below to return to the landing page.</h3><br> " +
                                "<input type='button' id='return' class='pbs-button pbs-button-green' value='Confirm'>";

                            $('.main-content').html(successHTML);

                            // onclick function for new button to return to landing page
                            $("#return").on('click', function () {
                                window.location.replace('landing.php');
                            });
                        } else {
                            alert("Deletion failed. Please try again.");
                        }
                    }
                });
            });

            // onclick function for the post announcement button
            $("#announcement-post").on('click', function () {
                //retrieve data from form
                var title = $("#title").val();
                var content = $('#content').val();
                var link;

                const isEmpty = str => !str.trim().length;

                if (!isEmpty($('#link').val())) {
                    link = $('#link').val();
                    let url_regex = /https?:\/\//;
                    if (url_regex.test(String(link).toLowerCase()) == false) {
                        link = "https://" + link;
                    }
                }

                //check data not empty
                if (title == "" || content == "") {
                    //prompt user to fill in all data
                    alert("Please fill out the required information in the form");
                } else {
                    //send data to php
                    $.ajax({
                        method: 'POST',
                        url: "new_announcement.php",
                        data: {
                            titlePHP: title,
                            contentPHP: content,
                            linkPHP: link,
                            announcement_idPHP: announcement_id
                        },
                        success: function (response) {
                            //check if the php execution was successful and the data was added to the db
                            if (response.includes("*announcement_created_successfully*")) {
                                //replace html with success message and button to return to landing page
                                var successHTML = "<h3>Your post was created succesfully. Please click the button below to return to the landing page.</h3><br> " +
                                    "<input type='button' id='return' class='pbs-button pbs-button-green' value='Confirm'>";

                                $('.main-content').html(successHTML);
                            } else if (response.includes("*announcement_updated_successfully*")) {
                                //replace html with success message and button to return to landing page
                                var successHTML = "<h3>Your post was updated succesfully. Please click the button below to return to the landing page.</h3><br> " +
                                    "<input type='button' id='return' class='pbs-button pbs-button-green' value='Confirm'>";

                                $('.main-content').html(successHTML);

                            } else {
                                //display error message if the php could not be executed
                                $('.main-content').html("<h3> There was an error processing your request. Please try again </h3><br>Error" + response +
                                    "<br><input type='button' id='return' class='pbs-button pbs-button-green' value='Confirm'>");
                            }

                            // onclick function for new button to return to landing page
                            $("#return").on('click', function () {
                                window.location.replace('landing.php');
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