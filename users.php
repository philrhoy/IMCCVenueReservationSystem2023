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
                <h1 class="h3 mb-2 text-gray-800">Users List</h1>
                <a href="user_add.php" class="btn btn-success btn-icon-split btn-sm keychainify-checked">
                    <span class="icon text-white-50">
                        <i class="fas fa-fw fa-plus"></i>
                    </span>
                    <span class="text">Add User</span>
                </a>
            </div>
            <div class="card shadow mb-4">

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hovered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>User ID</th>
                                    <th>Full Name</th>
                                    <th>Username</th>
                                    <th>Position</th>
                                    <th>Program</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $donor = $db->query("SELECT A.*,B.name as prg_name FROM `users` as A LEFT JOIN `program` as B ON A.programID = B.id ORDER BY A.last_name ASC");

                                $row_donor = $donor->fetchAll(PDO::FETCH_OBJ);
                                foreach ($row_donor as $row) {
                                ?>
                                    <tr>
                                        <td><?= '<a href="404.php?id='.$row->id.'">' . $row->userID. '</a>'; ?></td>
                                        <td><?= $row->last_name.', '.$row->first_name.' '.$row->middle_name ?></td>
                                        <td><?= $row->username; ?></td>
                                        <td><?= $row->position; ?></td>
                                        <td><?= $row->prg_name; ?></td>
                                        <td align="center">
                                        <a href="404.php?id=<?php echo $row->id; ?>" onclick="return confirm('Are you sure you want to reset the password for this user?')" class="btn btn-secondary btn-icon-split btn-sm keychainify-checked">
                                                <span class="icon text-white-50">
                                                    <i class="fas fa-edit"></i>
                                                </span>
                                                <span class="text">Reset Password</span>
                                            </a>
                                            <a href="404.php?id=<?php echo $row->id; ?>" class="btn btn-primary btn-icon-split btn-sm keychainify-checked">
                                                <span class="icon text-white-50">
                                                    <i class="fas fa-edit"></i>
                                                </span>
                                                <span class="text">Edit</span>
                                            </a>
                                            <a href="user_delete.php?id=<?php echo $row->id; ?>" onclick="return confirm('Are you sure you want to delete this user?')" class="btn btn-danger btn-icon-split btn-sm keychainify-checked">
                                                <span class="icon text-white-50">
                                                    <i class="fas fa-trash"></i>
                                                </span>
                                                <span class="text">Delete</span>
                                            </a>
                                        </td>
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