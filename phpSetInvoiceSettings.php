<?php
require_once ('db.php');
require_once ('functions.php');
session_start();

page_protect($conn, '1', $_SESSION['user_company']);

$_SESSION['isSaved'] = false;
unset($_SESSION['info']);

$type = "LT";
$date = "";
$searchTerm = "";
$result = "";

$today = date('Y-m-d');

    $result = getCurrencyRate('LT', $_POST['date'], $_POST['searchTerm']);
	
	if (isset($_POST['date'])) $_SESSION['date'] = $_POST['date'];
	if (isset($_POST['due_date'])) $_SESSION['due_date'] = $_POST['due_date'];
	if (isset($_POST['searchTerm'])) $_SESSION['currency'] = $_POST['searchTerm'];
    if (isset($_POST['newBankID'])) $_SESSION['bankID'] = $_POST['newBankID'];
	
	if (isset($_SESSION['advanceInvoice']))	{
		$_SESSION['vatRate'] = '';
		$_SESSION['vatPercentage'] = '0.00';
		$_SESSION['vatReason'] = '';
		$_SESSION['vatForeignReason'] = '';
	} else {
	if (isset($_POST['newVATRate'])) $_SESSION['vatRate'] = $_POST['newVATRate'];
	$vatPercents = $conn->query("SELECT * FROM classifier_vat_rate WHERE VATRate='{$_SESSION['vatRate']}'");
	$vatPercent = $vatPercents->fetch_assoc();
	$_SESSION['vatPercentage'] = number_format((float)$vatPercent['VATPercentage'], 2, '.', '');
	$_SESSION['vatReason'] = $vatPercent['Reason'];
	$_SESSION['vatForeignReason'] = $vatPercent['ForeignReason'];
	}
	
    //echo ($result . " " . $_POST['searchTerm']);
    //header('Location: CurrencyExchangeRate.php?msg=' . $result);

//set customer information
$query = "SELECT * FROM classifier_contractor WHERE CompanyCode='" . $_SESSION['user_company'] . "' AND ID='{$_POST['customer_selection']}'";
$customers = $conn->query($query);
$customer = $customers->fetch_assoc();

$_SESSION["customer_id"] = $customer["ID"];
$_SESSION["customer_name"] = htmlspecialchars($customer["Name"]);
$_SESSION["customer_code"] = $customer["Code"];
$_SESSION["customer_vat"] = $customer["VATCode"];

$_SESSION["customer_address1"] = $customer["Street"] . " " . $customer["House"];
if ($customer["Flat"] != "") { $_SESSION["customer_address1"] .= "-" . $customer["Flat"]; }

$_SESSION["customer_address2"] = $customer["PostalCode"] . " " . $customer["City"];
$_SESSION["customer_address3"] = $customer["District"];

$countryresult = $conn->query("SELECT *FROM classifier_country WHERE CountryCode='" . $customer["Country"] . "' AND Language='LT'");
$country = $countryresult->fetch_assoc();
$_SESSION["customer_address4"] = $country["CountryName"];

// set bank information
$bankQuery = "SELECT * FROM classifier_company_bank WHERE BankID='" . $_SESSION['bankID'] . "' AND CompanyCode='" . $_SESSION['user_company'] . "'";
$banks = $conn->query($bankQuery);
$bank = $banks->fetch_assoc();
$_SESSION['bankName'] = $bank['BankName'];
$_SESSION['bankSWIFT'] = $bank['SWIFT'];
$_SESSION['bankIBAN'] = $bank['IBAN'];
 
function getCurrencyRate($type, $date, $searchTerm)
{
	// https://www.lb.lt/webservices/FxRates/
    $xml = simplexml_load_file("http://www.lb.lt/webservices/FxRates/FxRates.asmx/getFxRates?tp=" . $type . "&dt=" . $date) or die("Klaida: Neįmanoma sukurti objekto.");
    foreach ($xml->children() as $FxRate) {

        foreach ($FxRate as $CcyAmt) {
            if ($CcyAmt->Ccy == $searchTerm) {
                $line = $CcyAmt->Amt;
				$_SESSION['exchangeRate'] = (float) $line;
                return $line;
            }
        }
}
}

	//ReClaculations
	$dNetTotal = 0;
	$dVATTotal = 0;
	$dGrossTotal = 0;
	$lNetTotal = 0;
	$lVATTotal = 0;
	$lGrossTotal = 0;
	
	for ($j = 0; $j <= $_SESSION['count']; $j++) { 
	
	$dNet = $_SESSION['quantity'][$j] * $_SESSION['documentPrice'][$j];
	$_SESSION['documentNet'][$j] = number_format((float)$dNet, 2, '.', '');
	$dVAT = $_SESSION['quantity'][$j] * $_SESSION['documentPrice'][$j] * $_SESSION['vatPercentage'] / 100;
	$_SESSION['documentVAT'][$j] = number_format((float)$dVAT, 2, '.', '');
	$dGross = $_SESSION['documentNet'][$j] + $_SESSION['documentVAT'][$j];
	$_SESSION['documentGross'][$j] = number_format((float)$dGross, 2, '.', '');
	
	$lNet = $_SESSION['quantity'][$j] * $_SESSION['documentPrice'][$j] / $_SESSION['exchangeRate'];
	$_SESSION['localNet'][$j] = number_format((float)$lNet, 2, '.', '');
	$lVAT = $_SESSION['quantity'][$j] * $_SESSION['documentPrice'][$j] * $_SESSION['vatPercentage'] / 100 / $_SESSION['exchangeRate'];
	$_SESSION['localVAT'][$j] = number_format((float)$lVAT, 2, '.', '');
	$lGross = ($_SESSION['documentNet'][$j] + $_SESSION['documentVAT'][$j]) / $_SESSION['exchangeRate'];
	$_SESSION['localGross'][$j] = number_format((float)$lGross, 2, '.', '');
		
		$dNetTotal += $_SESSION['documentNet'][$j];         
		$dVATTotal += $_SESSION['documentVAT'][$j];         
		$dGrossTotal += $_SESSION['documentGross'][$j];
		
		$lNetTotal += $_SESSION['localNet'][$j];         
		$lVATTotal += $_SESSION['localVAT'][$j];         
		$lGrossTotal += $_SESSION['localGross'][$j];
	}
	
	$_SESSION['documentNetTotal'] = number_format((float)$dNetTotal, 2, '.', '');
	$_SESSION['documentVATTotal'] = number_format((float)$dVATTotal, 2, '.', '');
	$_SESSION['documentGrossTotal'] = number_format((float)$dGrossTotal, 2, '.', '');
	
	$_SESSION['localNetTotal'] = number_format((float)$lNetTotal, 2, '.', '');
	$_SESSION['localVATTotal'] = number_format((float)$lVATTotal, 2, '.', '');
	$_SESSION['localGrossTotal'] = number_format((float)$lGrossTotal, 2, '.', '');

header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
?>