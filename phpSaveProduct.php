<?php
require_once 'db.php';
require_once 'functions.php';
session_start();
page_protect($conn, '1', $_SESSION['user_company']);

$con = new mysqli($servername, $username, $password, $database);
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
$con->set_charset("utf8");

// Set variables
$duplicateIndicator     = true;
$productCodeIndicator   = false;
$productNameLTIndicator = false;
$productNameENIndicator = false;
unset($_SESSION['info']); // Better unset

// Set SESSION variables
$_SESSION['productCode']     = $con->real_escape_string($_POST['productCode']);
$_SESSION['nameLT']          = $con->real_escape_string($_POST['descriptionLT']);
$_SESSION['nameEN']          = $con->real_escape_string($_POST['descriptionEN']);
$_SESSION['productType']     = $con->real_escape_string($_POST['productType']);
$_SESSION['uom']             = $con->real_escape_string($_POST['uom']);
$_SESSION['defaultQuantity'] = $con->real_escape_string($_POST['defaultQuantity']);
$_SESSION['defaultPrice']    = $con->real_escape_string($_POST['defaultPrice']);

// Check for duplicates
$productDuplicate = $con->query("SELECT * FROM classifier_product WHERE CompanyCode='{$_SESSION['user_company']}' AND ProductCode='{$_SESSION['productCode']}'");
if ($productDuplicate->num_rows > 0) {
	$_SESSION['info'] = "Toks produktas jau egzituoja";
} else $duplicateIndicator = false;

// Check mandatory fields and set message to the user
if (empty($_SESSION['productCode'])) {
    $_SESSION['info'] .= "Nurodykite produkto kodą<br>";
} else {
    $productCodeIndicator = true;
}

if (empty($_SESSION['nameLT'])) {
    $_SESSION['info'] .= "Nurodykite produkto pavadinimą (lietuvių kalba)<br>";
} else {
    $productNameLTIndicator = true;
}

if (empty($_SESSION['nameEN'])) {
    $_SESSION['info'] .= "Nurodykite produkto pavadinimą (anglų kalba)<br>";
} else {
    $productNameENIndicator = true;
}

// Check before insert
if ($duplicateIndicator == false && $productCodeIndicator == true && $productNameLTIndicator == true && $productNameENIndicator == true) {
    $insertProduct = "INSERT INTO classifier_product (CompanyCode, ProductCode, Description, ForeignDescription, GoodsServicesID, UnitOfMeasure, DefaultQuantity, DefaultPrice)
        VALUES ('{$_SESSION['user_company']}', '{$_SESSION['productCode']}', '{$_SESSION['nameLT']}', '{$_SESSION['nameEN']}', '{$_SESSION['productType']}', '{$_SESSION['uom']}', '{$_SESSION['defaultQuantity']}', '{$_SESSION['defaultPrice']}')";

    if ($con->query($insertProduct) === true) {
        $_SESSION['info'] = "Produktas nr. " . $_SESSION['productCode'] . " sėkmingai sukurtas";
    } else {
        $_SESSION['info'] = "Klaida! " . $con->error;
    }
}

$con->close();
header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
