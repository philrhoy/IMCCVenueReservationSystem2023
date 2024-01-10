<?php
include 'settings/system.php';
include 'session.php';
include 'settings/header.php';
?>

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
    $material = "";
    $act_form_file = "";
    $act_form_file_ext = "";
    $letter_approve_file = "";
    $letter_approve_file_ext = "";
    $sponsor = "";
    $contribution = "";
    $incharge = "";
    $name = "";

    $sequence = $db->query("SELECT A.*,B.name as VenueName, C.first_name, C.middle_name, C.last_name, C.position FROM schedules A 
    LEFT JOIN venues B ON A.venueID = B.id 
    LEFT JOIN users C ON A.userID = C.id
    WHERE A.id = '$res_id' OR A.reservationID = '$res_id'");
    $fetch = $sequence->fetchAll(PDO::FETCH_OBJ);

    foreach ($fetch as $data) {
        $res_dateadded = $data->date_added;
        $res_id_text = $data->reservationID;
        $activity = $data->name;
        $participants = $data->num_participants;
        $objectives = $data->description;
        $program_id = $data->programID;
        $venue_id = $data->VenueName;
        $start_date = $data->date_start;
        $start_time = $data->time_start;
        $end_date =  $data->date_end;
        $end_time = $data->time_end;
        $notes = $data->notes;
        $act_form_file = $data->act_form_file;
        $letter_approve_file = $data->letter_approve_file;
        $contact = $data->contact;
        $sponsor = $data->sponsor;
        $contribution = $data->contribution;
        $incharge = $data->incharge;
        $soundsystem = $data->sound_system;
        $microphone = $data->microphone;
        $material = $data->others_material;
        $name = $data->first_name . ' ' . $data->middle_name . ' ' . $data->last_name;

        $file_ext = explode(".", $act_form_file);
        $act_form_file_ext = (strtolower(end($file_ext)) == "pdf") ? "application/" . strtolower(end($file_ext)) : "image/" . strtolower(end($file_ext));

        $file_ext = explode(".", $letter_approve_file);
        $letter_approve_file_ext = (strtolower(end($file_ext)) == "pdf") ? "application/" . strtolower(end($file_ext)) : "image/" . strtolower(end($file_ext));

        $position = "";
        switch ($data->position) {
            case "DSA":
                $position = "Department Student Affairs";
                break;
            case "STO":
                $position = "Student Officer";
                break;
            case "PTC":
                $position = "Property Custodian";
                break;
        }
    }
}

?>

