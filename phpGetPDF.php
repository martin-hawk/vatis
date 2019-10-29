<?php
require_once ('db.php');
require_once ('functions.php');
require('fpdf/fpdf.php');
session_start();

page_protect($conn, '1', $_SESSION['user_company']);

if (isset($_GET['language'])) $_SESSION['language'] = $_GET['language'];
else $_SESSION['language'] = "lt";

if (isset($_GET['sid'])) {
	$sqlHeader = "SELECT * FROM document_sales_header WHERE SystemID = '" . $_GET['sid'] . "' AND CompanyCode='" . $_SESSION['user_company'] . "'";
	
	$invoiceHData = $conn->query($sqlHeader);
	$invoiceH = $invoiceHData->fetch_assoc();
	
	$companies = $conn->query("SELECT * FROM classifier_company WHERE CompanyCode='{$_SESSION['user_company']}'");
	$company = $companies->fetch_assoc();
	
	$_SESSION['companyName'] = $company['Name'];
	$_SESSION['company_code'] = $company['Code'];
	$_SESSION['company_vat_payer'] = $company['VATPayer'];
	$_SESSION['company_vat'] = $company['VATCode'];
	$_SESSION["company_address1"] = $company["Street"] . " " . $company["House"];
	if ($company["Flat"] != "") $_SESSION["company_address1"] .= "-" . $company["Flat"];
	$_SESSION["company_address2"] = $company["PostalCode"] . " " . $company["City"];
	$_SESSION["company_address3"] = $company["District"];
	$countries = $conn->query("SELECT *FROM classifier_country WHERE CountryCode='" . $company["Country"] . "' AND Language='" . $_SESSION['language'] . "'");
	$country = $countries->fetch_assoc();
	$_SESSION["company_address4"] = $country["CountryName"];
	
	$_SESSION['sid'] = $_GET['sid'];
	$_SESSION['prefix'] = $invoiceH['InvoiceSeries'];
	$_SESSION['date'] = $invoiceH['DocumentDate'];
	$_SESSION['due_date'] = $invoiceH['DueDate'];
    $_SESSION['bankID'] = $invoiceH['BankID'];
	$_SESSION['currency'] = $invoiceH['CurrencyCode'];
	$_SESSION['exchangeRate'] = $invoiceH['CurrencyRate'];
	$_SESSION['invoiceNo'] = $invoiceH['DocumentNo'];
	$_SESSION['customer_id'] = $invoiceH['Customer'];
	$_SESSION['documentNetTotal'] = $invoiceH['DocumentTotalNet'];
	$_SESSION['localNetTotal'] = $invoiceH['LocalTotalNet'];
	$_SESSION['documentVATTotal'] = $invoiceH['DocumentTotalVAT'];
	$_SESSION['localVATTotal'] = $invoiceH['LocalTotalVAT'];
	$_SESSION['documentGrossTotal'] = $invoiceH['DocumentTotalGross'];
	$_SESSION['localGrossTotal'] = $invoiceH['LocalTotalGross'];
	
    
    $banks = $conn->query("SELECT * FROM classifier_company_bank WHERE BankID='{$_SESSION['bankID']}' AND CompanyCode='{$_SESSION['user_company']}'");
    $bank = $banks->fetch_assoc();
    
    $_SESSION['bankName'] = $bank['BankName'];
    $_SESSION['bankSWIFT'] = $bank['SWIFT'];
    $_SESSION['bankIBAN'] = $bank['IBAN'];
    
	$_SESSION['reversedWith'] = $invoiceH['ReversedWith'];
	$reversals = $conn->query("SELECT * FROM document_sales_header WHERE CompanyCode='{$_SESSION['user_company']}' AND SystemID='{$_SESSION['reversedWith']}'");
	$reversal = $reversals->fetch_assoc();
	$_SESSION['creditPrefix'] = $reversal['InvoiceSeries'];
	$_SESSION['creditNo'] = $reversal['DocumentNo'];
	$_SESSION['creditDate'] = $reversal['DocumentDate'];
	
	$sqlItems = "SELECT * FROM document_sales_items WHERE SystemID = '" . $_GET['sid'] . "' AND CompanyCode='" . $_SESSION['user_company'] . "'";
	
	$invoiceIData = $conn->query($sqlItems);
	$_SESSION['count'] = $invoiceIData->num_rows -1;
	
	while ($row = $invoiceIData->fetch_assoc()) {
		$invoiceI[] = $row;
	}	
	
	for ($n = 0; $n <= $_SESSION['count']; $n++) {
	$_SESSION['productCode'][$n] = $invoiceI[$n]['ProductCode'];
	
	$queryProduct = "SELECT * FROM classifier_product WHERE CompanyCode='" . $_SESSION['user_company'] . "' AND ProductCode = '" . $invoiceI[$n]['ProductCode'] . "'";
	$products = $conn->query($queryProduct);
	$product = $products->fetch_assoc();
	$_SESSION['productDescription'][$n] = $product['Description'];
	$_SESSION['productForeignDescription'][$n] = $product['ForeignDescription'];
	$_SESSION['uom'][$n] = $product['UnitOfMeasure'];
	
	$sqlUOM = "SELECT * FROM classifier_uom WHERE UOMCode = '" . $product['UnitOfMeasure'] . "'";
	
	$uoms = $conn->query($sqlUOM);
	$uom = $uoms->fetch_assoc();
	$_SESSION['uomForeignDescription'][$n] = $uom['ForeignUOMDescription'];
	
	$_SESSION['quantity'][$n] = $invoiceI[$n]['Quantity'];
	$_SESSION['documentPrice'][$n] = $invoiceI[$n]['DocumentPrice'];
	$_SESSION['localPrice'][$n] = $invoiceI[$n]['LocalPrice'];
	$_SESSION['vatRate'] = $invoiceI[0]['VATRate'];
	
	$queryRate = "SELECT * FROM classifier_vat_rate WHERE VATRate = '" . $invoiceI[$n]['VATRate'] . "'";
	$rates = $conn->query($queryRate);
	$rate = $rates->fetch_assoc();
	$_SESSION['vatPercentage'] = $rate['VATPercentage'];
	$_SESSION['vatReason'] = $rate['Reason'];
	$_SESSION['vatForeignReason'] = $rate['ForeignReason'];
	
	$_SESSION['documentVAT'][$n] = $invoiceI[$n]['DocumentVAT'];
	$_SESSION['localVAT'][$n] = $invoiceI[$n]['LocalVAT'];
	$_SESSION['documentNet'][$n] = $invoiceI[$n]['DocumentNet'];
	$_SESSION['localNet'][$n] = $invoiceI[$n]['LocalNet'];
	$_SESSION['documentGross'][$n] = $invoiceI[$n]['DocumentGross'];
	$_SESSION['localGross'][$n] = $invoiceI[$n]['LocalGross'];
	}
	
	$sqlCustomer = "SELECT * FROM classifier_contractor WHERE ID = '" . $_SESSION['customer_id'] . "' AND CompanyCode='" . $_SESSION['user_company'] . "'";
	
	$customers = $conn->query($sqlCustomer);
	$customer = $customers->fetch_assoc();
	
	$_SESSION['customer_name'] = htmlspecialchars($customer["Name"]);
	$_SESSION['customer_code'] = $customer["Code"];
	$_SESSION['customer_vat'] = $customer["VATCode"];
	$_SESSION['customer_address1'] = $customer["Street"] . " " . $customer["House"];
if ($customer["Flat"] != "") { $_SESSION["customer_address1"] .= "-" . $customer["Flat"]; }

	$_SESSION['customer_address2'] = $customer["PostalCode"] . " " . $customer["City"];
	$_SESSION["customer_address3"] = $customer["District"];
	
	$countryresult = $conn->query("SELECT *FROM classifier_country WHERE CountryCode='" . $customer["Country"] . "' AND Language='" . $_SESSION['language'] . "'");
	$country = $countryresult->fetch_assoc();
	$_SESSION["customer_address4"] = $country["CountryName"];
	
	$_SESSION['isSaved'] = true;
	
}

include 'phpConvertPDF.php';
unset($_SESSION['sid']);
unset($_SESSION['reversedWith']);
?>