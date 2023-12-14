<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">

    <!-- Main Content -->
    <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

            <!-- Sidebar Toggle (Topbar) -->
            <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                <i class="fa fa-bars"></i>
            </button>

            <!-- Topbar Search -->
            <div class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                <div class="input-group">
                    <!-- Page Heading -->
                    <h5 class="form-control  border-0 text-gray-800"><?= NAME_; ?></h5>
                </div>
            </div>

            <!-- Topbar Navbar -->
            <ul class="navbar-nav ml-auto">

                <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                <li class="nav-item dropdown no-arrow d-sm-none">
                    <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-search fa-fw"></i>
                    </a>
                    <!-- Dropdown - Messages -->
                    <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                        <form class="form-inline mr-auto w-100 navbar-search">
                            <div class="input-group">
                                <input type="text" class="form-control bg-light border-0 small" name="searchfor" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button">
                                        <i class="fas fa-search fa-sm"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </li>

                <!-- Nav Item - Alerts -->
                <?php 
                    if(isset($_GET['notificationRef']) && isset($_GET['isRead'])){
                        $notificationRef = $_GET['notificationRef'];
                        $updateNotif = $db->query("UPDATE notifications 
                        SET isRead = '1' WHERE id='$notificationRef'");
                    }
                    //Get user notifications
                    $currentUserID = $_SESSION["id"];
                    $currentUserType = $_SESSION["position"];
                    $getNotif = $db->query("SELECT * FROM notifications 
                                            WHERE (recipient = '$currentUserID' OR notifyToAllUserType = '$currentUserType')  ORDER BY id DESC");
                    $fetchNotifications = $getNotif->fetchAll(PDO::FETCH_OBJ);
                    $notificationCount = 0;

                    // echo $_SERVER['HTTP_HOST'] . "<br>";
                    // echo $_SERVER['REQUEST_URI'] . "<br>";

                    foreach ($fetchNotifications as $data)
                    {
                        if($data->isRead == 0){
                            $notificationCount++;
                        }
                    }
                
                ?>
                <li class="nav-item dropdown no-arrow mx-1">
                    <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-bell fa-fw"> </i>
                        <!-- Counter - Alerts -->
                        <span class="badge badge-danger badge-counter" style="<?php echo (($notificationCount == 0) ? "visibility: hidden;": "visibility: visible;")?>"><?php echo $notificationCount; ?></span>
                    </a>
                    <!-- Dropdown - Alerts -->
                    <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
                        <h6 class="dropdown-header">
                            Notifications
                        </h6>
                        <?php 
                          foreach ($fetchNotifications as $data){
                            ?>
                            <a class="dropdown-item d-flex align-items-center" href="<?php echo $data->link."&notificationRef=".$data->id."&isRead=1";?>">
                                <div class="mr-3">
                                    <?php 

                                    $color = "success";
                                    $icon = "fa-doc-alt";

                                    if ($data->type == "CREATE") 
                                    { 
                                        $color = "success";
                                        $icon = "fa-plus";
                                    } 
                                    if ($data->type == "UPDATE") 
                                    { 
                                        $color = "info";
                                        $icon = "fa-pen";
                                    } 
                                    if ($data->type == "APPROVE") 
                                    { 
                                        $color = "success";
                                        $icon = "fa-check";
                                    } 
                                    if ($data->type == "REJECT") 
                                    { 
                                        $color = "warning";
                                        $icon = "fa-times";
                                    } 
                                    ?>
                                    <div class="icon-circle bg-<?= $color; ?>">
                                        <i class="fas <?= $icon; ?> text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="small text-gray-500"><?= date('m/d/Y h:i a', strtotime($data->dateAdded));?></div>
                                    <span class="font-weight-bold"><?= $data->details;?></span>
                                </div>
                            </a>
                            <?php 
                          }

                          if(sizeof($fetchNotifications) == 0){
                            ?>
                            <a class="dropdown-item d-flex align-items-center" >
                                <div class="mr-3">
                                    <div class="icon-circle bg-primary">
                                        <i class="fas fa-file-alt text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <!-- <div class="small text-gray-500">December 12, 2019</div> -->
                                    <span class="font-weight-bold">No notifications</span>
                                </div>
                            </a>
                            <?php 
                          }

                        ?>
                        <!-- <a class="dropdown-item d-flex align-items-center" href="#">
                            <div class="mr-3">
                                <div class="icon-circle bg-primary">
                                    <i class="fas fa-file-alt text-white"></i>
                                </div>
                            </div>
                            <div>
                                <div class="small text-gray-500">December 12, 2019</div>
                                <span class="font-weight-bold">A new monthly report is ready to download!</span>
                            </div>
                        </a>
                        <a class="dropdown-item d-flex align-items-center" href="#">
                            <div class="mr-3">
                                <div class="icon-circle bg-success">
                                    <i class="fas fa-donate text-white"></i>
                                </div>
                            </div>
                            <div>
                                <div class="small text-gray-500">December 7, 2019</div>
                                $290.29 has been deposited into your account!
                            </div>
                        </a>
                        <a class="dropdown-item d-flex align-items-center" href="#">
                            <div class="mr-3">
                                <div class="icon-circle bg-warning">
                                    <i class="fas fa-exclamation-triangle text-white"></i>
                                </div>
                            </div>
                            <div>
                                <div class="small text-gray-500">December 2, 2019</div>
                                Spending Alert: We've noticed unusually high spending for your account.
                            </div>
                        </a>
                      -->
                      <!-- <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>  -->
                    </div>
                </li>


                <div class="topbar-divider d-none d-sm-block"></div>

                <!-- Nav Item - User Information -->
                <li class="nav-item dropdown no-arrow">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= $_SESSION['name']; ?></span>
                        <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                    </a>
                    <!-- Dropdown - User Information -->
                    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="user_profile.php">
                            <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                            Profile
                        </a>
                        <a class="dropdown-item" href="404.php">
                            <i class="fas fa-lock fa-sm fa-fw mr-2 text-gray-400"></i>
                            Change Password
                        </a>
                        <!-- <a class="dropdown-item" href="#">
                            <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                            Activity Log
                        </a> -->
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                            Logout
                        </a>
                    </div>
                </li>

            </ul>

        </nav>
        <!-- End of Topbar -->