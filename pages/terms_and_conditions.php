<?php
    // ============================================
    //     - PBSCLP | terms_and_conditions
    //     - Adam Shepherd
    //     - PBSCLP
    //     - April 2022

    //     This file contains the page for a
    //     pracititoner to view the terms and
    //     conditions of the platform
    // ============================================

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

        <title>Terms & Conditions</title>
        
    </head>

    <body>

        <div id="pbs-nav-bar">
            <?php
                include "../common/nav-bar.php";
            ?>
        </div>

        <div class="page-header">
            <h1>Terms & Conditions</h1>
        </div>

        <div class="main-content">
            <p>
                <strong>The use of this portal is subject to the following terms of use:</strong>

                <br>Welcome to our collaboration portal.<br>

                If you continue to browse and use this portal, you are agreeing to comply with and be
                bound by the following terms and conditions of use, which together with our privacy policy
                govern PBSUK's relationship with you in relation to this service.

                <br><br>

                If you disagree with any part of these terms and conditions, please do not continue unless
                you have sought permission from PBSUK in writing in advance.

                <br><br>

                The term 'PBSCLP' or 'PBSUK' 'us' or 'we' or 'our' refers to the owner of this portal, PBSUK
                Ltd. a company registered in Scotland with company number SC540834, registered address
                24 Lauder Crescent Perth PH1 1SU
                
                <br><br>
                
                The term 'you' refers to the user or viewer of our collaboration portal.

                <br><br>

                <strong>The use of this portal is subject to the following terms of use:</strong>
                
                <br><br>

                The content of this portal is for your general information and use only. It is subject to
                change without notice. At this point in time the site does not make use of cookies.
                Neither we nor any third parties provide any warranty or guarantee as to the accuracy,
                timeliness, performance, completeness or suitability of the information and materials found
                or offered on this website for any particular purpose. You acknowledge that such
                information and materials may contain inaccuracies or errors and we expressly exclude
                liability for any such inaccuracies or errors to the fullest extent permitted by law.
                Your use of any information or materials on this website is entirely at your own risk, for
                which we shall not be liable. It shall be your own responsibility to ensure that any products,
                services or information available through this website meet your specific requirements.
                This portal contains material which is owned by or licensed to us. This material includes, but
                is not limited to, the design, layout, look, appearance and graphics. Reproduction is
                prohibited without our express written consent. Please report any copyright or trademark
                infringements to us by writing to our registered address, which is available on this site.
                All trademarks reproduced in this portal, which are not the property of, or licensed to us,
                are acknowledged on the portal.

                <br><br>

                You must not attempt to access parts of the site where we have not specifically granted
                such access.

                <br><br>

                Unauthorised use of this portal may give rise to a claim for damages and/or be a criminal
                offence.

                <br><br>

                This portal may also include links to other websites. These links are provided for your
                convenience to provide further information. They do not signify that we endorse the
                website(s) or the information contained therein. We have no responsibility for the content
                of any linked website(s).

                <br><br>

                Your use of this portal and any dispute arising out of such use of the portal is subject to the
                laws of Scotland and the jurisdiction of Scottish courts.

                <br><br>

                If you become a registered user of the portal, you will be issued with a username and
                password for which you will be solely responsible.

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