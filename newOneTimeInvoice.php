<?php
require_once ('db.php');
require_once ('functions.php');
require ('fpdf/fpdf.php');
require_once ('./js/calendar/tc_calendar.php');
session_start();

page_protect($conn, '1', $_SESSION['user_company']);

include 'header.php';
?>

<div class="container">
  <form name="setSettings" method="post" action="phpSetOneTimeSettings.php">
    <table align="center" width="50%" style="margin-top:20px;">
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
} else {
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
} else {
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
        <td>Pirkėjo pavadinimas / vardas, pavardė<a href="#" style="color: Red; text-decoration:none;" title="Privaloma užpildyti"><strong>*</strong></a></td>
      <td><input name="customer_name" type="text" value="<?php if (isset($_SESSION['customer_name'])) {
        echo htmlspecialchars($_SESSION['customer_name']);
    } ?>" tabindex="1" /></td>
      </tr>
      <tr>
        <td>Įmonės / asmens kodas<a href="#" style="color: Red; text-decoration:none;" title="Privaloma užpildyti"><strong>*</strong></a> <a href="#" style="color: Black;" title="Vietoje asmens kodo galima įvesti asmens gimimo datą 1986-05-07 formatu 19860507"><i class="fa fa-question-circle-o" aria-hidden="true"></i></a></td>
        <td><input name="customer_code" type="text" value="<?php if (isset
    ($_SESSION['customer_code'])) {
        echo $_SESSION['customer_code'];
    } ?>" tabindex="2" /></td>
      </tr>
      <tr>
        <td>PVM kodas<a href="#" style="color: Black; text-decoration:none;" title="Laukas privalomas tik PVM mokėtojams"><i class="fa fa-question-circle-o" aria-hidden="true"></i></a></td>
        <td><input name="customer_vat" type="text" value="<?php if (isset
    ($_SESSION['customer_vat'])) {
        echo $_SESSION['customer_vat'];
    } ?>" tabindex="3" /></td>
      </tr>
              <td>Šalis<a href="#" style="color: Red; text-decoration:none;" title="Privaloma užpildyti"><strong>*</strong></a></td>
              <td><select name="customer_country" id="country" style="width:225px;" tabindex="4">
                <?php
				  $countryresults = $conn->query("SELECT * FROM classifier_country WHERE Language='LT'");
					unset($which);
		
					if (isset($_SESSION['customer_country'])) $which = $_SESSION['customer_country'];
					else $which = "LT";
			
					while($country = $countryresults->fetch_array()) {
						if ($country['CountryCode'] == $which) {
							echo '<option value="' . $country['CountryCode'] . '" selected>' . $country['CountryName'] . '</option>';
						}
						else {
							echo '<option value="' . $country['CountryCode'] . '">' . $country['CountryName'] . '</option>';
							}
					}
				  ?>
              </select></td>
            </tr>
            <tr>
              <td>Administracinis vienetas</td>
              <td><input id="administrative_area_level_1" name="customer_district" type="text" placeholder="Vilniaus m. sav." style="width:225px;" value="<?php if (isset($_SESSION['customer_district'])) echo $_SESSION['customer_district']; ?>" tabindex="5" /></td>
            </tr>
            <tr>
              <td>Miestas<a href="#" style="color: Red; text-decoration:none;" title="Privaloma užpildyti"><strong>*</strong></a></td>
              <td><input id="locality" name="customer_city" type="text" placeholder="Vilnius" style="width:225px;" value="<?php if (isset($_SESSION['customer_city'])) echo $_SESSION['customer_city']; ?>" tabindex="6" /></td>
            </tr>
            <tr>
              <td>Gatvė<a href="#" style="color: Red; text-decoration:none;" title="Privaloma užpildyti"><strong>*</strong></a></td>
              <td><input id="route" name="customer_street" type="text" placeholder="M. K. Čiurlionio g." style="width:225px;" value="<?php if (isset($_SESSION['customer_street'])) echo $_SESSION['customer_street']; ?>" tabindex="7" /></td>
            </tr>
            <tr>
              <td>Namo numeris<a href="#" style="color: Red; text-decoration:none;" title="Privaloma užpildyti"><strong>*</strong></a></td>
              <td><input id="street_number" name="customer_house" type="text" placeholder="84" style="width:225px;" value="<?php if (isset($_SESSION['customer_house'])) echo $_SESSION['customer_house']; ?>" tabindex="8" /></td>
            </tr>
            <tr>
              <td>Buto numeris</td>
              <td><input id="flat_number" name="customer_flat" type="text" style="width:225px;" value="<?php if (isset($_SESSION['customer_flat'])) echo $_SESSION['customer_flat']; ?>" tabindex="9" /></td>
            </tr>
            <tr>
              <td>Pašto kodas</td>
              <td><input id="postal_code" name="customer_postalCode" type="text" placeholder="03104" style="width:225px;" value="<?php if (isset($_SESSION['customer_postalCode'])) echo $_SESSION['customer_postalCode']; ?>" tabindex="10" /></td>
      <tr>
        <td>Valiutos kodas<a href="#" style="color: Red; text-decoration:none;" title="Privaloma užpildyti"><strong>*</strong></a> <a href="#" style="color: Black;" title="Nurodoma PVM sąskaitos faktūros valiuta"><i class="fa fa-question-circle-o" aria-hidden="true"></i></a></td>
        <td><select name="searchTerm" id="currency">
            <?php
