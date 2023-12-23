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
                    <div class="form-group form-inline" <?php echo ($_SESSION["position"] == "STO" ? "hidden disabled" : "") ?>>
                        <label for=""><small>Filter by Status</small> &nbsp;&nbsp;</label>
                        <select class="form-control form-control-sm" name="filterByStatus" id="filterByStatus">
                            <option value="0">No Selection</option>
                            <option value="P">Pending</option>
                            <option value="A" <?php echo ((($_SESSION["position"] == "PTC") ? "selected" : "")); ?>>Approved</option>
                            <option value="R">Rejected</option>
                            <!-- <option value="AA"<?php echo ($_SESSION["position"] != "DSA" ? "hidden disabled" : "") ?>>Approved by Admin</option>
                            <option value="RA"<?php echo ($_SESSION["position"] != "DSA" ? "hidden disabled" : "") ?>>Rejected by Admin</option> -->
                        </select>
                        &nbsp;&nbsp;
                        <label><small>Venue</small> &nbsp;</label>
                        <select class="form-control form-control-sm" name="filterByVenue" id="filterByVenue">
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
                        &nbsp;&nbsp;
                        <label><small>Program</small> &nbsp;</label>
                        <select class="form-control form-control-sm" name="filterByProgram" id="filterByProgram">
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
                        &nbsp;&nbsp;
                        <label><small>User Type</small> &nbsp;</label>
                        <select class="form-control form-control-sm" name="filterByUserType" id="filterByUserType">
                            <option value="0" selected>No Selection</option>
                            <option value="S">Student</option>
                            <option value="D">Admin</option>
                        </select>
                        <div class="form-inline" style="margin-left: auto;">
                            <input class="form-control form-control-sm" type="text" name="filterSearch" id="filterSearch" placeholder="Search title" style="margin-left: auto;">
                        </div>

                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm table-bordered table-hovered" id="<?php echo ($_SESSION["position"] == "STO" ? "dataTable" : "") ?>" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Date Added</th>
                                    <th>Activity</th>
                                    <th>Schedule Date</th>
                                    <th>Program</th>
                                    <th>Venue</th>
                                    <th>Status</th>
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
                                                            `schedules`.date_end AS 'END_DATE',
                                                            `schedules`.status AS 'STATUS',
                                                            `schedules`.date_added as 'DATE_ADDEDS' FROM `schedules` 
                                                        INNER JOIN `venues` 
                                                        ON `schedules`.venueID = `venues`.id
                                                        INNER JOIN `program` 
                                                        ON `schedules`.programID = `program`.id";

                                if ($_SESSION['position'] == 'STO') {
                                    $userID = $_SESSION['id'];
                                    $fetchReservations .= " WHERE `schedules`.userID = '$userID'";
                                }

                                if ($_SESSION["position"] == "PTC") {
                                    $fetchReservations .= " WHERE `schedules`.status = 'A'";
                                }

                                $reservations = $db->query($fetchReservations);
                                // WHERE `schedules`.date_start BETWEEN '$filterStart' AND '$filterEnd'
                                $row_reservations = $reservations->fetchAll(PDO::FETCH_OBJ);
                                foreach ($row_reservations as $row) {
                                    $statusStr = "Pending for Approval";
                                    $statusColor = "";
                                    switch ($row->STATUS) {
                                        case "P":
                                            $statusColor = "badge-warning";
                                            $statusStr = "Pending for Approval";
                                            break;
                                        case "A":
                                            $statusColor = "badge-success";
                                            $statusStr = "Approved";
                                            break;
                                        case "R":
                                            $statusColor = "badge-danger";
                                            $statusStr = "Rejected";
                                            break;
                                    }
                                ?>
                                    <tr>
                                        <td><?= $row->DATE_ADDEDS; ?></td>
                                        <td><?= '<a href="view_reservation.php?reservation_id=' . $row->INT_RES_ID . '" target="_blank">' . $row->ACTIVITY . '</a>'; ?></td>
                                        <td><?= $row->START_DATE . "-" . $row->END_DATE; ?></td>
                                        <td><?= $row->PROGRAM_NAME; ?></td>
                                        <td><?= $row->VENUE_NAME; ?></td>
                                        <td align="center"><span class="badge <?= $statusColor ?>"><?= $statusStr; ?></span></td>
                                        <td align="center">
                                            <a href='edit_reservation.php?reservation_id=<?= $row->INT_RES_ID ?>' 
                                                class="btn btn-primary btn-icon-split btn-sm keychainify-checked" 
                                                target="_blank" 
                                                style="<?php echo ($row->STATUS == 'A' && $_SESSION['position'] == 'STO' ? "visibility: hidden;" : "");?>">
                                                
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
                        <script>
                            $(document).ready(function() {
                                $("#filterByStatus").on("change", function() {
                                    var statusVal = $(this).val();
                                    var venueVal = $("#filterByVenue").val();
                                    var programVal = $("#filterByProgram").val();
                                    var searchVal = $("#filterSearch").val();
                                    var userTypeVal = $("#filterByUserType").val();
                                    $.ajax({
                                        url: "fetch_filtered_reservations.php",
                                        type: "POST",
                                        data: {
                                            status: statusVal,
                                            venue: venueVal,
                                            program: programVal,
                                            userType: userTypeVal,
                                            search: searchVal
                                        },
                                        beforeSend: function() {

                                        },
                                        success: function(data) {
                                            $(".table-responsive").html(data);
                                        }
                                    });
                                });

                                $("#filterByVenue").on("change", function() {
                                    var statusVal = $("#filterByStatus").val();
                                    var programVal = $("#filterByProgram").val();
                                    var searchVal = $("#filterSearch").val();
                                    var userTypeVal = $("#filterByUserType").val();
                                    var venueVal = $(this).val();
                                    $.ajax({
                                        url: "fetch_filtered_reservations.php",
                                        type: "POST",
                                        data: {
                                            status: statusVal,
                                            venue: venueVal,
                                            program: programVal,
                                            userType: userTypeVal,
                                            search: searchVal
                                        },
                                        beforeSend: function() {

                                        },
                                        success: function(data) {
                                            $(".table-responsive").html(data);
                                        }
                                    });
                                });

                                $("#filterByProgram").on("change", function() {
                                    var statusVal = $("#filterByStatus").val();
                                    var venueVal = $("#filterByVenue").val();
                                    var searchVal = $("#filterSearch").val();
                                    var userTypeVal = $("#filterByUserType").val();
                                    var programVal = $(this).val();
                                    $.ajax({
                                        url: "fetch_filtered_reservations.php",
                                        type: "POST",
                                        data: {
                                            status: statusVal,
                                            venue: venueVal,
                                            program: programVal,
                                            userType: userTypeVal,
                                            search: searchVal
                                        },
                                        beforeSend: function() {

                                        },
                                        success: function(data) {
                                            $(".table-responsive").html(data);
                                        }
                                    });
                                });

                                $("#filterSearch").on("change", function() {
                                    var statusVal = $("#filterByStatus").val();
                                    var venueVal = $("#filterByVenue").val();
                                    var programVal = $("#filterByProgram").val();
                                    var userTypeVal = $("#filterByUserType").val();
                                    var searchVal = $(this).val();
                                    $.ajax({
                                        url: "fetch_filtered_reservations.php",
                                        type: "POST",
                                        data: {
                                            status: statusVal,
                                            venue: venueVal,
                                            program: programVal,
                                            userType: userTypeVal,
                                            search: searchVal
                                        },
                                        beforeSend: function() {

                                        },
                                        success: function(data) {
                                            $(".table-responsive").html(data);
                                        }
                                    });
                                });

                                $("#filterByUserType").on("change", function() {
                                    var statusVal = $("#filterByStatus").val();
                                    var venueVal = $("#filterByVenue").val();
                                    var programVal = $("#filterByProgram").val();
                                    var searchVal = $("#filterSearch").val();
                                    var userTypeVal = $(this).val();
                                    $.ajax({
                                        url: "fetch_filtered_reservations.php",
                                        type: "POST",
                                        data: {
                                            status: statusVal,
                                            venue: venueVal,
                                            program: programVal,
                                            userType: userTypeVal,
                                            search: searchVal
                                        },
                                        beforeSend: function() {

                                        },
                                        success: function(data) {
                                            $(".table-responsive").html(data);
                                        }
                                    });
                                });


                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'settings/footer.php'; ?>