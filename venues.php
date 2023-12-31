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
                <h1 class="h3 mb-2 text-gray-800">Venue List</h1>
                <?php if ($_SESSION['position'] === 'PTC') { ?>
                    <a href="venue_add.php" class="btn btn-success btn-icon-split btn-sm keychainify-checked">
                        <span class="icon text-white-50">
                            <i class="fas fa-fw fa-plus"></i>
                        </span>
                        <span class="text">Add Venue</span>
                    </a>
                <?php } ?>
            </div>
            <div class="card shadow mb-4">

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hovered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Venue ID</th>
                                    <th>Name</th>
                                    <th>Seat Capacity (Approx.)</th>
                                    <?php if ($_SESSION['position'] === 'PTC') { ?>
                                        <th>Actions</th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $donor = $db->query("SELECT * FROM `venues` ORDER BY name ASC");

                                $row_donor = $donor->fetchAll(PDO::FETCH_OBJ);
                                foreach ($row_donor as $row) {
                                ?>
                                    <tr>
                                        <?php if ($_SESSION['position'] == 'STO') {?>
                                            <td><?php echo $row->venueID ?></td>
                                        <?php }else{?>
                                            <td><a href="venue_edit.php?id=<?php echo $row->id ?>"><?php echo $row->venueID ?></a></td>     
                                        <?php }?>
                                        <td><?= $row->name; ?></td>
                                        <td><?= $row->capacity; ?></td>
                                        <?php if ($_SESSION['position'] == 'PTC') { ?>
                                            <td align="center">
                                                <a href="venue_edit.php?id=<?php echo $row->id; ?>" class="btn btn-primary btn-icon-split btn-sm keychainify-checked">
                                                    <span class="icon text-white-50">
                                                        <i class="fas fa-edit"></i>
                                                    </span>
                                                    <span class="text">Edit</span>
                                                </a>
                                                <a href="venue_delete.php?id=<?php echo $row->id; ?>" onclick="return confirm('Are you sure you want to delete this?')" class="btn btn-danger btn-icon-split btn-sm keychainify-checked">
                                                    <span class="icon text-white-50">
                                                        <i class="fas fa-trash"></i>
                                                    </span>
                                                    <span class="text">Delete</span>
                                                </a>
                                            </td>
                                        <?php } ?>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'settings/footer.php'; ?>