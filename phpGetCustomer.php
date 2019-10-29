<?php
require_once("db.php");
session_start();
$con = new mysqli($servername, $username, $password, $database);
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
} 
$con->set_charset("utf8");

$result = $conn->query("SELECT * FROM classifier_contractor WHERE (Name LIKE '".strtoupper($_POST['name_startsWith'])."%' OR Code LIKE '".strtoupper($_POST['name_startsWith'])."%') AND CompanyCode='{$_SESSION['user_company']}'");	
	$data = array();
	while ($row = $result->fetch_assoc()) {
		$customer = $row['ID'].'|'.$row['Code'].'|'.$row['Name'];
		array_push($data, $customer);	
	}	
	echo json_encode($data);
$con->close();
?>
