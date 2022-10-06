<?php
    // ============================================
    //     - PBSCLP | privacy_statement
    //     - Adam Shepherd
    //     - PBSCLP
    //     - April 2022

    //     This file contains the page for a
    //     practitioner to view the privacy statement
    //     of the platform
    // ============================================

    session_start();

    if(!isset($_SESSION['logged_in'])){
        header('Location: localhost/PBSCLP/');
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
                <br><br>
                
                This privacy policy sets out how we use and protect any information that you give PBSUK
                Limited when you use this portal.

                <br><br>

                PBSUK Limited is committed to ensuring that your privacy is protected. Should we ask you to
                provide certain information by which you can be identified when using this portal, then you
                can be assured that it will only be used in accordance with this privacy statement.
                
                <br><br>

                PBSUK Limited may change this policy from time to time by updating this page. Registered
                users will be informed by email of any changes. If you are not a registered site user, you
                should check this page from time to time to ensure that you are happy with any changes.
                This policy is effective from 1 st August 2022.

                <br><br>

                PBSUK Ltd is registered as a Data Controller with the Information Commissioner&#39;s Office
                (Ref: ZA196510.)

                <br><br>

                <strong>What we collect</strong>

                <br><br>

                We may collect the following information:
                <br>

                <ul>
                    <li>name and job title</li>
                    <li>contact information including email address</li>
                    <li>demographic information such as postcode, preferences and interests</li>                        
                </ul>

                <br>

                <strong>What we do with the information we gather</strong>

                <br><br>

                We require this information to understand your needs and provide you with a better
                service, and in particular for the following reasons:

                <br>

                <ul>
                    <li>Internal record keeping.</li>
                    <li>We may use the information to improve our products and services.</li>
                    <li>We may periodically send promotional emails about new products,
                        special offers or other information which we think you may find
                        interesting using the email address that you have provided.</li>
                </ul>

                <br>

                From time to time, we may also use your information to contact you for market research
                purposes. We may contact you by email, phone, fax or mail. We may use the information to
                customise the website according to your interests.

                <br><br>
                
                <strong>Security</strong>

                <br><br>

                We are committed to ensuring that your information is secure. In order to prevent
                unauthorised access or disclosure, we have put in place suitable physical, electronic and
                managerial procedures to safeguard and secure the information we collect online.

                <br><br>

                <strong>Links to other websites</strong>
                
                <br><br>

                Our portal may contain links to other websites of interest. However, once you have used
                these links to leave our site, you should note that we do not have any control over that
                other website. Therefore, we cannot be responsible for the protection and privacy of any
                information which you provide whilst visiting such sites and such sites are not governed by
                this privacy statement. You should exercise caution and look at the privacy statement
                applicable to the website in question.

                <br><br>

                <strong>Controlling your personal information</strong>

                <br><br>

                You may choose to restrict the collection or use of your personal information in the
                following ways:
                We will not sell, distribute or lease your personal information to third parties unless we
                have your permission or are required by law to do so. We may use your personal
                information to send you promotional information about third parties which we think you
                may find interesting if you tell us that you wish this to happen, for example, by subscribing
                to our newsletter.
                You may request details of personal information which we hold about you under the Data
                Protection Act 2018. A small fee may be payable. If you would like a copy of the information
                held about you, please use the contact form on this site, our website or write to our
                registered address.
                If you believe that any information we are holding on you is incorrect or incomplete, please
                write to us at our registered address or email us as soon as possible. We will promptly
                correct any information found to be incorrect.

                <br><br>

                <strong>Controlling your business information</strong>

                <br><br>

                As part of our service, we necessarily collect and store information relating to your use of
                the service.
                No director or employee of PBSUK Limited will access this information except where necessary to
                diagnose and resolve problems with the technology or in order to provide feedback to any
                questions you are raising.
                The service has been designed so that individual responses remain anonymous and as such
                these will not be disclosed unless we are required to do so by law.

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