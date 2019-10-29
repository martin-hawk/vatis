<?php
require_once ('db.php');
require_once ('functions.php');
session_start();
page_protect($conn, '1', $_SESSION['user_company']);

include 'header.php';

if (isset($_GET['pid']) && !isset($_SESSION['pid'])) {

$getProducts = "SELECT * FROM classifier_product WHERE CompanyCode='{$_SESSION['user_company']}' AND ProductCode='{$_GET['pid']}'";

$products = $conn->query($getProducts);
$product = $products->fetch_assoc();

$_SESSION['pid'] = $_GET['pid'];
$_SESSION['productCode'] = $product['ProductCode'];
$_SESSION['nameLT'] = $product['Description'];
$_SESSION['nameEN'] = $product['ForeignDescription'];
$_SESSION['productType'] = $product['GoodsServicesID'];
$_SESSION['uom'] = $product['UnitOfMeasure'];
$_SESSION['defaultQuantity'] = $product['DefaultQuantity'];
$_SESSION['defaultPrice'] = $product['DefaultPrice'];
}
?>

<div class="container">
  <form name="editProduct" method="post" action="phpSaveEditProduct.php">
    <table width="600px" align="center" cellpadding="5px" style="margin-top:10px;">
      <tr>
        <td colspan="2" align="center"><?php if (isset($_SESSION['info'])) {
    echo '<div style="border: 3px solid #090; margin-bottom: 1em; padding: 10px; border-radius: 15px 40px;">';
  				echo $_SESSION['info'];
				echo '</div>';
} ?></td>
      </tr>
      <tr>
        <td>Produkto kodas<a href="#" style="color: Red; text-decoration:none;" title="Privaloma užpildyti"><strong>*</strong></a></td>
        <td><input name="productCode" type="text" size="32" placeholder="ART16" value="<?php if (isset($_SESSION['productCode'])) echo $_SESSION['productCode'];
?>" tabindex="1" disabled /></td>
      </tr>
      <tr>
        <td>Aprašymas (lietuvių kalba)<a href="#" style="color: Red; text-decoration:none;" title="Privaloma užpildyti"><strong>*</strong></a> <a href="#" style="color: Black;" title="Aprašymas bus naudojamas užpildyti PVM sąskaitos faktūros šabloną"><i class="fa fa-question-circle-o" aria-hidden="true"></i></a></td>
        <td><input name="descriptionLT" type="text" size="32" placeholder="Straipsnis" value="<?php if (isset($_SESSION['nameLT'])) echo htmlspecialchars($_SESSION['nameLT']);
?>" tabindex="2" /></td>
      </tr>
      <tr>
        <td>Aprašymas (anglų kalba)<a href="#" style="color: Red; text-decoration:none;" title="Privaloma užpildyti"><strong>*</strong></a> <a href="#" style="color: Black;" title="Aprašymas (anglų kalba) bus naudojamas užpildyti PVM sąskaitos faktūros šabloną anglų kalba"><i class="fa fa-question-circle-o" aria-hidden="true"></i></a></td>
        <td><input name="descriptionEN" type="text" size="32" placeholder="Article" value="<?php if (isset($_SESSION['nameEN'])) echo htmlspecialchars($_SESSION['nameEN']);
?>" tabindex="3" /></td>
      </tr>
      <tr>
        <td>Produkto tipas<a href="#" style="color: Red; text-decoration:none;" title="Privaloma užpildyti"><strong>*</strong></a></td>
        <td><select name="productType" tabindex="4">
            <?php
		  	if (isset($_SESSION['productType']) && $_SESSION['productType'] == 'PR')
				echo '<option value="PS">Paslauga</option>
                  	  <option value="PR" selected>Prekė</option>';
			else echo '<option value="PS" selected>Paslauga</option>
                  		 <option value="PR">Prekė</option>';
          ?>
          </select></td>
      </tr>
      <tr>
        <td>Matavimo vienetas<a href="#" style="color: Red; text-decoration:none;" title="Privaloma užpildyti"><strong>*</strong></a> <a href="#" style="color: Black;" title="Matavimo vienetas bus naudojamas užpildyti matavimo vienetų dalį PVM sąskaitos faktūros šablone"><i class="fa fa-question-circle-o" aria-hidden="true"></i></a></td>
        <td><select name="uom" tabindex="5">
            <?php
        $uomresults = $conn->query("SELECT * FROM classifier_uom");
		unset($which);
		
		if (isset($_SESSION['uom'])) $which = $_SESSION['uom'];
			else $which = "vnt";
			
		while($uom = $uomresults->fetch_array()) {
			
			if ($uom['UOMCode'] == $which) {
				echo '<option value="' . $uom['UOMCode'] . '" selected>' . $uom['UOMDescription'] . '</option>';
			}
			else {
				echo '<option value="' . $uom['UOMCode'] . '">' . $uom['UOMDescription'] . '</option>';
			}
			}
			?>
          </select></td>
      </tr>
      <tr>
        <td>Numatytasis kiekis <a href="#" style="color: Black;" title="Numatytasis kiekis bus automatiškai įkeliamas į PVM sąskaitos faktūros šabloną, pridedant naują eilutę"><i class="fa fa-question-circle-o" aria-hidden="true"></i></a></td>
        <td><input name="defaultQuantity" type="text" size="32" placeholder="1" value="<?php if (isset($_SESSION['defaultQuantity'])) echo $_SESSION['defaultQuantity'];
?>" tabindex="6" /></td>
      </tr>
      <tr>
        <td>Numatytoji kaina, € <a href="#" style="color: Black;" title="Numatytoji kaina bus automatiškai įkeliama į PVM sąskaitos faktūros šabloną, pridedant naują eilutę"><i class="fa fa-question-circle-o" aria-hidden="true"></i></a></td>
        <td><input name="defaultPrice" type="text" size="32" placeholder="25.3600" value="<?php if (isset($_SESSION['defaultPrice'])) echo $_SESSION['defaultPrice'];
?>" tabindex="7" /></td>
      </tr>
      <tr>
        <td align="center" colspan="2"><a style="color: Black;" title="Saugoti" href="javascript:void(0);" onClick="editProduct.submit();"><i class="fa fa-floppy-o fa-2x" aria-hidden="true"></i></a></td>
      </tr>
    </table>
  </form>
</div>
<?php include 'footer.php';
?>