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
                <h1 class="h3 mb-2 text-gray-800"> Update College Program</h1>
                <a href="programs.php" class="btn btn-info btn-icon-split btn-sm keychainify-checked">
                    <span class="icon text-white-50">
                        <i class="fas fa-arrow-left"></i>
                    </span>
                    <span class="text">List of College Programs</span>
                </a>
            </div>
            <div class="card shadow mb-4 col-xl-5 col-md-6">

                <div class="card-body">
                    <?php
                    if (isset($_POST['name'])) {
                        $name = $_POST["name"];
                        $color = $_POST["color"];
                        $id = $_POST["id"];

                        $update_program = $db->query("UPDATE program SET name = '$name', color = '$color', dateUpdated = NOW() WHERE id ='$id'") or die($db->error);

                        if (!$update_program) {
                            echo '<script>
    alert("Error updating college program data.");
</script>';
                        } else {
                            echo '<script>
    alert("College program successfully updated.");
</script>';
                        }
                    }
                    if (!$_GET['id'] or empty($_GET['id']) or $_GET['id'] == '') {
                        header('location: programs.php');
                    } else {
                        $id = $_GET['id'];
                        $get_program = $db->query("SELECT * FROM `program` WHERE id = '$id' ");
                        $result_program = $get_program->fetchAll(PDO::FETCH_OBJ);
                        foreach ($result_program as $obj) {
                            $e_ID = $obj->id;
                            $e_programID = $obj->programID;
                            $e_name = $obj->name;
                            $e_color = $obj->color;
                        }
                    }
                    ?>
                    <div class="table-responsive">
                        <form role="form" method="post">

                            <div class="form-group">
                                <label>ID</label>
                                <input class="form-control" type="hidden" name="id" value="<?= $e_ID ?>" readonly>
                                <input class="form-control" type="text" name="programID" value="<?= $e_programID ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label>Name</label>
                                <input class="form-control" type="text" placeholder="Name" name="name" value="<?= $e_name ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Color</label>
                                <input class="form-control" type="color" placeholder="" name="color" value="<?= $e_color ?>" required>
                            </div>
                            <button type="submit" class="btn btn-success btn-icon-split btn-sm keychainify-checked">
                                <span class="icon text-white-50">
                                    <i class="fas fa-plus"></i>
                                </span>
                                <span class="text">Update College Program</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'settings/footer.php'; ?>