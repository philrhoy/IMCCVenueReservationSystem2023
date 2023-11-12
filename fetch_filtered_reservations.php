<?php
include 'settings/system.php';
include 'session.php';

if (isset($_POST['status'])){
    $status = $_POST['status'];
    $filterVenue = $_POST['venue'];
    $filterProgram = $_POST['program'];
    $appendAdminID = "";

    if($status == "AA" || $status == "RA"){
        $status = $status[0]; //get first character
        // $appendAdminID = " AND "
    }
    
    $fetchReservations = "SELECT `schedules`.`reservationID` AS 'RESERVATION_ID',
                            `schedules`.id AS 'INT_RES_ID',
                            `venues`.name AS 'VENUE_NAME',
                            `schedules`.name AS 'ACTIVITY',
                            `program`.name AS 'PROGRAM_NAME',
                            `schedules`.date_start AS 'START_DATE',
                            `schedules`.date_end AS 'END_DATE',
                            `schedules`.status AS 'STATUS' FROM `schedules` 
                        INNER JOIN `venues` 
                        ON `schedules`.venueID = `venues`.id
                        INNER JOIN `program` 
                        ON `schedules`.programID = `program`.id 
                        WHERE (`schedules`.status = '$status')";

    if($filterVenue != "0"){
        $fetchReservations .= "AND (`schedules`.venueID = '$filterVenue') ";
    }

    if($filterProgram != "0"){
        $fetchReservations .= "AND (`schedules`.programID = '$filterProgram')";
    }

?>
<table class="table table-bordered table-hovered" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th>Reservation ID</th>
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
        $reservations = $db->query($fetchReservations);
        // WHERE `schedules`.date_start BETWEEN '$filterStart' AND '$filterEnd'
        $row_reservations = $reservations->fetchAll(PDO::FETCH_OBJ);
        foreach ($row_reservations as $row) {
            $statusStr = "Pending for Approval";

            switch($row->STATUS){
                case "P":
                    $statusStr = "Pending for Approval";
                    break;
                case "A":
                    $statusStr = "Approved";
                    break;
                case "R":
                    $statusStr = "Rejected";
                    break;
            }
    
        ?>
            <tr>
                <td><?= '<a href="view_reservation.php?reservation_id='.$row->INT_RES_ID.'" target="_blank">' . $row->RESERVATION_ID. '</a>'; ?></td>
                <td><?= $row->ACTIVITY; ?></td>
                <td><?= $row->START_DATE . "-" . $row->END_DATE; ?></td>
                <td><?= $row->PROGRAM_NAME; ?></td>
                <td><?= $row->VENUE_NAME; ?></td>
                <td><?= $statusStr; ?></td>
                <td align="center">
                    <a href='edit_reservation.php?reservation_id=<?= $row->INT_RES_ID ?>' class="btn btn-primary btn-icon-split btn-sm keychainify-checked" target="_blank">
                        <span class="icon text-white-50">
                            <i class="fas fa-edit"></i>
                        </span>
                        <span class="text">Update</span>
                    </a>
                </td>
            </tr>
        <?php } }?>
    </tbody>
</table>