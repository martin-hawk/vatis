<?php
require_once ('db.php');
require_once ('functions.php');
session_start();

page_protect($conn, '1', $_SESSION['user_company']);

// set indicators
$_SESSION['isSaved'] = false;
$vatIndicator = false;
$productIndicator = false;
$quantityIndicator = false;
$priceIndicator = false;
unset($_SESSION['info']); // better unset
unset($_SESSION['tempCode']);
unset($_SESSION['tempQuantity']);
unset($_SESSION['tempUOM']);
unset($_SESSION['tempPrice']);

// set temporary variables
$_SESSION['tempCode'] = $_POST['newProduct'];
$_SESSION['tempQuantity'] = $_POST['newQuantity'];
$_SESSION['tempUOM'] = $_POST['newUOM'];
$_SESSION['tempPrice'] = $_POST['newPrice'];

// check for values
	if (empty($_SESSION['vatPercentage'])) $_SESSION['info'] = 'Nenustatytas PVM kodas ir tarifas<br>'; else $vatIndicator = true;
	if (empty($_SESSION['tempCode'])) $_SESSION['info'] .= 'Nenurodytas produkto kodas<br>';
	else {
		$products = $conn->query("SELECT * FROM classifier_product WHERE CompanyCode='{$_SESSION['user_company']}' AND ProductCode='{$_SESSION['tempCode']}'");
		$count = $products->num_rows;
		if ($count == 1) $productIndicator = true;
		else $_SESSION['info'] .= 'Tokio produkto kodo nėra sistemos kataloge. <a style="color: Black; text-decoration: underline;" href="newProduct.php" target="_blank">Sukurti naują</a><br>';
	}
	if (empty($_SESSION['tempQuantity']) || !is_numeric($_SESSION['tempQuantity'])) $_SESSION['info'] .= 'Nenurodytas arba neteisingai nurodytas produkto kiekis<br>'; else $quantityIndicator = true;
	if (empty($_SESSION['tempPrice']) || !is_numeric($_SESSION['tempPrice'])) $_SESSION['info'] .= 'Nenurodyta arba neteisingai nurodyta produkto kaina<br>'; else $priceIndicator = true;
	
	if ($vatIndicator == true && $productIndicator == true && $quantityIndicator == true &&$priceIndicator == true) {
	// additional check for some parameters
	if (isset($_SESSION['count'])) $_SESSION['count']++; else $_SESSION['count'] = 0;
	if (empty($_SESSION['exchangeRate'])) $_SESSION['exchangeRate'] = 1;
	if (empty($_SESSION['currency'])) $_SESSION['currency'] = 'EUR';
	
	// handle product
	$_SESSION['productCode'][$_SESSION['count']] = $_SESSION['tempCode'];
	$products = $conn->query("SELECT * FROM classifier_product WHERE ProductCode='{$_SESSION['tempCode']}'");
	$product = $products->fetch_assoc();
	$_SESSION['productDescription'][$_SESSION['count']] = $product['Description'];
	$_SESSION['productForeignDescription'][$_SESSION['count']] = $product['ForeignDescription'];
	
	// handle quantity
	$_SESSION['quantity'][$_SESSION['count']] = number_format((float)$_SESSION['tempQuantity'], 3, '.', '');
	
	// handle UOM
	$_SESSION['uom'][$_SESSION['count']] = $_SESSION['tempUOM'];
	$uoms = $conn->query("SELECT * FROM classifier_uom WHERE UOMCode='{$_SESSION['tempUOM']}'");
	$uom = $uoms->fetch_assoc();
	$_SESSION['uomDescription'][$_SESSION['count']] = $uom['UOMDescription'];
	$_SESSION['uomForeignDescription'][$_SESSION['count']] = $uom['ForeignUOMDescription'];
	
	// handle price
	$_SESSION['documentPrice'][$_SESSION['count']] = number_format((float)$_SESSION['tempPrice'], 4, '.', '');
	$localPrice[$_SESSION['count']] = $_SESSION['documentPrice'][$_SESSION['count']] / $_SESSION['exchangeRate'];
	$_SESSION['localPrice'][$_SESSION['count']] = number_format((float)$localPrice[$_SESSION['count']], 4, '.', '');
	
	// perfom claculations
	$dNet = $_SESSION['quantity'][$_SESSION['count']] * $_SESSION['documentPrice'][$_SESSION['count']];
	$_SESSION['documentNet'][$_SESSION['count']] = number_format((float)$dNet, 2, '.', '');
	$dVAT = $_SESSION['quantity'][$_SESSION['count']] * $_SESSION['documentPrice'][$_SESSION['count']] * $_SESSION['vatPercentage'] / 100;
	$_SESSION['documentVAT'][$_SESSION['count']] = number_format((float)$dVAT, 2, '.', '');
	$dGross = $_SESSION['documentNet'][$_SESSION['count']] + $_SESSION['documentVAT'][$_SESSION['count']];
	$_SESSION['documentGross'][$_SESSION['count']] = number_format((float)$dGross, 2, '.', '');

	$dNetTotal = 0;
	$dVATTotal = 0;
	$dGrossTotal = 0;
	$lNetTotal = 0;
	$lVATTotal = 0;
	$lGrossTotal = 0;
	
	$lNet = $_SESSION['quantity'][$_SESSION['count']] * $_SESSION['documentPrice'][$_SESSION['count']] / $_SESSION['exchangeRate'];
	$_SESSION['localNet'][$_SESSION['count']] = number_format((float)$lNet, 2, '.', '');
	$lVAT = $_SESSION['quantity'][$_SESSION['count']] * $_SESSION['documentPrice'][$_SESSION['count']] * $_SESSION['vatPercentage'] / 100 / $_SESSION['exchangeRate'];
	$_SESSION['localVAT'][$_SESSION['count']] = number_format((float)$lVAT, 2, '.', '');
	$lGross = ($_SESSION['documentNet'][$_SESSION['count']] + $_SESSION['documentVAT'][$_SESSION['count']]) / $_SESSION['exchangeRate'];
	$_SESSION['localGross'][$_SESSION['count']] = number_format((float)$lGross, 2, '.', '');
	
	for ($j = 0; $j <= $_SESSION['count']; $j++) { 
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
	
	// unset if successful
	unset($_SESSION['tempCode']);
	unset($_SESSION['tempQuantity']);
	unset($_SESSION['tempUOM']);
	unset($_SESSION['tempPrice']);
}

header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
?>