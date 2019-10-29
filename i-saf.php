<?php
require_once ('db.php');
require_once ('functions.php');
require_once ('./js/calendar/tc_calendar.php');
session_start();
page_protect($conn, '1', $_SESSION['user_company']);

$con = new mysqli($servername, $username, $password, $database);
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
} 
$con->set_charset("utf8");

if (isset($_GET['dateFrom'])) {
	$_SESSION['dateFrom'] = $_GET['dateFrom'];
	$_SESSION['dateTo'] = $_GET['dateTo'];
}

include 'header.php';
?>

<div class="container" style="margin-bottom:4em;">
  <form name="iSAF" method="get" action="<?php $_SERVER['PHP_SELF'] ?>">
    <table width="100%" align="center" style="margin-top:20px;">
      <tr>
        <td colspan="3"  align="center"><?php if (isset($_SESSION['info'])) {
				echo '<div style="width:600px; border: 3px solid #090; margin-bottom: 1em; padding: 10px;  border-radius: 15px 30px;">';
  				echo $_SESSION['info'];
				echo '</div>';
			} ?></td>
      </tr>
       <tr>
        <td>
        <table width="450px" align="center"><tr><td>
        Dokumento data<a href="#" style="color: Red; text-decoration:none;" title="Privaloma užpildyti"><strong>*</strong></a> <a href="#" style="color: Black;" title="PVM sąskaitos faktūros data. Nurodykite laikotarpį, kuriame ieškoti PVM sąskaitų faktūrų"><i class="fa fa-question-circle-o" aria-hidden="true"></i></a></td>
        <td><div style="font-size:12px">
            <?php
    $myCalendar = new tc_calendar("dateFrom", true, false);
    $myCalendar->setIcon("./js/calendar/images/iconCalendar.gif");
	$myCalendar->startMonday(true);
    if (isset($_SESSION['dateFrom'])) {
		$date = DateTime::createFromFormat("Y-m-d", $_SESSION['dateFrom']);
		$yyyy = $date->format("Y");
		$mm = $date->format("m");
		$dd = $date->format("d");
		$myCalendar->setDate($dd, $mm, $yyyy);
	}
	else {
	$myCalendar->setDate(1, date("m"), date("Y"));
	}
    $myCalendar->setPath("./js/calendar/");
    $myCalendar->setYearInterval(2000, 2050);
    $myCalendar->setDateFormat('Y-m-d');
    $myCalendar->setAlignment('left', 'bottom');
	$myCalendar->setTheme('theme3');
    $myCalendar->writeScript();
?>
          </div></td>
          <td><div style="font-size:12px">
            <?php
    $myCalendar = new tc_calendar("dateTo", true, false);
    $myCalendar->setIcon("./js/calendar/images/iconCalendar.gif");
	$myCalendar->startMonday(true);
    if (isset($_SESSION['dateTo'])) {
		$date = DateTime::createFromFormat("Y-m-d", $_SESSION['dateTo']);
		$yyyy = $date->format("Y");
		$mm = $date->format("m");
		$dd = $date->format("d");
		$myCalendar->setDate($dd, $mm, $yyyy);
	}
	else {
	define('t', 't');
	define('date_from', 'date_from');
	define('date_to', 'date_to');
	$last = date(t);
	$myCalendar->setDate($last, date("m"), date("Y"));
	}
    $myCalendar->setPath("./js/calendar/");
    $myCalendar->setYearInterval(2000, 2050);
    $myCalendar->setDateFormat('Y-m-d');
    $myCalendar->setAlignment('left', 'bottom');
	$myCalendar->setTheme('theme3');
    $myCalendar->writeScript();
?>
          </div></td>
          <td align="center"><a style="color: Black;" title="Formuoti i.SAF" href="javascript:void(0);" onClick="iSAF.submit();"><i class="fa fa-file-excel-o fa-3x" aria-hidden="true"></i></a></td>
      </tr>
      </table>
      </td>
      </tr>
    </table>
  </form>
  <?php

$page_limit = 10;

