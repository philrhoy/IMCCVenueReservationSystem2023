<?php
include 'settings/system.php';
include 'settings/header.php';
?>

<body class="login-bg-gradient-imcc">
    <!--<img src="img/imcc2.png" style="position: absolute; top:6%; left:7%; width:300px;">-->
    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-imcc" style="border-radius: 50px;">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block">
                                <div class="text-center text-main p-5" style="margin-top:20%;">
                                    <h1 class="h1 text-gray-900 mb-3">Venue Reservation System</h1>
                                    <h6 class="h56 text-gray-900">This site is exclusively used for reservation processing of student activities and events within the IMCC Institution.</h6>
                                </div>
                                <div class="text-center">
                                    <img src="img/calendar.png" style="width:230px;height:150px;">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <img src="img/imcc2.png" style="width:150px;height:150px;">
                                    </div>
                                    <br />
                                    <br />
                                    <form class="user" method="post">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user" id="username" name="username" aria-describedby="emailHelp" required placeholder="Username" autofocus>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user" id="password" name="password" required placeholder="Password">
                                        </div>
                                        <div class="form-group" style="text-align:right">
                                            <label for="show" class="password">
                                                <input type="checkbox" class="form-control-user password" id="show" value="" name="show">
                                                Show password</label>
                                        </div>
                                        <button type="submit" name="submit" id="submit" class="btn btn-primary  btn-user btn-block">
                                            Login
                                        </button>
                                    </form>
                                    <?php
                                    if (isset($_POST['submit'])) {
                                        $username = trim($_POST['username']);
                                        $password = $_POST['password'];

                                        $hash = md5($password);

                                        $q = $db->query("SELECT * FROM `users` WHERE `username` = '$username' AND `password` = '$hash' LIMIT 1 ");

                                        $count = $q->rowCount();
                                        $rows = $q->fetchAll(PDO::FETCH_OBJ);

                                        if ($count > 0) {
                                            foreach ($rows as $row_admin) {

                                                $_SESSION['user'] = $row_admin->username;

                                                if ($row_admin->change_pass == 1) {
                                                    $_SESSION['id'] = $row_admin->id;
                                                    $_SESSION['position'] = $row_admin->position;
                                                    $_SESSION['userID'] = $row_admin->userID;
                                                    $_SESSION['username'] = $row_admin->username;
                                                    $_SESSION['name'] = $row_admin->first_name . ' ' . $row_admin->middle_name . ' ' . $row_admin->last_name;
                                                    $_SESSION['name2'] = $row_admin->first_name . ' ' . $row_admin->last_name;
                                                    header('location: calendar.php');
                                                } else {
                                                    header('location: change_password.php');
                                                }
                                            }
                                        } else {
                                    ?>
                                            <script>
                                                alert("Invalid credentials.");
                                            </script>
                                    <?php
                                        }
                                    }
                                    ?>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="404.php">Forgot Password?</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
    <br />
    <br />
    <br />
    <br />
    <br />
    <!-- Footer -->
    <footer class="sticky-footer bg-white">
        <div class="container my-auto">
            <div class="copyright text-center my-auto">
                <span>Copyright C* 2023 IMCC Revenue Reservation System of Activities </span>
            </div>
        </div>
    </footer>
    <!-- End of Footer -->
    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
    <script>
        $(document).ready(function() {
            let showpass = document.getElementById("show");
            $('#show').change(function() {
                if (showpass.checked) {
                    $('#password').attr('type', 'text')
                } else {
                    $('#password').attr('type', 'password')
                }
            })
        });
    </script>
</body>

</html>