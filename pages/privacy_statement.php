<!--
    ============================================
        - PBSCLP | privacy_statement
        - Adam Shepherd
        - PBSCLP
        - April 2022

        This file contains the page for a
        practitioner to view the privacy statement
        of the platform
    ============================================
-->

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
        <link rel="stylesheet" href="../stylesheets/key_people.css">

        <title>Privacy Statement</title>
        
    </head>

    <body>

        <div id="pbs-nav-bar">
            <?php
                include "../common/nav-bar.php";
            ?>
        </div>

        <div class="page-header">
            <h1>Privacy Statement</h1>
        </div>

        <div class="main-content">
            <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut ultricies quis lacus quis euismod. Nullam porttitor orci nec tortor placerat, et imperdiet felis bibendum. Fusce eget lobortis felis. Ut rhoncus neque id ligula molestie, eu rutrum nisi porta. Suspendisse potenti. Donec et consequat justo. Sed eu metus arcu. Aliquam in varius lorem. Aliquam eget eros quis enim maximus vehicula vel eget ipsum. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Nulla commodo velit in diam vestibulum semper. Vivamus auctor, tellus nec condimentum ullamcorper, nisl ante faucibus elit, vel maximus nisl dui ac est. In ultricies sagittis orci, at pellentesque justo consectetur eget. Suspendisse cursus justo ligula, ut ultricies lacus rhoncus ut.
                <br><br>
                Ut augue massa, fringilla vel ante et, iaculis egestas massa. Vivamus tellus nibh, malesuada nec massa et, interdum suscipit orci. Nulla faucibus massa vel fermentum blandit. Aenean a felis ac mi venenatis venenatis. Fusce gravida, purus efficitur gravida ultrices, ligula libero aliquet tortor, a imperdiet velit nibh ac lorem. Etiam in ultrices nulla, id porta sapien. Fusce bibendum ornare leo non lacinia. Morbi nec turpis ut purus aliquam tempor. Suspendisse egestas massa vel dui imperdiet accumsan. Aenean id sem eu nulla volutpat iaculis. Curabitur sagittis orci eu odio molestie porta.
                <br><br>
                Quisque dapibus vulputate mauris eu tempus. Sed non facilisis tellus. Ut finibus erat non orci lacinia, ac rhoncus nunc scelerisque. Mauris accumsan neque ut erat congue blandit. Fusce quam lorem, varius at enim nec, dapibus viverra ante. Phasellus eleifend commodo aliquam. Proin tincidunt sem non mi condimentum, sit amet sodales leo fringilla. Maecenas sit amet velit auctor, fermentum turpis eu, consectetur massa. Aliquam auctor elementum lectus eget euismod. Suspendisse at suscipit nisi. Donec a molestie nulla, nec egestas orci. Fusce convallis pharetra velit, vehicula mattis lacus fermentum vel. Sed non dignissim mauris. Cras a vehicula mi, eget elementum arcu. In euismod quam ut velit rhoncus, ac porttitor risus posuere.
                <br><br>
            </p>
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
                
            });
        </script>
        
    </body>
</html>