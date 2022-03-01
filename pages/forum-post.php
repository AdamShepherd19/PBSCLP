<?php
    session_start();

    if(!isset($_SESSION['logged_in'])){
        header('Location: https://pbsclp.info');
        exit();
    }

    $thread_id = $_GET['threadID'];

    // https://makitweb.com/return-json-response-ajax-using-jquery-php
    $pass = file_get_contents('../../pass.txt', true);

        //connect to database
        try {
            $connectionPDO = new PDO('mysql:host=localhost;dbname=pbsclp_pbsclp', 'pbsclp', $pass);
            $connectionPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            exit('*database_connection_error*');
        }

    //perform query and sort into newest first
    $sql = "SELECT * FROM threads WHERE thread_id=? LIMIT 1";
    $stmt = $connectionPDO->prepare($sql);
    $stmt->execute([$thread_id]);
    $result = $stmt->fetchAll();


    if ($result){
        //initialise array
        $data = array();

        // output data of each row
        foreach($result as $row) {
            $sql = "SELECT firstname, lastname FROM users WHERE user_id=? LIMIT 1";
            $stmt = $connectionPDO->prepare($sql);
            $stmt->execute([$row['user_id']]);
            $names = $stmt->fetchAll();

            //retrieve data from query
            $thread_id = $row['thread_id'];
            $title = $row['title'];
            $content = $row['content'];
            $firstname = $names[0]['firstname'];
            $lastname = $names[0]['lastname'];
            
            //add data into array
            $data[] = array(
                "thread_id" => $thread_id,
                "title" => $title,
                "content" => $content,
                "firstname" => $firstname,
                "lastname" => $lastname
            );
        }
        //encode the array into jason
        echo json_encode($data);
    } else {
        echo json_encode("*warning_no_post_found*");
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
        <link rel="stylesheet" href="../stylesheets/forum.css">

        <title>Forum Post</title>
        
    </head>

    <body>

        <div id="pbs-nav-bar">
            <?php
                include "../common/nav-bar.php";
            ?>
        </div>

        <div class="page-header">
            <h1>Forum Post</h1>
        </div>

        <div class="main-content">
            <h2 id="temp-header">
                <!-- post here -->
            </h2>

            <ul id="temp-list">
                <!-- comments here -->
            </ul>
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

                var thread_id = "<?php echo $_GET['threadId']; ?>";

                $.ajax({
                    url: 'forum_post.php',
                    type: 'get',
                    dataType: 'JSON',
                    success: function(response) {
                        if (response.includes("*warning_no_post_found*")) {
                            var announcement = "no-post";

                            $('#temp-header').html(announcement);
                        } else {
                            for(var x = 0; x < response.length; x++) {
                                var post_content = response[x].content;

                                $('#temp-header').html(post_content);
                            }
                        }

                    }
                });

                $.ajax({
                    url: '../scripts/get_comments.php',
                    type: 'get',
                    dataType: 'JSON',
                    data: {
                        threadIDPHP: thread_id
                    },
                    success: function(response) {
                        if (response.includes("*warning_no_comments_found*")) {
                            var announcement = "no-comments";

                            // $("#announcement-wrapper").append(announcement);
                            $('#temp-header').html(announcement);
                        } else {
                            for(var x = 0; x < response.length; x++) {
                                var comment = response[x].content;

                                $('#temp-list').append("<li>" + comment + "</li>");
                            }
                        }

                    }
                });
            });
        </script>
        
    </body>
</html>