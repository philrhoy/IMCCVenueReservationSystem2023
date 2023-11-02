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
                <h1 class="h3 mb-2 text-gray-800"> Reservation Form</h1>
            </div>
            <div class="card shadow">

                <div class="card-body">
                    <?php
            
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
                            $notes = "";
                            $act_form_file = "";
                            $letter_approve_file = "";

                            $sequence = $db->query("SELECT * FROM schedules WHERE id = '$res_id'");
                            $fetch = $sequence->fetchAll(PDO::FETCH_OBJ);

                            foreach ($fetch as $data) {
                                $res_id_text = $data->reservationID;
                                $activity = $data->name;
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
                        elseif(isset($_POST['submit']))
                        {
                            $id = $_POST['id'];
                            $res_id = $_POST['resID'];
                            $activity = $_POST['activity'];
                            $participants = $_POST['participants'];
                            $description = $_POST['description'];
                            $venueID = $_POST['venue'];
                            $programID = $_POST['program'];
                            $startDate = $_POST['start_date'];
                            $endDate = $_POST['end_date'];
                            $startTime = $_POST['start_time'];
                            $endTime = $_POST['end_time'];
                            $imgForms = array();

                            if($_FILES["activityFormImg"]["error"]){
                                echo"<script> alert('Image does not exist')</script>";
                            }else{
                                //CHECK FOR IMAGE ERRORS:
                                $valid_extensions = ['jpeg', 'jpg', 'png'];
                                $check_error = 0;

                                foreach($_FILES as $file){
                                    $file_name = $file["name"];
                                    $file_size = $file["size"];

                                    $image_extension = explode(".",$file_name);
                                    $image_extension = strtolower(end($image_extension));

                                    if(!in_array($image_extension, $valid_extensions)){
                                        $check_error++;
                                        echo"<script> alert('Invalid image extension')</script>";
                                    }elseif($file_size > 10000000){
                                        $check_error++;
                                        echo"<script> alert('Image size is too big')</script>";
                                    }
                                }

                                //Upload image 
                                if ($check_error == 0){
                              
                                    foreach($_FILES as $file){
                                        $file_name = $file["name"];
                                        $tmp_name = $file["tmp_name"];
                                        //$tmp_name = $_FILES['photo']['tmp_name'];
                                        //activityFormImg
                                        //letterApprovalImg
                                        $fileerror = $file['error'];

                                        $image_extension = explode(".",$file_name);
                                        $image_extension = strtolower(end($image_extension));

                                        $unique_img_name = uniqid();
                                        // $unique_img_name = $unique_img_name . ($index == 0 ? "ACT_FORM" : "APPROVAL");
                                        $unique_img_name .= '.' .$image_extension;
                                        $path = 'uploads/';
                                        move_uploaded_file($tmp_name, $path.$unique_img_name);
                                      
                                        array_push($imgForms,$unique_img_name);
                                    }

                                    $activity_form = $imgForms[0];
                                    $letter_approval = $imgForms[1];

                                    //Execute DB Insert
                                    $add_res = $db->query("INSERT INTO `schedules` 
                                    (reservationID,venueID,programID,date_start,date_end,time_start,time_end,name,description,num_participants,act_form_file,letter_approve_file) values
                                    ('$res_id','$venueID','$programID','$startDate','$endDate','$startTime','$endTime','$activity','$description','$participants','$activity_form','$letter_approval')") 
                                    or die($db->error);
                                    $update_sequence = $db->query("UPDATE number_sequence SET last_number = '$id' WHERE page_name='reservations'");

                                    if (!$add_res) {
                                        echo '<script>
                                                alert("Error saving reservation.");
                                            </script>';
                                    } else {
                                        echo '<script>
                                                alert("Successfully created reservation.");
                                            </script>';
                                    }
                                }
                                
                            }

                        }
                    ?>
                    <div class="table-responsive-lg">
                        <form role="form" method="post" enctype="multipart/form-data">
                            <div class="row">   
                                <div class="col-4">   
                                    <div class="form-group">
                                        <label>ID</label>
                                        <input class="form-control" type="text" name="resID" value="<?= $res_id_text ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>Activity</label>
                                        <input class="form-control" type="text" name="activity" value="<?= $activity ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>Approximate No. of Participants</label>
                                        <input class="form-control" type="number" placeholder="No. of participants" value="<?= $participants ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>Objectives</label>
                                        <textarea class="form-control" id="exampleFormControlTextarea1" placeholder="Please specify the objectives" rows="3" readonly><?= $objectives ?></textarea>
                                        <!-- <input class="form-control" type="number" placeholder="No. of participants" name="participants" required> -->
                                    </div>
                                    <div class="form-group">
                                        <label>Program</label>
                                        <select class="form-control" disabled>
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
                                        <select class="form-control" disabled>
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
                                            <input class="form-control" type="date" value="<?= $start_date ?>" readonly>
                                        </div>

                                        <div class="col-6">   
                                            <label>Start Time</label>
                                            <input class="form-control" type="time" value="<?= $start_time ?>" readonly>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-6">   
                                            <label>End Date</label>
                                            <input class="form-control" type="date" value="<?= $end_date ?>" readonly >
                                        </div>
                                        <div class="col-6">   
                                            <label>End Time</label>
                                            <input class="form-control" type="time" value="<?= $end_time ?>" readonly >
                                        </div>
                                        
                                    </div>
                                </div>

                                <div class="col-4">   
                                    <div class="form-group">
                                        <div class="imgUp">
                                            <label><b>Upload Fully Signed Student Activity Form:</b></label>
                                            <img class="imagePreview" data-enlargeable src="uploads/<?= $act_form_file ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="imgUp">   
                                            <label><b>Upload Letter of Approval:</b></label>
                                            <img class="imagePreview" data-enlargeable src="uploads/<?= $letter_approve_file ?>">
                                        </div>
                                    </div>
                                    
                                </div>

                                <div class="col-4">
                                    <div class="form-group">
                                        <label>Notes</label>
                                        <textarea class="form-control" id="exampleFormControlTextarea1" name='notes' placeholder="Notes will be provided by Property Custodian or Admin" rows="3"
                                            <?= (($_SESSION['position'] == 'STO' ? 'disabled': ''));?> ><?= $notes ?></textarea>
                                    </div>      
                                <div> 

                            </div>
                            
                        </form>

                        <script>
                            $(function() {
                                $(document).on("change",".uploadFile", function()
                                {
                                    var uploadFile = $(this);
                                    var files = !!this.files ? this.files : [];
                                    if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support
                            
                                    if (/^image/.test( files[0].type)){ // only image file
                                        var reader = new FileReader(); // instance of the FileReader
                                        reader.readAsDataURL(files[0]); // read the local file
                            
                                        reader.onloadend = function(){ // set image data as background of div
                                            //alert(uploadFile.closest(".upimage").find('.imagePreview').length);
                                        uploadFile.closest(".imgUp").find('.imagePreview').css("background-image", "url("+this.result+")");
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
                        });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'settings/footer.php'; ?>