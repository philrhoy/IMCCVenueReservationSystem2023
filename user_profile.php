<?php
include 'settings/system.php';
include 'session.php';
include 'settings/header.php';
include "settings/sidebar.php";
include 'settings/topbar.php';
?>
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">

        <div class="container-fluid">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-2 text-gray-800"> User Profile</h1>
            </div>
            <div class="card shadow mb-4 col-xl-5 col-md-6">

                <div class="card-body">
                    <?php
                    if (isset($_POST['userID'])) {
                        $fname = $_POST["fname"];
                        $mname = $_POST["mname"];
                        $lname = $_POST["lname"];
                        $contact = $_POST["contact"];
                        $userID = $_POST["userID"];
                        $uname = $_POST["uname"];
                        $type = $_POST["type"];
                        $program =  "programID = NULL,";
                        $id = $_POST["id"];

                        if ($type == 'STO') {
                            $program = "programID =" . $_POST["program"] . ',';
                        }

                        if ($_SESSION['username'] != $uname) {
                            $users = $db->query("SELECT * FROM `users` WHERE username = '$uname'");
                            $exist = $users->rowCount();

                            if ($exist > 0) {
                                echo '<script>
                            alert("Username already exist.");
                            </script>';
                            } else {
                                $update_user = $db->query("UPDATE `users` SET first_name = '$fname', middle_name = '$mname', last_name = '$lname', username = '$uname', contact = '$contact', $program dateUpdated = NOW() WHERE userID = '$userID'") or die($db->error);

                                if (!$update_user) {
                                    echo '<script>
    alert("Error updating user data.");
    </script>';
                                } else {
                                    session_start();
                                    unset($_SESSION['name']);
                                    unset($_SESSION['username']);

                                    $_SESSION['username'] = $uname;
                                    $_SESSION['name'] = $fname . ' ' . $mname . ' ' . $lname;
                                    echo '<script>
    alert("User successfully updated.");
    </script>';
                                    header("Refresh:0");
                                }
                            }
                        } else {
                            $update_user = $db->query("UPDATE `users` SET first_name = '$fname', middle_name = '$mname', last_name = '$lname', contact = '$contact', $program dateUpdated = NOW() WHERE userID = '$userID'") or die($db->error);

                            if (!$update_user) {
                                echo '<script>
alert("Error updating user data.");
</script>';
                            } else {
                                session_start();
                                unset($_SESSION['name']);
                                unset($_SESSION['username']);

                                $_SESSION['username'] = $uname;
                                $_SESSION['name'] = $fname . ' ' . $mname . ' ' . $lname;
                                echo '<script>
alert("User successfully updated.");
</script>';
                                header("Refresh:0");
                            }
                        }
                    }

                    if (!$_SESSION['userID'] or empty($_SESSION['userID'])) {
                        header('location: calendar.php');
                    } else {
                        $id = $_SESSION['userID'];
                        $get_user = $db->query("SELECT * FROM `users` WHERE userID = '$id' ");
                        $result_user = $get_user->fetchAll(PDO::FETCH_OBJ);
                        foreach ($result_user as $obj) {
                            $e_ID = $obj->id;
                            $e_userID = $obj->userID;
                            $e_fname = $obj->first_name;
                            $e_mname = $obj->middle_name;
                            $e_lname = $obj->last_name;
                            $e_contact = $obj->contact;
                            $e_user = $obj->username;
                            $e_position = $obj->position;
                            $e_program = $obj->programID;
                        }
                    }
                    ?>
                    <div class="table-responsive">
                        <form role="form" method="post">

                            <div class="form-group">
                                <label>ID</label>
                                <input class="form-control" type="hidden" name="id" value="<?= $e_ID ?>" readonly>
                                <input class="form-control" type="text" name="userID" value="<?= $e_userID ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label>First Name</label>
                                <input class="form-control" type="text" name="fname" value="<?= $e_fname ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Middle Name (optional)</label>
                                <input class="form-control" type="text" name="mname" value="<?= $e_mname ?>">
                            </div>
                            <div class="form-group">
                                <label>Last Name</label>
                                <input class="form-control" type="text" name="lname" value="<?= $e_lname ?>">
                            </div>
                            <div class="form-group">
                                <label>Contact</label>
                                <input class="form-control" type="text" name="contact" value="<?= $e_contact ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Username</label>
                                <input class="form-control" type="text" name="uname" minlength="5" value="<?= $e_user ?>">
                            </div>
                            <div class="form-group">
                                <label>Type</label>
                                <select class="form-control" name="type" id="type" disabled>
                                    <option value="STO" <?php if ($e_position == "STO") {
                                                            echo 'selected';
                                                        } ?>>Student Officer</option>
                                    <option value="DSA" <?php if ($e_position == "DSA") {
                                                            echo 'selected';
                                                        } ?>>Department of Student Affairs</option>
                                    <option value="PTC" <?php if ($e_position == "PTC") {
                                                            echo 'selected';
                                                        } ?>>Property Custodian</option>
                                </select>
                                <small><i>Only admin user can change this.<i></small>
                            </div>

                            <div class="form-group">
                                <label id="program_name">Program</label>
                                <select class="form-control" name="program" id="program">
                                    <?php
                                    $getProgram = $db->query("SELECT * FROM program ORDER BY name ASC");
                                    $res = $getProgram->fetchAll(PDO::FETCH_OBJ);
                                    foreach ($res as $v) { ?>
                                        <option value="<?php echo $v->id; ?>" <?php if ($e_program == $v->id) {
                                                                                    echo 'selected';
                                                                                }  ?>><?php echo $v->name; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success btn-icon-split btn-sm keychainify-checked">
                                <span class="icon text-white-50">
                                    <i class="fas fa-save "></i>
                                </span>
                                <span class="text">Save</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'settings/footer.php'; ?>
    <script>
        $(document).ready(function() {

            if ($('#type').val() != 'STO') {
                $('#program').hide()
                $('#program_name').hide()
                $('#program').attr('required', false)
            } else {
                $('#program').show()
                $('#program_name').show()
                $('#program').attr('required', true)
            }

            $('#type').change(function() {
                if ($('#type').val() != 'STO') {
                    $('#program').hide('slow')
                    $('#program_name').hide('slow')
                    $('#program').attr('required', false)
                } else {
                    $('#program').show('slow')
                    $('#program_name').show('slow')
                    $('#program').attr('required', true)
                }
            })
        });
    </script>