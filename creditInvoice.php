<?php
require_once ('db.php');
require_once ('functions.php');
require ('fpdf/fpdf.php');
require_once ('./js/calendar/tc_calendar.php');
session_start();

page_protect($conn, '1', $_SESSION['user_company']);

if (isset($_GET['sid']) && !isset($_SESSION['sid'])) {
    $queryHeaders = "SELECT * FROM document_sales_header WHERE SystemID = '" . $_GET['sid'] .
        "' AND CompanyCode='" . $_SESSION['user_company'] . "'";

    $headers = $conn->query($queryHeaders);
    $header = $headers->fetch_assoc();

    $_SESSION['sid'] = $_GET['sid'];
    $_SESSION['prefix'] = $header['InvoiceSeries'];
    $_SESSION['date'] = $header['DocumentDate'];
    $_SESSION['due_date'] = $header['DueDate'];
    $_SESSION['bankID'] = $header['BankID'];
    $_SESSION['currency'] = $header['CurrencyCode'];
    $_SESSION['exchangeRate'] = $header['CurrencyRate'];
    $_SESSION['invoiceNo'] = $header['DocumentNo'];
    $_SESSION['customer_id'] = $header['Customer'];
    $_SESSION['documentNetTotal'] = $header['DocumentTotalNet'];
    $_SESSION['localNetTotal'] = $header['LocalTotalNet'];
    $_SESSION['documentVATTotal'] = $header['DocumentTotalVAT'];
    $_SESSION['localVATTotal'] = $header['LocalTotalVAT'];
    $_SESSION['documentGrossTotal'] = $header['DocumentTotalGross'];
    $_SESSION['localGrossTotal'] = $header['LocalTotalGross'];
    
    $banks = $conn->query("SELECT * FROM classifier_company_bank WHERE BankID='{$_SESSION['bankID']}' AND CompanyCode='{$_SESSION['user_company']}'");
    $bank = $banks->fetch_assoc();
    
    $_SESSION['bankName'] = $bank['BankName'];
    $_SESSION['bankSWIFT'] = $bank['SWIFT'];
    $_SESSION['bankIBAN'] = $bank['IBAN'];

    $queryItems = "SELECT * FROM document_sales_items WHERE SystemID = '" . $_GET['sid'] .
        "' AND CompanyCode='" . $_SESSION['user_company'] . "'";

    $items = $conn->query($queryItems);
    $_SESSION['count'] = $items->num_rows - 1;

    while ($row = $items->fetch_assoc()) {
        $item[] = $row;
    }

    for ($n = 0; $n <= $_SESSION['count']; $n++) {
        $_SESSION['productCode'][$n] = $item[$n]['ProductCode'];
        $queryProducts = "SELECT * FROM classifier_product WHERE CompanyCode='" . $_SESSION['user_company'] .
            "' AND ProductCode = '" . $item[$n]['ProductCode'] . "'";
        $products = $conn->query($queryProducts);
        $product = $products->fetch_assoc();
        $_SESSION['productDescription'][$n] = $product['Description'];
        $_SESSION['productForeignDescription'][$n] = $product['ForeignDescription'];

        $_SESSION['uom'][$n] = $product['UnitOfMeasure'];
        $queryUOMs = "SELECT * FROM classifier_uom WHERE UOMCode = '" . $_SESSION['uom'][$n] .
            "'";
        $uoms = $conn->query($queryUOMs);
        $uom = $uoms->fetch_assoc();
        $_SESSION['uomDescription'][$n] = $uom['UOMDescription'];
        $_SESSION['uomForeignDescription'][$n] = $uom['ForeignUOMDescription'];

        $_SESSION['quantity'][$n] = $item[$n]['Quantity'];
        $_SESSION['documentPrice'][$n] = $item[$n]['DocumentPrice'];
        $_SESSION['localPrice'][$n] = $item[$n]['LocalPrice'];

        $_SESSION['vatRate'] = $item[0]['VATRate']; // set by first item's VAT rate since all items have the same VAT rate

        $_SESSION['documentVAT'][$n] = $item[$n]['DocumentVAT'];
        $_SESSION['localVAT'][$n] = $item[$n]['LocalVAT'];
        $_SESSION['documentNet'][$n] = $item[$n]['DocumentNet'];
        $_SESSION['localNet'][$n] = $item[$n]['LocalNet'];
        $_SESSION['documentGross'][$n] = $item[$n]['DocumentGross'];
        $_SESSION['localGross'][$n] = $item[$n]['LocalGross'];
    }

    // query information about the VAT rate
    $queryRates = "SELECT * FROM classifier_vat_rate WHERE VATRate = '" . $_SESSION['vatRate'] .
        "'";
    $rates = $conn->query($queryRates);
    $rate = $rates->fetch_assoc();
    $_SESSION['vatPercentage'] = $rate['VATPercentage'];
    $_SESSION['vatReason'] = $rate['Reason'];
    $_SESSION['vatForeignReason'] = $rate['ForeignReason'];

    $queryCustomers = "SELECT * FROM classifier_contractor WHERE ID = '" . $_SESSION['customer_id'] .
        "' AND CompanyCode='" . $_SESSION['user_company'] . "'";
    $customers = $conn->query($queryCustomers);
    $customer = $customers->fetch_assoc();
    $_SESSION['customer_name'] = htmlspecialchars($customer["Name"]);
    $_SESSION['customer_code'] = $customer["Code"];
    $_SESSION['customer_vat'] = $customer["VATCode"];
    $_SESSION['customer_address1'] = $customer["Street"] . " " . $customer["House"];
    if ($customer["Flat"] != "") {
        $_SESSION["customer_address1"] .= "-" . $customer["Flat"];
    }
    $_SESSION['customer_address2'] = $customer["PostalCode"] . " " . $customer["City"];
    $_SESSION["customer_address3"] = $customer["District"];
    $countries = $conn->query("SELECT *FROM classifier_country WHERE CountryCode='" .
        $customer["Country"] . "'");
    $country = $countries->fetch_assoc();
    $_SESSION["customer_address4"] = $country["CountryName"];

    $_SESSION['isSaved'] = false;
}