if (isset($_SESSION['currency'])) {
    $eur = ($_SESSION['currency'] == 'EUR') ? ' selected>euras</option>' :
        '>euras</option>';
    $usd = ($_SESSION['currency'] == 'USD') ? ' selected>JAV doleris</option>' :
        '>JAV doleris</option>';
    $gbp = ($_SESSION['currency'] == 'GBP') ? ' selected>svaras sterlingų</option>' :
        '>svaras sterlingų</option>';
    echo '<option value="EUR"' . $eur;
    echo '<option value="USD"' . $usd;
    echo '<option value="GBP"' . $gbp;
} else
    echo '
		          <option value="EUR" selected>euras</option>
                  <option value="USD">JAV doleris</option>
                  <option value="GBP">svaras sterlingų</option>';
?>
          </select></td>
      </tr>
      <tr>
        <td>PVM tarifas<a href="#" style="color: Red; text-decoration:none;" title="Privaloma užpildyti"><strong>*</strong></a> <a href="#" style="color: Black;" title="PVM kodas ir PVM tarifas"><i class="fa fa-question-circle-o" aria-hidden="true"></i></a></td>
        <td><select name="newVATRate" id="newVATRate">
            <?php
$vatrates = $conn->query("SELECT * FROM classifier_vat_rate");
unset($which);

if (isset($_SESSION['vatRate']))
    $which = $_SESSION['vatRate'];
else
    $which = "PVM1";

