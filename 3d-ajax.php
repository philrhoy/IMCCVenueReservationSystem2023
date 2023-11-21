<?php
 function get_brightness($hex) 
 {
 
   $hex = str_replace('#', '', $hex);
  
   $c_r = hexdec(substr($hex, 0, 2));
   $c_g = hexdec(substr($hex, 2, 2));
   $c_b = hexdec(substr($hex, 4, 2));
  
   return (($c_r * 299) + ($c_g * 587) + ($c_b * 114)) / 1000;
 }
// (A) INVALID AJAX REQUEST
if (!isset($_POST['req'])) {
  die("INVALID REQUEST");
}
require "vendor/2-cal-core.php";
switch ($_POST['req']) {
    // (B) DRAW CALENDAR FOR MONTH
  case "draw":
    // (B1) DATE RANGE CALCULATIONS
    // NUMBER OF DAYS IN MONTH
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $_POST['month'], $_POST['year']);
    // FIRST & LAST DAY OF MONTH
    $dateFirst = "{$_POST['year']}-{$_POST['month']}-01";
    $dateLast = "{$_POST['year']}-{$_POST['month']}-{$daysInMonth}";
    // DAY OF WEEK - NOTE 0 IS SUNDAY
    $dayFirst = (new DateTime($dateFirst))->format("w");
    $dayLast = (new DateTime($dateLast))->format("w");

    // (B2) DAY NAMES
    $sunFirst = false; // CHANGE THIS IF YOU WANT MON FIRST
    $days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
    if ($sunFirst) {
      array_unshift($days, "Sunday");
    } else {
      $days[] = "Sunday";
    }
    foreach ($days as $d) {
      echo "<div class='calsq head'>$d</div>";
    }
    unset($days);

    // (B3) PAD EMPTY SQUARES BEFORE FIRST DAY OF MONTH
    if ($sunFirst) {
      $pad = $dayFirst;
    } else {
      $pad = $dayFirst == 0 ? 6 : $dayFirst - 1;
    }
    for ($i = 0; $i < $pad; $i++) {
      echo "<div class='calsq blank'></div>";
    }

    // (B4) DRAW DAYS IN MONTH
    $events = $CAL->get($_POST['month'], $_POST['year'], $_POST['venue']);
    $nowMonth = date("n");
    $nowYear = date("Y");
    $nowDay = ($nowMonth == $_POST['month'] && $nowYear == $_POST['year']) ? date("j") : 0;
    for ($day = 1; $day <= $daysInMonth; $day++) { ?>
      <div class="calsq day<?= $day == $nowDay ? " today" : "" ?>" data-day="<?= $day ?>">
        <div class="calnum"><?= $day ?></div>
        <?php if (isset($events['d'][$day])) {
          foreach ($events['d'][$day] as $eid) { 
            $programID = $events['e'][$eid]['programID'];
            $fetchProgram = $CAL->getProgram($programID);
            $tagColor = ($fetchProgram != null ? $fetchProgram["color"] : "fffff");
            ?>
            <div class="calevt" data-eid="<?= $eid ?>" style="border: 1px solid #EEE;padding: 5px;color: <?= get_brightness($tagColor) > 130 ? 'black': 'white';  ?>;background:<?= $tagColor ?>">
              <?= $events['e'][$eid]['reservationID'] . ' - ' . $events['e'][$eid]['description'] . ' (' . $events['e'][$eid]['name'] . ')'?>
              <!--<span class="tooltipText"><?= $events['e'][$eid]['description'] ?></span>-->
            </div>
        <?php if ($day == $events['e'][$eid]['first']) {
              echo "<div id='evt$eid' class='calninja'>" . json_encode($events['e'][$eid]) . "</div>";
            }
          }
        } ?>
      </div>
<?php }

    // (B5) PAD EMPTY SQUARES AFTER LAST DAY OF MONTH
    if ($sunFirst) {
      $pad = $dayLast == 0 ? 6 : 6 - $dayLast;
    } else {
      $pad = $dayLast == 0 ? 0 : 7 - $dayLast;
    }
    for ($i = 0; $i < $pad; $i++) {
      echo "<div class='calsq blank'></div>";
    }
    break;

    // (C) SAVE EVENT
  case "save":
    echo $CAL->save(
      $_POST['name'],
      $_POST['start'],
      $_POST['end'],
      $_POST['txt'],
      $_POST['reservationID'],
      $_POST['RID'],
      $_POST['venueID'],
      isset($_POST['eid']) ? $_POST['eid'] : null
    ) ? "OK" : $CAL->error;
    break;

    // (D) DELETE EVENT
  case "del":
    echo $CAL->del($_POST['eid'])  ? "OK" : $CAL->error;
    break;

   
}

