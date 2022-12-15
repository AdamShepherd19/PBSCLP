<?php
    // ============================================
    //     - PBSCLP | key_people
    //     - Adam Shepherd
    //     - PBSCLP
    //     - April 2022

    //     This file contains the page for a
    //     practitioner to view the key people for
    //     the platform so they can conact any of
    //     them in case of any issues
    // ============================================

    session_start();

    if(!isset($_SESSION['logged_in'])){
        header('Location: https://pbsclp.info/');
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

        <title>Key People</title>
        
    </head>

    <body>

        <div id="pbs-nav-bar">
            <?php
                include "../common/nav-bar.php";
            ?>
        </div>

        <div class="page-header">
            <h1>Key People</h1>
        </div>

        <div class="main-content">
            <div class="card key-person">
                <div class="card-header">
                    <h3> Hannah Newcombe </h3>
                </div>
                <div class="card-body key-person-profile">
                    <div class="key-person-photo">
                        <img src="../images/hannah_newcombe.jpg" alt="Hannah Newcombe">
                    </div>

                    <div class="key-person-info">
                        <h4> hnewcombe@pbsuk.org </h4>
                        <br>
                        <h4> 07904 599591 </h4>
                    </div>
                </div>
            </div>

            <div class="card key-person">
                <div class="card-header">
                    <h3> Huw Price </h3>
                </div>
                <div class="card-body key-person-profile">
                    <div class="key-person-photo">
                        <img src="../images/huw_price.jpg" alt="Huw Price">
                    </div>

                    <div class="key-person-info">
                        <h4> hprice@pbsuk.org </h4>
                        <br>
                        <h4> 07946 475075 </h4>
                    </div>
                </div>
            </div>

            <div class="card key-person">
                <div class="card-header">
                    <h3> Katy O'Donnell </h3>
                </div>
                <div class="card-body key-person-profile">
                    <div class="key-person-photo">
                        <img src="../images/katy_odonnell.jpg" alt="Katy O'Donnell">
                    </div>

                    <div class="key-person-info">
                        <h4> kodonnell@pbsuk.org </h4>
                        <br>
                        <h4> 07961 215856 </h4>
                    </div>
                </div>
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
                
            });
        </script>
        
    </body>
</html>