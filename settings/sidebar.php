<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-imcc sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="calendar.php">
                <div class="sidebar-brand-icon ">
                    <img class="fas" src="./img/imcc.png" style="width:50px;height:50px;"></img>
                </div>
                <div class="sidebar-brand-text mx-3">Welcome <br /><small><?php
                                                                            if ($_SESSION['position'] == 'STO') {
                                                                                echo 'Student Officer!';
                                                                            } elseif ($_SESSION['position'] == 'DSA') {
                                                                                echo 'Department of Student Affairs!';
                                                                            } else {
                                                                                echo 'Property Custodian!';
                                                                            }
                                                                            ?></small></div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <?php
            $curPageName = substr($_SERVER["SCRIPT_NAME"], strrpos($_SERVER["SCRIPT_NAME"], "/") + 1);
            if ($curPageName == 'index.php')  $dash = 'active';
            if ($curPageName == 'calendar.php')  $calendar = 'active';
            if ($curPageName == 'venues.php' || $curPageName == 'venue_add.php' || $curPageName == 'venue_edit.php')  $venue = 'active';
            if ($curPageName == 'users.php' || $curPageName == 'user_add.php' || $curPageName == 'user_edit.php')  $user = 'active';
            if ($curPageName == 'programs.php' || $curPageName == 'program_add.php' || $curPageName == 'program_edit.php')  $program = 'active';
            ?>

            <!-- Nav Item - Calendar -->
            <li class="nav-item <?= $calendar; ?>">
                <a class="nav-link" href="calendar.php">
                    <i class="fas fa-fw fa-calendar"></i>
                    <span>Calendar</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Transactions
            </div>

            <!-- Nav Item - Reservations -->
            <?php
            if ($_SESSION['position'] == 'STO') { ?>
                <li class="nav-item <?= $reservation; ?>">
                    <a class="nav-link" href="404.php">
                        <i class="fas fa-fw fa-bars"></i>
                        <span>Submitted Reservations</span></a>
                </li>
            <?php
            } else {
            ?>
                <li class="nav-item <?= $reservation; ?>">
                    <a class="nav-link" href="404.php">
                        <i class="fas fa-fw fa-bars"></i>
                        <span>Pending Reservations</span></a>
                </li>
            <?php
            }
            ?>

            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Profiles
            </div>

            <!-- Nav Item - Venues -->
            <li class="nav-item <?= $venue; ?>">
                <a class="nav-link" href="venues.php">
                    <i class="fas fa-fw fa-building"></i>
                    <span>Venues</span></a>
            </li>

            <!-- Nav Item - Programs -->
            <li class="nav-item <?= $program; ?>">
                <a class="nav-link" href="programs.php">
                    <i class="fas fa-fw fa-flag"></i>
                    <span>Programs</span></a>
            </li>

            <!-- Nav Item - Users -->
            <li class="nav-item <?= $user; ?>">
                <a class="nav-link" href="users.php">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Users</span></a>
            </li>
            <!-- Nav Item - Pages Collapse Menu -->
            <!-- <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Components</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Custom Components:</h6>
                        <a class="collapse-item" href="buttons.html">Buttons</a>
                        <a class="collapse-item" href="cards.html">Cards</a>
                    </div>
                </div>
            </li> -->

            <!-- Nav Item - Utilities Collapse Menu -->
            <!-- <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>Utilities</span>
                </a>
                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Custom Utilities:</h6>
                        <a class="collapse-item" href="utilities-color.html">Colors</a>
                        <a class="collapse-item" href="utilities-border.html">Borders</a>
                        <a class="collapse-item" href="utilities-animation.html">Animations</a>
                        <a class="collapse-item" href="utilities-other.html">Other</a>
                    </div>
                </div>
            </li> -->

            <!-- Divider -->
            <!-- <hr class="sidebar-divider"> -->

            <!-- Heading -->
            <!-- <div class="sidebar-heading">
                Addons
            </div> -->

            <!-- Nav Item - Pages Collapse Menu -->
            <!-- <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true" aria-controls="collapsePages">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Pages</span>
                </a>
                <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Login Screens:</h6>
                        <a class="collapse-item" href="login.php">Login</a>
                        <a class="collapse-item" href="register.html">Register</a>
                        <a class="collapse-item" href="forgot-password.html">Forgot Password</a>
                        <div class="collapse-divider"></div>
                        <h6 class="collapse-header">Other Pages:</h6>
                        <a class="collapse-item" href="404.html">404 Page</a>
                        <a class="collapse-item" href="blank.php">Blank Page</a>
                    </div>
                </div>
            </li> -->

            <!-- Nav Item - Charts -->
            <!-- <li class="nav-item">
                <a class="nav-link" href="charts.html">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Charts</span></a>
            </li> -->

            <!-- Nav Item - Tables -->
            <!-- <li class="nav-item">
                <a class="nav-link" href="tables.html">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Tables</span></a>
            </li> -->

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

            <!-- Sidebar Message -->
            <!-- <div class="sidebar-card d-none d-lg-flex">
                <img class="sidebar-card-illustration mb-2" src="img/undraw_rocket.svg" alt="...">
                <p class="text-center mb-2"><strong>SB Admin Pro</strong> is packed with premium features, components, and more!</p>
                <a class="btn btn-success btn-sm" href="https://startbootstrap.com/theme/sb-admin-pro">Upgrade to Pro!</a>
            </div> -->

        </ul>
        <!-- End of Sidebar -->