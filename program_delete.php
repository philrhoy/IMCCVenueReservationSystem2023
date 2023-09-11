<?php

include_once 'settings/db.php';

if (!$_GET['id'] or empty($_GET['id'])) {
    header('location:programs.php');
} else {
    $id = (int)$_GET['id'];
    $query = $db->query("DELETE FROM `program` WHERE `id` = $id");

    if ($query) {
        echo "<script type='text/javascript'>
        alert('Program successfully deleted.'); ";
        echo "window.location= 'programs.php';";
        echo "</script>";
    }
}
