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

    $imgForms = array();

    if ($_FILES["activityFormImg"]["error"]) {
        echo "<script> alert('Image does not exist')</script>";
    } else {
        //CHECK FOR IMAGE ERRORS:
        $valid_extensions = ['jpeg', 'jpg', 'png', 'pdf'];
        $check_error = 0;

        foreach ($_FILES as $file) {
            if ($file["name"] == "" || $file["size"] == "") continue;
            $file_name = $file["name"];
            $file_size = $file["size"];

            $image_extension = explode(".", $file_name);
            $image_extension = strtolower(end($image_extension));

            if (!in_array($image_extension, $valid_extensions)) {
                $queryStatus = 1;
                $check_error++;
            } elseif ($file_size > 10000000) {
                $queryStatus = 1;
                $check_error++;
            }
        }

        //Upload image 
        if ($check_error == 0) {

            foreach ($_FILES as $file) {
                if ($file["name"] == "" || $file_size == "") continue;
                $file_name = $file["name"];
                $tmp_name = $file["tmp_name"];
                //$tmp_name = $_FILES['photo']['tmp_name'];
                $fileerror = $file['error'];

                $image_extension = explode(".", $file_name);
                $image_extension = strtolower(end($image_extension));

                $unique_img_name = uniqid();
                $unique_img_name .= '.' . $image_extension;
                $path = 'uploads/';
                move_uploaded_file($tmp_name, $path . $unique_img_name);

                array_push($imgForms, $unique_img_name);
            }

            $activity_form = $imgForms[0];
            $letter_approval = ((sizeof($imgForms) > 1) ? $imgForms[1] : "");
            $add_res = $db->query("INSERT INTO `schedules` 
                    (reservationID,userID,venueID,programID,date_start,date_end,time_start,time_end,name,contact,description,num_participants,act_form_file,letter_approve_file) values
                    ('$res_id','$user_id','$venueID','$programID','$startDate','$endDate','$startTime','$endTime','$activity','$contact','$description','$participants','$activity_form','$letter_approval')")
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

$reservationListQuery = $db->query("SELECT * FROM schedules WHERE status = 'A' OR status = 'P'");
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
                                        <div class="imgUp">
                                            <label><b>Upload Fully Signed Student Activity Form:</b></label>
                                            <span class="invalidFormat" style="visibility: hidden; color: red">Invalid file format</span>
                                            <input type="file" class="form-control uploadFile img" name="activityFormImg" accept="image/jpeg, image/png, application/pdf" id="activityForm" aria-label="0" required>
                                            <iframe src="" type="" scrolling="auto" height="250px" width="100%" class="getImg"> </iframe>
                                            <a class="btn btn-sm btn-primary preview" id="0" hidden>Preview</a>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="imgUp" style="overflow-y:scroll;">
                                            <label><b>Upload Letter of Approval:</b></label>
                                            <span class="invalidFormat" style="visibility: hidden; color: red">Invalid file format</span>
                                            <input type="file" class="form-control uploadFile img" name="letterApprovalImg" id="letterApproval" accept="image/jpeg, image/png, application/pdf" aria-label="0">
                                            <iframe src="" type="" scrolling="auto" height="250px" width="100%" class=" getImg"> </iframe>
                                            <a class="btn btn-sm btn-primary preview" id="1" hidden>Preview</a>
                                        </div>
                                    </div>

                                </div>

                            </div>
                            <!-- data-toggle="modal" data-target="#emptyFormModal" -->
                            <a class="btn btn-success btn-icon-split btn-sm keychainify-checked confirmBtn1" href="">
                                <span class="icon text-white-50">
                                    <i class="fas fa-plus"></i>
                                </span>
                                <span class="text">SUBMIT</span>
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
                                            <a class="btn btn-secondary btn-icon-split btn-sm keychainify-checked" id="backToHome" data-dismiss="modal">
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

                                $(document).on("change", ".uploadFile", function() {
                                    var uploadFile = $(this);
                                    var files = !!this.files ? this.files : [];
                                    var fileExt = (files[0].type).split("/")[1];
                                    const allowedExt = ["pdf", "png", "jpg", "jpeg"];
                                    if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support

                                    var reader = new FileReader();
                                    reader.readAsDataURL(files[0]);

                                    // console.log(());
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

                                $('.confirmBtn1').click(function(e) {
                                    e.preventDefault();
                                    //Check fields
                                    var form = $('#form');
                                    var activityForm = $('#activityForm');
                                    var letterOfApproval = $('#letterApproval');
                                    var isValidated = form[0].reportValidity();
                                    var curStartDate = $('#startDate');
                                    var curStartTime = $('#startTime');
                                    var curEndDate = $('#endDate');
                                    var curEndTime = $('#endTime');

                                    if (isValidated) {
                                        //Check for errors
                                        var isFirst = $(this).attr("id") == "0";
                                        var fileObject = (isFirst) ? $('.getImg').first() : $('.getImg').last();
                                        var resConflict = checkScheduleConflict();

                                        if (activityForm.attr("aria-label") != "0" || letterOfApproval.attr("aria-label") != "0") {
                                            $('.modal-header').prop({
                                                class: "modal-header bg-danger"
                                            });
                                            $('.modal-title').html("Error");
                                            $('.modal-body').html("There were erros upon submitting reservation. Please review your changes.");
                                            $('#submitBtn').prop({
                                                hidden: true
                                            });
                                            $('#backToHome').prop({
                                                hidden: false
                                            });
                                            $('#emptyFormModal').modal({
                                                show: true
                                            });
                                        } else if (resConflict.length != 0){
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
                                        }else if (letterOfApproval.val() == '') {
                                            $('.modal-header').prop({
                                                class: "modal-header bg-warning"
                                            });
                                            $('.modal-title').html("Warning");
                                            $('.modal-body').html("This reservation does not have a Letter of Approval. Do you wish to..");
                                            $('#submitBtn').prop({
                                                hidden: false
                                            });
                                            $('#backToHome').prop({
                                                hidden: true
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