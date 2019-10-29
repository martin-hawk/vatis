<?php
require_once ('db.php');
require_once ('functions.php');
session_start();
page_protect($conn, '1', $_SESSION['user_company']);

$userIndicator = false;
$passwordIndicator = false;
unset($_SESSION['info']);

$_SESSION['un'] = $_POST['username'];

if (empty($_POST['username'])) $_SESSION['info'] = "Nenurodytas naudotojo vardas<br>";
else $userIndicator = true;
if (empty($_POST['password'])) $_SESSION['info'] .= "Nenurodytas slaptažodis<br>";
else $passwordIndicator = true;

if ($userIndicator == true && $passwordIndicator == true) {
	//Use the input username and password and check against 'user' table
    $auth_user = ($conn->query('SELECT * FROM classifier_user WHERE UserID = "' . $conn->real_escape_string($_POST['username']) . '" AND Password = "' . $conn->real_escape_string(md5($_POST['password'])) . '" AND CompanyCode ="' . $_POST['company'] . '"'));
	if ($auth_user->num_rows == 1) {
		$row = $auth_user->fetch_assoc();
		if ($row['Active'] == 1) {
			$_SESSION['user_id'] = $row['UserID'];
            $_SESSION['logged_in'] = true;
            $_SESSION['level_access'] = $row['Level_access'];
            $_SESSION['f_name'] = $row['First_name'];
            $_SESSION['l_name'] = $row['Last_name'];
			$_SESSION['user_company'] = $row['CompanyCode'];
			$auth_user->close();
            if ($_SESSION['level_access'] == 1) {
				unset($_SESSION['un']);
				header("Location: main.php");
				exit;
			}
	} else {
		$_SESSION['info'] .= 'Neteisingas slaptažodis<br>'; 
		header("Location: index.php");
		exit;
	}
}
}
		
header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
?>