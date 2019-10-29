<?php
require_once ('db.php');
require_once ('functions.php');
session_start();
page_protect($conn, '1', $_SESSION['user_company']);

$con = new mysqli($servername, $username, $password, $database);
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
} 
$con->set_charset("utf8");

// Set variables
$contractorIndicator = false;
$nameIndicator = false;
$codeIndicator = false;
$streetIndicator = false;
$houseIndicator = false;
$cityIndicator = false;
$countryIndicator = false;
unset($_SESSION['info']); // better unset

// set SESSION variables
$_SESSION['code'] = $con->real_escape_string($_POST['code']);
$_SESSION['vatCode'] = $con->real_escape_string($_POST['vatCode']);
$_SESSION['name'] = $con->real_escape_string($_POST['name']);
$_SESSION['street'] = $con->real_escape_string($_POST['street']);
$_SESSION['house'] = $con->real_escape_string($_POST['house']);
$_SESSION['flat'] = $con->real_escape_string($_POST['flat']);
$_SESSION['postalCode'] = $con->real_escape_string($_POST['postalCode']);
$_SESSION['city'] = $con->real_escape_string($_POST['city']);
$_SESSION['district'] = $con->real_escape_string($_POST['district']);
$_SESSION['country'] = $con->real_escape_string($_POST['country']);
$_SESSION['isLegal'] = (int) $_POST['isLegal'];
$_SESSION['isLT'] = (int) $_POST['isLT'];
$_SESSION['isEU'] = (int) $_POST['isEU'];

// check mandatory fields and set message to the user
if (empty($_SESSION['name'])) $_SESSION['info'] = "Nurodykite pavadinimą<br>"; else $nameIndicator = true;
if (empty($_SESSION['code'])) $_SESSION['info'] .= "Nurodykite įmonės kodą<br>"; else $codeIndicator = true;
if (empty($_SESSION['street'])) $_SESSION['info'] .= "Nurodykite įmonės gatvę<br>"; else $streetIndicator = true;
if (empty($_SESSION['house'])) $_SESSION['info'] .= "Nurodykite įmonės namo numerį<br>"; else $houseIndicator = true;
if (empty($_SESSION['city'])) $_SESSION['info'] .= "Nurodykite įmonės miestą<br>"; else $cityIndicator = true;
if (empty($_SESSION['country'])) $_SESSION['info'] .= "Nurodykite įmonės šalį<br>"; else $countryIndicator = true;

// check before update
    if ($nameIndicator == true && $codeIndicator == true && $streetIndicator == true && $houseIndicator == true && $cityIndicator == true && $countryIndicator == true) {
        $updateContractor = "UPDATE classifier_contractor SET Code='{$_SESSION['code']}', VATCode='{$_SESSION['vatCode']}', Name='{$_SESSION['name']}', Street='{$_SESSION['street']}', House='{$_SESSION['house']}', Flat='{$_SESSION['flat']}', PostalCode='{$_SESSION['postalCode']}', City='{$_SESSION['city']}', District='{$_SESSION['district']}', Country='{$_SESSION['country']}', IsLegal='{$_SESSION['isLegal']}', IsLT='{$_SESSION['isLT']}', IsEU='{$_SESSION['isEU']}' WHERE ID='{$_SESSION['cid']}' AND CompanyCode='{$_SESSION['user_company']}'";
	
	if ($con->query($updateContractor) === true) $contractorIndicator = true;
	
	// display success or error message on save
	if ($contractorIndicator === true) {
		$_SESSION['info'] = "Klientas nr. " . $_SESSION['cid'] . " sėkmingai atnaujintas";
	} else {
		$_SESSION['info'] = "Klaida! " . $con->error;
	}
}
$con->close();

header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
?>