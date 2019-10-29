<?php
require_once ('db.php');
require_once ('functions.php');
session_start();

page_protect($conn, '1', $_SESSION['user_company']);

$nameIndicator = false;
$surnameIndicator = false;
$oldIndicator = false;
$newIndicator = false;
$new2Indicator = false;
$compareOld = false;
$compareNew = false;
unset($_SESSION['info']);

$_SESSION['name'] = $_POST['first_name'];
$_SESSION['surname'] = $_POST['last_name'];
$_SESSION['pwd_old'] = $_POST['pwd_old'];
$_SESSION['pwd_new'] = $_POST['pwd_new'];

if (empty($_POST['first_name'])) $_SESSION['info'] = "Vardas turi būti nurodytas<br>";
else $nameIndicator = true; 

if (empty($_POST['last_name'])) $_SESSION['info'] .= "Pavardė turi būti nurodyta<br>";
else $surnameIndicator = true; 

if (empty($_POST['pwd_old'])) $_SESSION['info'] .= "Nurodykite seną slaptažodį<br>";
else $oldIndicator = true; 

if (empty($_POST['pwd_new'])) $_SESSION['info'] .= "Nurodykite naują slaptažodį<br>";
else $newIndicator = true;

if (empty($_POST['pwd_new2'])) $_SESSION['info'] .= "Patvirtinkite naują slaptažodį<br>";
else $new2Indicator = true;

$oldPasswords = $conn->query("SELECT Password FROM classifier_user WHERE UserID='{$_SESSION[user_id]}' AND CompanyCode='{$_SESSION['user_company']}'");
$oldPassword = $oldPasswords->fetch_assoc();
if ($oldPassword['Password'] == md5($_POST['pwd_old'])) $compareOld = true;
else $_SESSION['info'] .= "Neteisingas senas slaptažodis<br>";

if($_POST['pwd_new'] == $_POST['pwd_new2']) $compareNew = true;
else $_SESSION['info'] .= "Naujas slaptažodis nesutampa su patvirtinimo slaptažodžiu<br>";

if ($nameIndicator == true && $surnameIndicator == true && $oldIndicator == true && $newIndicator == true && $new2Indicator == true && $compareOld == true && $compareNew == true) {
	$newPwd = md5($_SESSION['pwd_new']);
	$name = $conn->escape_string($_SESSION['name']);
	$surname = $conn->escape_string($_SESSION['surname']);
	
	if($conn->query("UPDATE classifier_user SET First_name='$name', Last_name='$surname', Password = '$newPwd' WHERE UserID='{$_SESSION[user_id]}' AND CompanyCode='{$_SESSION['user_company']}'") == true) $_SESSION['info'] = 'Duomenys sėkmingai pakeisti';
	
	unset($_SESSION['pwd_old']);
	unset($_SESSION['pwd_new']);

}
	
header("Location: " . $_SERVER['HTTP_REFERER']);
?>