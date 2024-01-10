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
    $sponsor = $_POST['sponsor'];
    $contribution = $_POST['contribution'];
    $incharge = $_POST['incharge'];
    $soundsystem = $_POST['sound'];
    $microphone = $_POST['mic'];

    $imgForms = array();

    if ($status != "A") {
        /************UPLOAD FILES***********/
        //CHECK FOR IMAGE ERRORS:
        $valid_extensions = ['jpeg', 'jpg', 'png', 'pdf'];
        $check_error = 0;

        echo isset($_FILES["letterApprovalImg"]);
        foreach ($_FILES as $file) {
            if ($file["name"] == "" || $file["size"] == "") continue;
            $file_name = $file["name"];
            $file_size = $file["size"];

            $image_extension = explode(".", $file_name);
            $image_extension = strtolower(end($image_extension));

            if (!in_array($image_extension, $valid_extensions)) {
                $queryStatus = 1;
                $check_error++;
            } elseif ($file_size > 5000000) {
                $queryStatus = 1;
                $check_error++;
            }
        }

        // $origActForm = $_POST['origActForm'];
        // $origLetterApproval = $_POST['origLetterApproval'];

        if ($check_error == 0) {

            $checkCounter = 0;

            foreach ($_FILES as $file) {
                if ($file["name"] == "" || $file_size == "") continue;
                $file_name = $file["name"];
                $tmp_name = $file["tmp_name"];
                $fileerror = $file['error'];

                $image_extension = explode(".", $file_name);
                $image_extension = strtolower(end($image_extension));

                $unique_img_name = uniqid();
                $unique_img_name .= '.' . $image_extension;
                $path = 'uploads/';
                move_uploaded_file($tmp_name, $path . $unique_img_name);

                array_push($imgForms, $unique_img_name);
            }
        }

        /**********UPDATE SCHEDULE*********/
        $query = "UPDATE `schedules` 
                      SET date_start = '$startDate', date_end = '$endDate',
                        time_start = '$startTime', time_end = '$endTime', name = '$activity', description = '$description',
                        num_participants = '$participants', sponsor = '$sponsor', contribution = '$contribution', incharge = '$incharge', sound_system='$soundsystem', microphone='$microphone' ";

        $activity_form = "";
        $letter_approval = "";

        if (isset($_FILES["activityFormImg"])) {
            if ($_FILES["activityFormImg"]["name"] != "") {
                if (isset($_POST['origActForm'])) {
                    unlink("uploads/" . $_POST['origActForm']);
                }
                $activity_form = $imgForms[0];
                $query .= ",act_form_file = '$activity_form'";
            }
        }

        if (isset($_FILES["letterApprovalImg"])) {
            if ($_FILES["letterApprovalImg"]["name"] != "") {
                if (isset($_POST['origLetterApproval'])) {
                    unlink("uploads/" . $_POST['origLetterApproval']);
                }
                $letter_approval = ((sizeof($imgForms) > 1) ? $imgForms[1] : $imgForms[0]);
                $query .= ",letter_approve_file = '$letter_approval'";
            }
        }

        if (isset($_POST['programID'])) $query .= ",programID = '$programID'";
        if (isset($_POST['venueID'])) $query .= ",venueID = '$venueID'";
        if (isset($_POST['notes'])) $query .= ",notes = '$notes'";
        if (isset($_POST['material'])) $query .= ",others_material = '$material'";

        //Check if approved or rejected or update only
        if ($action == "APPROVE" && $_SESSION["position"] == "DSA") {
            $query .= ",status = 'A',
                            approvedByAdmin = '$adminID' 
                            WHERE id = '$res_id' OR reservationID = '$res_id'";
        } elseif ($action == "REJECT"  && $_SESSION["position"] == "DSA") {
            $query .= ",status = 'R',
                            rejectedByAdmin = '$adminID' 
                            WHERE id = '$res_id' OR reservationID = '$res_id'";
        } else if ($action == "SUBMIT") {
            $query .= ",status = 'P'  
                            WHERE id = '$res_id' OR reservationID = '$res_id'";
        } else {
            $query .= " WHERE id = '$res_id' OR reservationID = '$res_id'";
        }

        // if($_SESSION["position"] == "STO"){
        //     //Prevent Student Officer from updating notes
        //     $query .= ($_SESSION['position'] != 'STO' ?
        //         ", notes = '$notes', material = '$material' WHERE id = '$res_id' OR reservationID = '$res_id'" :
        //         " WHERE id = '$res_id' OR reservationID = '$res_id'");
        // }

        $update_reservation = $db->query($query);

        if (!$update_reservation) {
            $queryStatus = 1;
            /***** */
            header("location: edit_reservation.php?reservation_id=" . $res_id . "&queryStatus=" . $queryStatus);
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
            header("location: edit_reservation.php?reservation_id=" . $res_id . "&queryStatus=" . $queryStatus);
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
    $sponsor = "";
    $contribution = "";
    $incharge = "";
    $act_form_file = "";
    $letter_approve_file = "";
    $act_form_file_ext = "";
    $letter_approve_file_ext = "";
    $status = "";
    $material = "";
    $statusStr = "Draft";
    $statusID = "D";
    $soundsystem = "";
    $micro = "";

    $sequence = $db->query("SELECT * FROM schedules WHERE id = '$res_id' OR reservationID = '$res_id'");
    $fetch = $sequence->fetchAll(PDO::FETCH_OBJ);

    foreach ($fetch as $data) {
        //prevent studs to update record if approved
        if ($data->status == 'A' && $_SESSION['position'] == 'STO') {
            //redirect somewhere
            header("location: reservation_list.php");
            exit();
        }
        switch ($data->status) {
            case "D":
                $statusStr = "Draft";
                break;
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
        $sponsor = $data->sponsor;
        $contribution = $data->contribution;
        $incharge = $data->incharge;
        $notes = $data->notes;
        $material = $data->others_material;
        $statusID = $data->status;
        $act_form_file = $data->act_form_file;
        $letter_approve_file = $data->letter_approve_file;
        $soundsystem = $data->sound_system;
        $micro = $data->microphone;

        $file_ext = explode(".", $act_form_file);
        $act_form_file_ext = (strtolower(end($file_ext)) == "pdf") ? "application/" . strtolower(end($file_ext)) : "image/" . strtolower(end($file_ext));

        $file_ext = explode(".", $letter_approve_file);
        $letter_approve_file_ext = (strtolower(end($file_ext)) == "pdf") ? "application/" . strtolower(end($file_ext)) : "image/" . strtolower(end($file_ext));
    }
}

$queryStr = "";

if (!preg_match("/[a-z]/i", $tempID)) {
    $queryStr = "SELECT * FROM schedules 
                    WHERE (status = 'A' OR status = 'P' OR status = 'D') AND
                            (id != '$tempID')";
} else {
    $queryStr = "SELECT * FROM schedules 
                    WHERE (status = 'A' OR status = 'P' OR status = 'D') AND
                            (reservationID != '$tempID')";
}

$reservationListQuery = $db->query($queryStr);

$fetchReservations = $reservationListQuery->fetchAll(PDO::FETCH_OBJ);

if (!isset($_GET['queryStatus'])) {
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
                                        <input class="form-control" type="text" name="activity" value="<?= $activity ?>" required <?php echo ($statusID != "D" ? "readonly" : ""); ?>>
                                    </div>
                                    <div class="form-group">
                                        <label>Status</label>
                                        <input class="form-control" type="text" name="status" value="<?= $statusStr ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>Objectives</label>
                                        <textarea class="form-control" id="exampleFormControlTextarea1" name="description" placeholder="Please specify the objectives" rows="3" required <?php echo ($statusID != "D" ? "readonly" : ""); ?>><?= $objectives ?></textarea>
                                        <!-- <input class="form-control" type="number" placeholder="No. of participants" name="participants" required> -->
                                    </div>
                                    <div class="form-group">
                                        <label>Program</label>
                                        <select class="form-control" name="program" <?php echo ($statusID != "D" ? "disabled" : ""); ?>>
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
                                        <select class="form-control" name="venue" id="venue" <?php echo ($statusID != "D" ? "disabled" : ""); ?>>
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
                                            <input class="form-control" type="date" name='start_date' id="startDate" value="<?= $start_date ?>" required <?php echo ($statusID != "D" ? "readonly" : ""); ?>>
                                        </div>

                                        <div class="col-6">
                                            <label>Start Time</label>
                                            <input class="form-control" type="time" name='start_time' id="startTime" value="<?= $start_time ?>" required <?php echo ($statusID != "D" ? "readonly" : ""); ?>>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-6">
                                            <label>End Date</label>
                                            <input class="form-control" type="date" name='end_date' id="endDate" value="<?= $end_date ?>" required <?php echo ($statusID != "D" ? "readonly" : ""); ?>>
                                        </div>
                                        <div class="col-6">
                                            <label>End Time</label>
                                            <input class="form-control" type="time" name='end_time' id="endTime" value="<?= $end_time ?>" required <?php echo ($statusID != "D" ? "readonly" : ""); ?>>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Approximate No. of Participants</label>
                                        <input class="form-control" type="number" name="participants" placeholder="No. of participants" value="<?= $participants ?>" required <?php echo ($statusID != "D" ? "readonly" : ""); ?>>
                                    </div>
                                    <div class="form-group">
                                        <label>Organization/Sponsor</label>
                                        <input class="form-control" type="text" name="sponsor" value="<?= $sponsor ?>" required <?php echo ($statusID != "D" ? "readonly" : ""); ?>>
                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="form-group">
                                        <label>Amount of Contribution per Student</label>
                                        <input class="form-control" type="number" name="contribution" step="0.01" value="<?= $contribution ?>" required <?php echo ($statusID != "D" ? "readonly" : ""); ?>>
                                    </div>
                                    <div class="form-group">
                                        <label>Person/s in-charge</label>
                                        <input class="form-control" type="text" name="incharge" value="<?= $incharge ?>" required <?php echo ($statusID != "D" ? "readonly" : ""); ?>>
                                    </div>

                                    <div class="form-group">
                                        <div class="imgUp">
                                            <input type="<?php echo (($act_form_file == "" || $statusID != "D") ? "hidden" : "checkbox"); ?>" name="chk[]" class="check" id="chk1" style="width: 15px; height: 15px;" value="chk1">
                                            <input type="hidden" value="<?php echo $act_form_file; ?>" name="origActForm" readonly>
                                            <label><b>Upload Fully Signed Student Activity Form:</b></label>
                                            <span class="invalidFormat" style="visibility: hidden; color: red">Invalid file format</span>
                                            <input type="file" class="form-control uploadFile img activityForm" name="activityFormImg" accept="image/jpeg, image/png, application/pdf" id="activityForm" aria-label="0" <?php echo (($act_form_file != "" || $statusID != "D") ? "disabled" : ""); ?>>
                                            <iframe src="uploads/<?= (($act_form_file == "") ? "no-file-icon.png" : $act_form_file) ?>" type="<?= $act_form_file_ext ?>" scrolling="auto" height="250px" width="100%" class="getImg"> </iframe>
                                            <a class="btn btn-sm btn-primary preview" id="0" <?php echo (($act_form_file == "") ? "hidden" : ""); ?>>Preview</a>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="imgUp" style="overflow-y:scroll;">
                                            <input type="<?php echo (($act_form_file == "" || $statusID != "D") ? "hidden" : "checkbox"); ?>" name="chk[]" class="check" id="chk2" style="width: 15px; height: 15px;" value="chk2">
                                            <input type="hidden" value="<?php echo $letter_approve_file; ?>" name="origLetterApproval" readonly>
                                            <label><b>Upload Letter of Approval:</b></label>
                                            <span class="invalidFormat" style="visibility: hidden; color: red">Invalid file format</span>
                                            <input type="file" class="form-control uploadFile img" name="letterApprovalImg" id="letterApproval" accept="image/jpeg, image/png, application/pdf" aria-label="0" <?php echo (($act_form_file != "" || $statusID != "D") ? "disabled" : ""); ?>>
                                            <iframe src="uploads/<?= (($letter_approve_file == "") ? "no-file-icon.png" : $letter_approve_file) ?>" type="<?= $letter_approve_file_ext ?>" scrolling="auto" height="250px" width="100%" class="getImg"> </iframe>
                                            <a class="btn btn-sm btn-primary preview" id="1" <?php echo (($letter_approve_file == "") ? "hidden" : ""); ?>>Preview</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="form-group note-form-group">
                                        <label>Materials to reserve:</label>
                                    </div>
                                    <div class="input-group mb-3">
                                        <div class="input-group-text">
                                            <input class="form-check-input mt-0  sound" type="checkbox" <?php echo ($statusID != "D" ? "disabled" : ""); ?> <?php if ($soundsystem != NULL) {
                                                                                                                                                                echo "checked";
                                                                                                                                                            } ?> value="" name="sound-check" id="sound-check">
                                            Sound System
                                        </div>
                                        <input type="number" class="form-control" <?php echo ($statusID != "D" ? "readonly" : ""); ?> <?php if ($soundsystem == NULL) {
                                                                                                                                            echo "disabled";
                                                                                                                                        } ?> id="sound" value="<?= $soundsystem ?>" name="sound">
                                    </div>
                                    <div class="input-group mb-3">
                                        <div class="input-group-text">
                                            <input class="form-check-input mt-0 mic" <?php echo ($statusID != "D" ? "disabled" : ""); ?> type="checkbox" value="" <?php if ($micro != NULL) {
                                                                                                                                                                        echo "checked";
                                                                                                                                                                    } ?> name="mic-check" id="mic-check">
                                            Microphone &nbsp;&nbsp;&nbsp;&nbsp;
                                        </div>
                                        <input type="number" <?php echo ($statusID != "D" ? "readonly" : ""); ?> <?php if ($micro == NULL) {
                                                                                                                        echo "disabled";
                                                                                                                    } ?> class="form-control" id="mic" name="mic" value="<?= $micro ?>">
                                    </div>
                                    <div class="form-group note-form-group">
                                        <label>Others, Please specify:</label>
                                        <textarea class="form-control" id="noteTextArea" name='material' rows="3" <?php echo ($statusID != "D" ? "readonly" : ""); ?>><?= $material ?></textarea>
                                    </div>
                                    <div class="form-group note-form-group">
                                        <label>Notes</label>
                                        <textarea class="form-control" id="noteTextArea" name='notes' placeholder="Notes will be provided by Property Custodian" rows="3" <?= (($_SESSION['position'] == 'STO' ? 'readonly' : '')); ?>><?= $notes ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Choose action to perform</label>
                                        <select class="form-control" id="performAction" name="action">
                                            <option value='UPDATE' selected>Update Record Only</option>
                                            <?php if ($statusID == "D") { ?>
                                                <option value='SUBMIT'>Submit for Approval</option>
                                            <?php }
                                            if ($statusID == "P" && $_SESSION["position"] == "DSA") { ?>
                                                <option value='APPROVE'>Approve</option>
                                                <option value='REJECT'>Reject</option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div>

                                    </div>
                                    <a class="btn btn-success btn-icon-split btn-sm keychainify-checked confirmBtn" href="">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-check"></i>
                                        </span>
                                        <span class="text">APPLY UPDATES</span>
                                    </a>

                                    <div class="form-group mt-5" <?php echo (($statusID != "D") ? "hidden" : ""); ?>>
                                        <p>Please <a href="SAF.php?reservation_id=<?= $res_id ?>" target="_blank">print your SAF here</a> (with details included) and Complete Approval before Upload.</p>
                                    </div>

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
                                var errors = 0;
                                var curStartDate = $('#startDate').val();
                                var curStartTime = $('#startTime').val();

                                $('#performAction').change(function() {
                                    if ($(this).val() == "UPDATE") {
                                        $('#activityForm').attr('required', false)
                                    } else {
                                        $('#activityForm').attr('required', true)
                                    }
                                })

                              

                                $(document).on("click", ".sound", function() {
                                    let soundsystem = document.getElementById("sound-check");
                                    $('#sound-check').change(function() {
                                        if (soundsystem.checked) {
                                            $('#sound').attr('disabled', false)
                                            $('#sound').attr('required', true)
                                        } else {
                                            $('#sound').attr('disabled', true)
                                            $('#sound').attr('required', false)
                                            $('#sound').val("")
                                        }
                                    })
                                });

                                $(document).on("click", ".mic", function() {
                                    let miccheck = document.getElementById("mic-check");
                                    $('#mic-check').change(function() {
                                        if (miccheck.checked) {
                                            $('#mic').attr('disabled', false)
                                            $('#mic').attr('required', true)
                                        } else {
                                            $('#mic').attr('disabled', true)
                                            $('#mic').attr('required', false)
                                            $('#mic').val("")
                                        }
                                    })
                                });

                                $(document).on("click", ".check", function() {
                                    var check1 = $("#chk1");
                                    var check2 = $("#chk2");
                                    var checks = $(".check");
                                    var activityForm = $("#activityForm");
                                    var letterApproval = $("#letterApproval");

                                    if (check1.is(":checked")) {
                                        activityForm.prop({
                                            disabled: false,
                                            required: true
                                        });
                                    } else {
                                        activityForm.prop({
                                            disabled: true,
                                            required: false
                                        });
                                    }

                                    if (check2.is(":checked")) {
                                        letterApproval.prop({
                                            disabled: false
                                        });
                                    } else {
                                        letterApproval.prop({
                                            disabled: true
                                        });
                                    }

                                });

                                $(document).on("change", ".uploadFile", function() {
                                    var uploadFile = $(this);
                                    var files = !!this.files ? this.files : [];
                                    var fileExt = (files[0].type).split("/")[1];
                                    const allowedExt = ["pdf", "png", "jpg", "jpeg"];
                                    if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support

                                    var reader = new FileReader();
                                    reader.readAsDataURL(files[0]);

                                    if (fileExt == null) {
                                        uploadFile.prop({
                                            ariaLabel: "1"
                                        });
                                        errors++;
                                        uploadFile.closest(".imgUp").find('.getImg').attr('src', '');
                                        uploadFile.closest(".imgUp").find('.invalidFormat').css({
                                            visibility: "visible"
                                        });
                                        uploadFile.css({
                                            color: "red",
                                        });
                                    } else {
                                        if (!(allowedExt.includes(fileExt.toLowerCase()))) {
                                            uploadFile.prop({
                                                ariaLabel: "1"
                                            });
                                            errors++;
                                            uploadFile.closest(".imgUp").find('.getImg').attr('src', '');
                                            uploadFile.closest(".imgUp").find('.invalidFormat').css({
                                                visibility: "visible"
                                            });
                                            uploadFile.css({
                                                color: "red",
                                            });

                                        } else {
                                            uploadFile.prop({
                                                ariaLabel: "0"
                                            });
                                            uploadFile.closest(".imgUp").find('.invalidFormat').css({
                                                visibility: "hidden"
                                            });
                                            uploadFile.css({
                                                color: "black"
                                            });

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
                                        }
                                    }

                                    // if (/^image/.test( files[0].type) || /^pdf/.test( files[0].type)){ // only image file
                                    //     var reader = new FileReader(); // instance of the FileReader
                                    //     reader.readAsDataURL(files[0]); // read the local file

                                    //     reader.onloadend = function(){ // set image data as background of div
                                    //         //alert(uploadFile.closest(".upimage").find('.imagePreview').length);
                                    //         // css("src", "url("+this.result+")");
                                    //         uploadFile.closest(".imgUp").find('.getImg').attr('src',this.result);
                                    //         $(".test").attr("src",this.result);
                                    //         console.log($(".test"));
                                    //         console.log(this.result);
                                    //     }
                                    // }

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

                                $(".confirmBtn").on("click", function(e) {
                                    e.preventDefault();
                                    var form = $('#form');
                                    var confirmMsg = "Press PROCEED to apply updates to this record.";
                                    var perfActionVal = $("#performAction").val();
                                    var activityForm = $('#activityForm');
                                    var letterOfApproval = $('#letterApproval');
                                    var isValidated = form[0].reportValidity();
                                    var resConflict = checkScheduleConflict();
                                    console.log(isValidated);

                                    if (isValidated) {
                                        if (activityForm.attr("aria-label") != "0" || letterOfApproval.attr("aria-label") != "0") {
                                            $('.modal-header').prop({
                                                class: "modal-header bg-danger"
                                            });
                                            $('.modal-title').html("Error");
                                            $('.modal-body').html("There were erros upon submitting reservation. Please review your changes.");
                                            $('#submitBtn').prop({
                                                hidden: true
                                            });
                                            $('#confirmModal').modal({
                                                show: true
                                            });
                                        } else if (resConflict.length != 0) {
                                            $('.modal-header').prop({
                                                class: "modal-header bg-warning"
                                            });
                                            $('.modal-title').html("Warning");
                                            $('.modal-body').html("Warning: This reservation has conflicted with " + resConflict[0][5] + " Reservation " +
                                                resConflict[0][0] + ", conflict detected!<br><br> " + resConflict[0][5] + " Reservation Details:<br><br> " +
                                                "<b>ReservationID: </b>" + resConflict[0][0] + "<br>" +
                                                "<b>Start Date: </b>" + resConflict[0][1] + "<br>" +
                                                "<b>Start Time: </b>" + resConflict[0][2] + "<br>" +
                                                "<b>End Date: </b>" + resConflict[0][3] + "<br>" +
                                                "<b>End Time: </b>" + resConflict[0][4] + "<br>");
                                            $('#submitBtn').prop({
                                                hidden: true
                                            });

                                            // $('#reEdit').prop({
                                            //     hidden: false
                                            // });

                                            $('#confirmModal').modal({
                                                show: true
                                            });


                                        } else {
                                            if (perfActionVal == "REJECT") {
                                                confirmMsg = "Are you sure you want to REJECT this reservation?";
                                                $('.modal-header').prop({
                                                    class: "modal-header bg-danger"
                                                });
                                                $('.modal-title').html("Confirm Update");
                                                $('#confirmModal').modal({
                                                    show: true
                                                });
                                            } else if (perfActionVal == "APPROVE") {
                                                confirmMsg = "Are you sure you want to APPROVE this reservation?";
                                                $('.modal-header').prop({
                                                    class: "modal-header bg-success"
                                                });
                                                $('.modal-title').html("Confirm Update");
                                                $('#confirmModal').modal({
                                                    show: true
                                                });
                                            } else if (perfActionVal == "SUBMIT") {
                                                confirmMsg = "Are you sure you want to SUBMIT this reservation?";
                                                $('.modal-header').prop({
                                                    class: "modal-header bg-primary"
                                                });
                                                $('.modal-title').html("Confirm Submit");
                                                $('#confirmModal').modal({
                                                    show: true
                                                });
                                            } else {
                                                confirmMsg = "Are you sure you want to UPDATE this reservation?";
                                                $('.modal-header').prop({
                                                    class: "modal-header bg-success"
                                                });
                                                $('.modal-title').html("Confirm Update");
                                                $('#confirmModal').modal({
                                                    show: true
                                                });
                                            }

                                            $(".modal-body").html(confirmMsg);
                                            $('#submitBtn').prop({
                                                hidden: false
                                            });
                                            $('#confirmModal').modal({
                                                show: true
                                            });
                                        }
                                    }


                                });

                                function checkScheduleConflict() {
                                    var reservationList = <?php echo json_encode($fetchReservations); ?>;
                                    var conflictCounter = 0;
                                    var currentVenue = $("#venue").val();
                                    var returnDetail = [];

                                    console.log(reservationList.length);

                                    for (let index = 0; index < reservationList.length; index++) {
                                        if (reservationList[index]["venueID"] == currentVenue) {
                                            var convCurStartDate = new Date($('#startDate').val());
                                            var convCurStartTime = $('#startTime').val();
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
                                            var isApprovedStartDateBetweenCurrentDates = (approvedStartDate.getTime() > convCurStartDate.getTime()) &&
                                                (approvedStartDate.getTime() < convCurEndDate.getTime());

                                            //Vice versa
                                            var isApprovedEndDateBetweenCurrentDates = (approvedEndDate.getTime() > convCurStartDate.getTime()) &&
                                                (approvedEndDate.getTime() < convCurEndDate.getTime());

                                            if ((convCurStartDate.getTime() > approvedStartDate.getTime()) &&
                                                (convCurStartDate.getTime() < approvedEndDate.getTime())) {
                                                console.log("1");
                                                conflictCounter++;
                                                break;
                                            }
                                            if ((convCurStartDate.getTime() == approvedStartDate.getTime()) ||
                                                (convCurStartDate.getTime() == approvedEndDate.getTime())) {
                                                if ((convCurStartTime >= approvedStartTime) && (convCurStartTime <= approvedEndTime)) {
                                                    console.log("2");
                                                    conflictCounter++;
                                                    break;
                                                } else {
                                                    if ((approvedStartTime >= convCurStartTime) && (approvedStartTime <= convCurEndTime)) {
                                                        console.log("2.1");
                                                        conflictCounter++;
                                                        break;
                                                    }
                                                    if ((approvedEndTime >= convCurStartTime) && (approvedEndTime <= convCurEndTime)) {
                                                        console.log("2.2");
                                                        conflictCounter++;
                                                        break;
                                                    }
                                                }
                                            }
                                            if ((convCurEndDate.getTime() > approvedStartDate.getTime()) &&
                                                (convCurEndDate.getTime() < approvedEndDate.getTime())) {
                                                console.log("3");
                                                conflictCounter++;
                                                break;
                                            }
                                            if ((convCurEndDate.getTime() == approvedStartDate.getTime()) ||
                                                (convCurEndDate.getTime() == approvedEndDate.getTime())) {
                                                if ((convCurEndTime >= approvedStartTime) && (convCurEndTime <= approvedEndTime)) {
                                                    console.log("4");
                                                    conflictCounter++;
                                                    break;
                                                } else {
                                                    if ((approvedStartTime >= convCurStartTime) && (approvedStartTime <= convCurEndTime)) {
                                                        console.log("4.1");
                                                        conflictCounter++;
                                                        break;
                                                    }
                                                    if ((approvedEndTime >= convCurStartTime) && (approvedEndTime <= convCurEndTime)) {
                                                        console.log("4.2");
                                                        conflictCounter++;
                                                        break;
                                                    }
                                                }
                                            }
                                            if (isApprovedStartDateBetweenCurrentDates && isApprovedEndDateBetweenCurrentDates) {
                                                console.log("5");
                                                conflictCounter++;
                                                break;
                                            }
                                        }
                                    }
                                    returnDetail = ((conflictCounter == 0) ? [] : returnDetail);
                                    console.log("Counter: " + ((conflictCounter == 0) ? "No Conflicts" : "Conflict"));
                                    return returnDetail;
                                }

                                // else if (letterOfApproval.val() == '') {
                                //             $('.modal-header').prop({
                                //                 class: "modal-header bg-warning"
                                //             });
                                //             $('.modal-title').html("Warning");
                                //             $('.modal-body').html("This reservation does not have a Letter of Approval. Do you wish to..");
                                //             $('#submitBtn').prop({
                                //                 hidden: false
                                //             });
                                //             $('#confirmModal').modal({
                                //                 show: true
                                //             });
                                //         } 
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'settings/footer.php'; ?>