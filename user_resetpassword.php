<?php

include_once 'settings/db.php';
session_start();

if (!$_GET['id'] or empty($_GET['id'])) {
    header('location:users.php');
} else {
    $id = (int)$_GET['id'];

    $get_user = $db->query("SELECT * FROM `users` WHERE id = '$id' ");
    $result_user = $get_user->fetchAll(PDO::FETCH_OBJ);
    foreach ($result_user as $obj) {
        $password = md5($obj->username);
        $uname = $obj->username;
    }

    $query = $db->query("UPDATE `users` SET password = '$password', change_pass = 0, dateUpdated = NOW() WHERE `id` = $id");

    if ($query) {
        if ($_SESSION['user'] == $uname) {
            echo "<script type='text/javascript'> alert('Password resetted successfully.'); ";
            echo "window.location= 'logout.php';";
            echo "</script>";
            //session_destroy();
        } else {
            echo "<script type='text/javascript'> alert('Password resetted successfully.'); ";
            echo "window.location= 'users.php';";
            echo "</script>";
        }
    }
}
