<?php
require_once ('db.php');
require_once ('functions.php');
session_start();

page_protect($conn, '1', $_SESSION['user_company']);

//if($_POST['type'] == 'product'){
	//$row_num = $_POST['row_num'];
	$result = $conn->query("SELECT * FROM classifier_product WHERE CompanyCode='{$_SESSION['user_company']}' AND (Description LIKE '".strtoupper($_POST['name_startsWith'])."%' OR ProductCode LIKE '".strtoupper($_POST['name_startsWith'])."%')");	
	$data = array();
	while ($row = $result->fetch_assoc()) {
		$product = $row['ProductCode'].'|'.$row['Description'].'|'.$row['DefaultQuantity'].'|'.$row['UnitOfMeasure'].'|'.$row['DefaultPrice'];//.'|'.$row_num;
		array_push($data, $product);	
	}	
	echo json_encode($data);
//}
?>