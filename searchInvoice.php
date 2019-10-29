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

if (isset($_GET['dateFrom']) || isset($_GET['number']) || isset($_GET['client'])) {
	$_SESSION['dateFrom'] = $_GET['dateFrom'];
	$_SESSION['dateTo'] = $_GET['dateTo'];
	$_SESSION['client'] = $_GET['client'];
	$_SESSION['number'] = $_GET['number'];
}

include 'header.php';
?>

<div class="container" style="margin-bottom:4em;">
  <form name="searchInvoice" method="get" action="<?php $_SERVER['PHP_SELF'] ?>">
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
          <td rowspan="3" align="center"><a style="color: Black;" title="Ieškoti" href="javascript:void(0);" onClick="searchInvoice.submit();"><i class="fa fa-search fa-3x" aria-hidden="true"></i></a></td>
      </tr>
      <tr>
      <td>Pirkėjas <a href="#" style="color: Black;" title="Nurodykite pirkėją, kurio PVM sąskaitų faktūrų ieškoti"><i class="fa fa-question-circle-o" aria-hidden="true"></i></a></td>
        <td colspan="2"><input name="client" id="customer_selection" type="text" title="Įveskite pirkėjo pavadinimą. Nenurodžius jokio kriterijaus bus išvestas visų PVM sąskaitų faktūrų sąrašas" value="<?php if (isset($_SESSION['client'])) echo $_SESSION['client']; ?>" />
      </td>
      </tr>
      <tr>
      <td>Dokumento numeris <a href="#" style="color: Black;" title="Nurodykite PVM sąskaitos faktūros numerį"><i class="fa fa-question-circle-o" aria-hidden="true"></i></a></td>
        <td colspan="2"><input name="number" autocomplete="off" type="text" title="Įveskite PVM sąskaitos numerį ar jo dalį be serijos. Nenurodžius jokio kriterijaus bus išvestas visų PVM sąskaitų faktūrų sąrašas" placeholder="00023" value="<?php if (isset($_SESSION['number'])) echo $_SESSION['number']; ?>" />
      </td>
      </tr>
      </table>
      </td>
      </tr>
    </table>
  </form>
  <?php

$page_limit = 5;

