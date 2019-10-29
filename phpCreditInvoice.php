<?php
require_once ('db.php');
require_once ('functions.php');
session_start();

page_protect($conn, '1', $_SESSION['user_company']);


if (!empty($_SESSION['creditDate'])) {
	
	$invoiceDate = strtotime($_SESSION['date']);
	$invoiceYear = date("Y", $invoiceDate);
	$invoiceMonth = date("m", $invoiceDate);
	
	$creditDate = strtotime($_SESSION['creditDate']);
	$creditYear = date("Y", $creditDate);
	$creditMonth = date("m", $creditDate);

	$entryresult = $conn->query("SELECT * FROM classifier_counter WHERE CompanyCode='" . $_SESSION['user_company'] . "' AND DocumentType='DC'");
	$entry = $entryresult->fetch_assoc();
	if ($entry['CurrentNumber'] === NULL) {
		$systemID = $entry['RangeFrom'];
	} else $systemID = $entry['CurrentNumber']+1;
	$_SESSION['creditPrefix'] = $entry['Prefix'];
	
	$insert_header = "INSERT INTO document_sales_header (CompanyCode, Year, SystemID, Period, DocumentType, InvoiceSeries, DocumentNo, DocumentDate, PostingDate, BankID, Customer, CurrencyDate, CurrencyCode, CurrencyRate, DocumentTotalNet, LocalTotalNet, DocumentTotalVAT, LocalTotalVAT, DocumentTotalGross, LocalTotalGross, Username, VATDate, ReversedWith, ReverseYear, ReversePeriod, ReverseDocumentDate, ReverseType) VALUES ('{$_SESSION['user_company']}', '$creditYear', '$systemID', '$creditMonth', 'DC', '{$_SESSION['creditPrefix']}', '{$_SESSION['creditNo']}', '{$_SESSION['creditDate']}', '{$_SESSION['creditDate']}', '{$_SESSION['bankID']}', '{$_SESSION['customer_id']}', '{$_SESSION['creditDate']}', '{$_SESSION['currency']}', '{$_SESSION['exchangeRate']}', '{$_SESSION['documentNetTotal']}', '{$_SESSION['localNetTotal']}', '{$_SESSION['documentVATTotal']}', '{$_SESSION['localVATTotal']}', '{$_SESSION['documentGrossTotal']}', '{$_SESSION['localGrossTotal']}', '{$_SESSION['user_id']}', '{$_SESSION['creditDate']}', '{$_SESSION['sid']}', '$invoiceYear', '$invoiceMonth', '{$_SESSION['date']}', 'RL')"; // {$_SESSION['headerComment']}
	
	$header_indicator = false;
	$items_indicator = false;
	$counter_indicator = false;
	$update_indicator = false;
	if ($conn->query($insert_header) === TRUE) $header_indicator = true;
	
	if ($header_indicator == true) {
	// get all values from SESSION array
	for ($n = 0; $n <= $_SESSION['count']; $n++) {
		$lineID = $n + 1;
		$line_item[$n] = "('{$_SESSION['user_company']}', '$creditYear', '$systemID', '$lineID', 'D', '{$_SESSION['productCode'][$n]}', '{$_SESSION['quantity'][$n]}', '{$_SESSION['documentPrice'][$n]}', '{$_SESSION['localPrice'][$n]}', '{$_SESSION['vatRate']}', '{$_SESSION['documentVAT'][$n]}', '{$_SESSION['localVAT'][$n]}', '{$_SESSION['documentNet'][$n]}', '{$_SESSION['localNet'][$n]}', '{$_SESSION['documentGross'][$n]}', '{$_SESSION['localGross'][$n]}')"; // {$_SESSION['lineComment'][$n]} 
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
	
	if ($items_indicator === true) {
	// update counter
	$update_counter = "UPDATE classifier_counter SET CurrentNumber='$systemID' WHERE CompanyCode='{$_SESSION['user_company']}' AND DocumentType='DC'";   // padaryti tikrinimą, ar į Range telpa
	if ($conn->query($update_counter) === TRUE) { $counter_indicator = true; }
	}
	
	if ($counter_indicator === true) {
		$update_header = "UPDATE document_sales_header SET ReversedWith = '$systemID', ReverseYear = '$creditYear',  ReversePeriod = '$creditMonth', ReverseDocumentDate = '{$_SESSION['creditDate']}', ReverseType = 'RD' WHERE CompanyCode = '{$_SESSION['user_company']}' AND SystemID = '{$_SESSION['sid']}'";
		
		if ($conn->query($update_header) === true) $update_indicator = true;
	}
	
	if ($header_indicator === true && $items_indicator === true && $counter_indicator === true && $update_indicator === true) {
		$_SESSION['info'] = "Kreditinė sąskaita Nr. " . $_SESSION['creditPrefix'] . sprintf('%05d', $_SESSION['creditNo']) . " sėkmingai išsaugota";
		$_SESSION['isSaved'] = true;
		$_SESSION['reversedWith'] = $systemID;
	} else {
		$_SESSION['info'] = "Klaida! " . $conn->error;
}
} else $_SESSION['info'] = "Nustatykite kreditinės sąskaitos datą";

header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
?>