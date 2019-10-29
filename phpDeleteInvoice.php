<?php
require_once 'db.php';
require_once 'functions.php';
session_start();
page_protect($conn, '1', $_SESSION['user_company']);
print_r($_GET);
print_r($_SESSION);
$headerIndicator = false;
$linesIndicator  = false;

if ($_GET['idate'] == date("Y-m-d")) {

    $deleteHeader = "DELETE FROM document_sales_header WHERE CompanyCode = '{$_SESSION['user_company']}' AND SystemID = '{$_GET['sid']}'";

    if ($conn->query($deleteHeader) == true) {
        $headerIndicator = true;
    }

    if ($headerIndicator == true) {
        $deleteLines = "DELETE FROM document_sales_items WHERE CompanyCode = '{$_SESSION['user_company']}' AND SystemID = '{$_GET['sid']}'";

        if ($conn->query($deleteLines) == true) {
            $linesIndicator = true;
        }
    }
} else {
    $_SESSION['info'] = 'Galima trinti tik ' . date("Y-m-d") . ' PVM sąskaitas!';
}

if ($headerIndicator == true && $linesIndicator == true) {
    $_SESSION['info'] = 'PVM sąskaita faktūra Nr. ' . $_GET['inv'] . ' sėkmingai ištrinta';
}

header("Location: " . $_SERVER['HTTP_REFERER']);
?>
