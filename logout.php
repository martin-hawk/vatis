<?php
require_once ('db.php');

    global $db;
	$conn->close();
    
    session_start();

    unset($_SESSION['user_id']);
    unset($_SESSION['loged_in']);
    unset($_SESSION['level_access']);
    unset($_SESSION['f_name']);
    unset($_SESSION['l_name']);
    session_unset();
    session_destroy();

    header("Location: index.php");
?>