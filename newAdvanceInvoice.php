<?php
require_once ('db.php');
require_once ('functions.php');
require('fpdf/fpdf.php');
require_once ('./js/calendar/tc_calendar.php');
session_start();

page_protect($conn, '1', $_SESSION['user_company']);

include 'header.php';
?>

<div class="container">
  <form name="setSettings" method="post" action="phpSetInvoiceSettings.php">
    <table align="center" width="500px" style="margin-top:20px;">
      <tr>
        <td align="center" colspan="2"><?php if (isset($_SESSION['info'])) {
				echo '<div align="center" style="border: 3px solid #090; margin-bottom: 1em; padding: 10px; border-radius: 15px 40px;">';
  				echo $_SESSION['info'];
				echo '</div>';
			} ?></td>
      </tr>
      <tr>
        <td>Data<a href="#" style="color: Red; text-decoration:none;" title="Privaloma užpildyti"><strong>*</strong></a> <a href="#" style="color: Black;" title="PVM sąskaitos faktūros išrašymo data"><i class="fa fa-question-circle-o" aria-hidden="true"></i></a></td>
        <td><div style="font-size:12px">
            <?php
    $myCalendar = new tc_calendar("date", true, false);
    $myCalendar->setIcon("./js/calendar/images/iconCalendar.gif");
	$myCalendar->startMonday(true);
    if (isset($_SESSION['date'])) {
		$date = DateTime::createFromFormat("Y-m-d", $_SESSION['date']);
		$yyyy = $date->format("Y");
		$mm = $date->format("m");
		$dd = $date->format("d");
		$myCalendar->setDate($dd, $mm, $yyyy);
	}
	else {
	$myCalendar->setDate(date("d"), date("m"), date("Y"));
	}
    $myCalendar->setPath("./js/calendar/");
    $myCalendar->setYearInterval(2000, 2050);
    $myCalendar->setDateFormat('Y-m-d');
    $myCalendar->setAlignment('left', 'bottom');
	$myCalendar->setTheme('theme3');
    $myCalendar->writeScript();
?>
          </div></td>
      </tr>
      <tr>
        <td>Mokėjimo data <a href="#" style="color: Black;" title="PVM sąskaitos faktūros apmokėjimo terminas (data)"><i class="fa fa-question-circle-o" aria-hidden="true"></i></a></td>
        <td><div style="font-size:12px">
            <?php
    $myCalendar = new tc_calendar("due_date", true, false);
    $myCalendar->setIcon("./js/calendar/images/iconCalendar.gif");
	$myCalendar->startMonday(true);
    if (isset($_SESSION['due_date'])) {
		$date = DateTime::createFromFormat("Y-m-d", $_SESSION['due_date']);
		$yyyy = $date->format("Y");
		$mm = $date->format("m");
		$dd = $date->format("d");
		$myCalendar->setDate($dd, $mm, $yyyy);
	}
	else {
	$myCalendar->setDate(date("d"), date("m"), date("Y"));
	}
    $myCalendar->setPath("./js/calendar/");
    $myCalendar->setYearInterval(2000, 2050);
    $myCalendar->setDateFormat('Y-m-d');
    $myCalendar->setAlignment('left', 'bottom');
	$myCalendar->setTheme('theme3');
    $myCalendar->writeScript();
?>
          </div></td>
      </tr>
      <tr>
        <td>Pirkėjas<a href="#" style="color: Red; text-decoration:none;" title="Privaloma užpildyti"><strong>*</strong></a> <a href="#" style="color: Black;" title="Iš sistemoje užregistruotų pirkėjų pasirenkamas prikėjo pavadinimas"><i class="fa fa-question-circle-o" aria-hidden="true"></i></a></td>
        <td><input autocomplete="off" type="text" name="customer_selection" id="customer_selection" value="<?php if (isset($_SESSION['customer_id'])) echo $_SESSION['customer_id']; ?>">
          <div id="suggest-box"></div></td>
      </tr>
      <tr>
        <td>Valiutos kodas<a href="#" style="color: Red; text-decoration:none;" title="Privaloma užpildyti"><strong>*</strong></a> <a href="#" style="color: Black;" title="Nurodoma PVM sąskaitos faktūros valiuta"><i class="fa fa-question-circle-o" aria-hidden="true"></i></a></td>
        <td><select name="searchTerm" id="currency">
            <?php
          if (isset($_SESSION['currency'])) {
			  $eur = ($_SESSION['currency'] == 'EUR') ? ' selected>euras</option>' : '>euras</option>';
			  $usd = ($_SESSION['currency'] == 'USD') ? ' selected>JAV doleris</option>' : '>JAV doleris</option>';
			  $gbp = ($_SESSION['currency'] == 'GBP') ? ' selected>svaras sterlingų</option>' : '>svaras sterlingų</option>';			  
			  echo '<option value="EUR"' . $eur; 
              echo '<option value="USD"' . $usd;
              echo '<option value="GBP"' . $gbp;
		  }
		  else echo '
		          <option value="EUR" selected>euras</option>
                  <option value="USD">JAV doleris</option>
                  <option value="GBP">svaras sterlingų</option>';
		  ?>
          </select></td>
      </tr>
      <tr>
      <td>Mokėjimo būdas</td>
      <td><select name="newBankID" id="newBankID">
      <?php