while ($rate = $vatrates->fetch_array()) {

    if ($rate['VATRate'] == $which) {
        echo '<option value="' . $rate['VATRate'] . '" selected>' . $rate['VATRate'] .
            ' (' . $rate['VATPercentage'] . ')</option>';
    } else {
        echo '<option value="' . $rate['VATRate'] . '">' . $rate['VATRate'] . ' (' . $rate['VATPercentage'] .
            ')</option>';
    }
}
?>
          </select>
          <a href="#" style="color: Black;" title="Lietuvoje fiziniams ir juridiniams asmenims taikomas PVM1 (21%)"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAKAAAACgCAMAAAC8EZcfAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAACKFBMVEUAAAAAOIPKnhDFmhWYjyLZng/ImhSbJid0ODCnICWYJygAv22aISW3lRy6lhjNmxPWnRDWnRDWnRC9mBjLmhPQnBLRnBHUnBHWnRDWnRDFmRXPmxLUnRHFmRXQnBLWnRC7lxjPmxLLmhPUnRHQnBLRnBHUnBGiIiWgIyafIyabJSeiIiWQKyqfJCaXKCigIyakISWXKCifJCaiIiWSKyqbJSefIyagIyaiIiWkISWkISWMMCyOKyqdJCekISWkISWkISXWnRDWnRDWnRDWnRDWnRDWnRDWnRDWnRDWnRDWnRDWnRDWnRDWnRDWnRDWnRDZnxDboBDboBDboBDWnRDiphH0shL3tBP3tBP3tBPWnRD0shL9uRP8uBP8uBPboBDWnRDWnRDYnhDXnRDcoRD5tRL+uRP+uRO9lRW9lRXBmBXarBjfrxnfrxkZYjUZYjUZZDYdcT0dcz4dcz4AWToAWToAWzwAZ0MAakQAaUQAWjoAWjoAXDsAaEMAakQAakQAWjoAWjoAXDsAaEMAa0QAa0QTUzgTUzcTVTkWYEAWYkEWYkGRKCeRKCeUKCinLi6rLy+rLy+lISWlISWpISa/JivDJyzCJiykISWkISWoIia9JivBJyzBJyzBJyyoIiakISWoIia9JiukISWmISa7JivCJyykISWkISWkISWuIye7Jiu9Jiu9JiukISWkISWkISWkISWkISWkISWkISWkISWkISX///8CVIuQAAAAQXRSTlMAAAAAAAAAAAAAAAAAAhQdHh4eCUibz+Hi4R6c8x65/wmbUvCv6f7+6a9S8AmbHrn/HpzzCUicz+Hi4QIUHR4eHhQrZgwAAAABYktHRLfdADtnAAAAB3RJTUUH4QgSEAIM/Ao7iwAAAedJREFUeNrt3E1rE1EYhuHnOXM66ccixY+6CAY3EnFRf4q/p6CI4B8URAyuu6i1JaGkjWmS10XGj4J7X+N9bybM6mLmzAk5h4xERERERERbnCXZB7btRKyIiJhFSJaaQ2fzdcKYrOTqh/bTlMDPEefh9pE98jrh6CsxjjjzE4+8TPqA1BhHKaVk9WlZSilluMo7x6yGpbobf22yaWYhSWu7dq69TDNNRERzI0l2PZ5srl+bDLhoF5J8XKeWpP51tlt82z+X5GndnLiqyzbVTH3bv+xmw83hfrIvEvnqwR3gRTqgv94B5g0gQIAAAQIECBAgQIAAAQL8n4H1cfdDPuE2xAbYAgT4d4FMMwABAgQIECBAgAABAgQIECBAgAABAgS4tUAW0QEC/GNesU8CECBAgAABAgQIECBAgAAB/kPAnVys9U9QB+yVXqprWXqlt/nU/ce9ses8EXD3x9KMan8qSTu2W18m4d37tbjVbz68nEma9+vNt3mWl2osl+u95kKSjl5XN5Kks8PIc4sX2p9PuqFX7cGpJE1SPcWzzWEQLj5J9gKD3/OJSykeZvUNXYp7I/tdnCbkDfwqYuxdP7PfxOpLMt5R47cRn8LS/vO0r7X6eC1LOvCLlNsQ72MmIiIiIiLa7r4Da626AJeqHisAAAAldEVYdGRhdGU6Y3JlYXRlADIwMTctMDgtMThUMTY6MDE6NDgrMDA6MDCHOT0OAAAAJXRFWHRkYXRlOm1vZGlmeQAyMDE3LTA4LTE4VDE2OjAxOjQ4KzAwOjAw9mSFsgAAAABJRU5ErkJggg==" width="30px" /></a> <a href="#" style="color: Black;" title="Europos Sąjungoje juridiniams asmenims taikomas PVM15 (), fiziniams asmenims taikomas PVM1 (21%)"><img src="data:image/png;base64,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" width="30px" /></a> <a href="#" style="color: Black;" title="Už Europos Sąjungos ribų fiziniams ir juridiniams asmenims taikomas PVM15 ()"><img src="data:image/png;base64,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" width="30px" /></a></td>
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
$companyresult = $conn->query('SELECT * FROM classifier_company, classifier_country WHERE classifier_company.Country = classifier_country.CountryCode AND classifier_country.Language="LT" AND CompanyCode="' .
    $_SESSION['user_company'] . '"');
$company = $companyresult->fetch_assoc();

$_SESSION["companyName"] = $company["Name"];
$_SESSION["company_code"] = $company["Code"];
$_SESSION["company_vat"] = $company["VATCode"];

