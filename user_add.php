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
                    if (isset($_POST['name'])) {
                        $name = $_POST["name"];
                        $userID = $_POST["userID"];
                        $uname = $_POST["uname"];
                        $password = md5($_POST["uname"]);
                        $type = $_POST["type"];
                        $id = $_POST["id"];

                        $add_venue = $db->query("INSERT INTO `users` (userID,name,username,password,position,dateAdded) values ('$userID','$name','$uname','$password','$type',NOW())") or die($db->error);
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
                                <label>Full Name</label>
                                <input class="form-control" type="text" placeholder="Full Name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label>Username</label>
                                <input class="form-control" type="text" placeholder="Username" name="uname" required>
                            </div>
                            <div class="form-group">
                                <label>Type</label>
                                <select class="form-control" name="type" required>
                                <option value="STO">Student Officer</option>
                                <option value="DSA">Department of Student Affairs</option>
                                <option value="PTC">Property Custodian</option>
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