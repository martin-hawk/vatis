<?php
require_once ('db.php');
include ('functions.php');
session_start();
page_protect($conn, '1', $_SESSION['user_company']);

$queryCompany = "SELECT * FROM classifier_company, classifier_country WHERE classifier_company.Country = classifier_country.CountryCode AND classifier_country.Language='LT' AND CompanyCode='" . $_SESSION['user_company'] .
    "'";
$resultCompany = $conn->query($queryCompany);

$company = $resultCompany->fetch_assoc();

$queryBank = "SELECT * FROM classifier_company_bank WHERE CompanyCode='" . $_SESSION['user_company'] .
    "'";
$resultBank = $conn->query($queryBank);

include 'header.php';
?>

<div class="container">
  <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" name="companySettings">
    <table align="center" width="40%" style="margin-top:10px; margin-bottom:4em;">
      <tr>
        <td>Įmonės ID</td>
        <td><input type="text" name="companyCode" size="32" value="<?php echo $company['CompanyCode']; ?>"  disabled/></td>
      </tr>
      <tr>
        <td>Įmonės kodas</td>
        <td><input type="text" name="code" size="32" value="<?php echo $company['Code']; ?>"  disabled/></td>
      </tr>
      <tr>
        <td>PVM kodas</td>
        <td><input type="text" name="codeVAT" size="32" value="<?php echo $company['VATCode']; ?>"  disabled/></td>
      </tr>
      <tr>
        <td>Pavadinimas</td>
        <td><input type="text" name="companyname" size="32" value="<?php echo
htmlspecialchars($company['Name']); ?>"  disabled/></td>
      </tr>
      <tr>
        <td>Adresas</td>
        <td><input type="text" name="street" size="32" value="<?php echo $company['Street']; ?>"  disabled/></td>
      </tr>
      <tr>
        <td>Namo numeris</td>
        <td><input type="text" name="house" size="32" value="<?php echo $company['House']; ?>"  disabled/></td>
      </tr>
      <tr>
        <td>Butas</td>
        <td><input type="text" name="flat" size="32" value="<?php echo $company['Flat']; ?>"  disabled/></td>
      </tr>
      <tr>
        <td>Pašto kodas</td>
        <td><input type="text" name="postalCode" size="32" value="<?php echo $company['PostalCode']; ?>"  disabled/></td>
      </tr>
      <tr>
        <td>Miestas, gyvenvietė</td>
        <td><input type="text" name="city" size="32" value="<?php echo $company['City']; ?>"  disabled/></td>
      </tr>
      <tr>
        <td>Administracinis vienetas</td>
        <td><input type="text" name="postalCode" size="32" value="<?php echo $company['District']; ?>"  disabled/></td>
      </tr>
      <tr>
        <td>Šalis</td>
        <td><input type="text" name="country" size="32" value="<?php
			
			echo $company['CountryName']; ?>"  disabled/></td>
      </tr>
      <tr>
        <td>Vadovo pareigos</td>
        <td><input type="text" name="director_position" size="32" value="<?php echo
$company['DirectorPosition']; ?>"  disabled/></td>
      </tr>
      <tr>
        <td>Vadovas</td>
        <td><input type="text" name="director" size="32" value="<?php echo $company['Director']; ?>"  disabled/></td>
      </tr>
      <tr>
        <td>Buhalterio pareigos</td>
        <td><input type="text" name="accountant" size="32" value="<?php echo $company['AccountantPosition']; ?>"  disabled/></td>
      </tr>
      <tr>
        <td>Buhalteris</td>
        <td><input type="text" name="accountant" size="32" value="<?php echo $company['Accountant']; ?>"  disabled/></td>
      </tr>
      <?php while ($bank = $resultBank->fetch_assoc()) { ?>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>Bankas</td>
        <td><input type="text" name="bank" size="32" value="<?php echo
    htmlspecialchars($bank['BankName']); ?>"  disabled/></td>
      </tr>
      <tr>
        <td>Banko adresas</td>
        <?php
    if (!empty($bank['Street'])) {
        $bankAddress = $bank['Street'] . ' ' . $bank['House'];

        if (isset($bank['Flat']))
            $bankAddress .= ' ' . $bank['Flat'];

        $bankAddress .= ', ' . $bank['PostalCode'] . ' ' . $bank['City'];

        $queryCountry = "SELECT * FROM classifier_country WHERE CountryCode='" . $bank['Country'] .
            "'";
        $resultCountry = $conn->query($queryCountry);

        $country = $resultCountry->fetch_assoc();

        $bankAddress .= ', ' . $country['CountryName'];
    } else
        $bankAddress = '';
?>
        <td><input type="text" name="bank_address" size="32" value="<?php echo
    isset($bankAddress) ? $bankAddress : ''; ?>"  disabled/></td>
      </tr>
      <tr>
        <td>SWIFT/BIC</td>
        <td><input type="text" name="swift" size="32" value="<?php echo $bank['SWIFT']; ?>"  disabled/></td>
      </tr>
      <tr>
        <td>IBAN</td>
        <td><input type="text" name="iban" size="32" value="<?php echo $bank['IBAN']; ?>"  disabled/></td>
      </tr>
      <tr>
        <td>Valiuta</td>
        <?php
    $queryCurrency = "SELECT * FROM classifier_currency WHERE CurrencyCode='" . $bank['AccountCurrency'] .
        "'";
    $resultCurrency = $conn->query($queryCurrency);

    $currency = $resultCurrency->fetch_assoc();

    $accountCurrency = $currency['CurrencyDescription'];
?>
        <td><input type="text" name="currency" size="32" value="<?php echo $accountCurrency; ?>"  disabled/></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <?php } ?>
      <!--<tr><td align="center" colspan="2"><a style="color: Black;" href="javascript:void(0);" onClick="companySettings.submit();"><i class="fa fa-floppy-o fa-2x" aria-hidden="true"></i></a></td></tr>-->
    </table>
  </form>
</div>
<?php
include 'footer.php';
$conn->close();
?>
