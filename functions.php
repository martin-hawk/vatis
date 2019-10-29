<?php
require_once('db.php');

function page_protect($conn, $levels, $company)
{
    if (!isset($_SESSION)) {
        session_start();
    }
    global $db;

    if (!$_SESSION['logged_in']) { // if false
        $access = false;
    } else {
        $kt = explode(' ', $levels);

        $query = $conn->query('SELECT CompanyCode, Level_access FROM classifier_user WHERE UserID = "' . $_SESSION['user_id'] . '"');
        $row = $query->fetch_assoc();
		
		$query->close();
		
        $accessLevel = false;
		$accessCompany = false;

        while (list($key, $val) = each($kt)) {
            if ($val == $row['Level_access']) {
                $accessLevel = true;
            }
        }
		if ($company == $row['CompanyCode']) $accessCompany = true;
    }
    if ($accessLevel == false && $accessCompany == false) {
        header("Location: denied.php");
    }
}
?>