//if (isset($_POST['date'])) { echo ($_POST['date'] . " dienos kursas yra 1 EUR = " . $result . " " . $_POST['searchTerm']); }
include 'header.php';
?>

<div class="container" style="margin-bottom:4em;">
  <form name="setSettings" method="post" action="phpSetCreditSettings.php">
    <table align="center" width="50%" style="margin-top:20px;">
      <tr>
        <td align="center" colspan="2"><?php if (isset($_SESSION['info'])) {
    echo '<div align="center" style="border: 3px solid #090; margin-bottom: 1em; padding: 10px; border-radius: 15px 40px;">';
    echo $_SESSION['info'];
    echo '</div>';
} ?></td>
      </tr>
      <tr>
        <td align="right">Data<a href="#" style="color: Red; text-decoration:none;" title="Privaloma užpildyti"><strong>*</strong></a> <a href="#" style="color: Black;" title="Kreditinės sąskaitos išrašymo data"><i class="fa fa-question-circle-o" aria-hidden="true"></i></a></td>
        <td><div style="font-size:12px">
            <?php
$myCalendar = new tc_calendar("creditDate", true, false);
$myCalendar->setIcon("./js/calendar/images/iconCalendar.gif");
$myCalendar->startMonday(true);
if (isset($_SESSION['creditDate'])) {
    $date = DateTime::createFromFormat("Y-m-d", $_SESSION['creditDate']);
    $yyyy = $date->format("Y");
    $mm = $date->format("m");
    $dd = $date->format("d");
    $myCalendar->setDate($dd, $mm, $yyyy);
} else {
    $myCalendar->setDate(date('d'), date('m'), date('Y'));
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
$companyresult = $conn->query('SELECT * FROM classifier_company, classifier_country WHERE classifier_company.Country = classifier_country.CountryCode AND CompanyCode="' .
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
            <td align="right">
			  Tiekėjas: <?php echo $_SESSION['companyName']; ?><br/>
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
if (!isset($_SESSION['creditNo'])) {
    $creditNotes = $conn->query('SELECT * FROM document_sales_header WHERE CompanyCode="' . $_SESSION['user_company'] . '" AND DocumentType="DC" ORDER BY SystemID DESC LIMIT 1');
    $creditNote = $creditNotes->fetch_assoc();
    $_SESSION['creditNo'] = $creditNote['DocumentNo'] + 1;
}

	$cnPrefixes = $conn->query("SELECT * FROM classifier_counter WHERE CompanyCode='{$_SESSION['user_company']}' AND DocumentType='DC'");
	$cnPrefix = $cnPrefixes->fetch_assoc();
	$_SESSION['creditPrefix'] = $cnPrefix['Prefix'];

echo 'Kreditinė sąskaita Serija ' . $_SESSION['creditPrefix'] . ' Nr. ' . sprintf('%05d', $_SESSION['creditNo']);
?>
              </b></td>
            <td align="right" width="50%"> Pirkėjas: <?php echo $_SESSION['customer_name']; ?></td>
          </tr>
          <tr>
            <td> Išrašymo data: <?php echo (empty($_SESSION['creditDate'])) ? "" : $_SESSION['creditDate']; ?></td>
            <td align="right"> Įm. kodas: <?php echo $_SESSION['customer_code']; ?></td>
          </tr>
          <tr>
            <td></td>
            <td align="right"> PVM kodas: <?php echo $_SESSION['customer_vat']; ?></td>
          </tr>
          <tr>
            <td align="right" colspan="2"> Adresas: <?php echo $_SESSION['customer_address1'] . '<br/>';
echo $_SESSION['customer_address2'] . '<br/>';
echo $_SESSION['customer_address3'] . '<br/>';
echo $_SESSION['customer_address4']; ?></td>
          </tr>
          <tr>
            <td colspan="2"><div style="font-style:italic;">Kredituojama <?php echo $_SESSION['date']; ?> PVM sąskaita faktūra Serija <?php echo
$_SESSION['prefix']; ?> Nr. <?php echo
sprintf('%05d', $_SESSION['invoiceNo']); ?></div></td>
          </tr>
        </table></td>
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
          </tr>
          <?php
if (isset($_SESSION['count'])) {
    for ($i = 0; $i <= $_SESSION['count']; $i++) { ?>
          <tr>
            <td><?php echo ($i + 1); ?></td>
            <td><div><?php echo $_SESSION['productCode'][$i]; ?></div></td>
            <td><div align="right"><?php echo $_SESSION['quantity'][$i]; ?></div></td>
            <td><div><?php echo $_SESSION['uom'][$i]; ?></div></td>
            <td><div align="right"><?php echo $_SESSION['documentPrice'][$i]; ?></div></td>
            <td><div align="right"><?php echo $_SESSION['vatPercentage']; ?></div></td>
            <td><div align="right"><?php echo $_SESSION['documentVAT'][$i]; ?></div></td>
            <td><div align="right"><?php echo $_SESSION['documentNet'][$i]; ?></div></td>
            <td><div align="right"><?php echo $_SESSION['documentGross'][$i]; ?></div></td>
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
            <td align="right"><?php if (isset($_SESSION['language']) && $_SESSION['language'] ==
"en") {
    echo 'Net Total';
} else
    echo 'Iš viso'; ?></td>
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
if (isset($_SESSION['currency']) && $_SESSION['currency'] != "EUR") {
    $nT = 'Iš viso, EUR';
    $vT = 'PVM suma, EUR';
    $gT = 'Iš viso (su PVM), EUR';
    echo '
        <tr>
          <td align="right" style="color:Grey;"><i>' . $nT . '</i></td>
          <td align="right" style="color:Grey;"><i>';
    if (isset($_SESSION['localNetTotal']))
        echo $_SESSION['localNetTotal'];
    echo ' EUR</i></td>
        </tr>
		<tr>
          <td align="right" style="color:Grey;"><i>' . $vT . '</i></td>
          <td align="right" style="color:Grey;"><i>';
    if (isset($_SESSION['localVATTotal']))
        echo $_SESSION['localVATTotal'];
    echo ' EUR</i></td>
        </tr>
        <tr>
          <td align="right" style="color:Grey;"><i>' . $gT . '</i></td>
          <td align="right" style="color:Grey;"><i>';
    if (isset($_SESSION['localGrossTotal']))
        echo $_SESSION['localGrossTotal'];
    echo ' EUR</i></td>
        </tr>';
}
?>
        </table></td>
    </tr>
    <tr>
      <td align="center"><table>
          <tr>
            <td><form action="phpCreditInvoice.php" method="post" name="creditInvoice">
                <a style="color: Black; margin:10px;" title="Formuoti kreditinę sąskaitą" href="javascript:void(0);" onClick="creditInvoice.submit();"><i class="fa fa-floppy-o fa-2x" aria-hidden="true"></i></a>
              </form></td>
            <td><form action="phpCreditPDF.php" method="post" target="_blank" name="toPDFlt">
                <input type="hidden" name="language" value="lt" />
                <a style="color: Black; margin:10px; text-decoration:none;" title="Suformuoti PDF lietuvių kalba" href="javascript:void(0);" onClick="toPDFlt.submit();"><span style="font-size:30px;">LT</span><i class="fa fa-file-pdf-o fa-2x" aria-hidden="true"></i></a>
              </form></td>
            <td><form action="phpCreditPDF.php" method="post" target="_blank" name="toPDFen">
                <input type="hidden" name="language" value="en" />
                <a style="color: Black; margin:10px; text-decoration:none;" title="Suformuoti PDF anglų kalba" href="javascript:void(0);" onClick="toPDFen.submit();"><span style="font-size:30px;">EN</span><i class="fa fa-file-pdf-o fa-2x" aria-hidden="true"></i></a>
              </form></td>
          </tr>
        </table></td>
    </tr>
  </table>
</div>
<?php
include 'footer.php';
$conn->close();
?>
