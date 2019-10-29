<?php
require_once ('db.php');
require_once ('functions.php');
session_start();
page_protect($conn, '1', $_SESSION['user_company']);

$con = new mysqli($servername, $username, $password, $database);
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
} 
$con->set_charset("utf8");

include 'header.php';
?>

<div class="container">
  <form name="searchContractor" method="get" action="<?php $_SERVER['PHP_SELF'] ?>">
    <table width="100%" align="center" style="margin-top:20px;">
      <tr>
      <td align="center"><?php if (isset($_SESSION['info'])) {
				echo '<div style="width:600px; border: 3px solid #090; margin-bottom: 1em; padding: 10px; border-radius: 15px 40px;">'; //patikrinti kaip atrodo, kai nori pvz trinti
  				echo $_SESSION['info'];
				echo '</div>';
			} ?></td>
      </tr>
      <tr>
        <td align="center"><input name="q" type="text" size="50" title="Įveskite kliento pavadinimą ar jo dalį. Nenurodžius jokio kriterijaus bus išvestas visų klientų sąrašas" placeholder="Ober..."  value="<?php if (isset($_GET['q'])) echo $_GET['q']; ?>" />
          <a style="color: Black;" href="#" title="Ieškoti" onClick="searchContractor.submit();"><i class="fa fa-search fa-2x" aria-hidden="true"></i></a></td>
      </tr>
    </table>
  </form>
  <?php
$page_limit = 5;

if (isset($_GET['q'])) {
    if ($_GET['q'] == '') {
        $sql = "SELECT * FROM classifier_contractor, classifier_contractor_group WHERE classifier_contractor.CompanyCode='" . $_SESSION['user_company'] . "' AND classifier_contractor_group.CompanyCode='" . $_SESSION['user_company'] . "' AND classifier_contractor.ContractorsGroup=classifier_contractor_group.GroupID";
    } else {
        $sql = "SELECT * FROM classifier_contractor, classifier_contractor_group WHERE classifier_contractor.Name LIKE '%$_REQUEST[q]%' AND classifier_contractor.CompanyCode='" . $_SESSION['user_company'] . "' AND classifier_contractor_group.CompanyCode='" . $_SESSION['user_company'] . "' AND classifier_contractor.ContractorsGroup=classifier_contractor_group.GroupID";
    }

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
    <table width="100%" align="center" class="table" style="margin-bottom:4em;">
      <tr>
        <td colspan="6"><?php
    if ($total > $page_limit) {
        echo "<strong>Puslapiai:</strong> ";
        $i = 0;
        while ($i < $total_pages) {
            $page_no = $i + 1;
            $qstr = preg_replace("&page=[0-9]+&", "", $_SERVER['QUERY_STRING']);
            echo "<a style='color: Black;' href=\"searchContractor.php?$qstr&page=$page_no\">$page_no</a> ";
            $i++;
        };
    }?></td>
      </tr>
      <tr>
        <th>ID</th>
        <th>Įmonės kodas</th>
        <th>PVM kodas</th>
        <th>Pavadinimas</th>
        <th>Grupė</th>
        <th>Operacijos</th>
      </tr>
      <?php
    while ($rrows = $rs_results->fetch_assoc()) {
        echo '<tr><td>' . $rrows['ID'] . '</td>';
        echo '<td>' . $rrows['Code'] . '</td>';
        echo '<td>' . $rrows['VATCode'] . '</td>';
        echo '<td>' . $rrows['Name'] . '</td>';
        echo '<td>' . $rrows['GroupDescription'] . '</td>';
        echo '<td style="color: Black;"><a title="Redaguoti" style="color: Black;" href="editContractor.php?cid=' . $rrows['ID'] . '"><i class="fa fa-pencil fa-2x" aria-hidden="true"></i></a> <a title="Trinti" style="color: Black;" href="phpDeleteContractor.php?cid=' . $rrows['ID'] . '"><i class="fa fa-trash fa-2x" aria-hidden="true"></i></a></td></tr>';
    }
	?>
      <tr>
        <td colspan="6"><input name="query_str" type="hidden" value="<?php $_SERVER['QUERY_STRING']; ?>"/></td>
      </tr>
    </table>
  </form>
</div>
<?php
}
$con->close();
include ('footer.php');
unset($_SESSION['info']);
unset($_SESSION['cid']);
?>
