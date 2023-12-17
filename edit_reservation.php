<?php
    include 'settings/system.php';
    include 'session.php';
    include 'settings/header.php';
    include "settings/sidebar.php";
    include 'settings/topbar.php';
    include 'notification_helper.php';
    $queryStatus = 0;
    $tempID = "";
    /*
        0 = default/no query
        1 = error 
        2 = success
    */

    if (isset($_POST['submit'])) {
        $adminID = $_SESSION['id'];
        $action = $_POST['action'];
        $res_id = $_POST['id'];
        $res_id_text = $_POST['resID'];
        $recipient = $_POST['userID'];
        $activity = $_POST['activity'];
        $participants = $_POST['participants'];
        $description = $_POST['description'];
        $venueID = $_POST['venue'];
        $programID = $_POST['program'];
        $startDate = $_POST['start_date'];
        $endDate = $_POST['end_date'];
        $startTime = $_POST['start_time'];
        $endTime = $_POST['end_time'];
        $notes = $_POST['notes'];
        $material = $_POST['material'];
        $status = $_POST['status'];

        if ($status != "A") {
            $query = "UPDATE `schedules` 
                SET venueID = '$venueID', programID = '$programID', date_start = '$startDate', date_end = '$endDate',
                    time_start = '$startTime', time_end = '$endTime', name = '$activity', description = '$description',
                    num_participants = '$participants'";

            //Check if approved or rejected
            if ($_SESSION["position"] == "DSA") {
                if ($action == "APPROVE") {
                    $query .= ", notes = '$notes',
                                    status = 'A',
                                    approvedByAdmin = '$adminID'
                                    WHERE id = '$res_id' OR reservationID = '$res_id'";
                } elseif ($action == "REJECT") {
                    $query .= ", notes = '$notes',
                                    status = 'R',
                                    rejectedByAdmin = '$adminID'
                                    WHERE id = '$res_id' OR reservationID = '$res_id'";
                } else {
                    $query .= ", notes = '$notes'
                                    WHERE id = '$res_id' OR reservationID = '$res_id'";
                }
            } else {
                //Prevent Student Officer from updating notes
                $query .= ($_SESSION['position'] != 'STO' ?
                    ", notes = '$notes', material = '$material' WHERE id = '$res_id' OR reservationID = '$res_id'" :
                    " WHERE id = '$res_id' OR reservationID = '$res_id'");
            }

            $update_reservation = $db->query($query);

            if (!$update_reservation) {
                $queryStatus = 1; /***** */ 
                header("location: edit_reservation.php?reservation_id=".$res_id."&queryStatus=".$queryStatus);
            } else {
                $notiHelper = new NotificationHelper();
                $user_id = $_SESSION['id'];
                $user_name =  $_SESSION['name2'];
                $redirectPage = "edit_reservation.php?reservation_id=" . $res_id_text;
                $approveRedirectPage = "view_reservation.php?reservation_id=" . $res_id_text;
                $addNotifQuery = "";

                if ($_SESSION['position'] == 'STO' || $_SESSION['position'] == 'PTC') {
                    $notificationContent = $notiHelper->createNotification($res_id_text, strtoupper($user_name), "UPDATE");
                    $addNotifQuery = "INSERT INTO `notifications` 
                                        (type, sourceUser, notifyToAllUserType, details, link, dateAdded) values
                                        ('UPDATE','$user_id','DSA','$notificationContent','$redirectPage',NOW())";
                    $add_notif = $db->query($addNotifQuery) or die($db->error);
                } else if ($_SESSION['position'] == 'DSA') {
                    if ($action == "APPROVE") {
                        $notificationContent = $notiHelper->createNotification($res_id_text, strtoupper($user_name), "APPROVE");
                        $addNotifQuery = "INSERT INTO `notifications` 
                                        (type,sourceUser, recipient, details, link, dateAdded) values
                                        ('APPROVE','$user_id','$recipient','$notificationContent','$approveRedirectPage',NOW())";
                        $add_notif = $db->query($addNotifQuery) or die($db->error);

                        $addNotifQuery = "INSERT INTO `notifications` 
                                        (type,sourceUser, notifyToAllUserType, details, link, dateAdded) values
                                        ('APPROVE','$user_id','PTC','$notificationContent','$redirectPage',NOW())";
                        $add_notif2 = $db->query($addNotifQuery) or die($db->error);
                    } elseif ($action == "REJECT") {
                        $notificationContent = $notiHelper->createNotification($res_id_text, strtoupper($user_name), "REJECT");
                        $addNotifQuery = "INSERT INTO `notifications` 
                                        (type,sourceUser, recipient, details, link, dateAdded) values
                                        ('REJECT','$user_id','$recipient', '$notificationContent','$redirectPage',NOW())";
                        $add_notif = $db->query($addNotifQuery) or die($db->error);
                    } else {
                        $notificationContent = $notiHelper->createNotification($res_id_text, strtoupper($user_name), "UPDATE");
                        $addNotifQuery = "INSERT INTO `notifications` 
                                        (type,sourceUser, recipient, details, link, dateAdded) values
                                        ('UPDATE','$user_id','$recipient','$notificationContent','$redirectPage',NOW())";
                        $add_notif = $db->query($addNotifQuery) or die($db->error);
                    }
                }

                //Alert message must correspond to action performed
                $queryStatus = 2;
                header("location: edit_reservation.php?reservation_id=".$res_id."&queryStatus=".$queryStatus);
            }
        }
    }
    if (isset($_GET['reservation_id'])) {
        $res_id = $_GET['reservation_id'];
        $tempID = $_GET['reservation_id'];
        $user_id = "";
        $res_id_text = "";
        $activity = "";
        $participants = "";
        $objectives = "";
        $program_id = "";
        $venue_id = "";
        $start_date = "";
        $start_time = "";
        $end_date = "";
        $end_time = "";
        $act_form_file = "";
        $letter_approve_file = "";
        $act_form_file_ext = "";
        $letter_approve_file_ext = "";
        $status = "";
        $material = "";
        $statusStr = "Pending for Approval";

        $sequence = $db->query("SELECT * FROM schedules WHERE id = '$res_id' OR reservationID = '$res_id'");
        $fetch = $sequence->fetchAll(PDO::FETCH_OBJ);

        foreach ($fetch as $data) {
            //prevent studs to update record if approved
            if($data->status == 'A' && $_SESSION['position'] == 'STO'){
                //redirect somewhere
                header("location: reservation_list.php");
                exit();
            }
            switch ($data->status) {
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
            $res_id_text = $data->reservationID;
            $user_id = $data->userID;
            $activity = $data->name;
            $status = $data->status;
            $participants = $data->num_participants;
            $objectives = $data->description;
            $program_id = $data->programID;
            $venue_id = $data->venueID;
            $start_date = $data->date_start;
            $start_time = $data->time_start;
            $end_date =  $data->date_end;
            $end_time = $data->time_end;
            $notes = $data->notes;
            $material = $data->material;
            $act_form_file = $data->act_form_file;
            $letter_approve_file = $data->letter_approve_file;

            $file_ext = explode(".", $act_form_file);
            $act_form_file_ext = (strtolower(end($file_ext)) == "pdf") ? "application/" . strtolower(end($file_ext)) : "image/" . strtolower(end($file_ext));

            $file_ext = explode(".", $letter_approve_file);
            $letter_approve_file_ext = (strtolower(end($file_ext)) == "pdf") ? "application/" . strtolower(end($file_ext)) : "image/" . strtolower(end($file_ext));
        }
    }

    $queryStr = "";

    if(!preg_match("/[a-z]/i", $tempID)){
        $queryStr = "SELECT * FROM schedules 
                    WHERE (status = 'A' OR status = 'P') AND
                            (id != '$tempID')";
    }else{
        $queryStr = "SELECT * FROM schedules 
                    WHERE (status = 'A' OR status = 'P') AND
                            (reservationID != '$tempID')";
    }

    $reservationListQuery = $db->query($queryStr);
                                            
    $fetchReservations = $reservationListQuery->fetchAll(PDO::FETCH_OBJ);

    if(!isset($_GET['queryStatus'])){
        $_GET['queryStatus'] = 0;
    }
?>
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">

        <div class="container-fluid">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-2 text-gray-800">Update Reservation</h1>
            </div>
            <div class="justify-content">
                <div class="alert alert-success" role="alert" style="display:<?= (($_GET['queryStatus'] == 2) ? "block;" : "none;") ?>">Successfully updated reservation</div>
                <div class="alert alert-danger" role="alert" style="display:<?= (($_GET['queryStatus'] == 1) ? "block;" : "none;") ?>">Failed to update reservation. Error code: #</div>
            </div>
            <div class="card shadow">
                <div class="card-body">
                    <div class="table-responsive-lg">
                        <form role="form" method="post" id="form" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>ID</label>
                                        <input class="form-control" type="hidden" name="userID" value="<?= $user_id ?>" readonly>
                                        <input class="form-control" type="hidden" name="id" value="<?= $res_id ?>" readonly>
                                        <input class="form-control" type="text" name="resID" value="<?= $res_id_text ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>Activity</label>
                                        <input class="form-control" type="text" name="activity" value="<?= $activity ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Status</label>
                                        <input class="form-control" type="text" name="status" value="<?= $statusStr ?>" readonly>
                                    </div>

                                    <div class="form-group">
                                        <label>Objectives</label>
                                        <textarea class="form-control" id="exampleFormControlTextarea1" name="description" placeholder="Please specify the objectives" rows="3" required><?= $objectives ?></textarea>
                                        <!-- <input class="form-control" type="number" placeholder="No. of participants" name="participants" required> -->
                                    </div>
                                    <div class="form-group">
                                        <label>Program</label>
                                        <select class="form-control" name="program">
                                            <?php
                                            $fetchPrograms = $db->query("SELECT * FROM `program` ORDER BY name ASC");

                                            $row_donor = $fetchPrograms->fetchAll(PDO::FETCH_OBJ);

                                            foreach ($row_donor as $row) {
                                                if ($row->id == $program_id) {
                                            ?>
                                                    <option value="<?php echo $row->id ?>" selected> <?php echo $row->name ?> </option>
                                                <?php
                                                    continue;
                                                }
                                                ?>
                                                <option value="<?php echo $row->id ?>"> <?php echo $row->name ?> </option>
                                            <?php
                                            }

                                            ?>

                                        </select>

                                    </div>
                                    <div class="form-group">
                                        <label>Choose Venue</label>
                                        <select class="form-control" name="venue" id="venue">
                                            <?php
                                            $fetchVenues = $db->query("SELECT * FROM `venues` ORDER BY name ASC");

                                            $row_donor = $fetchVenues->fetchAll(PDO::FETCH_OBJ);

                                            foreach ($row_donor as $row) {
                                                if ($row->id == $venue_id) {
                                            ?>
                                                    <option value="<?php echo $row->id ?>" selected> <?php echo $row->name ?> </option>
                                                <?php
                                                    continue;
                                                }
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
                                            <input class="form-control" type="date" name='start_date' id="startDate"value="<?= $start_date ?>"required>
                                        </div>

                                        <div class="col-6">
                                            <label>Start Time</label>
                                            <input class="form-control" type="time" name='start_time' id="startTime"value="<?= $start_time ?>"required>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-6">
                                            <label>End Date</label>
                                            <input class="form-control" type="date" name='end_date' id="endDate"value="<?= $end_date ?>"required>
                                        </div>
                                        <div class="col-6">
                                            <label>End Time</label>
                                            <input class="form-control" type="time" name='end_time' id="endTime"value="<?= $end_time ?>"required>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="form-group">
                                        <label>Approximate No. of Participants</label>
                                        <input class="form-control" type="number" name="participants" placeholder="No. of participants" value="<?= $participants ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <div class="imgUp">
                                            <label><b>Uploaded Fully Signed Student Activity Form:</b></label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="imgUp">
                                            <iframe src="uploads/<?= $act_form_file ?>" type="<?= $act_form_file_ext ?>" scrolling="auto" height="220px" width="100%" class="getImg"> </iframe>
                                            <a class="btn btn-sm btn-primary preview" id="0">Preview</a>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="imgUp">
                                            <label><b>Uploaded Letter of Approval:</b></label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="imgUp">
                                            <iframe src="uploads/<?= (($letter_approve_file == "") ? "no-file-icon.png" : $letter_approve_file) ?>" type="<?= $letter_approve_file_ext ?>" scrolling="auto" height="220px" width="100%" class="getImg"> </iframe>
                                            <a class="btn btn-sm btn-primary preview" id="1">Preview</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="form-group note-form-group">
                                        <label>Notes</label>
                                        <textarea class="form-control" id="noteTextArea" name='notes' placeholder="Notes will be provided by Property Custodian or Admin" rows="3" <?= (($_SESSION['position'] != 'PTC' ? 'readonly' : '')); ?>><?= $notes ?></textarea>
                                    </div>
                                    <div class="form-group note-form-group">
                                        <label>Materials will be provided by Property Custodian. (i.e MIcrophone (1pc) / Projector (1pc))</label>
                                        <textarea class="form-control" id="noteTextArea" name='material' placeholder="Notes will be provided by Property Custodian or Admin" rows="3" <?= (($_SESSION['position'] != 'PTC' ? 'readonly' : '')); ?>><?= $material ?></textarea>
                                        <!-- <select class="form-control" name="material">
                                            <option value="M1"<?php //echo (($material == "M1") ? "selected" : ""); 
                                                                ?>>Microphone (1pc)</option>
                                            <option value="E1"<?php //echo (($material == "E1") ? "selected" : ""); 
                                                                ?>>Extension Wire (1pc)</option>
                                            <option value="EP"<?php //echo (($material == "EP") ? "selected" : ""); 
                                                                ?>>Epson Projector Device</option>
                                            <option value="PR"<?php //echo (($material == "PR") ? "selected" : ""); 
                                                                ?>>Projector White Screen</option>
                                            <option value="PC50"<?php //echo (($material == "PC50") ? "selected" :""); 
                                                                ?>>Plastic Chairs (50 pcs)</option>
                                            <option value="PC100"<?php //echo (($material == "PC100") ? "selected" :""); 
                                                                    ?>>Plastic Chairs (100 pcs)</option>
                                        </select> -->
                                    </div>

                                    <div class="form-group" <?= (($_SESSION['position'] != 'DSA' ? 'hidden' : '')); ?>>
                                        <label>Choose action to perform</label>
                                        <select class="form-control" id="performAction" name="action">
                                            <option value='UPDATE' selected>Update Record Only</option>
                                            <option value='APPROVE'>Approve</option>
                                            <option value='REJECT'>Reject</option>
                                        </select>
                                    </div>
                                    <div>

                                    </div>
                                    <a class="btn btn-success btn-icon-split btn-sm keychainify-checked confirmBtn" href="#" data-toggle="modal" data-target="#confirmModal">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-check"></i>
                                        </span>
                                        <span class="text">APPLY UPDATES</span>
                                    </a>

                                    <!-- Confirm Update Modal-->
                                    <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger">
                                                    <h5 class="modal-title text-white" id="exampleModalLabel">Confirm Action</h5>
                                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body"></div>
                                                <div class="modal-footer">
                                                    <button type="submit" name='submit' class="btn btn-success btn-icon-split btn-sm keychainify-checked" id='submitBtn'>
                                                        <span class="icon text-white-50">
                                                            <i class="fas fa-check"></i>
                                                        </span>
                                                        <span class="text">Proceed</span>
                                                    </button>
                                                    <button class="btn btn-info btn-icon-split btn-sm keychainify-checked" id="reEdit" data-dismiss="modal">
                                                        <span class="icon text-white-50">
                                                            <i class="fas fa-edit"></i>
                                                        </span>
                                                        <span class="text">Re-edit reservation</span>
                                                    </button>
                                                    <!-- <button class="btn btn-secondary btn-icon-split btn-sm keychainify-checked" type="button" data-dismiss="modal">
                                                        <span class="icon text-white-50">
                                                            <i class="fas fa-window-close"></i>
                                                        </span>
                                                        <span class="text">Cancel</span>
                                                    </button> -->

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                        </form>

                        <script>
                            $(function() {
                                // var imageContainer = $(".imgUp").css("height", "30%");

                                $(document).on("change", ".uploadFile", function() {
                                    var uploadFile = $(this);
                                    var files = !!this.files ? this.files : [];
                                    var getCurrentUpload = $(".imagePreview");
                                    if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support

                                    reader.onloadend = function() { // set image data as background of div
                                        uploadFile.closest(".imgUp").find('.getImg').attr('src', this.result);

                                        if (uploadFile.attr("name") == "activityFormImg") {
                                            $(".getImg").first().attr("src", this.result);
                                            $(".getImg").first().attr("type", files[0].type);
                                            $(".preview").first().attr("hidden", false);
                                        } else if (uploadFile.attr("name") == "letterApprovalImg") {
                                            $(".getImg").last().attr("src", this.result);
                                            $(".getImg").last().attr("type", files[0].type);
                                            $(".preview").last().attr("hidden", false);
                                        }
                                    }

                                });

                                $('.preview').click(function() {
                                    var isFirst = $(this).attr("id") == "0";
                                    var fileObject = (isFirst) ? $('.getImg').first() : $('.getImg').last();
                                    var fileObjectSrc = fileObject.attr('src');
                                    var fileObjectType = fileObject.attr('type');
                                    var modal;
                                    var modal2;
                                    var closeBtn;

                                    function removeModal() {
                                        modal.remove();
                                        modal2.remove();
                                        $('body').off('keyup.modal-close');
                                    }

                                    modal2 = $('<iframe>').attr('src', fileObjectSrc).css({
                                        background: 'RGBA(0,0,0,.5)',
                                        width: '100%',
                                        height: '100%',
                                        padding: '5%',
                                        position: 'fixed',
                                        zIndex: '10000',
                                        top: '0',
                                        left: '0'
                                    }).click(function() {
                                        removeModal();
                                    });

                                    modal = $('<div>').css({
                                        background: 'RGBA(0,0,0,.5) url(' + fileObjectSrc + ') no-repeat center',
                                        backgroundSize: 'contain',
                                        width: '100%',
                                        height: '100%',
                                        position: 'fixed',
                                        zIndex: '10000',
                                        top: '0',
                                        left: '0',
                                        cursor: 'zoom-out'
                                    }).click(function() {
                                        removeModal();
                                    })
                                    //handling ESC
                                    $('body').on('keyup.modal-close', function(e) {
                                        if (e.key === 'Escape') {
                                            removeModal();
                                        }
                                    });

                                    if (/^image/.test(fileObjectType)) {
                                        modal.appendTo('body');
                                        // closeBtn.appendTo('body');
                                    } else if (/^application/.test(fileObjectType)) {
                                        modal2.appendTo('body');
                                        // closeBtn.appendTo('body');
                                    }

                                });

                                $("#performAction").on("change", function() {
                                    var statusVal = $(this).val();
                                    if (statusVal == "REJECT") {
                                        $("#noteTextArea").prop({
                                            required: true,
                                            readonly: false
                                        });
                                    } else if (statusVal == "APPROVE") {
                                        $("#noteTextArea").prop({
                                            required: false,
                                            readonly: true
                                        });
                                    }
                                });

                                $(".confirmBtn").on("click", function() {
                                    var form = $('#form');
                                    var confirmMsg = "Press PROCEED to apply updates to this record.";
                                    var perfActionVal = $("#performAction").val();
                                    var isValidated = form[0].reportValidity();
                                    var resConflict = checkScheduleConflict();

                                    if(isValidated){
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
                                         
                
                                        }else{
                                            if (perfActionVal == "REJECT") {
                                                confirmMsg = "Are you sure you want to REJECT this reservation?";
                                                $('.modal-header').prop({
                                                    class: "modal-header bg-danger"
                                                });
                                                $('.modal-title').html("Confirm Update");
                                            } else if (perfActionVal == "APPROVE") {
                                                confirmMsg = "Are you sure you want to APPROVE this reservation?";
                                                $('.modal-header').prop({
                                                    class: "modal-header bg-success"
                                                });
                                                $('.modal-title').html("Confirm Update");
                                            }else{
                                                confirmMsg = "Are you sure you want to UPDATE this reservation?";
                                                $('.modal-header').prop({
                                                    class: "modal-header bg-success"
                                                });
                                                $('.modal-title').html("Confirm Update");
                                            }

                                            $(".modal-body").html(confirmMsg);
                                            $('#submitBtn').prop({
                                                hidden: false
                                            });
                                        }
                                    }

                                    
                                });

                                function checkScheduleConflict () {
                                    var reservationList = <?php echo json_encode($fetchReservations); ?>; 
                                    var conflictCounter = 0;
                                    var currentVenue = $("#venue").val();
                                    var returnDetail = [];

                                    console.log(reservationList.length);

                                    for (let index = 0; index < reservationList.length; index++) {
                                        if(reservationList[index]["venueID"] == currentVenue){
                                            console.log("nisulod");
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