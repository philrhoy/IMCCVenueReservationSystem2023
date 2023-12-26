<?php
include 'settings/system.php';
include 'session.php';

if (isset($_POST['status'])){
    $status = $_POST['status'];
    $statusColor = "";
    $filterVenue = $_POST['venue'];
    $filterProgram = $_POST['program'];
    $filterSearch = $_POST['search'];
    $filterUserType = $_POST['userType'];
    $orderBy = $_POST['orderBy'];
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
                            `schedules`.status AS 'STATUS',
                            `schedules`.date_added as 'DATE_ADDEDS' FROM `schedules` 
                        INNER JOIN `venues` 
                            ON `schedules`.venueID = `venues`.id
                        INNER JOIN `program` 
                            ON `schedules`.programID = `program`.id 
                        INNER JOIN `users`
                            ON `schedules`.userID = `users`.id ";

    if($status != "0"){
        $fetchReservations .= "WHERE (`schedules`.status = '$status') ";
    }else{
        $fetchReservations .= "WHERE (1 = 1) ";
    }

    if($filterVenue != "0"){
        $fetchReservations .= "AND (`schedules`.venueID = '$filterVenue') ";
    }

    if($filterProgram != "0"){
        $fetchReservations .= "AND (`schedules`.programID = '$filterProgram')";
    }

    if($filterUserType != "0"){
        $position = (($filterUserType == "S") ? "STO" : "DSA");
        $fetchReservations .= "AND (`users`.position = '$position')";
    }

    if($filterSearch != ""){
        $fetchReservations .= "AND (`schedules`.name LIKE '%$filterSearch%')";
    }

    $fetchReservations .=  " ORDER BY `schedules`.date_added $orderBy";

?>
<table class="table table-sm table-bordered table-hovered" width="100%" cellspacing="0">
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
        $reservations = $db->query($fetchReservations);
        // WHERE `schedules`.date_start BETWEEN '$filterStart' AND '$filterEnd'
        $row_reservations = $reservations->fetchAll(PDO::FETCH_OBJ);
        foreach ($row_reservations as $row) {
            $statusStr = "Pending for Approval";

            switch($row->STATUS){
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
                <td><?= '<a href="view_reservation.php?reservation_id='.$row->INT_RES_ID.'" target="_blank">' . $row->ACTIVITY. '</a>'; ?></td>
                <td><?= $row->START_DATE . "-" . $row->END_DATE; ?></td>
                <td><?= $row->PROGRAM_NAME; ?></td>
                <td><?= $row->VENUE_NAME; ?></td>
                <td align="center"><span class="badge <?= $statusColor?>"><?= $statusStr; ?></td>
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