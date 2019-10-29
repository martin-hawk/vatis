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
$contractorIndicator = false;
$counterIndicator    = false;
$nameIndicator       = false;
$codeIndicator       = false;
$streetIndicator     = false;
$houseIndicator      = false;
$cityIndicator       = false;
$countryIndicator    = false;
unset($_SESSION['info']); // better unset

// set SESSION variables
$_SESSION['group'] = $_POST['group'];
$resultIDs         = $conn->query("SELECT * FROM classifier_contractor_group WHERE CompanyCode='" . $_SESSION['user_company'] . "' AND GroupID='" . $_SESSION['group'] . "'");
$resultID          = $resultIDs->fetch_assoc();
if ($resultID['CurrentNumber'] === null) {
    $ID = $resultID['RangeFrom'];
} else {
    $ID = $resultID['CurrentNumber'] + 1;
}

$_SESSION['code']       = $con->real_escape_string($_POST['code']);
$_SESSION['vatCode']    = $con->real_escape_string($_POST['vatCode']);
$_SESSION['name']       = $con->real_escape_string($_POST['name']);
$_SESSION['street']     = $con->real_escape_string($_POST['street']);
$_SESSION['house']      = $con->real_escape_string($_POST['house']);
$_SESSION['flat']       = $con->real_escape_string($_POST['flat']);
$_SESSION['postalCode'] = $con->real_escape_string($_POST['postalCode']);
$_SESSION['city']       = $con->real_escape_string($_POST['city']);
$_SESSION['district']   = $con->real_escape_string($_POST['district']);
$_SESSION['country']    = $con->real_escape_string($_POST['country']);
$_SESSION['isLegal']    = (int) $_POST['isLegal'];
$_SESSION['isLT']       = (int) $_POST['isLT'];
$_SESSION['isEU']       = (int) $_POST['isEU'];

// check mandatory fields and set message to the user
if (empty($_SESSION['name'])) {
    $_SESSION['info'] = "Nurodykite pavadinimą<br>";
} else {
    $nameIndicator = true;
}

if (empty($_SESSION['code'])) {
    $_SESSION['info'] .= "Nurodykite įmonės kodą<br>";
} else {
    $codeIndicator = true;
}

if (empty($_SESSION['street'])) {
    $_SESSION['info'] .= "Nurodykite įmonės gatvę<br>";
} else {
    $streetIndicator = true;
}

if (empty($_SESSION['house'])) {
    $_SESSION['info'] .= "Nurodykite įmonės namo numerį<br>";
} else {
    $houseIndicator = true;
}

if (empty($_SESSION['city'])) {
    $_SESSION['info'] .= "Nurodykite įmonės miestą<br>";
} else {
    $cityIndicator = true;
}

if (empty($_SESSION['country'])) {
    $_SESSION['info'] .= "Nurodykite įmonės šalį<br>";
} else {
    $countryIndicator = true;
}

// check before insert
if ($nameIndicator == true && $codeIndicator == true && $streetIndicator == true && $houseIndicator == true && $cityIndicator == true && $countryIndicator == true) {
    $insertContractor = "INSERT INTO `classifier_contractor` (`CompanyCode`, `ID`, `Code`, `VATCode`, `Name`, `Street`, `House`, `Flat`, `PostalCode`, `City`, `District`, `Country`, `IsLegal`, `IsLT`, `IsEU`, `ContractorsGroup`)
        VALUES ('{$_SESSION['user_company']}', '$ID', '{$_SESSION['code']}', '{$_SESSION['vatCode']}', '{$_SESSION['name']}', '{$_SESSION['street']}', '{$_SESSION['house']}', '{$_SESSION['flat']}', '{$_SESSION['postalCode']}', '{$_SESSION['city']}', '{$_SESSION['district']}', '{$_SESSION['country']}', '{$_SESSION['isLegal']}', '{$_SESSION['isLT']}', '{$_SESSION['isEU']}', '{$_SESSION['group']}')";

    if ($con->query($insertContractor) === true) {
        $contractorIndicator = true;
    }

    if ($contractorIndicator === true) {
        // check if counter ID in range
        $checkCounter  = "SELECT * FROM classifier_contractor_group WHERE CompanyCode='{$_SESSION['user_company']}' AND GroupID='{$_SESSION['group']}'";
        $counterResult = $con->query($checkCounter);
        $counter       = $counterResult->fetch_assoc();

        if ($counter['RangeFrom'] <= $ID && $counter['RangeTo'] >= $ID) {

            // update counter
            $updateCounter = "UPDATE classifier_contractor_group SET CurrentNumber='$ID' WHERE CompanyCode='{$_SESSION['user_company']}' AND GroupID='{$_SESSION['group']}'";
            if ($con->query($updateCounter) === true) {
                $counterIndicator = true;
            }

        }
    } else {
        $_SESSION['info'] = "Klientų numeracijos riba pasiekta";
    }
    // error message

    // display success or error message on save
    if ($contractorIndicator === true && $counterIndicator === true) {
        $_SESSION['info'] = "Klientas nr. " . $ID . " sėkmingai sukurtas";
    } else {
        $_SESSION['info'] = "Klaida! " . $con->error;
    }
}
$con->close();

header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