$_SESSION["company_address1"] = $company["Street"] . " " . $company["House"];
if ($company["Flat"] != "") {
    $_SESSION["company_address1"] .= "-" . $company["Flat"];
}

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
if (!isset($_SESSION['invoiceNo'])) {
    $inoresult = $conn->query('SELECT * FROM document_sales_header WHERE CompanyCode="' .
        $_SESSION['user_company'] . '" ORDER BY SystemID DESC LIMIT 1');
    $inorow = $inoresult->fetch_assoc();
    $_SESSION['invoiceNo'] = $inorow['DocumentNo'] + 1;
}

$prefixes = $conn->query("SELECT * FROM classifier_counter WHERE CompanyCode='{$_SESSION['user_company']}' AND DocumentType='SD'");
$prefix = $prefixes->fetch_assoc();
$_SESSION['prefix'] = $prefix['Prefix'];

echo 'PVM sąskaita faktūra Serija ' . $_SESSION['prefix'] . ' Nr. ' . sprintf('%05d',
    $_SESSION['invoiceNo']);
?>
              </b></td>
            <td align="right" width="50%">
            Pirkėjas: <?php echo isset($_SESSION['customer_name']) ? $_SESSION['customer_name'] :
''; ?></td>
          </tr>
          <tr>
            <td>Išrašymo data: <?php echo empty($_SESSION['date']) ? "" : $_SESSION['date']; ?></td>
            <td align="right">Įm. kodas: <?php echo isset($_SESSION['customer_code']) ?
$_SESSION['customer_code'] : ''; ?></td>
          </tr>
          <tr>
            <td>Mokėjimo data: <?php echo empty($_SESSION['due_date']) ? "" : $_SESSION['due_date']; ?></td>
            <td align="right">PVM kodas: <?php echo isset($_SESSION['customer_vat']) ?
$_SESSION['customer_vat'] : ''; ?></td>
          </tr>
          <tr>
            <td align="right" colspan="2">Adresas: <?php echo isset($_SESSION['customer_address1']) ?
$_SESSION['customer_address1'] : ''; ?>
              <br/>
              <?php echo isset($_SESSION['customer_address2']) ? $_SESSION['customer_address2'] :
''; ?>
              <br/>
              <?php echo isset($_SESSION['customer_address3']) ? $_SESSION['customer_address3'] :
''; ?>
              <br/>
              <?php echo isset($_SESSION['customer_address4']) ? $_SESSION['customer_address4'] :
''; ?></td>
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
              <td><input type="text" name="newProduct" id="newProduct" autocomplete="off" tabindex="1" value="<?php echo (isset
($_SESSION['tempCode'])) ? $_SESSION['tempCode'] : ''; ?>"></td>
              <td><input type="text" name="newQuantity" id="newQuantity" style="text-align:right;" autocomplete="off"  tabindex="2" value="<?php echo (isset
($_SESSION['tempQuantity'])) ? $_SESSION['tempQuantity'] : ''; ?>"></td>
              <td><input type="text" name="newUOM" id="newUOM" autocomplete="off" readonly value="<?php echo (isset
($_SESSION['tempUOM'])) ? $_SESSION['tempUOM'] : ''; ?>"></td>
              <td><input type="text" name="newPrice" id="newPrice" style="text-align:right;" autocomplete="off"  tabindex="3" value="<?php echo (isset
($_SESSION['tempPrice'])) ? $_SESSION['tempPrice'] : ''; ?>"></td>
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
            <th>PVM, %</th>
            <th>PVM suma</th>
            <th>Suma</th>
            <th>Suma (su PVM)</th>
            <th>&nbsp;</th>
          </tr>
          <?php
