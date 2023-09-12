<?php
include 'settings/system.php';
include 'settings/header.php';
?>

<body class="login-bg-gradient-imcc">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-imcc">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-6">
                                <br />
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-5">Update your password!</h1>
                                    </div>
                                    <form class="user" method="post">
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user" id="npassword" name="npassword" aria-describedby="emailHelp" required placeholder="New Password" autofocus minlength="5">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user" id="cpassword" name="cpassword" required placeholder="Confirm Password" minlength="5">
                                        </div>
                                        <div class="form-group" style="text-align:right">
                                            <label for="show" class="password">
                                                <input type="checkbox" class="form-control-user password" id="show" value="" name="show">
                                                Show password</label>
                                        </div>
                                        <div class="form-group" style="text-align:center;">
                                            <small class="btn-outline-danger fail"><i>Password don't match.</i></small>

                                            <small class="btn-outline-success success"><i>Password match.</i></small>
                                        </div>
                                        <button type="submit" name="submit" id="submit" class="btn btn-primary  btn-user btn-block">
                                            Update Password
                                        </button>
                                    </form>
                                    <?php
                                    if (isset($_POST['submit'])) {
                                        $npassword = $_POST['npassword'];
                                        $cpassword = $_POST['cpassword'];

                                        if ($npassword === $cpassword) {

                                            $hash = md5($npassword);

                                            $update_password = $db->query("UPDATE `users` SET password = '$hash', change_pass=1, dateUpdated = NOW() WHERE username='" . $_SESSION['user'] . "'");

                                            if ($update_password) {
                                                $q = $db->query("SELECT * FROM `users` WHERE `username` = '" . $_SESSION['user'] . "' AND `password` = '$hash' LIMIT 1 ");
                                                $rows = $q->fetchAll(PDO::FETCH_OBJ);
                                                foreach ($rows as $row_user) {

                                                    $_SESSION['id'] = $row_user->id;
                                                    $_SESSION['position'] = $row_user->position;
                                                    $_SESSION['name'] = $row_user->name;
                                                    echo "<script type='text/javascript'>
                                                    alert('Password successfully updated.'); ";
                                                    echo "window.location= 'calendar.php';";
                                                    echo "</script>";
                                                }
                                            }
                                        } else {
                                    ?>
                                            <script>
                                                alert("Password don't match!");
                                            </script>
                                    <?php
                                        }
                                    }
                                    ?>
                                    <hr />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
    <script>
        $(document).ready(function() {

            if ($('#npassword').val() == '' && $('#cpassword').val() == '') {
                $('.fail').hide()
                $('.success').hide()
                $('#submit').attr('disabled', true)
            }
            if ($('#npassword').val() == $('#cpassword').val() && ($('#npassword').val() != '' || $('#cpassword').val() != '')) {
                $('.fail').hide()
                $('.success').show()
                $('#submit').attr('disabled', false)
            }
            if ($('#npassword').val() != $('#cpassword').val() && ($('#npassword').val() != '' || $('#cpassword').val() != '')) {
                $('.fail').show()
                $('.success').hide()
                $('#submit').attr('disabled', true)
            }

            $('#npassword').change(function() {
                if ($('#npassword').val() == $('#cpassword').val()) {

                    $('.fail').hide()
                    $('.success').show()
                    $('#submit').attr('disabled', false)
                } else {
                    $('.fail').show()
                    $('.success').hide()
                    $('#submit').attr('disabled', true)
                }
            })
            $('#cpassword').change(function() {
                if ($('#npassword').val() == $('#cpassword').val()) {

                    $('.fail').hide()
                    $('.success').show()
                    $('#submit').attr('disabled', false)
                } else {
                    $('.fail').show()
                    $('.success').hide()
                    $('#submit').attr('disabled', true)
                }
            })

            let showpass = document.getElementById("show");
            $('#show').change(function() {
                if (showpass.checked) {
                    $('#npassword').attr('type', 'text')
                    $('#cpassword').attr('type', 'text')
                } else {
                    $('#npassword').attr('type', 'password')
                    $('#cpassword').attr('type', 'password')
                }
            })
        });
    </script>
</body>

</html>