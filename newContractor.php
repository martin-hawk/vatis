<?php
require_once ('db.php');
require_once ('functions.php');
session_start();

page_protect($conn, '1', $_SESSION['user_company']);

include 'header.php';
?>

<div class="container">
  <form name="newContractor" method="post" action="phpSaveContractor.php">
    <table align="center" cellpadding="5px" style="margin-top:10px; margin-bottom:4em;">
      <tr>
        <td colspan="2" align="center"><?php if (isset($_SESSION['info'])) {
				echo '<div style="border: 3px solid #090; margin-bottom: 1em; padding: 10px; border-radius: 15px 40px;">';
  				echo $_SESSION['info'];
				echo '</div>';
			} ?></td>
        <td rowspan="18" width="600px"><iframe height="600px" width="100%" src="http://rekvizitai.vz.lt/imoniu-paieska/#divSearchForm" name="iframe_vz"></iframe></td>
      </tr>
      <tr>
        <td>Įmonės pavadinimas / vardas, pavardė<a href="#" style="color: Red; text-decoration:none;" title="Privaloma užpildyti"><strong>*</strong></a></td>
        <td><input name="name" type="text" placeholder="Pavadinimas" value="<?php if (isset($_SESSION['name'])) {
        echo htmlspecialchars($_SESSION['name']);
    } ?>" tabindex="1" /></td>
      </tr>
      <tr>
        <td>Įmonės / asmens kodas<a href="#" style="color: Red; text-decoration:none;" title="Privaloma užpildyti"><strong>*</strong></a> <a href="#" style="color: Black;" title="Vietoje asmens kodo galima įvesti asmens gimimo datą 1986-05-07 formatu 19860507"><i class="fa fa-question-circle-o" aria-hidden="true"></i></a></td>
        <td><input name="code" type="text" placeholder="301325685" value="<?php if (isset
    ($_SESSION['code'])) {
        echo $_SESSION['code'];
    } ?>" tabindex="2" /></td>
      </tr>
      <tr>
        <td>PVM kodas <a href="#" style="color: Black; text-decoration:none;" title="Laukas privalomas tik PVM mokėtojams"><i class="fa fa-question-circle-o" aria-hidden="true"></i></a></td>
        <td><input name="vatCode" type="text" placeholder="LT100011093810" value="<?php if (isset
    ($_SESSION['vatCode'])) {
        echo $_SESSION['vatCode'];
    } ?>" tabindex="3" /></td>
      </tr>
      <tr>
        <td colspan="2">Adresas<a href="#" style="color: Red; text-decoration:none;" title="Privaloma užpildyti"><strong>*</strong></a> <a href="#" style="color: Black; text-decoration:none;" title="Užpildyti žemiau esančius laukus"><i class="fa fa-question-circle-o" aria-hidden="true"></i></a></td>
        <!-- https://postit.lt/example/v2-adresu-ir-pasto-kodu-paieska/ --> 
      </tr>
      <tr>
        <td colspan="2"><table id="address">
            <tr>
              <td align="right">Šalis<a href="#" style="color: Red; text-decoration:none;" title="Privaloma užpildyti"><strong>*</strong></a></td>
              <td><select name="country" id="country" style="width:225px;" tabindex="5">
              	<?php
				  $countryresults = $conn->query("SELECT * FROM classifier_country WHERE Language='LT'");
					unset($which);
		
					if (isset($_SESSION['country'])) $which = $_SESSION['country'];
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
              </select>
            </tr>
            <tr>
              <td align="right">Administracinis vienetas</td>
              <td><input id="administrative_area_level_1" name="district" type="text" placeholder="Vilniaus m. sav." style="width:225px;" value="<?php if (isset($_SESSION['district'])) echo $_SESSION['district']; ?>" tabindex="6" /></td>
            </tr>
            <tr>
              <td align="right">Miestas<a href="#" style="color: Red; text-decoration:none;" title="Privaloma užpildyti"><strong>*</strong></a></td>
              <td><input id="locality" name="city" type="text" placeholder="Vilnius" style="width:225px;" value="<?php if (isset($_SESSION['city'])) echo $_SESSION['city']; ?>" tabindex="7" /></td>
            </tr>
            <tr>
              <td align="right">Gatvė<a href="#" style="color: Red; text-decoration:none;" title="Privaloma užpildyti"><strong>*</strong></a></td>
              <td><input id="route" name="street" type="text" placeholder="M. K. Čiurlionio g." style="width:225px;" value="<?php if (isset($_SESSION['street'])) echo $_SESSION['street']; ?>" tabindex="8" /></td>
            </tr>
            <tr>
              <td align="right">Namo numeris<a href="#" style="color: Red; text-decoration:none;" title="Privaloma užpildyti"><strong>*</strong></a></td>
              <td><input id="street_number" name="house" type="text" placeholder="84" style="width:225px;" value="<?php if (isset($_SESSION['house'])) echo $_SESSION['house']; ?>" tabindex="9" /></td>
            </tr>
            <tr>
              <td align="right">Buto numeris</td>
              <td><input id="flat_number" name="flat" type="text" style="width:225px;" value="<?php if (isset($_SESSION['flat'])) echo $_SESSION['flat']; ?>" tabindex="10" /></td>
            </tr>
            <tr>
              <td align="right">Pašto kodas</td>
              <td><input id="postal_code" name="postalCode" type="text" placeholder="03104" style="width:225px;" value="<?php if (isset($_SESSION['postalCode'])) echo $_SESSION['postalCode']; ?>" tabindex="11" /></td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td>Ar juridinis asmuo? <a href="#" style="color: Black; text-decoration:none;" title="Laukas pažymimas, jeigu tai įmonė (Svarbu dėl PVM apskaitos)"><i class="fa fa-question-circle-o" aria-hidden="true"></i></a></td>
        <td><input name="isLegal" value="1" type="checkbox"<?php if (isset($_SESSION['isLegal']) && $_SESSION['isLegal'] == 1) echo " checked "; ?> tabindex="12"></td>
      </tr>
      <tr>
        <td>Ar Lietuvos juridinis / fizinis asmuo? <a href="#" style="color: Black; text-decoration:none;" title="Laukas pažymimas, jeigu tai Lietuvoje registruota įmonė arba Lietuvos pilietis (Svarbu dėl PVM apskaitos)"><i class="fa fa-question-circle-o" aria-hidden="true"></i></a></td>
        <td><input name="isLT" value="1" type="checkbox"<?php if (isset($_SESSION['isLT']) && $_SESSION['isLT'] == 1) echo " checked "; ?> tabindex="13"></td>
      </tr>
      <tr>
        <td>Ar ES juridinis / fizinis asmuo? <a href="#" style="color: Black; text-decoration:none;" title="Laukas pažymimas, jeigu tai ES registruota įmonė arba ES šalies pilietis (Svarbu dėl PVM apskaitos)"><i class="fa fa-question-circle-o" aria-hidden="true"></i></a></td>
        <td><input name="isEU" value="1" type="checkbox"<?php if (isset($_SESSION['isEU']) && $_SESSION['isEU'] == 1) echo " checked "; ?> tabindex="14"></td>
      </tr>
      <tr>
        <td>Grupė<a href="#" style="color: Red; text-decoration:none;" title="Privaloma užpildyti"><strong>*</strong></a></td>
        <td>
		<select name="group" tabindex="15">
		<?php
		$groupresults = $conn->query("SELECT * FROM classifier_contractor_group WHERE CompanyCode='" . $_SESSION['user_company'] . "'");
		unset($which);
		
		if (isset($_SESSION['group'])) $which = $_SESSION['group'];
			else $which = "PIRK";
			
		while($group = $groupresults->fetch_array()) {
			
			if ($group['GroupID'] == $which) {
				echo '<option value="' . $group['GroupID'] . '" selected>' . $group['GroupDescription'] . '</option>';
			}
			else {
				echo '<option value="' . $group['GroupID'] . '">' . $group['GroupDescription'] . '</option>';
			}
			}
			?>
          </select></td>
      </tr>
      <tr>
        <td align="center" colspan="2"><a style="color: Black;" title="Saugoti" href="javascript:void(0);" onClick="newContractor.submit();"><i class="fa fa-floppy-o fa-2x" aria-hidden="true"></i></a></td>
      </tr>
    </table>
  </form>
</div>
<?php include ('footer.php'); ?>
