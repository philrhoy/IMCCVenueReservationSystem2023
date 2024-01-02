<?php
    include 'settings/system.php';
    include 'session.php';
    include 'settings/header.php';
    include "settings/sidebar.php";
    include 'settings/topbar.php';
    include 'notification_helper.php';
    $queryStatus = 0;
    /*
        0 = default/no query
        1 = error 
        2 = success
    */

    if (isset($_POST['submit'])) {
        $id = $_POST['id'];
        $res_id = $_POST['resID'];
        $user_id = $_SESSION['id'];
        $user_name = $_SESSION['username'];
        $activity = $_POST['activity'];
        $participants = $_POST['participants'];
        $description = $_POST['description'];
        $venueID = $_POST['venue'];
        $programID = $_POST['program'];
        $startDate = $_POST['start_date'];
        $endDate = $_POST['end_date'];
        $startTime = $_POST['start_time'];
        $endTime = $_POST['end_time'];
        $contact = $_POST['contact_no'];
        $sponsor = $_POST['sponsor'];
        $contribution = $_POST['contribution'];
        $incharge = $_POST['incharge'];

        $imgForms = array();
        
        $add_res = $db->query("INSERT INTO `schedules` 
                        (reservationID,userID,venueID,programID,date_start,date_end,time_start,time_end,name,contact,description,num_participants,sponsor,contribution,incharge) values
                        ('$res_id','$user_id','$venueID','$programID','$startDate','$endDate','$startTime','$endTime','$activity','$contact','$description','$participants','$sponsor','$contribution','$incharge')")
                    or die($db->error);
        $update_sequence = $db->query("UPDATE number_sequence SET last_number = '$id' WHERE page_name='reservations'");

        if (!$add_res) {
            $queryStatus = 1;
            header("location: add_reservation.php?queryStatus=".$queryStatus);
        } else {
            $notiHelper = new NotificationHelper();
            $user_name = $_SESSION['name2'];
            $notificationContent = $notiHelper->createNotification($res_id, strtoupper($user_name), "CREATE");
            $redirectPage = "edit_reservation.php?reservation_id=" . $res_id;

            $add_notif = $db->query("INSERT INTO `notifications` 
                (type, sourceUser, notifyToAllUserType, details, link, dateAdded) values
                ('CREATE','$user_id','DSA','$notificationContent','$redirectPage',NOW())")
                or die($db->error);

            $queryStatus = 2;
            header("location: add_reservation.php?queryStatus=".$queryStatus);
        }
    }

    $sequence = $db->query("SELECT * FROM number_sequence WHERE page_name = 'reservations'");
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

        $reservationListQuery = $db->query("SELECT * FROM schedules WHERE status = 'A' OR status = 'P' OR status = 'D'");
        $fetchReservations = $reservationListQuery->fetchAll(PDO::FETCH_OBJ);

        if(!isset($_GET['queryStatus'])){
            $_GET['queryStatus'] = 0;
        }
    }

?>
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">

        <div class="container-fluid">
            <div class="d-sm-flex align-items-center justify-content mb-4">
                <h1 class="h3 mb-2 text-gray-800"> Reservation Form</h1>
            </div>
            <div class="justify-content">
                <div class="alert alert-success" role="alert" style="display:<?= (($_GET['queryStatus'] == 2) ? "block;" : "none;") ?>">Successfully created reservation</div>
                <div class="alert alert-danger" role="alert" style="display:<?= (($_GET['queryStatus'] == 1) ? "block;" : "none;") ?>">Failed to save reservation. Error code: #</div>
            </div>
            <div class="card shadow">
                <div class="card-body">
                    <div class="table-responsive-lg">
                        <form role="form" method="post" enctype="multipart/form-data" id="form">
                            <div class="row">
                                <div class="col-5">
                                    <div class="form-group">
                                        <label>ID</label>
                                        <input class="form-control" type="hidden" name="id" value="<?= $newID ?>" readonly>
                                        <input class="form-control" type="text" name="resID" value="<?= 'RES' . $ID ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>Activity</label>
                                        <input class="form-control" type="text" name="activity" value="" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Approximate No. of Participants</label>
                                        <input class="form-control" type="number" placeholder="No. of participants" name="participants" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Objectives</label>
                                        <textarea class="form-control" id="exampleFormControlTextarea1" pattern="[A-Za-z]{1,25}" placeholder="Please specify the objectives" rows="3" name="description" requried></textarea>
                                        <!-- <input class="form-control" type="number" placeholder="No. of participants" name="participants" required> -->
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-6">
                                            <label>Program</label>
                                            <select class="form-control" name="program">
                                                <?php
                                                $fetchPrograms = $db->query("SELECT * FROM `program` ORDER BY name ASC");

                                                $row_donor = $fetchPrograms->fetchAll(PDO::FETCH_OBJ);

                                                foreach ($row_donor as $row) {
                                                ?>
                                                    <option value="<?php echo $row->id ?>"> <?php echo $row->name ?> </option>
                                                <?php
                                                }

                                                ?>

                                            </select>
                                        </div>
                                        <div class="col-6">
                                            <label>Contact No.</label>
                                            <input class="form-control" type="text" name="contact_no" maxlength="11" minlength="11" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Choose Venue</label>
                                        <select class="form-control" name="venue" id="venue">
                                            <?php
                                            $fetchVenues = $db->query("SELECT * FROM `venues` ORDER BY name ASC");

                                            $row_donor = $fetchVenues->fetchAll(PDO::FETCH_OBJ);

                                            foreach ($row_donor as $row) {
                                            ?>
                                                <option value="<?php echo $row->id ?>"> <?php echo $row->name ?> </option>
                                            <?php
                                            }

                                            ?>

                                        </select>

                                    </div>
                                    <div class="form-group row">
                                        <div class="col-6">
                                            <label>Start Date</label>
                                            <input class="form-control" type="date" name="start_date" id="startDate" required>
                                        </div>

                                        <div class="col-6">
                                            <label>Start Time</label>
                                            <input class="form-control" type="time" name="start_time" id="startTime" required>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-6">
                                            <label>End Date</label>
                                            <input class="form-control" type="date" name="end_date" id="endDate"required>
                                        </div>
                                        <div class="col-6">
                                            <label>End Time</label>
                                            <input class="form-control" type="time" name="end_time" id="endTime"required>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-7">
                                    <div class="form-group">
                                            <label>Organization/Sponsor</label>
                                            <input class="form-control" type="text" name="sponsor" value="" required>
                                    </div>
                                    <div class="form-group">
                                            <label>Amount of Contribution per Student</label>
                                            <input class="form-control" type="number" name="contribution" step="0.01" value="" required>
                                    </div>
                                    <div class="form-group">
                                            <label>Person/s in-charge</label>
                                            <input class="form-control" type="text" name="incharge" value="" required>
                                    </div>
                                    <div class="form-group">
                                        <strong>
                                            <p>You are now creating a reservation. Enter Activity Details and Proceed <b class="bg-success text-white">Save As Draft</b>.
                                                You can further edit your drafted reservation at “Reservations” tab.
                                                Look for this Reservation with a <b class="bg-primary text-white">Draft</b> Status and click <b class="bg-primary text-white">Update</b> .</p>
                                            <p>In editing your drafted reservation, you will have options of:<br><b class="bg-secondary text-white">'Update Record Only'</b> and <b class="bg-success text-white">'Submit to Property Custodian'.</b></p>
                                            <p>You are required to Print Student Activity Form (a Hyperlink is provided in your next edit of this reservation) and complete signing it by school staff for approval.</p>
                                            <p>Once the Printed S.A.F. is completed (Fully-Signed), please scan and upload the Fully-Signed S.A.F file (.png / .jpg / .pdf) before proceeding <b class="bg-success text-white">‘Submit to Property Custodian’</b>.</p>
                                            <p>Reservations submitted to Property Custodian are no longer editable and your reservation is set to <b class="bg-warning text-white">‘Pending’</b> status.</p>
                                        </strong>
                                    </div>

                                    <!-- <div class="form-group">
                                        <div class="imgUp">
                                            <label><b>Upload Fully Signed Student Activity Form:</b></label>
                                            <span class="invalidFormat" style="visibility: hidden; color: red">Invalid file format</span>
                                            <input type="file" class="form-control uploadFile img" name="activityFormImg" accept="image/jpeg, image/png, application/pdf" id="activityForm" aria-label="0" required>
                                            <iframe src="" type="" scrolling="auto" height="250px" width="100%" class="getImg"> </iframe>
                                            <a class="btn btn-sm btn-primary preview" id="0" hidden>Preview</a>
                                        </div>
                                    </div> -->

                                    <!-- <div class="form-group">
                                        <div class="imgUp" style="overflow-y:scroll;">
                                            <label><b>Upload Letter of Approval:</b></label>
                                            <span class="invalidFormat" style="visibility: hidden; color: red">Invalid file format</span>
                                            <input type="file" class="form-control uploadFile img" name="letterApprovalImg" id="letterApproval" accept="image/jpeg, image/png, application/pdf" aria-label="0">
                                            <iframe src="" type="" scrolling="auto" height="250px" width="100%" class=" getImg"> </iframe>
                                            <a class="btn btn-sm btn-primary preview" id="1" hidden>Preview</a>
                                        </div>
                                    </div> -->
                                </div> 
                            </div>
                            <!-- data-toggle="modal" data-target="#emptyFormModal" -->
                            <a class="btn btn-success btn-icon-split btn-sm keychainify-checked confirmBtn1" href="">
                                <span class="icon text-white-50">
                                    <i class="fas fa-plus"></i>
                                </span>
                                <span class="text">SAVE AS DRAFT</span>
                            </a>

                            <!-- FOR STUDENT FORM MODAL & ERRORS -->
                            <div class="modal fade" id="emptyFormModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger">
                                            <h5 class="modal-title text-white" id="exampleModalLabel">Warning: This reservation does not have an Letter of Approval.. Do you wish to Proceed to</h5>
                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body"></div>
                                        <div class="modal-footer">
                                            <button type="submit" name='submit' id='submitBtn' class="btn btn-success btn-icon-split btn-sm keychainify-checked">
                                                <span class="icon text-white-50">
                                                    <i class="fas fa-save"></i>
                                                </span>
                                                <span class="text">Submit</span>
                                            </button>
                                            <button class="btn btn-info btn-icon-split btn-sm keychainify-checked" id="reEdit" data-dismiss="modal">
                                                <span class="icon text-white-50">
                                                    <i class="fas fa-edit"></i>
                                                </span>
                                                <span class="text">Re-edit reservation</span>
                                            </button>
                                            <a href="calendar.php" class="btn btn-secondary btn-icon-split btn-sm keychainify-checked" id="backToHome">
                                                <span class="icon text-white-50">
                                                    <i class="fas fa-window-close"></i>
                                                </span>
                                                <span class="text">Back to homepage</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </form>

                        <script>
                            $(function() {
                                var errors = 0;
                                var curStartDate = $('#startDate').val();
                                var curStartTime = $('#startTime').val();

                                $('.confirmBtn1').click(function(e) {
                                    e.preventDefault();
                                    //Check fields
                                    var form = $('#form');
                                    var isValidated = form[0].reportValidity();
                                    var curStartDate = $('#startDate');
                                    var curStartTime = $('#startTime'); 
                                    var curEndDate = $('#endDate');
                                    var curEndTime = $('#endTime');

                                    if (isValidated) {
                                        //Check for errors
                                        var resConflict = checkScheduleConflict();

                                        if (resConflict.length != 0){
                                            $('.modal-header').prop({
                                                class: "modal-header bg-warning"
                                            });
                                            $('.modal-title').html("Warning");
                                            $('.modal-body').html("Warning: This reservation has conflicted with "+ resConflict[0][5] +" Reservation "
                                                                    + resConflict[0][0] + ", conflict detected!<br><br> "+ resConflict[0][5] +" Reservation Details:<br><br> "
                                                                    + "<b>ReservationID: </b>" + resConflict[0][0] + "<br>"
                                                                    + "<b>Start Date: </b>" + resConflict[0][1] + "<br>"
                                                                    + "<b>Start Time: </b>" + resConflict[0][2] + "<br>"
                                                                    + "<b>End Date: </b>" + resConflict[0][3] + "<br>"
                                                                    + "<b>End Time: </b>" + resConflict[0][4] + "<br>");
                                            $('#submitBtn').prop({
                                                hidden: true
                                            });
                                            $('#backToHome').prop({
                                                hidden: false
                                            });
                                            $('#emptyFormModal').modal({
                                                show: true
                                            });
                                        }else {
                                            //Submit form
                                            $("#submitBtn").click();
                                            // form[0].submit();
                                        }
                                    }

                                });

                                function checkScheduleConflict () {
                                    var reservationList = <?php echo json_encode($fetchReservations); ?>; 
                                    var conflictCounter = 0;
                                    var currentVenue = $("#venue").val();
                                    var returnDetail = [];

                                    for (let index = 0; index < reservationList.length; index++) {
                                        if(reservationList[index]["venueID"] == currentVenue){
                                            var convCurStartDate = new Date($('#startDate').val());
                                            var convCurStartTime= $('#startTime').val();
                                            var convCurEndDate = new Date($('#endDate').val());
                                            var convCurEndTime = $('#endTime').val();

                                            var approvedStartDate = new Date(reservationList[index]["date_start"]);
                                            var approvedStartTime = reservationList[index]["time_start"];
                                            var approvedEndDate = new Date(reservationList[index]["date_end"]);
                                            var approvedEndTime = reservationList[index]["time_end"];

                                            //To return
                                            var reservationFlag = reservationList[index]["reservationID"];
                                            var status = ((reservationList[index]["status"] == "A") ? "Approved" : "Pending");
                                            var sTimeSplit = approvedStartTime.split(":");
                                            var eTimeSplit = approvedEndTime.split(":");
                                            returnDetail = [
                                                [
                                                    reservationFlag, 
                                                    approvedStartDate.toDateString(), 
                                                    ((sTimeSplit[0] > 12) ? (sTimeSplit[0] % 12) + ":" + sTimeSplit[1] : (approvedStartTime)) + " " + ((sTimeSplit[0] > 12) ? "PM" : "AM"),
                                                    approvedEndDate.toDateString(),
                                                    ((eTimeSplit[0] > 12) ? (eTimeSplit[0] % 12) + ":" + eTimeSplit[1] : (approvedEndTime)) + " " + ((eTimeSplit[0] > 12) ? "PM" : "AM"),
                                                    status,
                                                ]
                                            ];

                                            //Approved: 6:03am 
                                            //Input: 10:30am

                                            //If Approved Start Date is between current Data Range
                                            var isApprovedStartDateBetweenCurrentDates = (approvedStartDate.getTime() > convCurStartDate.getTime()) 
                                                && (approvedStartDate.getTime() < convCurEndDate.getTime());
                                
                                            //Vice versa
                                            var isApprovedEndDateBetweenCurrentDates = (approvedEndDate.getTime() > convCurStartDate.getTime()) 
                                                && (approvedEndDate.getTime() < convCurEndDate.getTime());

                                            if((convCurStartDate.getTime() > approvedStartDate.getTime()) 
                                                && (convCurStartDate.getTime() < approvedEndDate.getTime())) {
                                                    console.log("1");
                                                    conflictCounter++; break;
                                            }
                                            if((convCurStartDate.getTime() == approvedStartDate.getTime()) 
                                                || (convCurStartDate.getTime() == approvedEndDate.getTime())) {
                                                if((convCurStartTime >= approvedStartTime) && (convCurStartTime <= approvedEndTime)){
                                                    console.log("2");
                                                    conflictCounter++; break;
                                                }else {
                                                    if((approvedStartTime >= convCurStartTime) && (approvedStartTime <=  convCurEndTime)){
                                                        console.log("2.1");
                                                        conflictCounter++; break;
                                                    }
                                                    if((approvedEndTime >= convCurStartTime) && (approvedEndTime <= convCurEndTime)){
                                                        console.log("2.2");
                                                        conflictCounter++; break;
                                                    }
                                                }
                                            }
                                            if((convCurEndDate.getTime() > approvedStartDate.getTime()) 
                                                && (convCurEndDate.getTime() < approvedEndDate.getTime())){
                                                    console.log("3");
                                                    conflictCounter++; break;
                                            }
                                            if((convCurEndDate.getTime() == approvedStartDate.getTime()) 
                                                || (convCurEndDate.getTime() == approvedEndDate.getTime())){
                                                if((convCurEndTime >= approvedStartTime) && (convCurEndTime <= approvedEndTime)){
                                                    console.log("4");
                                                    conflictCounter++; break;
                                                }else {
                                                    if((approvedStartTime >= convCurStartTime) && (approvedStartTime <=  convCurEndTime)){
                                                        console.log("4.1");
                                                        conflictCounter++; break;
                                                    }
                                                    if((approvedEndTime >= convCurStartTime) && (approvedEndTime <= convCurEndTime)){
                                                        console.log("4.2");
                                                        conflictCounter++; break;
                                                    }
                                                }
                                            }
                                            if(isApprovedStartDateBetweenCurrentDates && isApprovedEndDateBetweenCurrentDates) {
                                                console.log("5");
                                                conflictCounter ++; break;
                                            }
                                        }    
                                    }
                                    returnDetail = ((conflictCounter == 0) ? [] : returnDetail);
                                    console.log("Counter: " + ((conflictCounter == 0) ? "No Conflicts" : "Conflict"));
                                    return returnDetail;
                                }

                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'settings/footer.php'; ?>