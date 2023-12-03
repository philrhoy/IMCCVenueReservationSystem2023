<?php
    include 'settings/system.php';
    include 'session.php';
    include 'settings/header.php';
    include "settings/sidebar.php";
    include 'settings/topbar.php';
    $queryStatus = 0;
    /*
        0 = default/no query
        1 = error 
        2 = success
    */


    if(isset($_POST['submit']))
    {
        $id = $_POST['id'];
        $res_id = $_POST['resID'];
        $user_id = $_SESSION['id'];
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
            $valid_extensions = ['jpeg', 'jpg', 'png', 'pdf'];
            $check_error = 0;

            foreach($_FILES as $file){
                if($file["name"] == "" || $file["size"] == "") continue;
                $file_name = $file["name"];
                $file_size = $file["size"];

                $image_extension = explode(".",$file_name);
                $image_extension = strtolower(end($image_extension));

                if(!in_array($image_extension, $valid_extensions)){
                    $queryStatus = 1;
                    $check_error++;
                    // echo"<script> alert('Invalid image extension')</script>";
                }
                elseif($file_size > 10000000){
                    $queryStatus = 1;
                    $check_error++;
                    // echo"<script> alert('Image size is too big')</script>";
                }
            }

            //Upload image 
            if ($check_error == 0){
            
                foreach($_FILES as $file){
                    if($file["name"] == "" || $file_size == "") continue;
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
                $letter_approval = ((sizeof($imgForms) > 1) ? $imgForms[1] : "");

                //Execute DB Insert
                $add_res = $db->query("INSERT INTO `schedules` 
                (reservationID,userID,venueID,programID,date_start,date_end,time_start,time_end,name,description,num_participants,act_form_file,letter_approve_file) values
                ('$res_id','$user_id','$venueID','$programID','$startDate','$endDate','$startTime','$endTime','$activity','$description','$participants','$activity_form','$letter_approval')") 
                or die($db->error);
                $update_sequence = $db->query("UPDATE number_sequence SET last_number = '$id' WHERE page_name='reservations'");

                if (!$add_res) {
                    $queryStatus = 1;
                    echo '<script>
                            alert("Error saving reservation.");
                        </script>';
                } else {
                    $queryStatus = 2;
                    // echo '<script>
                    //         alert("Successfully created reservation.");
                    //     </script>';
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
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">

        <div class="container-fluid">
            <div class="d-sm-flex align-items-center justify-content mb-4">
                <h1 class="h3 mb-2 text-gray-800"> Reservation Form</h1>
                
            </div>
            <div class="justify-content">   
                <div class="alert alert-success" role="alert" style="display:<?= (($queryStatus == 2)? "block;":"none;")?>">Successfully created reservation</div>
                <div class="alert alert-danger" role="alert" style="display:<?= (($queryStatus == 1)? "block;":"none;")?>">Failed to save reservation. Error code: #</div>
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
                                            <span class="invalidFormat"style="visibility: hidden; color: red">Invalid file format</span>
                                            <input type="file" class="form-control uploadFile img" name="activityFormImg" accept="image/jpeg, image/png, application/pdf" id="activityForm" aria-label="0" required>
                                            <iframe
                                                src=""
                                                type=""
                                                scrolling="auto"
                                                height="200px"
                                                width="100%"
                                                class="getImg">  </iframe> 
                                            <a class="btn btn-sm btn-primary preview" id="0" hidden>Preview</a>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="imgUp">   
                                            <label><b>Upload Letter of Approval:</b></label>
                                            <span class="invalidFormat"style="visibility: hidden; color: red">Invalid file format</span>
                                            <input type="file" class="form-control uploadFile img" name="letterApprovalImg" id="letterApproval" accept="image/jpeg, image/png, application/pdf" aria-label="0">
                                            <iframe
                                                src=""
                                                type=""
                                                scrolling="auto"
                                                height="200px"
                                                width="100%"
                                                class="getImg">  </iframe> 
                                            <a class="btn btn-sm btn-primary preview" id="1" hidden>Preview</a>
                                        </div>
                                    </div>
                                    
                                </div>

                            </div>
                            <!-- data-toggle="modal" data-target="#emptyFormModal" -->
                            <a class="btn btn-success btn-icon-split btn-sm keychainify-checked confirmBtn1" href="" >
                                <span class="icon text-white-50">
                                    <i class="fas fa-plus"></i>
                                </span>
                                <span class="text">SUBMIT</span>
                            </a>

                             <!-- FOR STUDENT FORM MODAL & ERRORS -->
                             <div class="modal fade" id="emptyFormModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                aria-hidden="true">
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
                                                <i class="fas fa-plus"></i>
                                            </span>
                                            <span class="text">Submit</span>
                                        </button>
                                        <button class="btn btn-info btn-icon-split btn-sm keychainify-checked" id="reEdit" data-dismiss="modal">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-plus"></i>
                                            </span>
                                            <span class="text">Re-edit reservation</span>
                                        </button>
                                        <a class="btn btn-secondary btn-icon-split btn-sm keychainify-checked" id="backToHome"data-dismiss="modal">
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

                                $(document).on("change",".uploadFile", function()
                                {
                                    var uploadFile = $(this);
                                    var files = !!this.files ? this.files : [];
                                    if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support
                                  
                                    var reader = new FileReader(); 
                                    reader.readAsDataURL(files[0]); 

                                    if (!/^image/.test( files[0].type) && !/^pdf/.test( files[0].type)){
                                        uploadFile.prop({ariaLabel : "1"});
                                        errors++;
                                        uploadFile.closest(".imgUp").find('.getImg').attr('src','');
                                        uploadFile.closest(".imgUp").find('.invalidFormat').css({visibility: "visible"});
                                        uploadFile.css({
                                            color: "red",
                                        });
                                       
                                    }else{
                                        uploadFile.prop({ariaLabel: "0"});
                                        uploadFile.closest(".imgUp").find('.invalidFormat').css({visibility: "hidden"});
                                        uploadFile.css({
                                            color: "black"
                                        });

                                        reader.onloadend = function(){ // set image data as background of div
                                            uploadFile.closest(".imgUp").find('.getImg').attr('src',this.result);
                                            if(uploadFile.attr("name")=="activityFormImg"){
                                                $(".getImg").first().attr("src",this.result);
                                                $(".getImg").first().attr("type",files[0].type);
                                                $(".preview").first().attr("hidden", false);
                                            }else if(uploadFile.attr("name")=="letterApprovalImg"){
                                                $(".getImg").last().attr("src",this.result);
                                                $(".getImg").last().attr("type",files[0].type);
                                                $(".preview").last().attr("hidden", false);
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
                                    var fileObject =  (isFirst) ? $('.getImg').first() : $('.getImg').last();
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
                                    
                                    modal2 = $('<iframe>').attr('src',fileObjectSrc).css({
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

                                    if(/^image/.test(fileObjectType)){
                                        modal.appendTo('body');
                                        // closeBtn.appendTo('body');
                                    }else if(/^application/.test(fileObjectType)){
                                        modal2.appendTo('body');
                                        // closeBtn.appendTo('body');
                                    }
                                    
                                });

                                $('.confirmBtn1').click(function (e){
                                    e.preventDefault();
                                    //Check fields
                                    var form = $('#form');
                                    var activityForm = $('#activityForm');
                                    var letterOfApproval = $('#letterApproval');
                                    var isValidated = form[0].reportValidity();
                                    console.log(activityForm.attr("aria-label"));
                                    console.log(letterOfApproval.attr("aria-label"));

                                    if(isValidated){
                                        //Check for errors
                                        var isFirst = $(this).attr("id") == "0";
                                        var fileObject =  (isFirst) ? $('.getImg').first() : $('.getImg').last();
                                        if(activityForm.attr("aria-label") != "0" || letterOfApproval.attr("aria-label") != "0"){
                                            $('.modal-header').prop({class : "modal-header bg-danger"});
                                            $('.modal-title').html("Error");
                                            $('.modal-body').html("There were erros upon submitting reservation. Please review your changes.");
                                            $('#submitBtn').prop({hidden : true});
                                            $('#backToHome').prop({hidden : false});
                                            $('#emptyFormModal').modal({
                                                show: true
                                            }); 
                                        }else if(letterOfApproval.val() == ''){
                                            $('.modal-header').prop({class : "modal-header bg-warning"});
                                            $('.modal-title').html("Warning");
                                            $('.modal-body').html("This reservation does not have a Letter of Approval. Do you wish to..");
                                            $('#submitBtn').prop({hidden : false});
                                            $('#backToHome').prop({hidden : true});
                                            $('#emptyFormModal').modal({
                                                show: true
                                            }); 
                                        }
                                        else{
                                            //Submit form
                                            $("#submitBtn").click();
                                            // form[0].submit();
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