<?php
require_once ('db.php');
require_once ('functions.php');
session_start();
page_protect($conn, '1', $_SESSION['user_company']);

include 'header.php';
?>

<div class="container">
  <form name="searchProduct" method="get" action="<?php $_SERVER['PHP_SELF'] ?>">
    <table width="100%" align="center" style="margin-top:20px;">
      <tr>
      <td align="center"><?php if (isset($_SESSION['info'])) {
				echo '<div style="width:600px; border: 3px solid Red; margin-bottom: 1em; padding: 10px; border-radius: 15px 40px;">'; //patikrinti kaip atrodo, kai nori pvz trinti
  				echo $_SESSION['info'];
				echo '</div>';
			} ?></td>
      </tr>
      <tr>
        <td align="center"><input name="q" type="text" size="50" title="Įveskite produkto pavadinimą ar jo dalį. Nenurodžius jokio kriterijaus bus išvestas visų produktų sąrašas" placeholder="Straipsnis" value="<?php if (isset($_GET['q'])) echo $_GET['q']; ?>" />
          <a style="color: Black;" href="javascript:void(0);" title="Ieškoti" onClick="searchProduct.submit();"><i class="fa fa-search fa-2x" aria-hidden="true"></i></a></td>
      </tr>
    </table>
  </form>
  <?php
$page_limit = 5;

	if (isset($_GET['q'])) {
    if ($_GET['q'] == '') {
        $sql = "SELECT * FROM classifier_product WHERE CompanyCode='" . $_SESSION['user_company'] . "'";
    } else {
        $sql = "SELECT * FROM classifier_product WHERE `Description` LIKE '%$_REQUEST[q]%' OR `ForeignDescription` LIKE '%$_REQUEST[q]%' AND CompanyCode='" . $_SESSION['user_company'] . "'";
    }

    $rs_total = $conn->query($sql);
    $total = $rs_total->num_rows;

    if (!isset($_GET['page'])) {
        $start = 0;
    } else {
        $start = ($_GET['page'] - 1) * $page_limit;
    }

    $rs_results = $conn->query($sql . " limit $start,$page_limit");
    $total_pages = ceil($total / $page_limit);
?>
  <form name="searchform" action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
    <table width="100%" align="center" class="table">
      <tr>
        <td colspan="8"><?php
    if ($total > $page_limit) {
        echo '<strong>Puslapiai:</strong> ';
        $i = 0;
        while ($i < $total_pages) {
            $page_no = $i+1;
            $qstr = preg_replace("&page=[0-9]+&", "", $_SERVER['QUERY_STRING']);
            echo "<a style='color: Black;' href=\"searchProduct.php?$qstr&page=$page_no\">$page_no</a> ";
            $i++;
        };
    } ?></td>
      </tr>
      <tr>
        <th>Produkto kodas</th>
        <th>Aprašymas (LT)</th>
        <th>Aprašymas (EN)</th>
        <th>Produkto tipas</th>
        <th>Mat. vienetas</th>
        <th>Numatytasis kiekis</th>
        <th>Numatytoji kaina</th>
        <th>Operacijos</th>
      </tr>
      <?php
    while ($rrows = $rs_results->fetch_assoc()) {
        echo '<tr><td>' . $rrows['ProductCode'] . '</td>';
        echo '<td>' . $rrows['Description'] . '</td>';
        echo '<td>' . $rrows['ForeignDescription'] . '</td>';
        echo '<td>' . $rrows['GoodsServicesID'] . '</td>';
        echo '<td>' . $rrows['UnitOfMeasure'] . '</td>';
        echo '<td>' . $rrows['DefaultQuantity'] . '</td>';
		echo '<td>' . $rrows['DefaultPrice'] . '</td>';
		echo '<td><a style="color:  Black;" title="Redaguoti" href="editProduct.php?pid=' . $rrows['ProductCode'] . '"><i class="fa fa-pencil fa-2x" aria-hidden="true"></i></a> <a style="color: Black;" title="Trinti" href="phpDeleteProduct.php?pid=' . $rrows['ProductCode'] . '"><i class="fa fa-trash fa-2x" aria-hidden="true"></i></a></td></tr>';
    }
	?>
      <tr>
        <td colspan="8"><input name="query_str" type="hidden" value="<?php $_SERVER['QUERY_STRING']; ?>" /></td>
      </tr>
    </table>
  </form>
</div>
<?php
} 
$conn->close();
unset($_SESSION['info']);
unset($_SESSION['pid']);
include 'footer.php';
?>
