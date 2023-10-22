<?php
error_reporting(E_ALL);
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
                    
                        if(isset($_POST['submit']))
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
									    $message = $upload_errors[$fileerror];

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
                    }
                    ?>
                    <div class="table-responsive-lg">
                        <form role="form" method="post" enctype="multipart/form-data">
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
                                        <!-- <input class="form-control" type="text" name="venueID" value="<?= 'VN' . $ID ?>" readonly> -->
                                    </div>
                                    <div class="form-group">
                                        <label>Approximate No. of Participants</label>
                                        <input class="form-control" type="number" placeholder="No. of participants" name="participants" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Objectives</label>
                                        <textarea class="form-control" id="exampleFormControlTextarea1" placeholder="Please specify the objectives" rows="3" name="description"></textarea>
                                        <!-- <input class="form-control" type="number" placeholder="No. of participants" name="participants" required> -->
                                    </div>
                                    <div class="form-group">
                                        <label>Program</label>
                                        <select class="form-control" name="program" >
                                            <?php  
                                            $fetchPrograms = $db->query("SELECT * FROM `program` ORDER BY name ASC");

                                            $row_donor = $fetchPrograms->fetchAll(PDO::FETCH_OBJ);
                                            
                                            foreach($row_donor as $row){
                                            ?>
                                                <option value="<?php echo $row->id ?>"> <?php echo $row->name ?> </option>
                                            <?php
                                            }
                                            
                                            ?>
                            
                                        </select>
                   
                                    </div>
                                    <div class="form-group">
                                        <label>Choose Venue</label>
                                        <select class="form-control" name="venue" >
                                            <?php  
                                            $fetchVenues = $db->query("SELECT * FROM `venues` ORDER BY name ASC");

                                            $row_donor = $fetchVenues->fetchAll(PDO::FETCH_OBJ);
                                            
                                            foreach($row_donor as $row){
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
                                            <input class="form-control" type="date" name="start_date" required>
                                        </div>

                                        <div class="col-6">   
                                            <label>Start Time</label>
                                            <input class="form-control" type="time" name="start_time" required>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-6">   
                                            <label>End Date</label>
                                            <input class="form-control" type="date" name="end_date" required>
                                        </div>
                                        <div class="col-6">   
                                            <label>End Time</label>
                                            <input class="form-control" type="time" name="end_time" required>
                                        </div>
                                        
                                    </div>
                                </div>

                                <div class="col-7">   
                                    <div class="form-group">
                                        <div class="imgUp">
                                            <label><b>Upload Fully Signed Student Activity Form:</b></label>
                                            <input type="file" class="form-control uploadFile img" name="activityFormImg" required>
                                            <div class="imagePreview"></div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="imgUp">   
                                            <label><b>Upload Letter of Approval:</b></label>
                                            <input type="file" class="form-control uploadFile img" name="letterApprovalImg" >
                                            <div class="imagePreview"></div>
                                        </div>
                                    </div>
                                    
                                </div>

                            </div>

                            <button type="submit" name='submit' class="btn btn-success btn-icon-split btn-sm keychainify-checked">
                                <span class="icon text-white-50">
                                    <i class="fas fa-plus"></i>
                                </span>
                                <span class="text">SUBMIT</span>
                            </button>
                            
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
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'settings/footer.php'; ?>