<?php 
 if(!isset($_SESSION['id']) OR !isset($_SESSION['user']))
 {
 	header('location: login.php');
 }
