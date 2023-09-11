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
                <h1 class="h3 mb-2 text-gray-800"> Add User</h1>
                <a href="users.php" class="btn btn-info btn-icon-split btn-sm keychainify-checked">
                    <span class="icon text-white-50">
                        <i class="fas fa-arrow-left"></i>
                    </span>
                    <span class="text">List of Users</span>
                </a>
            </div>
            <div class="card shadow mb-4 col-xl-5 col-md-6">

                <div class="card-body">
                    <?php
                    if (isset($_POST['uname'])) {
                        $fname = $_POST["fname"];
                        $mname = $_POST["mname"];
                        $lname = $_POST["lname"];
                        $contact = $_POST["contact"];
                        $userID = $_POST["userID"];
                        $uname = $_POST["uname"];
                        $password = md5($_POST["uname"]);
                        $type = $_POST["type"];
                        $program = "";
                        $id = $_POST["id"];

                        if ($type == 'STO') {
                            $program = $_POST["program"];
                        }

                        $add_venue = $db->query("INSERT INTO `users` (userID,first_name,middle_name,last_name,contact,username,password,position,programID,dateAdded) values ('$userID','$fname','$mname','$lname','$contact','$uname','$password','$type','$program',NOW())") or die($db->error);
                        $update_sequence = $db->query("UPDATE number_sequence SET last_number = '$id' WHERE page_name='users'");

                        if (!$add_venue) {
                            echo '<script>
    alert("Error saving user data.");
</script>';
                        } else {
                            echo '<script>
    alert("User successfully saved.");
</script>';
                        }
                    }

                    $sequence = $db->query("SELECT * FROM number_sequence WHERE page_name = 'users'");
                    $fetch = $sequence->fetchAll(PDO::FETCH_OBJ);
                    foreach ($fetch as $data) {
                        $newID = $data->last_number + 1;
                        $lengthID = strlen((string)$newID);
                        if ($lengthID == 1) $ID = "000" . $newID;
                        elseif ($lengthID == 2) $ID = "00" . $newID;
                        elseif ($lengthID == 3) $ID = "0" . $newID;
                        else $ID = $newID;
                    }
                    ?>
                    <div class="table-responsive">
                        <form role="form" method="post">

                            <div class="form-group">
                                <label>ID</label>
                                <input class="form-control" type="hidden" name="id" value="<?= $newID ?>" readonly>
                                <input class="form-control" type="text" name="userID" value="<?= 'USR' . $ID ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label>First Name</label>
                                <input class="form-control" type="text" name="fname" required>
                            </div>
                            <div class="form-group">
                                <label>Middle Name (optional)</label>
                                <input class="form-control" type="text" name="mname">
                            </div>
                            <div class="form-group">
                                <label>Last Name</label>
                                <input class="form-control" type="text" name="lname">
                            </div>
                            <div class="form-group">
                                <label>Contact</label>
                                <input class="form-control" type="text" name="contact" required>
                            </div>
                            <div class="form-group">
                                <label>Username</label>
                                <input class="form-control" type="text" name="uname" minlength="5" required>
                            </div>
                            <div class="form-group">
                                <label>Type</label>
                                <select class="form-control" name="type" id="type" required>
                                    <option value="STO">Student Officer</option>
                                    <option value="DSA">Department of Student Affairs</option>
                                    <option value="PTC">Property Custodian</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label id="program_name">Program</label>
                                <select class="form-control" name="program" id="program">
                                    <?php
                                    $getProgram = $db->query("SELECT * FROM program ORDER BY name ASC");
                                    $res = $getProgram->fetchAll(PDO::FETCH_OBJ);
                                    foreach ($res as $v) { ?>
                                        <option value="<?php echo $v->id; ?>" ?><?php echo $v->name; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success btn-icon-split btn-sm keychainify-checked">
                                <span class="icon text-white-50">
                                    <i class="fas fa-plus"></i>
                                </span>
                                <span class="text">Add User</span>
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
                $('#program').hide('slow')
                $('#program_name').hide('slow')
                $('#program').attr('required', false)
            } else {
                $('#program').show('slow')
                $('#program_name').show('slow')
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