if (isset($_SESSION['dateFrom'])) {
	// form the SQL statement
	$sql = "SELECT * FROM document_sales_header WHERE CompanyCode='{$_SESSION['user_company']}' AND DocumentDate BETWEEN '{$_SESSION['dateFrom']}' AND '{$_SESSION['dateTo']}' ORDER BY DocumentNo ASC";

    $rs_total = $con->query($sql);
    $total = $rs_total->num_rows;

    if (!isset($_GET['page'])) {
        $start = 0;
    } else {
        $start = ($_GET['page'] - 1) * $page_limit;
    }

    $rs_results = $con->query($sql . " limit $start,$page_limit");
    $total_pages = ceil($total / $page_limit);
?>
  <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
    <table width="100%" align="center" class="table">
      <tr>
        <td colspan="10"><?php
    if ($total > $page_limit) {
        echo '<strong>Puslapiai:</strong> ';
        $i = 0;
        while ($i < $total_pages) {
            $page_no = $i + 1;
            $qstr = preg_replace("&page=[0-9]+&", "", $_SERVER['QUERY_STRING']);
            echo "<a style='color: Black;' href=\"i-saf.php?$qstr&page=$page_no\">$page_no</a> ";
            $i++;
        };
    } ?></td>
      </tr>
      <tr>
        <th>Dokumento Nr.</th>
        <th>Dokumento data</th>
        <th>Pirkėjo kodas</th>
        <th>Pirkėjo PVM kodas</th>
        <th>Pirkėjas</th>
        <th>PVM kodas</th>
        <th>Suma</th>
        <th>PVM</th>
        <th>Iš viso</th>
      </tr>
      <?php
    while ($rrows = $rs_results->fetch_assoc()) {
        $sqlhelper = "SELECT * FROM classifier_contractor WHERE ID = '" . $rrows['Customer'] . "' AND CompanyCode='" . $_SESSION['user_company'] . "'";
        $helperresults = $con->query($sqlhelper);
        $helperrows = $helperresults->fetch_assoc();
		
		switch ($rrows['ReverseType']) {
		case '': 
		echo '<tr> 
            	<td>' . $rrows['InvoiceSeries'] . $rrows['DocumentNo'] .
            	'</a></td>';
        	echo '<td>' . $rrows['DocumentDate'] . '</td>';
            echo '<td>' . $helperrows['Code'] . '</td>';
            echo '<td>' . $helperrows['VATCode'] . '</td>';
        	echo '<td>' . $helperrows['Name'] . '</td>';
            
            echo '<td>';
            $VATRateResults = $con->query("SELECT * FROM document_sales_items WHERE SystemID = '" . $rrows['SystemID'] . "' AND LineID = '00001' AND CompanyCode='" . $_SESSION['user_company'] . "'");
            $VATRateRows = $VATRateResults->fetch_assoc();
            echo $VATRateRows['VATRate'];
            echo '</td>';
        	
            echo '<td>' . $rrows['LocalTotalNet'] . '</td>';
            echo '<td>' . $rrows['LocalTotalVAT'] . '</td>';
            echo '<td>' . $rrows['LocalTotalGross'] . '</td>';
			echo '</tr>';
		break;
		case 'RL':
		echo '<tr style="color: Red;"> 
            <td>' . $rrows['InvoiceSeries'] . $rrows['DocumentNo'] .
            '</a></td>';
        	echo '<td>' . $rrows['DocumentDate'] . '</td>';
            echo '<td>' . $helperrows['Code'] . '</td>';
            echo '<td>' . $helperrows['VATCode'] . '</td>';
       		echo '<td>' . $helperrows['Name'] . '</td>';
            
            echo '<td>';
            $VATRateResults = $con->query("SELECT * FROM document_sales_items WHERE SystemID = '" . $rrows['SystemID'] . "' AND LineID = '00001' AND CompanyCode='" . $_SESSION['user_company'] . "'");
            $VATRateRows = $VATRateResults->fetch_assoc();
            echo $VATRateRows['VATRate'];
            echo '</td>';
            
            echo '<td>' . $rrows['LocalTotalNet'] . '</td>';
            echo '<td>' . $rrows['LocalTotalVAT'] . '</td>';
        	echo '<td>' . $rrows['LocalTotalGross'] . '</td>';
			echo '</tr>';
		break;
		case 'RD':
		echo '<tr style="color: SlateGray;"> 
            <td>' . $rrows['InvoiceSeries'] . $rrows['DocumentNo'] .
            '</a></td>';
        	echo '<td>' . $rrows['DocumentDate'] . '</td>';
       		echo '<td>' . $helperrows['Code'] . '</td>';
            echo '<td>' . $helperrows['VATCode'] . '</td>';
            echo '<td>' . $helperrows['Name'] . '</td>';
            
            echo '<td>';
            $VATRateResults = $con->query("SELECT * FROM document_sales_items WHERE SystemID = '" . $rrows['SystemID'] . "' AND LineID = '00001' AND CompanyCode='" . $_SESSION['user_company'] . "'");
            $VATRateRows = $VATRateResults->fetch_assoc();
            echo $VATRateRows['VATRate'];
            echo '</td>';
            
            echo '<td>' . $rrows['LocalTotalNet'] . '</td>';
            echo '<td>' . $rrows['LocalTotalVAT'] . '</td>';
        	echo '<td>' . $rrows['LocalTotalGross'] . '</td>';
			echo '</tr>';
		break;
		}
    }
	?>
      <tr><td colspan="10"><div style="color: Red; font-size:12px;">Kreditinės sąskaitos</div><div style="color: SlateGray; font-size:12px;">Atšauktos PVM sąskaitos faktūros</div><div style="font-size:12px;">PVM sąskaitos faktūros</div></td></tr>
      <tr>
        <td colspan="10"><input name="query_str" type="hidden" value="<?php $_SERVER['QUERY_STRING'] ?>" /></td>
      </tr>
    </table>
  </form>
</div>
<?php
}
$con->close();
include 'footer.php';
unset($_SESSION['info']);
?>
