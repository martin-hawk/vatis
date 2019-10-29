<?php
require_once ('db.php');
require_once ('functions.php');
session_start();

page_protect($conn, '1', $_SESSION['user_company']);

$_SESSION['isSaved'] = false;
unset($_SESSION['info']);

$_SESSION['creditDate'] = $_POST['creditDate'];
		
header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
?>