$banks = $conn->query("SELECT * FROM classifier_company_bank");
unset($which);

if (isset($_SESSION['bankID']))
    $which = $_SESSION['bankID'];
else
    $which = "1";

while ($bank = $banks->fetch_array()) {
    if ($bank['BankID'] == $which) {
        echo '<option value="' . $bank['BankID'] . '" selected>' . $bank['AccountName'] .
            '</option>';
    } else {
        echo '<option value="' . $bank['BankID'] . '">' . $bank['AccountName'] .
            '</option>';
    }
}
    
    $banks = $conn->query("SELECT * FROM classifier_company_bank WHERE BankID='{$_SESSION['bankID']}' AND CompanyCode='{$_SESSION['user_company']}'");
    $bank = $banks->fetch_assoc();
    
    $_SESSION['bankName'] = $bank['BankName'];
    $_SESSION['bankSWIFT'] = $bank['SWIFT'];
    $_SESSION['bankIBAN'] = $bank['IBAN'];
?>
      </select>
      </td>
      </tr>
      <tr>
        <td align="center" colspan="2"><a style="color: Black;" title="Nustatyti" href="javascript:void(0);" onClick="setSettings.submit();"><i class="fa fa-exchange fa-2x" aria-hidden="true"></i></a></td>
      </tr>
    </table>
  </form>
  <table align="center" width="80%" style="margin-top: 0.5em">
    <tr>
      <td><table width="100%">
          <tr>
            <td valign="top"><h1>
                <?php 
			  $companyresult = $conn->query('SELECT * FROM classifier_company, classifier_country WHERE classifier_company.Country = classifier_country.CountryCode AND classifier_country.Language="LT" AND CompanyCode="' . $_SESSION['user_company'] .'"');
			  $company = $companyresult->fetch_assoc();
			  
			  $_SESSION["companyName"] = $company["Name"];
			  $_SESSION["company_code"] = $company["Code"];
			  $_SESSION["company_vat"] = $company["VATCode"];

			  $_SESSION["company_address1"] = $company["Street"] . " " . $company["House"];
			  if ($company["Flat"] != "") { $_SESSION["company_address1"] .= "-" . $company["Flat"]; }

			  $_SESSION["company_address2"] = $company["PostalCode"] . " " . $company["City"];
			  $_SESSION["company_address3"] = $company["District"];
			  $_SESSION['company_address4'] = $company["CountryName"];
			
			  echo $_SESSION['companyName'];
			  ?>
              </h1></td>
            <td align="right"> Tiekėjas: <?php echo $_SESSION['companyName']; ?><br/>
              Įm. kodas: <?php echo $_SESSION["company_code"]; ?><br/>
              PVM kodas: <?php echo $_SESSION["company_vat"]; ?><br/>
              Adresas: <?php echo $_SESSION["company_address1"] . '<br/>';
							echo $_SESSION["company_address2"] . '<br/>';
							echo $_SESSION["company_address3"] . '<br/>';
							echo $_SESSION["company_address4"]; ?></td>
          </tr>
          <tr >
            <td height="30px" colspan="2">&nbsp;</td>
          </tr>
          <tr>
            <td width="50%"><b>
              <?php 
			$_SESSION['prefix'] = "AVN";
			$_SESSION['invoiceNo'] = date("Ymd");
			
		echo 'Išankstinė sąskaita apmokėti Serija ' . $_SESSION['prefix'] . ' Nr. ' . $_SESSION['invoiceNo'];
	?>
              </b></td>
            <td align="right" width="50%">
            Pirkėjas: <?php echo isset($_SESSION['customer_name']) ? $_SESSION['customer_name'] : ''; ?></td>
          </tr>
          <tr>
            <td>Išrašymo data: <?php echo empty($_SESSION['date']) ? "" : $_SESSION['date']; ?></td>
            <td align="right">Įm. kodas: <?php echo isset($_SESSION['customer_code']) ? $_SESSION['customer_code'] : ''; ?></td>
          </tr>
          <tr>
            <td>Mokėjimo data: <?php echo empty($_SESSION['due_date']) ? "" : $_SESSION['due_date']; ?></td>
            <td align="right">PVM kodas: <?php echo isset($_SESSION['customer_vat']) ? $_SESSION['customer_vat'] : ''; ?></td>
          </tr>
          <tr>
            <td align="right" colspan="2">Adresas: <?php echo isset($_SESSION['customer_address1']) ? $_SESSION['customer_address1'] : ''; ?>
              <br/>
              <?php echo isset($_SESSION['customer_address2']) ? $_SESSION['customer_address2'] : ''; ?>
              <br/>
              <?php echo isset($_SESSION['customer_address3']) ? $_SESSION['customer_address3'] : ''; ?>
              <br/>
            <?php echo isset($_SESSION['customer_address4']) ? $_SESSION['customer_address4'] : ''; ?></td>
          </tr>
        </table></td>
    </tr>
    <tr>
      <td><form name="addRow" action="phpAddInvoiceRow.php" method="post">
          <table align="center" width="100%" frame="box" style="margin-top:40px;">
            <tr>
              <th>Produktas<a href="#" style="color: Red; text-decoration:none;" title="Privaloma užpildyti"><strong>*</strong></a> <a href="#" style="color: Black;" title="Produkto pavadinimas iš sistemoje registruotų produktų sąrašo. Paieška pagal produkto kodą arba aprašymą (LT). PVM sąskaitos faktūros šablone bus spausdinamas pilnas produkto pavadinimas"><i class="fa fa-question-circle-o" aria-hidden="true"></i></a></th>
              <th>Kiekis<a href="#" style="color: Red; text-decoration:none;" title="Privaloma užpildyti"><strong>*</strong></a> <a href="#" style="color: Black;" title="Produkto kiekis"><i class="fa fa-question-circle-o" aria-hidden="true"></i></a></th>
              <th>Matavimo vnt.<a href="#" style="color: Red; text-decoration:none;" title="Privaloma užpildyti"><strong>*</strong></a> <a href="#" style="color: Black;" title="Laukas užpildomas automatiškai iš produkto kortelės. Matavimo vienetas iš sistemoje registruotų matavimo vienetų sąrašo"><i class="fa fa-question-circle-o" aria-hidden="true"></i></a></th>
              <th>Kaina<a href="#" style="color: Red; text-decoration:none;" title="Privaloma užpildyti"><strong>*</strong></a> <a href="#" style="color: Black;" title="Produkto kaina (be PVM)"><i class="fa fa-question-circle-o" aria-hidden="true"></i></a></th>
              <th>&nbsp;</th>
            </tr>
            <tr>
              <td><input type="text" name="newProduct" id="newProduct" autocomplete="off" tabindex="1" value="<?php echo (isset($_SESSION['tempCode'])) ? $_SESSION['tempCode'] : ''; ?>"></td>
              <td><input type="text" name="newQuantity" id="newQuantity" style="text-align:right;" autocomplete="off"  tabindex="2" value="<?php echo (isset($_SESSION['tempQuantity'])) ? $_SESSION['tempQuantity'] : ''; ?>"></td>
              <td><input type="text" name="newUOM" id="newUOM" autocomplete="off" readonly value="<?php echo (isset($_SESSION['tempUOM'])) ? $_SESSION['tempUOM'] : ''; ?>"></td>
              <td><input type="text" name="newPrice" id="newPrice" style="text-align:right;" autocomplete="off"  tabindex="3" value="<?php echo (isset($_SESSION['tempPrice'])) ? $_SESSION['tempPrice'] : ''; ?>"></td>
              <td align="right"><a style="color: Black;" href="javascript:void(0);" title="Pridėti naują eilutę" onClick="addRow.submit();"><i class="fa fa-plus-circle fa-2x" aria-hidden="true"></i></a></td>
            </tr>
          </table>
        </form></td>
    </tr>
    <!-- Line headers and lines ***************************************************** -->
    <tr>
      <td><table id="lines" name="lines" width="100%" frame="border" border="1" align="center" style="margin-top: 0.5em">
          <tr>
            <th>Nr.</th>
            <th>Pavadinimas</th>
            <th>Kiekis</th>
            <th>Vienetai</th>
            <th>Kaina</th>
            <th>Suma</th>
            <th>&nbsp;</th>
          </tr>
          <?php 
		if (isset($_SESSION['count'])) {
		for ($i = 0; $i <= $_SESSION['count']; $i++)  { ?>
          <tr>
            <td><?php echo ($i+1); ?></td>
            <td><div> <?php echo $_SESSION['productCode'][$i]; ?> </div></td>
            <td><div align="right"> <?php echo $_SESSION['quantity'][$i]; ?> </div></td>
            <td><div> <?php echo $_SESSION['uom'][$i]; ?> </div></td>
            <td><div align="right"> <?php echo $_SESSION['documentPrice'][$i]; ?> </div></td>
            <td><div align="right"> <?php echo $_SESSION['documentNet'][$i]; ?> </div></td>
            <td align="right"><a style="text-decoration: none; color: Black;" title="Ištrinti eilutę" href="phpDeleteInvoiceRow.php?delete=<?php echo ($i+1); ?>"><i class="fa fa-trash-o fa-2x" aria-hidden="true"></i> </a></td>
          </tr>
          <?php };
		}; ?>
        </table></td>
    </tr>
    <!-- Totals ********************************************************************* -->
    <tr>
      <td><table id="totals" name="totals" align="right" style="margin-top: 0.25em">
          <tr>
            <td align="right">Iš viso</td>
            <td align="right"><div id="netTotal" align="right">
                <?php if (isset($_SESSION['documentNetTotal'])) { echo $_SESSION['documentNetTotal']; } else echo '0.00'; echo ' '; if(isset($_SESSION['currency'])) echo $_SESSION['currency']; ?>
              </div></td>
          </tr>
          <?php 
			if (isset($_SESSION['currency']) && $_SESSION['currency'] != "EUR") { ?>
        <tr>
          <td align="right" style="color:Grey;"><i>Iš viso, EUR</i></td>
          <td align="right" style="color:Grey;"><i>
		 <?php if (isset($_SESSION['localNetTotal'])) echo $_SESSION['localNetTotal']; ?> EUR</i></td> 
        </tr>
	<?php	}
		?>
        </table></td>
    </tr>
    <tr>
      <td align="center"><table>
          <tr>
            <td><form action="phpAdvancePDF.php" method="post" target="_blank" name="toPDF">
                <input type="hidden" name="language" value="lt" />
                <a style="color: Black; margin:10px; text-decoration:none;" title="Suformuoti PDF lietuvių kalba" href="javascript:void(0);" onClick="toPDF.submit();"><span style="font-size:30px;">LT</span><i class="fa fa-file-pdf-o fa-2x" aria-hidden="true"></i></a>
              </form></td>
            <td><form action="phpAdvancePDF.php" method="post" target="_blank" name="toPDFen">
                <input type="hidden" name="language" value="en" />
                <a style="color: Black; margin:10px; text-decoration:none;" title="Suformuoti PDF anglų kalba" href="javascript:void(0);" onClick="toPDFen.submit();"><span style="font-size:30px;">EN</span><i class="fa fa-file-pdf-o fa-2x" aria-hidden="true"></i></a>
              </form></td>
          </tr>
        </table></td>
    </tr>
    <tr>
      <td><!-- Valiutos skaičiuoklė ************************************************ -->
        
        <div align="center" style="margin-top: 25px; margin-bottom:4em; border:thin Black solid;">
          <h4>Valiutos konversijos skaičiuoklė</h4>
          <input id="currency1" type="text" style="text-align:right" oninput="calculateRateF(<?php echo
$_SESSION['exchangeRate'] ?>)" value="1" />
          EUR <i class="fa fa-exchange" aria-hidden="true"></i>
          <input id="currency2" type="text" style="text-align:right;" oninput="calculateRateB(<?php echo
    $_SESSION['exchangeRate'] ?>)" value="<?php if (isset($_SESSION['exchangeRate'])) {
    echo $_SESSION['exchangeRate'];
} ?>" />
          <?php if (isset($_SESSION['currency']))
    echo $_SESSION['currency']; ?>
        </div></td>
    </tr>
  </table>
</div>
<?php
$_SESSION['isSaved'] = true;
$_SESSION['advanceInvoice'] = true;
include 'footer.php';
$conn->close();
?>
