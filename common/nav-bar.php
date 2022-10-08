<!--
    ============================================
        - PBSCLP | nav-bar
        - Adam Shepherd
        - PBSCLP
        - April 2022

        This file contains the template for the
        nav-bar that will be imported into each
        page of the platform
    ============================================
-->





<div class="navbar-wrapper">
    <nav class="navbar navbar-expand-lg navbar-light">

        <!-- PBSuk logo and link to landing page -->
        <a href="landing.php">
            <img src="../images/pbslogo.png" alt="PBSuk Logo">
        </a>
        
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarText">
            
            <ul class="navbar-nav mr-auto" id="nav-options">
                <!-- home button -->
                <li class="nav-item current-nav" id="landing">
                    <a class="nav-link" href="landing.php" >Home <span class="sr-only">(current)</span></a>
                </li>

                <!-- forum button -->
                <!-- <li class="nav-item" id="nav-forum">
                    <a class="nav-link" href="forum.php">Forum</a>
                </li> -->

                <!-- drop down to hold less vital pages -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="forum.php" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Forums
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown" id="forum-dropdown">
                        <a class="dropdown-item nav-link" href="forum.php">Public</a>
                        <div class="dropdown-divider"></div>
                    </div>
                </li>

                <!-- resource bank button -->
                <li class="nav-item" id="nav-resource-bank">
                    <a class="nav-link" href="resource_bank_home.php">Resource Bank</a>
                </li>

                <!-- profile button -->
                <li class="nav-item" id="nav-profile">
                    <a class="nav-link" href="profile.php">Profile</a>
                </li>

                <!-- manage users button -->
                <li class="nav-item admin-only" id="nav-manage-users">
                    <a class="nav-link" href="manage_users.php">Manage Users</a>
                </li>

                <!-- drop down to hold less vital pages -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        More
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item nav-link" href="key_people.php">Key People</a>
                        <a class="dropdown-item nav-link" href="faq.php">FAQ</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item nav-link" href="terms_and_conditions.php">T's and C's</a>
                        <a class="dropdown-item nav-link" href="privacy_statement.php">Privacy Statement</a>
                    </div>
                </li>
            </ul>

            <span class="navbar-text">
                <!-- logout button -->
                <form action="../scripts/logout.php">
                    <input type="submit" id="logout" value="Log Out" class="pbs-button pbs-button-grey">
                </form>

            </span>

        </div>
    </nav>
</div>



<script type="text/javascript">
    var organisationID = '<?php echo $_SESSION['organisation_id']; ?>';
    $("#forum-dropdown").append('<a class="dropdown-item nav-link" href="organisation_forum.php?orgid=' + organisationID + '">' + organisationID + '</a>');
</script> 
