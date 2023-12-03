<?php
include 'settings/system.php';
include 'session.php';
include 'settings/header.php';
include "settings/sidebar.php";
include 'settings/topbar.php';
$donor = null;
?>
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">

        <div class="container-fluid">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-2 text-gray-800">Monthly Report</h1>
                <!-- <a href="venue_add.php" class="btn btn-success btn-icon-split btn-sm keychainify-checked">
                    <span class="icon text-white-50">
                        <i class="fas fa-fw fa-plus"></i>
                    </span>
                    <span class="text">Add Venue</span>
                </a> -->
            </div>

            <div class="card shadow mb-4">

                <div class="card-body">
                    <div class="form-group mb-2 mr-4"> <small>Please select a Date Range to Display Activities. You can use filters below to see specific result:</small>
                    </div>
                    <form class="form-inline col-sm-12 " method='POST'>

                        <div class="form-group mb-2 mr-4">
                            <label><small>Start Date</small> &nbsp;</label>
                            <input class="form-control form-control-sm" type="date" name="start_date" value="<?php if (isset($_POST['start_date'])) {
                                                                                                                    echo htmlentities($_POST['start_date']);
                                                                                                                } ?>">
                        </div>

                        <div class="form-group mb-2 mr-4">
                            <label><small>End Date</small> &nbsp;</label>
                            <input class="form-control form-control-sm" type="date" name="end_date" value="<?php if (isset($_POST['end_date'])) {
                                                                                                                echo htmlentities($_POST['end_date']);
                                                                                                            } ?>">
                        </div>

                        <div class="form-group mb-2 mr-4">
                            <label><small>Venue</small> &nbsp;</label>
                            <select class="form-control form-control-sm" name="venue">
                                <option value="0" selected>No selection</option>
                                <?php
                                $fetchVenues = $db->query("SELECT * FROM `venues` ORDER BY name ASC");

                                $row_donor = $fetchVenues->fetchAll(PDO::FETCH_OBJ);

                                foreach ($row_donor as $row) {
                                    if (htmlentities($_POST['venue']) == $row->id) {
                                ?>
                                        <option value="<?php echo $row->id ?>" selected> <?php echo $row->name ?> </option>
                                    <?php
                                    } else {
                                    ?>
                                        <option value="<?php echo $row->id ?>"> <?php echo $row->name ?> </option>
                                <?php
                                    }
                                }

                                ?>
                            </select>
                        </div>

                        <div class="form-group mb-2 mr-4">
                            <label><small>Program</small> &nbsp;</label>
                            <select class="form-control form-control-sm" name="program">
                                <option value="0" selected>No selection</option>
                                <?php
                                $fetchPrograms = $db->query("SELECT * FROM `program` ORDER BY name ASC");

                                $row_donor = $fetchPrograms->fetchAll(PDO::FETCH_OBJ);

                                foreach ($row_donor as $row) {
                                    if (htmlentities($_POST['program']) == $row->id) {
                                ?>
                                        <option value="<?php echo $row->id ?>" selected> <?php echo $row->name ?> </option>
                                    <?php
                                    } else {
                                    ?>
                                        <option value="<?php echo $row->id ?>"> <?php echo $row->name ?> </option>
                                <?php
                                    }
                                }
                                ?>

                            </select>
                        </div>

                        &nbsp;&nbsp;
                        <div class="form-group mb-2">
                            <button type="submit" name='submit' class="btn btn-success btn-icon-split btn-sm keychainify-checked">
                                <span class="icon text-white-50">
                                    <i class="fas fa-plus"></i>
                                </span>
                                <span class="text">GENERATE</span>
                            </button>
                        </div>
                    </form>

                    <div class="table-responsive">

                        <table class="table table-bordered table-hovered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Reservation ID</th>
                                    <th>Activity</th>
                                    <th>Schedule Date</th>
                                    <th>Program</th>
                                    <th>Venue</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                if (isset($_POST['submit'])) {
                                    $filterStart = $_POST['start_date'];
                                    $filterEnd = $_POST['end_date'];
                                    $filterVenue = $_POST['venue'];
                                    $filterProgram = $_POST['program'];

                                    $str_query = "SELECT `schedules`.`reservationID` AS 'RESERVATION_ID',
                                                        `schedules`.id AS 'INT_RES_ID',
                                                        `venues`.name AS 'VENUE_NAME',
                                                        `schedules`.name AS 'ACTIVITY',
                                                        `program`.name AS 'PROGRAM_NAME',
                                                        `schedules`.date_start AS 'START_DATE',
                                                        `schedules`.date_end AS 'END_DATE' FROM `schedules` 
                                                    INNER JOIN `venues` 
                                                    ON `schedules`.venueID = `venues`.id
                                                    INNER JOIN `program` 
                                                    ON `schedules`.programID = `program`.id 
                                                    WHERE (`schedules`.date_start BETWEEN '$filterStart' AND '$filterEnd') ";

                                    if ($filterVenue != "0") {
                                        $str_query .= "AND (`schedules`.venueID = '$filterVenue') ";
                                    }

                                    if ($filterProgram != "0") {
                                        $str_query .= "AND (`schedules`.programID = '$filterProgram')";
                                    }

                                    $donor = $db->query($str_query);
                                    $row_donor = $donor->fetchAll(PDO::FETCH_OBJ);
                                    foreach ($row_donor as $row) {
                                ?>
                                        <tr>
                                            <td><?= $row->RESERVATION_ID; ?></td>
                                            <td><?= '<a href="view_reservation.php?reservation_id=' . $row->INT_RES_ID . '" target="_blank">' . $row->ACTIVITY . '</a>'; ?></td>
                                            <td><?= $row->START_DATE . "-" . $row->END_DATE; ?></td>
                                            <td><?= $row->PROGRAM_NAME; ?></td>
                                            <td><?= $row->VENUE_NAME; ?></td>
                                        </tr>
                                <?php }
                                } ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'settings/footer.php'; ?>