if (isset($_SESSION['dateFrom']) || isset($_SESSION['number']) || isset($_SESSION['client'])) {
	// form the SQL statement
	$sql = "SELECT * FROM document_sales_header WHERE CompanyCode='{$_SESSION['user_company']}' AND DocumentDate BETWEEN '{$_SESSION['dateFrom']}' AND '{$_SESSION['dateTo']}'";
	if (!empty($_SESSION['client'])) $sql .= " AND Customer='{$_SESSION['client']}'";
	if (!empty($_SESSION['number'])) $sql .= " AND DocumentNo='{$_SESSION['number']}'"; 					
	$sql .= " ORDER BY DocumentNo ASC";

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
  <form name="searchform" action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
    <table width="100%" align="center" class="table">
      <tr>
        <td colspan="10"><?php
    if ($total > $page_limit) {
        echo '<strong>Puslapiai:</strong> ';
        $i = 0;
        while ($i < $total_pages) {
            $page_no = $i + 1;
            $qstr = preg_replace("&page=[0-9]+&", "", $_SERVER['QUERY_STRING']);
            echo "<a style='color: Black;' href=\"searchInvoice.php?$qstr&page=$page_no\">$page_no</a> ";
            $i++;
        };
    } ?></td>
      </tr>
      <tr>
        <th>Dokumento Nr.</th>
        <th>Dokumento data</th>
        <th>Pirkėjas</th>
        <th>Iš viso (su PVM)</th>
        <th colspan="5"><div align="center">Operacijos</div></th>
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
        	echo '<td>' . $helperrows['Name'] . '</td>';
        	echo '<td>' . $rrows['LocalTotalGross'] . ' ' . $rrows['CurrencyCode'] . '</td>';
        
			echo '<td><a style="color: Black;" title="Redaguoti" href="editInvoice.php?sid=' . $rrows['SystemID'] . '"><i class="fa fa-pencil fa-2x" aria-hidden="true"></i></a></td>
			<td><a style="color: Black; text-decoration:none;" title="Suformuoti PDF (lietuvių kalba)" href="phpGetPDF.php?sid=' . $rrows['SystemID'] .'&language=lt" target="_blank"><span style="font-size:30px;">LT</span><i class="fa fa-file-pdf-o fa-2x" aria-hidden="true"></i></a></td>
			<td><a style="color: Black; text-decoration:none;" title="Suformuoti PDF (anglų kalba)" href="phpGetPDF.php?sid=' . $rrows['SystemID'] .'&language=en" target="_blank"><span style="font-size:30px;">EN</span><i class="fa fa-file-pdf-o fa-2x" aria-hidden="true"></i></a></td>
			<td><a style="color: Black;" title="Ištrinti" href="phpDeleteInvoice.php?sid=' . $rrows['SystemID'] . '&idate=' . $rrows['DocumentDate'] . '&inv=' . $rrows['InvoiceSeries'] . $rrows['DocumentNo'] . '"><i class="fa fa-trash fa-2x" aria-hidden="true"></i></a></td>
			<td><a title="Išrašyti kreditinę sąskaitą faktūrą" style="color: Black;" href="creditInvoice.php?sid=' . $rrows['SystemID'] . '"><i class="fa fa-undo fa-2x" aria-hidden="true"></i></a></td></tr>';
		break;
		case 'RL':
		echo '<tr style="color: Red;"> 
            <td>' . $rrows['InvoiceSeries'] . $rrows['DocumentNo'] .
            '</a></td>';
        	echo '<td>' . $rrows['DocumentDate'] . '</td>';
       		echo '<td>' . $helperrows['Name'] . '</td>';
        	echo '<td>' . $rrows['LocalTotalGross'] . ' ' . $rrows['CurrencyCode'] . '</td>';
			echo '<td></td>
			<td><a style="color: Red; text-decoration:none;" title="Suformuoti PDF (lietuvių kalba)" href="phpGetCreditPDF.php?sid=' . $rrows['SystemID'] .'&language=lt" target="_blank"><span style="font-size:30px;">LT</span><i class="fa fa-file-pdf-o fa-2x" aria-hidden="true"></i></a></td>
			<td><a style="color: Red; text-decoration:none;" title="Suformuoti PDF (anglų kalba)" href="phpGetCreditPDF.php?sid=' . $rrows['SystemID'] .'&language=en" target="_blank"><span style="font-size:30px;">EN</span><i class="fa fa-file-pdf-o fa-2x" aria-hidden="true"></i></a></td>
			<td></td>
			<td></td></tr>';
		break;
		case 'RD':
		echo '<tr style="color: SlateGray;"> 
            <td>' . $rrows['InvoiceSeries'] . $rrows['DocumentNo'] .
            '</a></td>';
        	echo '<td>' . $rrows['DocumentDate'] . '</td>';
       		echo '<td>' . $helperrows['Name'] . '</td>';
        	echo '<td>' . $rrows['LocalTotalGross'] . ' ' . $rrows['CurrencyCode'] . '</td>';
			echo '<td></td>
			<td><a style="color: SlateGray; text-decoration:none;" title="Suformuoti PDF (lietuvių kalba)" href="phpGetPDF.php?sid=' . $rrows['SystemID'] .'&language=lt" target="_blank"><span style="font-size:30px;">LT</span><i class="fa fa-file-pdf-o fa-2x" aria-hidden="true"></i></a></td>
			<td><a style="color: SlateGray; text-decoration:none;" title="Suformuoti PDF (anglų kalba)" href="phpGetPDF.php?sid=' . $rrows['SystemID'] . '&language=en" target="_blank"><span style="font-size:30px;">EN</span><i class="fa fa-file-pdf-o fa-2x" aria-hidden="true"></i></a></td>
			<td></td>
			<td></td></tr>';
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
unset($_SESSION['sid']);
unset($_SESSION['reversedWith']);
?>
