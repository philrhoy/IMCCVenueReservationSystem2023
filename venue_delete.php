<?php

include_once 'settings/db.php';

if (!$_GET['id'] or empty($_GET['id'])) {
    header('location:venues.php');
} else {
    $id = (int)$_GET['id'];
    $query = $db->query("DELETE FROM `venues` WHERE `id` = $id");

    if ($query) {
        echo "<script type='text/javascript'>
        alert('Venue successfully deleted.'); ";
        echo "window.location= 'venues.php';";
        echo "</script>";
    }
}