<body onLoad="self.print()" style="color:black !important; font-family: Times New Roman !important;background-color:white;">
    <div class="container bootdey">
        <div class="row invoice row-printable">
            <div class="col-md-10">
                <img src="img/head_report.png" width="100%">
                <br />
                <br />
                <table>
                    <tbody>
                        <tr>
                            <td width="80%" style="border: 0px solid white;">SAS FORM 14-A</td>
                            <td width="0%" style="border: 0px solid white;">Date:</td>
                            <td width="30%" style="border-bottom:1px solid black; border-top: 0px solid white;"><?= date('M d, Y H:i a', strtotime($res_dateadded)) ?></td>
                        </tr>
                        <tr>
                            <td style="border: 0px solid white;">Co-Curricular Activities</td>
                        </tr>
                    </tbody>
                </table>
                <br />
                <center>
                    <h4><b>STUDENT ACTIVITY FORM</b></h4>
                </center>
                <br />
                <table class="table">
                    <tbody>
                        <tr style="border: 1px solid white;">
                            <td width="10%" style="border: 0px solid white;">Activity:</td>
                            <td width="90%" colspan="3" style="border-bottom:1px solid black; border-top: 0px solid white;text-transform:uppercase;"><?= $activity ?></td>
                        </tr>
                        <tr>
                            <td width="20%" style="border-bottom: 1px solid black; border-top: 0px solid white;">Number of Participants:</td>
                            <td width="60%" colspan="3" style="border-bottom:1px solid black"><?= $participants ?></td>
                        </tr>
                        <tr style="border: 1px solid black !important;">
                            <td width="20%" style="border-right: 1px solid white;">Objectives:</td>
                            <td colspan="2" height="100" style="text-transform:uppercase;"><?= $objectives ?></td>
                        </tr>
                        <tr>
                            <td width="20%" style="border-top: 1px solid black;">Date of Implementation:</td>
                            <td width="45%" style="border-bottom:1px solid black; border-top: 1px solid black;"><?= date('M d, Y', strtotime($start_date)) . ' - ' . date('M d, Y', strtotime($end_date)) ?></td>
                            <td width="5%" style="border: 0px solid black;">Time:</td>
                            <td width="20%" style="border-bottom:1px solid black; border-top: 1px solid black;"><?= date('H:i a', strtotime($start_time)) . ' - ' . date('H:i a', strtotime($end_time)) ?></td>
                        </tr>
                        <tr>
                            <td width="20%" style="border: 0px solid white;">Venue:</td>
                            <td width="80%" colspan="3" style="border-bottom:1px solid black; border-top: 0px solid white;text-transform:uppercase;"><?= $venue_id ?></td>
                        </tr>
                        <tr>
                            <td width="20%" style="border: 0px solid white;">Organization/Sponsor:</td>
                            <td width="80%" colspan="3" style="border-bottom:1px solid black;text-transform:uppercase;"><?= $sponsor ?></td>
                        </tr>
                        <tr>
                            <td colspan="1" style="width:30% !important;border: 0px solid white;">Amount of Contribution per Student:</td>
                            <td colspan="3" style="border-bottom:1px solid black;text-transform:uppercase;"><?= $contribution ?></td>
                        </tr>
                        <tr>
                            <td width="20%" style="border: 0px solid white;">Person/s in-charge:</td>
                            <td colspan="3" style="border-bottom:1px solid black;text-transform:uppercase;"><?= $incharge ?></td>
                        </tr>
                    </tbody>
                </table>
                <table class="table">
                    <tbody>
                        <tr style="border-top: 2px solid black;border-left: 2px solid black;border-right: 2px solid black;">
                            <td width="10%" style="border: 1px solid white;"><b>Please Reserve the following: </b></td>
                            <td width="10%" colspan="2" style="border: 1px solid white;">Venue: ( <?php if ($venue_id != '') echo '✓'; ?> ) <label style="width:86% ;border-bottom: 1px solid black;text-transform:uppercase;"><?= $venue_id ?></label> </td>

                        </tr>
                        <tr style="border-top: 0px solid white;border-bottom: 0px solid white;border-left: 2px solid black;border-right: 2px solid black;">
                            <td width="10%" style="border: 1px solid white;">( <?php if ($soundsystem != '') echo '✓';
                                                                                else echo "&nbsp;&nbsp;"; ?> )<label style="border-bottom: 1px solid black;text-transform:uppercase;"><?= "&nbsp;&nbsp;&nbsp;&nbsp;" . $soundsystem . "&nbsp;&nbsp;&nbsp;&nbsp;" ?></label> Sound System</td>
                            <td width="25%" colspan="2" style="border: 1px solid white;">( <?php if ($microphone != '') echo '✓';
                                                                                            else echo "&nbsp;&nbsp;"; ?> ) <label style="border-bottom: 1px solid black;text-transform:uppercase;"><?= "&nbsp;&nbsp;&nbsp;&nbsp;" . $microphone . "&nbsp;&nbsp;&nbsp;&nbsp;" ?></label> Microphone</td>

                        </tr>
                        <tr  style="border-top: 0px solid white;border-bottom: 2px solid black;border-left: 2px solid black;border-right: 2px solid black;">
                            <td width="10%" height="50" style="border: 1px solid white;">Others, please specify: </td>
                            <td width="25%"><label style="width:100% !important;border-bottom: 1px solid black;text-transform:uppercase;"><?= $material ?></label></td>
                            
                        </tr>
                    </tbody>
                </table>
                <table class="table">
                    <tbody>
                        <tr>
                            <td width="15%" style="border: 0px solid white;">Submitted By:</td>
                            <td width="30%" style="border-bottom:1px solid black; border-top: 0px solid white;text-align:center;"><?php if ($position == "STO") echo $name; ?></td>
                            <td style="border: 0px solid white;"></td>
                            <td width="10%" style="border: 0px solid white;">Position:</td>
                            <td width="25%" style="border-bottom:1px solid black; border-top: 0px solid white;"><?php if ($position == "STO") echo $position ?></td>
                        </tr>
                        <tr>
                            <td style="border: 0px solid white;"></td>
                            <td style="text-align:center;border: 0px solid white;">Signature over printed Name</td>
                            <td colspan="3" style="border: 0px solid white;"></td>
                        </tr>
                    </tbody>
                </table>
                <table class="table">
                    <tbody>
                        <tr>
                            <td width="15%" style="border: 0px solid white;">Noted By:</td>
                            <td width="30%" style="border-bottom:1px solid black; border-top: 0px solid white;"></td>
                            <td style="border: 0px solid white;"></td>
                            <td width="35%" colspan="2" style="border-bottom:1px solid black; border-top: 0px solid white;"></td>
                        </tr>
                        <tr>
                            <td style="border: 0px solid white;"></td>
                            <td style="text-align:center;border: 0px solid white;">Adviser/Department Head</td>
                            <td style="border: 0px solid white;"></td>
                            <td width="35%" colspan="2" style="text-align:center;border: 0px solid white;">Head, Student Affairs</td>
                        </tr>
                    </tbody>
                </table>
                <table class="table">
                    <tbody>
                        <tr>
                            <td width="15%" style="border: 0px solid white;">Approved By:</td>
                            <td width="30%" style="border-bottom:1px solid black; border-top: 0px solid white;"></td>
                            <td style="border: 0px solid white;"></td>
                            <td width="35%" colspan="2" style="border-bottom:1px solid black; border-top: 0px solid white;"></td>
                        </tr>
                        <tr>
                            <td style="border: 0px solid white;"></td>
                            <td style="text-align:center;border: 0px solid white;">ODAA/ Comptroller</td>
                            <td style="border: 0px solid white;"></td>
                            <td width="35%" colspan="2" style="text-align:center;border: 0px solid white;">President</td>
                        </tr>
                    </tbody>
                </table>
                <table class="table">
                    <tbody>
                        <tr>
                            <td width="15%" style="border: 0px solid white;">Reservation Reviewed By:</td>
                            <td width="30%" style="border-bottom:1px solid black; border-top: 0px solid white;"></td>
                            <td style="border: 0px solid white;"></td>
                            <td width="35%" colspan="2" style="border-bottom:1px solid white; border-top: 0px solid white;"></td>
                        </tr>
                        <tr>
                            <td style="border: 0px solid white;"></td>
                            <td style="text-align:center;border: 0px solid white;">Property Custodian</td>
                            <td style="border: 0px solid white;"></td>
                            <td width="35%" colspan="2" style="text-align:center;border: 0px solid white;"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>