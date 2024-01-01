<?php
include 'settings/system.php';
include 'session.php';
include 'settings/header.php';
include "settings/sidebar.php";
include 'settings/topbar.php';
?>

<div class="container-fluid">
    <!-- (B) PERIOD SELECTOR -->
    <br />
    <div id="calPeriod">
        <div class="form-group">
            <div class="row">
                <div class="col-md-2">
                    <select name="calvenue" style='height: 50px !important; font-size: 1.5rem !important;' id="calvenue" class="form-control" title="Selected Venue will filter the display to the corresponding reservation." required>
                        <option value="0">All Venues</option>
                        <?php
                        $getVenues = $db->query("SELECT * FROM venues ORDER BY name ASC");
                        $res = $getVenues->fetchAll(PDO::FETCH_OBJ);
                        foreach ($res as $v) { ?>
                            <option value="<?php echo $v->id; ?>" ?><?php echo $v->name; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <?php
                    // (B1) MONTH SELECTOR
                    // NOTE: DEFAULT TO CURRENT SERVER MONTH YEAR
                    $months = [
                        1 => "January", 2 => "Febuary", 3 => "March", 4 => "April",
                        5 => "May", 6 => "June", 7 => "July", 8 => "August",
                        9 => "September", 10 => "October", 11 => "November", 12 => "December"
                    ];
                    $monthNow = date("m");
                    echo "<select id='calmonth' style='height: 50px !important; font-size: 1.5rem !important;' class='form-control' title='Select a month. (Default: Current Month)'>";
                    foreach ($months as $m => $mth) {
                        printf(
                            "<option value='%s'%s>%s</option>",
                            $m,
                            $m == $monthNow ? " selected" : "",
                            $mth
                        );
                    }
                    echo "</select>";
                    ?>
                </div>
                <div class="col-md-2">
                    <?php
                    // (B2) YEAR SELECTOR
                    echo "<input type='number' min='0' style='height: 50px !important; font-size: 1.5rem !important;' id='calyear' title='Input a year. (Default: Current Year)' class='form-control form-sm' value='" . date("Y") . "'/>";
                    ?>
                </div>
                <div class="col-md-4">
                    <button class="btn btn-primary " style='height: 50px !important; font-size: 1.5rem !important;' id='clear' title='Clear all the filters and set it back to default.' name='clear'>Clear </button>
                </div>
            </div>
        </div>
    </div>
    <br />
</div>
<!-- (C) CALENDAR WRAPPER -->
<div id="calwrap"></div>

<!-- (D) EVENT FORM -->
<div id="calblock">
    <div class="col-lg-18">
        <form id="calform">
            <input type="hidden" id="evtid" />
            <div class="form-group">
                <div class="row">
                    <div class="col-md-3">
                        <label for="evtstart">Date Start</label>
                        <input type="hidden" id="RID" value="0" class="form-control" required />
                        <input type="hidden" id="reservationID" value="0" class="form-control" required />
                        <input type="date" id="evtstart" class="form-control" readonly />
                    </div>
                    <div class="col-md-3">
                        <label for="evtstart">Time Start</label>
                        <input type="time" id="evtstime" class="form-control" readonly />
                    </div>
                    <div class="col-md-3">
                        <label for="evtend">Date End</label>
                        <input type="date" id="evtend" class="form-control" readonly />
                    </div>
                    <div class="col-md-3">
                        <label for="evtend">Time End</label>
                        <input type="time" id="evtetime" class="form-control" readonly />
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <label for="evtname">Event Name</label>
                        <input type="text" id="evtname" class="form-control" readonly />
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label for="evtvenue">Program</label>
                        <select name="evtvenue" id="evtprogram" class="form-control" disabled readonly>
                            <option value=""></option>
                            <?php
                            $getProgram = $db->query("SELECT * FROM program ORDER BY name ASC");
                            $res = $getProgram->fetchAll(PDO::FETCH_OBJ);
                            foreach ($res as $p) { ?>
                                <option value="<?php echo $p->id; ?>" ?><?php echo $p->name; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="evtvenue">Venue</label>
                        <select name="evtvenue" id="evtvenue" class="form-control" disabled readonly>
                            <option value=""></option>
                            <?php
                            $getVenues = $db->query("SELECT * FROM venues ORDER BY name ASC");
                            $res = $getVenues->fetchAll(PDO::FETCH_OBJ);
                            foreach ($res as $v) { ?>
                                <option value="<?php echo $v->id; ?>" ?><?php echo $v->name; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <!-- <label for="evtcontact">Contact No</label> -->
                                <input type="hidden" id="evtcontact" class="form-control" minlength="11" maxlength="11" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <!-- <label for="evtcolor">Tag Color</label> -->
                                <input type="hidden" id="evtcolor" class="form-control" value="#e4edff" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <label for="evttxt">Status</label>
                        <select name="evtstatus" id="evtstatus" class="form-control" disabled readonly>
                            <option value="A">Approved</option>
                            <option value="R">Rejected</option>
                            <option value="P">Pending for Approval</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <label for="evttxt">Purpose</label>
                        <textarea id="evttxt" class="form-control" rows="5" readonly></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <!-- <button type="submit" id="calformsave" class="btn btn-sn btn-success" value="Save"><i class="fa fa-save"></i> Save</button> 
                <button type="button" id="calformdel" class="btn btn-sn btn-danger w3-right" value="Delete"><i class="fa fa-trash"></i> Delete</button>  -->
                <button type="button" id="calformcx" class="btn btn-sm btn-secondary w3-right" value="Cancel">Close</button>
            </div>
        </form>
    </div>
</div>
</body>
<script>
    const numInputs = document.querySelectorAll('input[type=number]')

    numInputs.forEach(function(input) {
        input.addEventListener('change', function(e) {
            if (e.target.value == '') {
                e.target.value = new Date().getFullYear()
            }
        })
    })

    $("#clear").on("click", function() {
        var month = new Date().getMonth();
        var year = new Date().getFullYear();

        $("#calvenue").select().val(0).trigger("change");
        $("#calmonth").select().val(month + 1).trigger("change");
        $("#calyear").select().val(year).trigger("change");

        window.location.reload();

    });
</script>

<?php include 'settings/footer.php'; ?>