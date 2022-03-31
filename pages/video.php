<?php
    session_start();

    if(!isset($_SESSION['logged_in'])){
        header('Location: https://pbsclp.info');
        exit();
    }

    function getVideoLink($video_id) {
        $pass = file_get_contents('../../pass.txt', true);

        //connect to database
        try {
            $connectionPDO = new PDO('mysql:host=localhost;dbname=pbsclp_pbsclp', 'pbsclp', $pass);
            $connectionPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            exit('*database_connection_error*');
        }

        $sql = "SELECT name, link FROM videos WHERE video_id=? LIMIT 1";
        $stmt = $connectionPDO->prepare($sql);
        $stmt->execute([$video_id]);
        $result = $stmt->fetch();

        if ($result){
            $data = array("name" => $result['name'], "link" => $result['link']);
            return json_encode($data);
        } else {
            return json_encode("*warning_video_not_found*");
        }

        // close connection to db
        $stmt = null;
        $connectionPDO = null;
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
        <link rel="stylesheet" href="../stylesheets/resource_bank.css">

        <title>Video</title>
        
    </head>

    <body>

        <div id="pbs-nav-bar">
            <?php
                include "../common/nav-bar.php";
            ?>
        </div>

        <div class="page-header">
            <h1>Video</h1>
        </div>
        <div class="page-subheader">
            <h3 id='video-subheading'></h3>
        </div>

        <div class="main-content">
            <!-- <iframe width="853" height="480" src="https://www.youtube.com/embed/_-wmIW6FSVo" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe> -->
            <!-- <iframe src="https://www.youtube.com/embed/_-wmIW6FSVo" class="pbs-video"> </iframe> -->

        </div>

        
        <script type="text/javascript">

            // https://stackoverflow.com/questions/21607808/convert-a-youtube-video-url-to-embed-code
            function getId(url) {
                const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
                const match = url.match(regExp);

                return (match && match[2].length === 11)
                ? match[2]
                : null;
            }

            $(document).ready(function () {

                // only show administrator content if an admin logged in
                var accountType = '<?php echo $_SESSION['account_type']; ?>';
                if (accountType != 'administrator') {
                    $('.admin-only').hide();
                } else {
                    $('.admin-only').show();
                }

                
                var video_info = <?php echo getVideoLink($_GET['video_id']);?>;
                // console.log(video_info);

                if (video_info.includes("*warning_no_video_found*")) {
                    $('.main-content').html("<h2> This video could not be found. </h2>");
                } else {
                    $("#video-subheading").html(video_info[0].name);

                    const videoId = getId(video_info[0].link);
                    const iframeMarkup = '<iframe src="//www.youtube.com/embed/' + videoId + '" class="pbs-video" frameborder="0" allowfullscreen></iframe>';
                    $('.main-content').html(iframeMarkup);
                }
            });
        </script>
        
    </body>
</html>