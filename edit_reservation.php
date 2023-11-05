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
                <h1 class="h3 mb-2 text-gray-800">Update Reservation</h1>
            </div>
            <div class="card shadow">

                <div class="card-body">
                    <?php
                        if(isset($_POST['submit']))
                        {
                            $adminID = $_SESSION['id'];
                            $action = $_POST['action'];
                            $res_id = $_POST['id'];
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
                            $status = $_POST['status'];

                            if($status != "A"){
                                $query = "UPDATE `schedules` 
                                SET venueID = '$venueID', programID = '$programID', date_start = '$startDate', date_end = '$endDate',
                                    time_start = '$startTime', time_end = '$endTime', name = '$activity', description = '$description',
                                    num_participants = '$participants'";

                                //Check if approved or rejected
                                if($_SESSION["position"] == "DSA"){
                                    if($action == "APPROVE"){
                                        $query .= ", notes = '$notes',
                                                    status = 'A',
                                                    approvedByAdmin = '$adminID'
                                                    WHERE id = '$res_id'";
                                    }elseif($action == "REJECT"){
                                        $query .= ", notes = '$notes',
                                                    status = 'R',
                                                    rejectedByAdmin = '$adminID'
                                                    WHERE id = '$res_id'";
                                    }else{
                                        $query .= ", notes = '$notes'
                                                    WHERE id = '$res_id'";
                                    }
                                }else{
                                    //Prevent Student Officer from updating notes
                                    $query .= ($_SESSION['position'] != 'STO' ?  
                                    ", notes = '$notes' WHERE id = '$res_id'":
                                    " WHERE id = '$res_id'");
                                }
                            
                                $update_reservation = $db->query($query);

                                if (!$update_reservation) {
                                    echo '<script>
                                            alert("Error updating reservation.");
                                        </script>';
                                } else {
                                    //Alert message must correspond to action performed
                                    echo '<script>
                                            alert("Successfully updated reservation.");
                                        </script>';
                                }
                            }
                        }
                        if(isset($_GET['reservation_id']))
                        {
                            $res_id = $_GET['reservation_id'];
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
                            $status = "";
                            $statusStr = "Pending for Approval";

                            $sequence = $db->query("SELECT * FROM schedules WHERE id = '$res_id'");
                            $fetch = $sequence->fetchAll(PDO::FETCH_OBJ);

                            foreach ($fetch as $data) {  
                                switch($data->status){
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
                                $act_form_file = $data->act_form_file;
                                $letter_approve_file = $data->letter_approve_file;
                            }
                        }
                       
                    ?>
                    <div class="table-responsive-lg">
                        <form role="form" method="post" enctype="multipart/form-data">
                            <div class="row">   
                                <div class="col-4">   
                                    <div class="form-group">
                                        <label>ID</label>
                                        <input class="form-control" type="hidden" name="id" value="<?= $res_id ?>" readonly>
                                        <input class="form-control" type="text" name="resID" value="<?= $res_id_text ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>Activity</label>
                                        <input class="form-control" type="text" name="activity" value="<?= $activity ?>" >
                                    </div>
                                    <div class="form-group">
                                        <label>Status</label>
                                        <input class="form-control" type="text" name="status" value="<?= $statusStr ?>" readonly>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Objectives</label>
                                        <textarea class="form-control" id="exampleFormControlTextarea1" name="description" placeholder="Please specify the objectives" rows="3" ><?= $objectives ?></textarea>
                                        <!-- <input class="form-control" type="number" placeholder="No. of participants" name="participants" required> -->
                                    </div>
                                    <div class="form-group">
                                        <label>Program</label>
                                        <select class="form-control" name="program">
                                            <?php  
                                            $fetchPrograms = $db->query("SELECT * FROM `program` ORDER BY name ASC");

                                            $row_donor = $fetchPrograms->fetchAll(PDO::FETCH_OBJ);
                                            
                                            foreach($row_donor as $row){
                                                if($row->id == $program_id){
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
                                        <select class="form-control" name="venue">
                                            <?php  
                                            $fetchVenues = $db->query("SELECT * FROM `venues` ORDER BY name ASC");

                                            $row_donor = $fetchVenues->fetchAll(PDO::FETCH_OBJ);
                                            
                                            foreach($row_donor as $row){
                                                if($row->id == $venue_id){
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
                                            <input class="form-control" type="date" name='start_date' value="<?= $start_date ?>" >
                                        </div>

                                        <div class="col-6">   
                                            <label>Start Time</label>
                                            <input class="form-control" type="time" name='start_time' value="<?= $start_time ?>" >
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-6">   
                                            <label>End Date</label>
                                            <input class="form-control" type="date" name='end_date' value="<?= $end_date ?>"  >
                                        </div>
                                        <div class="col-6">   
                                            <label>End Time</label>
                                            <input class="form-control" type="time" name='end_time' value="<?= $end_time ?>"  >
                                        </div>
                                        
                                    </div>
                                </div>

                                <div class="col-4">   
                                    <div class="form-group">
                                        <label>Approximate No. of Participants</label>
                                        <input class="form-control" type="number" name="participants" placeholder="No. of participants" value="<?= $participants ?>" >
                                    </div>
                                    <div class="form-group">
                                        <div class="imgUp">
                                            <label><b>Uploaded Fully Signed Student Activity Form:</b></label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="imgUp">
                                            <img data-enlargeable class="imagePreview" src="uploads/<?= $act_form_file ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="imgUp">   
                                            <label><b>Uploaded Letter of Approval:</b></label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="imgUp">   
                                             <img data-enlargeable class="imagePreview" src="uploads/<?= $letter_approve_file ?>">
                                        </div>
                                    </div> 
                                </div>

                                <div class="col-4">
                                    <div class="form-group note-form-group">
                                        <label>Notes</label>
                                        <textarea class="form-control" id="noteTextArea" name='notes' placeholder="Notes will be provided by Property Custodian or Admin" rows="3"
                                            <?= (($_SESSION['position'] != 'PTC' ? 'readonly': ''));?>><?= $notes ?></textarea>
                                    </div> 

                                    <div class="form-group" <?= (($_SESSION['position'] != 'DSA'? 'hidden': ''));?>>
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
                                    <i class="fas fa-plus"></i>
                                </span>
                                <span class="text">APPLY UPDATES</span>
                            </a>

                             <!-- Confirm Update Modal-->
                            <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                aria-hidden="true">
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
                                        <button type="submit" name='submit' class="btn btn-success btn-icon-split btn-sm keychainify-checked"
                                            <?= (($status == 'A' ? 'disabled': ''));?>>
                                            <span class="icon text-white-50">
                                                <i class="fas fa-plus"></i>
                                            </span>
                                            <span class="text">PROCEED</span>
                                        </button>
                                        <button class="btn btn-secondary btn-icon-split btn-sm keychainify-checked" type="button" data-dismiss="modal">
                                             <span class="icon text-white-50">
                                                <i class="fas fa-window-close"></i>
                                            </span>
                                            <span class="text">CANCEL</span>
                                        </button>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </form>

                        <script>
                            $(function() {
                                // var imageContainer = $(".imgUp").css("height", "30%");

                                $(document).on("change",".uploadFile", function()
                                {
                                    var uploadFile = $(this);
                                    var files = !!this.files ? this.files : [];
                                    var getCurrentUpload = $(".imagePreview");
                                    if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support
                                    
                            
                                    if (/^image/.test( files[0].type)){ // only image file
                                        var reader = new FileReader(); // instance of the FileReader
                                        reader.readAsDataURL(files[0]); // read the local file
                            
                                        reader.onloadend = function(){ // set image data as background of div
                                            //alert(uploadFile.closest(".upimage").find('.imagePreview').length);
                                        uploadFile.closest(".imgUp")
                                            .find('.imagePreview').css("background-image", "url("+this.result+")")
                                            .prop("src","");
                                        }
                                    }
                                
                                });

                                $('img[data-enlargeable]').addClass('img-enlargeable').click(function() {
                                var src = $(this).attr('src');
                                var modal;

                                function removeModal() {
                                    modal.remove();
                                    $('body').off('keyup.modal-close');
                                }
                                modal = $('<div>').css({
                                        background: 'RGBA(0,0,0,.5) url(' + src + ') no-repeat center',
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
                                    }).appendTo('body');
                                    //handling ESC
                                    $('body').on('keyup.modal-close', function(e) {
                                        if (e.key === 'Escape') {
                                        removeModal();
                                        }
                                    });
                                 });

                                $("#performAction").on("change", function(){
                                    var statusVal = $(this).val();
                                    if(statusVal == "REJECT"){
                                        $("#noteTextArea").prop({
                                            required: true,
                                            readonly: false
                                        });
                                    }else if(statusVal == "APPROVE"){
                                        $("#noteTextArea").prop({
                                            required: false,
                                            readonly: true
                                        });
                                    }
                                });

                                $(".confirmBtn").on("click", function(){
                                    var confirmMsg = "Press PROCEED to apply updates to this record.";
                                    var perfActionVal = $("#performAction").val();
                                   
                                    if(perfActionVal == "REJECT"){
                                        confirmMsg = "Are you sure you want to REJECT this reservation?";
                                    }else if(perfActionVal == "APPROVE"){
                                        confirmMsg = "Are you sure you want to APPROVE this reservation?";
                                    }
                                    $(".modal-body").html(confirmMsg);
                                });
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'settings/footer.php'; ?>