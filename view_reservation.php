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

                    if (isset($_GET['reservation_id'])) {
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
                        $act_form_file_ext = "";
                        $letter_approve_file = "";
                        $letter_approve_file_ext = "";

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
                            $contact = $data->contact;

                            $file_ext = explode(".", $act_form_file);
                            $act_form_file_ext = (strtolower(end($file_ext)) == "pdf") ? "application/" . strtolower(end($file_ext)) : "image/" . strtolower(end($file_ext));

                            $file_ext = explode(".", $letter_approve_file);
                            $letter_approve_file_ext = (strtolower(end($file_ext)) == "pdf") ? "application/" . strtolower(end($file_ext)) : "image/" . strtolower(end($file_ext));
                        }
                    }

                    ?>
                    <div class="table-responsive-lg">
                        <form role="form" method="post" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-6">
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
                                    <div class="form-group row">
                                        <div class="col-6">
                                            <label>Program</label>
                                            <select class="form-control" disabled>
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
                                        <div class="col-6">
                                            <label>Contact No.</label>
                                            <input class="form-control" type="text" name="contact_no" value="<?= $contact ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Choose Venue</label>
                                        <select class="form-control" disabled>
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
                                            <input class="form-control" type="date" value="<?= $end_date ?>" readonly>
                                        </div>
                                        <div class="col-6">
                                            <label>End Time</label>
                                            <input class="form-control" type="time" value="<?= $end_time ?>" readonly>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group">
                                        <label><b>Upload Fully Signed Student Activity Form:</b></label>
                                        <div class="imgUp">
                                            <!-- <img class="" data-enlargeable src="uploads/<?= $act_form_file ?>" > -->
                                            <iframe src="uploads/<?= $act_form_file ?>" type="<?= $act_form_file_ext ?>" scrolling="auto" height="220px" width="100%" class="getImg"> </iframe>
                                            <a class="btn btn-sm btn-primary preview" id="0">Preview</a>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label><b>Upload Letter of Approval:</b></label>
                                        <div class="imgUp">
                                            <!-- <img class="" data-enlargeable src="uploads/<?= $letter_approve_file ?>" > -->
                                            <iframe src="uploads/<?= (($letter_approve_file == "") ? "no-file-icon.png" : $letter_approve_file) ?>" type="<?= $letter_approve_file_ext ?>" scrolling="auto" height="220px" width="100%" class="getImg"> </iframe>
                                            <a class="btn btn-sm btn-primary preview" id="1">Preview</a>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Notes</label>
                                        <textarea class="form-control" id="exampleFormControlTextarea1" name='notes' placeholder="Notes will be provided by Property Custodian or Admin" rows="3" <?= (($_SESSION['position'] == 'STO' ? 'disabled' : '')); ?> readonly><?= $notes ?></textarea>
                                    </div>

                                </div>

                                <div class="col-4">

                                    <div>

                                    </div>

                        </form>

                        <script>
                            $(function() {
                                $(document).on("change", ".uploadFile", function() {
                                    var uploadFile = $(this);
                                    var files = !!this.files ? this.files : [];
                                    if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support

                                    if (/^image/.test(files[0].type)) { // only image file
                                        var reader = new FileReader(); // instance of the FileReader
                                        reader.readAsDataURL(files[0]); // read the local file

                                        reader.onloadend = function() { // set image data as background of div
                                            //alert(uploadFile.closest(".upimage").find('.imagePreview').length);
                                            uploadFile.closest(".imgUp").find('.imagePreview').css("background-image", "url(" + this.result + ")");
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
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'settings/footer.php'; ?>