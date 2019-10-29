<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "vatis";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Nepavyko susijungti su serveriu: " . $conn->connect_error);
} 
// Set encoding
$conn->set_charset("utf8");
			 
?>