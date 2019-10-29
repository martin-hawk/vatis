<?php
require_once 'db.php';
require_once 'functions.php';
session_start();
page_protect($conn, '1', $_SESSION['user_company']);

$deleteIndicator = false;
unset($_SESSION['info']);

$getDocuments = $conn->query("SELECT DISTINCT document_sales_header.InvoiceSeries, document_sales_header.DocumentNo FROM document_sales_header, document_sales_items WHERE document_sales_items.ProductCode = '{$_GET['pid']}' AND document_sales_header.SystemID = document_sales_items.SystemID LIMIT 5");
if ($getDocuments->num_rows > 0) {
    $_SESSION['info'] = 'Produktas <strong>' . $_GET['pid'] . '</strong> dalyvauja apskaitoje. Trynimas negalimas!<br>';
    while ($document = $getDocuments->fetch_assoc()) {
        $_SESSION['info'] .= 'Egzistuoja dokumentas Nr. ' . $document['InvoiceSeries'] . $document['DocumentNo'] . '<br>';
    }
} else $deleteIndicator = true;

if ($deleteIndicator == true) {
    $deleteProduct = "DELETE FROM classifier_product WHERE ProductCode = '{$_GET['pid']}' AND CompanyCode='{$_SESSION['user_company']}'";
    if (!$conn->query($deleteProduct)) {
        $_SESSION['info'] = $conn->error;
    } else {
        $_SESSION['info'] = 'Produktas <strong>' . $_GET['pid'] . '</strong> sėkmingai ištrintas';
    }
}
header("Location: " . $_SERVER['HTTP_REFERER']);
?>