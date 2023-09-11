<?php

include_once 'settings/db.php';

if (!$_GET['id'] or empty($_GET['id'])) {
    header('location:users.php');
} else {
    $id = (int)$_GET['id'];
    $query = $db->query("DELETE FROM `users` WHERE `id` = $id");

    if ($query) {
        echo "<script type='text/javascript'>
        alert('User successfully deleted.'); ";
        echo "window.location= 'users.php';";
        echo "</script>";
    }
}
