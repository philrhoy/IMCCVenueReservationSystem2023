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
                <h1 class="h3 mb-2 text-gray-800">Reservation List</h1>
                <!-- <a href="user_add.php" class="btn btn-success btn-icon-split btn-sm keychainify-checked">
                    <span class="icon text-white-50">
                        <i class="fas fa-fw fa-plus"></i>
                    </span>
                    <span class="text">Add User</span>
                </a> -->
            </div>
            <div class="card shadow mb-4">

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hovered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Reservation ID</th>
                                    <th>Activity</th>
                                    <th>Venue</th>
                                    <th>Program</th>
                                    <th>Schedule Date</th>
                                    <th>Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $fetchReservations = "SELECT `schedules`.`reservationID` AS 'RESERVATION_ID',
                                                            `schedules`.id AS 'INT_RES_ID',
                                                            `venues`.name AS 'VENUE_NAME',
                                                            `schedules`.name AS 'ACTIVITY',
                                                            `program`.name AS 'PROGRAM_NAME',
                                                            `schedules`.date_start AS 'START_DATE',
                                                            `schedules`.date_end AS 'END_DATE' FROM `schedules` 
                                                        INNER JOIN `venues` 
                                                        ON `schedules`.venueID = `venues`.id
                                                        INNER JOIN `program` 
                                                        ON `schedules`.programID = `program`.id";

                                if($_SESSION['position']=='STO'){
                                    $userID = $_SESSION['id'];
                                    $fetchReservations .= " WHERE `schedules`.userID = '$userID'";
                                }
                               
                                $reservations = $db->query($fetchReservations);
                                // WHERE `schedules`.date_start BETWEEN '$filterStart' AND '$filterEnd'
                                $row_reservations = $reservations->fetchAll(PDO::FETCH_OBJ);
                                foreach ($row_reservations as $row) {
                                ?>
                                    <tr>
                                        <td><?= '<a href="view_reservation.php?reservation_id='.$row->INT_RES_ID.'" target="_blank">' . $row->RESERVATION_ID. '</a>'; ?></td>
                                        <td><?= $row->ACTIVITY; ?></td>
                                        <td><?= $row->VENUE_NAME; ?></td>
                                        <td><?= $row->PROGRAM_NAME; ?></td>
                                        <td><?= $row->START_DATE . "-" . $row->END_DATE; ?></td>
                                        <td align="center">
                                            <a href='edit_reservation.php?reservation_id=<?= $row->INT_RES_ID ?>' class="btn btn-primary btn-icon-split btn-sm keychainify-checked" target="_blank">
                                                <span class="icon text-white-50">
                                                    <i class="fas fa-edit"></i>
                                                </span>
                                                <span class="text">Update</span>
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