<?php
require_once ('db.php');
require_once ('functions.php');
session_start();
page_protect($conn, '1', $_SESSION['user_company']);

include 'phpResetVariables.php';
include 'header.php';
?>

<div class="container" style="margin-bottom:4em;">
  <table align="center" style="margin-top:1em; border-collapse: separate; border-spacing:5px;" cellpadding="10px">
    <tr>
      <td align="center" style="width:175px; height:100px; color: White; background-color:DarkOrange; border-radius: 10px;"><i class="fa fa-users fa-2x" aria-hidden="true"></i><br />
        <strong>Klientai</strong></td>
      <td align="center" style="width:175px; height:100px; color: White; background-color:DarkGreen; border-radius: 10px;"><i class="fa fa-file-text fa-2x" aria-hidden="true"></i><br />
        <strong>PVM sąskaitos faktūros</strong></td>
      <td align="center" style="width:175px; height:100px; color: White; background-color:Red; border-radius: 10px;"><i class="fa fa-shopping-cart fa-2x" aria-hidden="true"></i><br />
        <strong>Produktai</strong></td>
    </tr>
    <tr>
      <td align="center" style="width:175px; height:130px; border:solid 10px DarkOrange; border-radius: 15px 40px;"><a style="color:DarkOrange;" href="newContractor.php"> <i class="fa fa-plus-circle fa-2x" aria-hidden="true"></i><br />
        Naujas klientas </a></td>
      <td align="center" style="width:175px; height:130px; border:solid 10px DarkGreen; border-radius: 15px 40px;"><a style="color:DarkGreen;" href="newInvoice.php"><i class="fa fa-plus-circle fa-2x" aria-hidden="true"></i><br />
        Nauja PVM sąskaita </a></td>
      <td align="center" style="width:175px; height:130px; border:solid 10px Red; border-radius: 15px 40px;"><a style="color:Red;" href="newProduct.php"><i class="fa fa-plus-circle fa-2x" aria-hidden="true"></i><br />
        Naujas produktas </a></td>
    </tr>
    <tr>
      <td align="center" style="width:175px; height:130px; border:solid 10px DarkOrange; border-radius: 15px 40px;"><a style="color:DarkOrange;" href="searchContractor.php"><i class="fa fa-search fa-2x" aria-hidden="true"></i><br />
        Klientų paieška </a></td>
      <td align="center" style="width:175px; height:130px; border:solid 10px DarkGreen; border-radius: 15px 40px;"><a style="color:DarkGreen;" href="newOneTimeInvoice.php"><i class="fa fa-plus-circle fa-2x" aria-hidden="true"></i><br />
        Vienkartinė PVM sąskaita </a></td>
      <td align="center" style="width:175px; height:130px; border:solid 10px Red; border-radius: 15px 40px;"><a style="color:Red;" href="searchProduct.php"><i class="fa fa-search fa-2x" aria-hidden="true"></i><br />
        Produktų paieška </a></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td align="center" style="width:175px; height:130px; border:solid 10px DarkGreen; border-radius: 15px 40px;"><a style="color:DarkGreen;" href="searchInvoice.php"><i class="fa fa-search fa-2x" aria-hidden="true"></i><br />
        PVM sąskaitų paieška </a></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td align="center" style="width:175px; height:130px; border:solid 10px DarkGreen; border-radius: 15px 40px;"><a style="color:DarkGreen;" href="newAdvanceInvoice.php"><i class="fa fa-file-text fa-2x" aria-hidden="true"></i><br />
        Išankstinė sąskaita</a></td>
      <td>&nbsp;</td>
    </tr>
    <?php if ($_SESSION['user_id'] == 'martynas') {
        echo '<tr>
      <td>&nbsp;</td>
      <td align="center" style="width:175px; height:130px; border:solid 10px Blue; border-radius: 15px 40px;"><a style="color:Blue;" href="i-saf.php"><i class="fa fa-file-code-o fa-2x" aria-hidden="true"></i><br />
        Formuoti i.SAF</a></td>
      <td>&nbsp;</td>
    </tr>';
    }
    ?>
  </table>
</div>
<?php include 'footer.php'; ?>
