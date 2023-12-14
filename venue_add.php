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
                <h1 class="h3 mb-2 text-gray-800"> Add Venue</h1>
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
                        $venueID = $_POST["venueID"];
                        $id = $_POST["id"];
                        $capacity = $_POST["capacity"];

                        $add_venue = $db->query("INSERT INTO venues (venueID,name,capacity,dateAdded) values ('$venueID','$name','$capacity',NOW())") or die($db->error);
                        $update_sequence = $db->query("UPDATE number_sequence SET last_number = '$id' WHERE page_name='venues'");
                        
                        if (!$add_venue) {
                            echo '<script>
    alert("Error saving venue data.");
</script>';
                        } else {
                            echo '<script>
    alert("Venue successfully saved.");
</script>';
                        }
                    }

                    $sequence = $db->query("SELECT * FROM number_sequence WHERE page_name = 'venues'");
                    $fetch = $sequence->fetchAll(PDO::FETCH_OBJ);
                    foreach ($fetch as $data) {
                        $newID = $data->last_number + 1;
                        $lengthID = strlen((string)$newID);
                        if ($lengthID == 1) $ID = "00000" . $newID;
                        elseif ($lengthID == 2) $ID = "0000" . $newID;
                        elseif ($lengthID == 3) $ID = "000" . $newID;
                        elseif ($lengthID == 4) $ID = "00" . $newID;
                        elseif ($lengthID == 5) $ID = "0" . $newID;
                        else $ID = $newID;
                    }
                    ?>
                    <div class="table-responsive">
                        <form role="form" method="post">

                            <div class="form-group">
                                <label>ID</label>
                                <input class="form-control" type="hidden" name="id" value="<?= $newID ?>" readonly>
                                <input class="form-control" type="text" name="venueID" value="<?= 'VN' . $ID ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label>Name</label>
                                <input class="form-control" type="text" placeholder="Name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label>Seat Capacity (Approx.)</label>
                                <input class="form-control" type="text" placeholder="Seat Capacity" name="capacity" required>
                            </div>
                            <button type="submit" class="btn btn-success btn-icon-split btn-sm keychainify-checked">
                                <span class="icon text-white-50">
                                    <i class="fas fa-plus"></i>
                                </span>
                                <span class="text">Add Venue</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'settings/footer.php'; ?>