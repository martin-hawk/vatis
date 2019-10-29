<?php
require_once ('db.php');
require_once ('functions.php');
session_start();

page_protect($conn, '1', $_SESSION['user_company']);

	$date = strtotime($_SESSION['date']);
	$year = date("Y", $date);
	$month = date("m", $date);
	
	$update_header = "UPDATE document_sales_header SET Year = '$year', Period = '$month', DocumentDate = '{$_SESSION['date']}', DueDate = '{$_SESSION['due_date']}', BankID = '{$_SESSION['bankID']}', Customer = '{$_SESSION['customer_id']}', CurrencyDate = '{$_SESSION['date']}', CurrencyCode = '{$_SESSION['currency']}', CurrencyRate = '{$_SESSION['exchangeRate']}', DocumentTotalNet = '{$_SESSION['documentNetTotal']}', LocalTotalNet = '{$_SESSION['localNetTotal']}', DocumentTotalVAT = '{$_SESSION['documentVATTotal']}', LocalTotalVAT = '{$_SESSION['localVATTotal']}', DocumentTotalGross = '{$_SESSION['documentGrossTotal']}', LocalTotalGross = '{$_SESSION['localGrossTotal']}', VATDate = '{$_SESSION['date']}' WHERE CompanyCode = '{$_SESSION['user_company']}' AND SystemID = '{$_SESSION['sid']}'";
	
	$header_indicator = false;
	$delete_indicator = false;
	$items_indicator = false;
	
	if ($conn->query($update_header) === true) $header_indicator = true;
	
	if ($header_indicator == true) {
	
	// discard of all line items
	$delete_lines = "DELETE FROM document_sales_items WHERE CompanyCode='{$_SESSION['user_company']}' AND SystemID = '{$_SESSION['sid']}'";
	
	if ($conn->query($delete_lines) == true) $delete_indicator = true;
	
	if ($delete_indicator == true) {
	// get all values from SESSION array
	for ($n = 0; $n <= $_SESSION['count']; $n++) {
		$lineID = $n + 1;
		$line_item[$n] = "('{$_SESSION['user_company']}', '$year', '{$_SESSION['sid']}', '$lineID', 'K', '{$_SESSION['productCode'][$n]}', '{$_SESSION['quantity'][$n]}', '{$_SESSION['documentPrice'][$n]}', '{$_SESSION['localPrice'][$n]}', '{$_SESSION['vatRate']}', '{$_SESSION['documentVAT'][$n]}', '{$_SESSION['localVAT'][$n]}', '{$_SESSION['documentNet'][$n]}', '{$_SESSION['localNet'][$n]}', '{$_SESSION['documentGross'][$n]}', '{$_SESSION['localGross'][$n]}')"; // {$_SESSION['lineComment'][$n]} 
	}
	
	// append the query with each set
	$insert_items = "INSERT INTO document_sales_items (CompanyCode, Year, SystemID, LineID, DebitCreditID, ProductCode, Quantity, DocumentPrice, LocalPrice, VATRate, DocumentVAT, LocalVAT, DocumentNet, LocalNet, DocumentGross, LocalGross) VALUES "; // Pridėti Comment gale
	
	for ($n = 0; $n <= $_SESSION['count']; $n++) {
		if ($n == 0) $insert_items .= $line_item[$n];
		if ($n > 0 && n <= $_SESSION['count']) $insert_items .= ", " . $line_item[$n];
	} 
	
	// excute the query
	if ($conn->query($insert_items) === TRUE) { $items_indicator = true; }
	}
		
	if (($header_indicator === true) && ($items_indicator === true)) {
		$_SESSION['info'] = "PVM sąskaita faktūra Nr. " . $_SESSION['prefix'] . $_SESSION['invoiceNo'] . " sėkmingai atnaujinta";
		$_SESSION['isSaved'] = true;
	} else {
		$_SESSION['info'] = "Klaida! " . $conn->error;
}
} 

header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
?>