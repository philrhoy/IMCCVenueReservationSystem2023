<?php
include 'settings/system.php';
include 'session.php';
include 'settings/header.php';
include "settings/sidebar.php";
include 'settings/topbar.php';
$donor = null;
?>
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">

        <div class="container-fluid">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-2 text-gray-800">Monthly Report</h1>
                <!-- <a href="venue_add.php" class="btn btn-success btn-icon-split btn-sm keychainify-checked">
                    <span class="icon text-white-50">
                        <i class="fas fa-fw fa-plus"></i>
                    </span>
                    <span class="text">Add Venue</span>
                </a> -->
            </div>
            <form method='POST'>
                <div class="d-flex">   
                    <div class="mr-auto">   
                        <select class="form-control form-control-sm"name='filter_type'id="filterType">
                            <option value="1" <?php if (isset($_POST['filter_type'])) echo (($_POST['filter_type'] == '1')?"selected":""); ?>>Use Month Year Filter</option>
                            <option value="2" <?php if (isset($_POST['filter_type'])) echo (($_POST['filter_type'] == '2')?"selected":""); ?>>Use Date Range Filter</option>
                        </select>
                    </div>
                </div>
            
            <div class="card shadow mb-4">
                <div class="card-body">
                    <!-- <div class="form-group mb-2 mr-4"> <small>Please select a Date Range to Display Activities. You can use filters below to see specific result:</small>
                    </div> -->
                    
                        <div class="row form-inline">   
                            <div class="form-group mb-2 mr-2 monthYearFilter">   
                                <label><small><strong>Month Year</strong></small> &nbsp;</label>
                                <input class="form-control form-control-sm" type="month" name="month_year" id="month_year" value="<?php if (isset($_POST['month_year'])) echo $_POST['month_year']; ?>"> 
                            </div>

                            <div class="form-group mb-2 mr-2 dateRangeFilter" style="display:none;">
                                <label><small><strong>Start Date</strong></small> &nbsp;</label>
                                <input class="form-control form-control-sm" type="date" name="start_date" id="start_date" value="<?php if (isset($_POST['start_date'])) {
                                                                                                                        echo htmlentities($_POST['start_date']);
                                                                                                                    } ?>">
                            </div>

                            <div class="form-group mb-2 mr-2 dateRangeFilter"style="display:none;">
                                <label><small><strong>End Date</strong></small> &nbsp;</label>
                                <input class="form-control form-control-sm" type="date" name="end_date" id="end_date"value="<?php if (isset($_POST['end_date'])) {
                                                                                                                    echo htmlentities($_POST['end_date']);
                                                                                                                } ?>">
                            </div>
                            <div class="form-group mb-2 mr-2">
                                <label><small><strong>Venue</strong></small> &nbsp;</label>
                                <select class="form-control form-control-sm" name="venue">
                                    <option value="0" selected>No selection</option>
                                    <?php
                                    $fetchVenues = $db->query("SELECT * FROM `venues` ORDER BY name ASC");

                                    $row_donor = $fetchVenues->fetchAll(PDO::FETCH_OBJ);

                                    foreach ($row_donor as $row) {
                                        if (htmlentities($_POST['venue']) == $row->id) {
                                    ?>
                                            <option value="<?php echo $row->id ?>" selected> <?php echo $row->name ?> </option>
                                        <?php
                                        } else {
                                        ?>
                                            <option value="<?php echo $row->id ?>"> <?php echo $row->name ?> </option>
                                    <?php
                                        }
                                    }

                                    ?>
                                </select>
                            </div>

                            <div class="form-group mb-2 mr-2" >
                                <label><small><strong>Program</strong></small> &nbsp;</label>
                                <select class="form-control form-control-sm" name="program">
                                    <option value="0" selected>No selection</option>
                                    <?php
                                    $fetchPrograms = $db->query("SELECT * FROM `program` ORDER BY name ASC");

                                    $row_donor = $fetchPrograms->fetchAll(PDO::FETCH_OBJ);

                                    foreach ($row_donor as $row) {
                                        if (htmlentities($_POST['program']) == $row->id) {
                                    ?>
                                            <option value="<?php echo $row->id ?>" selected> <?php echo $row->name ?> </option>
                                        <?php
                                        } else {
                                        ?>
                                            <option value="<?php echo $row->id ?>"> <?php echo $row->name ?> </option>
                                    <?php
                                        }
                                    }
                                    ?>

                                </select>
                            </div>
                            <div class="form-group mb-2 mr-2" >
                                <button type="submit" name='submit' class="btn btn-success btn-icon-split btn-sm keychainify-checked">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-plus"></i>
                                    </span>
                                    <span class="text">GENERATE</span>
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">

                        <table class="table table-bordered table-hovered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Activity</th>
                                    <th>Schedule Date</th>
                                    <th>Program</th>
                                    <th>Venue</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                if (isset($_POST['submit'])) {
                                    $filterStart = $_POST['start_date'];
                                    $filterEnd = $_POST['end_date'];
                                    $filterVenue = $_POST['venue'];
                                    $filterProgram = $_POST['program'];
                                    $filterMonthYear = $_POST['month_year'];
                                    $filterType = $_POST['filter_type'];
                                    $explodeYear = "";
                                    $explodeMonth = "";

                                    if($filterType == "1" && $filterMonthYear != ""){                   
                                        $explodeYear = explode("-",$filterMonthYear)[0];
                                        $explodeMonth = explode("-",$filterMonthYear)[1];
                                    }

                                    $str_query = "SELECT `schedules`.`reservationID` AS 'RESERVATION_ID',
                                                        `schedules`.id AS 'INT_RES_ID',
                                                        `venues`.name AS 'VENUE_NAME',
                                                        `schedules`.name AS 'ACTIVITY',
                                                        `program`.name AS 'PROGRAM_NAME',
                                                        `schedules`.date_start AS 'START_DATE',
                                                        SUBSTRING(`schedules`.date_start,1,4) as 'START_DATE_YEAR',
                                                        SUBSTRING(`schedules`.date_start,6,2) as 'START_DATE_MONTH',
                                                        `schedules`.date_end AS 'END_DATE',
                                                        SUBSTRING(`schedules`.date_end,1,4) as 'END_DATE_YEAR',
                                                        SUBSTRING(`schedules`.date_end,6,2) as 'END_DATE_MONTH' 
                                                    FROM `schedules` 
                                                    INNER JOIN `venues` 
                                                    ON `schedules`.venueID = `venues`.id
                                                    INNER JOIN `program` 
                                                    ON `schedules`.programID = `program`.id 
                                                    WHERE `status` = 'A' ";

                                    if($filterType == "2"){
                                        $str_query .= "AND (`schedules`.date_start BETWEEN '$filterStart' AND '$filterEnd') ";
                                    }
                                    // WHERE ((@START_DATE_YEAR = '$explodeYear' AND @START_DATE_MONTH = '$explodeMonth') OR
                                    // (@END_DATE_YEAR = '$explodeYear' AND @END_DATE_MONTH = '$explodeMonth'))
                                    // (`schedules`.date_start BETWEEN '$filterStart' AND '$filterEnd' AND `status` = 'A')

                                    if ($filterVenue != "0") {
                                        $str_query .= "AND (`schedules`.venueID = '$filterVenue') ";
                                    }

                                    if ($filterProgram != "0") {
                                        $str_query .= "AND (`schedules`.programID = '$filterProgram')";
                                    }

                                    $donor = $db->query($str_query);
                                    $row_donor = $donor->fetchAll(PDO::FETCH_OBJ);
                                    
                                    foreach ($row_donor as $row) {
                                        $flag = true;
                                        if($filterType == "1"){
                                            if(($row->START_DATE_YEAR != $explodeYear || $row->START_DATE_MONTH != $explodeMonth) || 
                                                ($row->END_DATE_YEAR != $explodeYear || $row->END_DATE_MONTH != $explodeMonth)){
                                                $flag = false;
                                            }
                                        }
                                        if($flag){
                                     ?>
                                        <tr>
                                            <td><?= '<a href="view_reservation.php?reservation_id=' . $row->INT_RES_ID . '" target="_blank">' . $row->ACTIVITY . '</a>'; ?></td>
                                            <td><?= $row->START_DATE . "-" . $row->END_DATE; ?></td>
                                            <td><?= $row->PROGRAM_NAME; ?></td>
                                            <td><?= $row->VENUE_NAME; ?></td>
                                        </tr>
                                  <?php }}

                                   } ?>

                            </tbody>
                        </table>
                    </div>
                </div>
        </div>
    </div>
</div>
    <script>    
        $(document).ready(function() {
            var filterTypeOnLoad = $("#filterType").val();
            toggleFilter(filterTypeOnLoad);

            $("#filterType").on("change", function(){
                var filterType = $(this).val();
                toggleFilter(filterType);
            });

            function toggleFilter(filterType){
                if(filterType == "1"){
                    $(".dateRangeFilter").css({
                        display : "none",
                    });
                    $(".monthYearFilter").css({
                        display : "flex",
                    });
                    $("#start_date").val("");
                    $("#end_date").val("");
                }else{
                    $(".monthYearFilter").css({
                        display : "none",
                    });
                    $(".dateRangeFilter").css({
                        display : "flex",
                    });
                    $("#month_year").val("");
                }
            }
        });
    </script>
    <?php include 'settings/footer.php'; ?>