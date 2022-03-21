<div class="navbar-wrapper">
    <nav class="navbar navbar-expand-lg navbar-light">

        <a href="landing.php">
            <img src="../images/pbslogo.png" alt="PBSuk Logo">
        </a>
        
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarText">
            
            <ul class="navbar-nav mr-auto" id="nav-options">

                <li class="nav-item current-nav" id="landing">
                    <a class="nav-link" href="landing.php" >Home <span class="sr-only">(current)</span></a>
                </li>

                <li class="nav-item" id="nav-forum">
                    <a class="nav-link" href="forum.php">Forum</a>
                </li>

                <li class="nav-item" id="nav-resource-bank">
                    <a class="nav-link" href="resource_bank_home.php">Resource Bank</a>
                </li>

                <li class="nav-item" id="nav-profile">
                    <a class="nav-link" href="profile.php">Profile</a>
                </li>

                <li class="nav-item admin-only" id="nav-manage-users">
                    <a class="nav-link" href="manage_users.php">Manage Users</a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        More
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item nav-link" href="key_people.php">Key People</a>
                        <a class="dropdown-item nav-link" href="faq.php">FAQ</a>
                    <!-- <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Something else here</a>
                    </div> -->
                </li>
            </ul>

            <span class="navbar-text">

                <form action="../scripts/logout.php">
                    <input type="submit" id="logout" value="Log Out" class="pbs-button pbs-button-grey">
                </form>

            </span>

        </div>
    </nav>
</div>
