<?php
require_once ('db.php');
require_once ('functions.php');
session_start();

page_protect($conn, '1', $_SESSION['user_company']);

$_SESSION['isSaved'] = false;

	$delete = $_GET['delete']-1;
	if (isset($_SESSION[count])) {
		$_SESSION['count']--;

	array_splice($_SESSION['productCode'], $delete, 1);
	array_splice($_SESSION['productDescription'], $delete, 1);
	array_splice($_SESSION['productForeignDescription'], $delete, 1);
	array_splice($_SESSION['quantity'], $delete, 1);
	array_splice($_SESSION['uom'], $delete, 1);
	array_splice($_SESSION['uomDescription'], $delete, 1);
	array_splice($_SESSION['uomForeignDescription'], $delete, 1);
	array_splice($_SESSION['documentPrice'], $delete, 1);
	array_splice($_SESSION['localPrice'], $delete, 1);
	array_splice($_SESSION['documentNet'], $delete, 1);
	array_splice($_SESSION['documentVAT'], $delete, 1);
	array_splice($_SESSION['documentGross'], $delete, 1);
	array_splice($_SESSION['localNet'], $delete, 1);
	array_splice($_SESSION['localVAT'], $delete, 1);
	array_splice($_SESSION['localGross'], $delete, 1);
	
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