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
                <h1 class="h3 mb-2 text-gray-800"> Add Program</h1>
                <a href="programs.php" class="btn btn-dark btn-icon-split btn-sm keychainify-checked">
                    <span class="icon text-white-50">
                        <i class="fas fa-arrow-left"></i>
                    </span>
                    <span class="text">List of Programs</span>
                </a>
            </div>
            <div class="card shadow mb-4 col-xl-5 col-md-6">

                <div class="card-body">
                    <?php
                    if (isset($_POST['name'])) {
                        $name = $_POST["name"];
                        $programID = $_POST["programID"];
                        $color = $_POST["color"];
                        $org = $_POST["org"];
                        $id = $_POST["id"];

                        $add_venue = $db->query("INSERT INTO program (programID,name,color,incharge_organization,dateAdded) values ('$programID','$name','$color','$org',NOW())") or die($db->error);
                        $update_sequence = $db->query("UPDATE number_sequence SET last_number = '$id' WHERE page_name='programs'");

                        if (!$add_venue) {
                            echo '<script>
    alert("Error saving college program data.");
</script>';
                        } else {
                            echo '<script>
    alert("College program successfully saved.");
</script>';
                        }
                    }

                    $sequence = $db->query("SELECT * FROM number_sequence WHERE page_name = 'programs'");
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
                                <input class="form-control" type="text" name="programID" value="<?= 'PRG' . $ID ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label>Name</label>
                                <input class="form-control" type="text"  name="name" required autofocus>
                            </div>
                            <div class="form-group">
                                <label>In-charge Organization</label>
                                <input class="form-control" type="text"  name="org" required>
                            </div>
                            <div class="form-group">
                                <label>Color</label>
                                <input class="form-control" type="color" placeholder="" name="color" required>
                            </div>
                            <button type="submit" class="btn btn-success btn-icon-split btn-sm keychainify-checked">
                                <span class="icon text-white-50">
                                    <i class="fas fa-plus"></i>
                                </span>
                                <span class="text">Add Program</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'settings/footer.php'; ?>