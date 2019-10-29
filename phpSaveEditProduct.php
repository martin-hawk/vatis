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
$productCodeIndicator   = false;
$productNameLTIndicator = false;
$productNameENIndicator = false;
unset($_SESSION['info']); // Better unset

// Set SESSION variables
$_SESSION['nameLT']          = $con->real_escape_string($_POST['descriptionLT']);
$_SESSION['nameEN']          = $con->real_escape_string($_POST['descriptionEN']);
$_SESSION['productType']     = $con->real_escape_string($_POST['productType']);
$_SESSION['uom']             = $con->real_escape_string($_POST['uom']);
$_SESSION['defaultQuantity'] = $con->real_escape_string($_POST['defaultQuantity']);
$_SESSION['defaultPrice']    = $con->real_escape_string($_POST['defaultPrice']);

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

// Check before update
if ($productCodeIndicator == true && $productNameLTIndicator == true && $productNameENIndicator == true) {
    $updateProduct = "UPDATE classifier_product SET Description='{$_SESSION['nameLT']}', ForeignDescription='{$_SESSION['nameEN']}', GoodsServicesID='{$_SESSION['productType']}', UnitOfMeasure='{$_SESSION['uom']}', DefaultQuantity='{$_SESSION['defaultQuantity']}', DefaultPrice='{$_SESSION['defaultPrice']}' WHERE CompanyCode='{$_SESSION['user_company']}' AND ProductCode='{$_SESSION['productCode']}'";

    if ($con->query($updateProduct) === true) {
        $_SESSION['info'] = "Produktas <strong>" . $_SESSION['productCode'] . "</strong> sėkmingai atnaujintas";
    } else {
        $_SESSION['info'] = "Klaida! " . $con->error;
    }
}

$con->close();
header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
?>
