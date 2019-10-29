<?php
require_once 'db.php';
require_once 'functions.php';
session_start();
page_protect($conn, '1', $_SESSION['user_company']);

$deleteIndicator = false;
unset($_SESSION['info']);

$getDocuments = $conn->query("SELECT InvoiceSeries, DocumentNo FROM document_sales_header WHERE Customer = '{$_GET['cid']}' LIMIT 5");
if ($getDocuments->num_rows > 0) {
    $_SESSION['info'] = 'Klientas dalyvauja apskaitoje. Trynimas negalimas!<br>';
    while ($document = $getDocuments->fetch_assoc()) {
        $_SESSION['info'] .= 'Egzistuoja dokumentas Nr. ' . $document['InvoiceSeries'] . $document['DocumentNo'] . '<br>';
    }
} else $deleteIndicator = true;

if ($deleteIndicator == true) {
    $deleteContractor = "DELETE FROM classifier_contractor WHERE ID = '{$_GET['cid']}' AND CompanyCode='{$_SESSION['user_company']}'";
    if (!$conn->query($deleteContractor)) {
        $_SESSION['info'] = $conn->error;
    } else {
        $_SESSION['info'] = 'Klientas sėkmingai ištrintas';
    }
}
header("Location: " . $_SERVER['HTTP_REFERER']);
?>