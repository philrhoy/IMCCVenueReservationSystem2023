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
                <h1 class="h3 mb-2 text-gray-800"> Update Venue</h1>
                <a href="venues.php" class="btn btn-dark btn-icon-split btn-sm keychainify-checked">
                    <span class="icon text-white-50">
                        <i class="fas fa-arrow-left"></i>
                    </span>
                    <span class="text">List of Venues</span>
                </a>
            </div>
            <div class="card shadow mb-4 col-xl-5 col-md-6">

                <div class="card-body">
                    <?php
                    if (isset($_POST['name'])) {
                        $name = $_POST["name"];
                        $capacity = $_POST["capacity"];
                        $id = $_POST["id"];

                        $update_venue = $db->query("UPDATE venues SET `name` = '$name', `capacity` = '$capacity', `dateUpdated` = NOW() WHERE `id`= $id") or die($db->error);
                        if (!$update_venue) {
                            echo '<script>
    alert("Error updating venue data.");
</script>';
                        } else {
                            echo '<script>
    alert("Venue successfully updated.");
</script>';
                        }
                    }

                    if (!$_GET['id'] or empty($_GET['id']) or $_GET['id'] == '') {
                        header('location: venues.php');
                    } else {
                        $id = $_GET['id'];
                        $get_venue = $db->query("SELECT * FROM `venues` WHERE id = '$id' ");
                        $result_venue = $get_venue->fetchAll(PDO::FETCH_OBJ);
                        foreach ($result_venue as $obj) {
                            $e_ID = $obj->id;
                            $e_name = $obj->name;
                            $e_venueID = $obj->venueID;
                            $e_capacity = $obj->capacity;
                        }
                    }
                    ?>
                    <div class="table-responsive">
                        <form role="form" method="post">

                            <div class="form-group">
                                <label>ID</label>
                                <input class="form-control" type="hidden" name="id" value="<?= $e_ID ?>" readonly>
                                <input class="form-control" type="text" name="venueID" value="<?= $e_venueID ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label>Name</label>
                                <input class="form-control" type="text" placeholder="Name" name="name" value="<?= $e_name ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Seat Capacity (Approx.)</label>
                                <input class="form-control" type="text" placeholder="Seat Capacity" name="capacity" value="<?= $e_capacity ?>" required>
                            </div>
                            <button type="submit" class="btn btn-success btn-icon-split btn-sm keychainify-checked">
                                <span class="icon text-white-50">
                                    <i class="fas fa-save"></i>
                                </span>
                                <span class="text">Update Venue</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'settings/footer.php'; ?>