if (isset($_SESSION['count'])) {
    for ($i = 0; $i <= $_SESSION['count']; $i++) { ?>
          <tr>
            <td><?php echo ($i + 1); ?></td>
            <td><div> <?php echo $_SESSION['productCode'][$i]; ?> </div></td>
            <td><div align="right"> <?php echo $_SESSION['quantity'][$i]; ?> </div></td>
            <td><div> <?php echo $_SESSION['uom'][$i]; ?> </div></td>
            <td><div align="right"> <?php echo $_SESSION['documentPrice'][$i]; ?> </div></td>
            <td><div align="right"><?php echo $_SESSION['vatPercentage']; ?></div></td>
            <td><div align="right"> <?php echo $_SESSION['documentVAT'][$i]; ?> </div></td>
            <td><div align="right"> <?php echo $_SESSION['documentNet'][$i]; ?> </div></td>
            <td><div align="right"> <?php echo $_SESSION['documentGross'][$i]; ?> </div></td>
            <td align="right"><a style="text-decoration: none; color: Black;" title="Ištrinti eilutę" href="phpDeleteInvoiceRow.php?delete=<?php echo ($i +
1); ?>"><i class="fa fa-trash-o fa-2x" aria-hidden="true"></i> </a></td>
          </tr>
          <?php }
    ;
}
; ?>
        </table></td>
    </tr>
    <!-- Totals ********************************************************************* -->
    <tr>
      <td><table id="totals" name="totals" align="right" style="margin-top: 0.25em">
          <tr>
            <td align="right">Iš viso</td>
            <td align="right"><div id="netTotal" align="right">
                <?php if (isset($_SESSION['documentNetTotal'])) {
    echo $_SESSION['documentNetTotal'];
} else
    echo '0.00';
echo ' ';
if (isset($_SESSION['currency']))
    echo $_SESSION['currency']; ?>
              </div></td>
          </tr>
          <tr>
            <td align="right">PVM suma</td>
            <td align="right"><div align="right">
                <?php if (isset($_SESSION['documentVATTotal'])) {
    echo $_SESSION['documentVATTotal'];
} else
    echo '0.00';
echo ' ';
if (isset($_SESSION['currency']))
    echo $_SESSION['currency']; ?>
              </div></td>
          </tr>
          <tr>
            <td align="right">Iš viso (su PVM)</td>
            <td align="right"><div align="right">
                <?php if (isset($_SESSION['documentGrossTotal'])) {
    echo $_SESSION['documentGrossTotal'];
} else
    echo '0.00';
echo ' ';
if (isset($_SESSION['currency']))
    echo $_SESSION['currency']; ?>
              </div></td>
          </tr>
          <?php
if (isset($_SESSION['currency']) && $_SESSION['currency'] != "EUR") { ?>
        <tr>
          <td align="right" style="color:Grey;"><i>Iš viso, EUR</i></td>
          <td align="right" style="color:Grey;"><i>
		 <?php if (isset($_SESSION['localNetTotal']))
        echo $_SESSION['localNetTotal']; ?> EUR</i></td> 
        </tr>
		<tr>
          <td align="right" style="color:Grey;"><i>PVM suma, EUR</i></td>
          <td align="right" style="color:Grey;"><i>
		 <?php if (isset($_SESSION['localVATTotal']))
        echo $_SESSION['localVATTotal']; ?> EUR</i></td>
        </tr>
        <tr>
          <td align="right" style="color:Grey;"><i>Iš viso (su PVM), EUR</i></td>
          <td align="right" style="color:Grey;"><i>
		 <?php if (isset($_SESSION['localGrossTotal']))
        echo $_SESSION['localGrossTotal']; ?> EUR</i></td>
        </tr>
	<?php }
?>
        </table></td>
    </tr>
    <tr>
      <td align="center"><table>
          <tr>
            <td><form action="phpSaveOneTimeInvoice.php" method="post" name="saveInvoice">
                <a style="color: Black; margin:10px;" title="Saugoti" href="javascript:void(0);" onClick="saveInvoice.submit();"><i class="fa fa-floppy-o fa-2x" aria-hidden="true"></i></a>
              </form></td>
            <td><form action="phpResetInvoice.php" method="post" name="resetInvoice">
                <a style="color: Black; margin:10px;" title="Išvalyti" href="javascript:void(0);" onClick="resetInvoice.submit();"><i class="fa fa-trash fa-2x" aria-hidden="true"></i></a>
              </form></td>
            <td><form action="phpConvertPDF.php" method="post" target="_blank" name="toPDF">
                <input type="hidden" name="language" value="lt" />
                <a style="color: Black; margin:10px; text-decoration:none;" title="Suformuoti PDF lietuvių kalba" href="javascript:void(0);" onClick="toPDF.submit();"><span style="font-size:30px;">LT</span><i class="fa fa-file-pdf-o fa-2x" aria-hidden="true"></i></a>
              </form></td>
            <td><form action="phpConvertPDF.php" method="post" target="_blank" name="toPDFen">
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
include 'footer.php';
$conn->close();
?>
