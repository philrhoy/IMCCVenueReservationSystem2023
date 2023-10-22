<?php
class Calendar
{
  // (A) CONSTRUCTOR - CONNECT TO DATABASE
  private $pdo = null;
  private $stmt = null;
  private $sequence = null;
  public $error = "";
  function __construct()
  {
    try {
      $this->pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
        DB_USER,
        DB_PASSWORD,
        [
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
      );
    } catch (Exception $ex) {
      die($ex->getMessage());
    }
  }

  // (B) DESTRUCTOR - CLOSE DATABASE CONNECTION
  function __destruct()
  {
    if ($this->stmt !== null) {
      $this->stmt = null;
    }
    if ($this->pdo !== null) {
      $this->pdo = null;
    }
  }

  // (C) SAVE EVENT
  function save($name, $start, $end, $txt, $reservationID, $RID, $venueID, $id = null)
  {
    // (C1) START & END DATE QUICK CHECK 
    $uStart = strtotime($start);
    $uEnd = strtotime($end);
    if ($uEnd < $uStart) {
      $this->error = "End date cannot be earlier than start date";
      return false;
    }

    // (C2) SQL - INSERT OR UPDATE
    if ($id == null) {
      $this->sequence = $this->pdo->prepare("SELECT * FROM `number_sequence` WHERE `page_name` = ?");
      $this->sequence->execute(['reservations']);
      while ($data = $this->sequence->fetch()) {
        $newID = $data['last_number'] + 1;
        $lengthID = strlen((string)$newID);
        if ($lengthID == 1) $ID = "00000" . $newID;
        elseif ($lengthID == 2) $ID = "0000" . $newID;
        elseif ($lengthID == 3) $ID = "000" . $newID;
        elseif ($lengthID == 4) $ID = "00" . $newID;
        elseif ($lengthID == 5) $ID = "0" . $newID;
        else $ID = $newID;
      }
      $ResID = 'RES' . $ID;
      $sql = "INSERT INTO `schedules` (`name`, `date_start`, `date_end`, `description`, `reservationID`, `venueID`) VALUES (?,?,?,?,?,?)";
      $data = [$name, $start, $end, $txt, $ResID, $venueID];

      $update_sequence = "UPDATE `number_sequence` SET `last_number`=? WHERE `page_name`='reservations'";
      $data_number = [$newID];
    } else {
      $sql = "UPDATE `schedules` SET `name`=?,`date_start`=?, `date_end`=?, `description`=?, `venueID`=? WHERE `id`=?";
      $data = [$name, $start, $end, $txt, $venueID, $id];
    }


    // (C3) EXECUTE
    try {
      $this->stmt = $this->pdo->prepare($sql);
      $this->stmt->execute($data);
      if ($id == null) {
        $this->stmt = $this->pdo->prepare($update_sequence);
        $this->stmt->execute($data_number);
      }
    } catch (Exception $ex) {
      $this->error = $ex->getMessage();
      return false;
    }
    return true;
  }

  // (D) DELETE EVENT
  function del($id)
  {
    try {
      $this->stmt = $this->pdo->prepare("DELETE FROM `schedules` WHERE `id`=?");
      $this->stmt->execute([$id]);
    } catch (Exception $ex) {
      $this->error = $ex->getMessage();
      return false;
    }
    return true;
  }

  // (E) GET EVENTS FOR SELECTED MONTH
  function get($month, $year, $venue)
  {
    // (E1) FIST & LAST DAY OF MONTH
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    $dayFirst = "{$year}-{$month}-01";
    $dayLast = "{$year}-{$month}-{$daysInMonth}";
    if ($venue != 0) {
      $data = "`venueID` = $venue AND";
    } else {
      $data = '';
    }

    // (E2) GET EVENTS
    $this->stmt = $this->pdo->prepare("SELECT * FROM `schedules`
    WHERE $data `date_start` BETWEEN ? AND ?
    AND `date_end` BETWEEN ? AND ?
    AND cancelled = 0
    AND deleted = 0 ");
    $this->stmt->execute([$dayFirst, $dayLast, $dayFirst, $dayLast]);

    $events = ["e" => [], "d" => []];
    while ($row = $this->stmt->fetch()) {
      $eStartMonth = substr($row['date_start'], 5, 2);
      $eEndMonth = substr($row['date_end'], 5, 2);
      $eStartDay = $eStartMonth == $month
        ? (int)substr($row['date_start'], 8, 2)
        : 1;
      $eEndDay = $eEndMonth == $month
        ? (int)substr($row['date_end'], 8, 2)
        : $daysInMonth;
      for ($d = $eStartDay; $d <= $eEndDay; $d++) {
        if (!isset($events['d'][$d])) {
          $events['d'][$d] = [];
        }
        $events['d'][$d][] = $row['id'];
      }
      $events['e'][$row['id']] = $row;
      $events['e'][$row['id']]['first'] = $eStartDay;
    }
    return $events;
  }

  function getProgram($programID)
  {
    $returnProgram = null;
    try{
      $this->stmt = $this->pdo->prepare('SELECT * FROM program WHERE id = ?');
      $this->stmt->execute([$programID]);
      while($row = $this->stmt->fetch()) { $returnProgram = $row; }
    }catch(Exception $ex){
      $this->error = $ex->getMessage();
      echo $this->error;
    }

    return $returnProgram;
  }
}

// (F) DATABASE SETTINGS - CHANGE TO YOUR OWN!
define('DB_HOST', 'localhost');
define('DB_NAME', 'db_vrs');
define('DB_CHARSET', 'utf8');
define('DB_USER', 'root');
define('DB_PASSWORD', '');

// (G) NEW CALENDAR OBJECT
$CAL = new Calendar();
