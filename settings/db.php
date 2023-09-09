<?php
try {
	$db = new PDO('mysql:host=localhost;dbname=db_vrs', 'root', '');
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
	die('<h4 style="color:red">Incorrect Connection Details</h4>');
}
?>