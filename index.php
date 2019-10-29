<?php
require_once ('db.php');
session_start();

include 'header.php';
?>

<form action="phpLoginUser.php" method="post" name="loginForm">
  <table align="center" style="margin-top: 5em">
    <tr>
    <tr>
      <td><h3>VAT IS Prisijungti</h3></td>
    </tr>
    <tr>
      <td><table width="66%" cellpadding="4" cellspacing="4">
          <tr>
            <td colspan="3" align="center"><?php if (isset($_SESSION['info'])) {
    echo '<div style="border: 3px solid #090; margin-bottom: 1em; padding: 10px; border-radius: 15px 40px;">';
    echo $_SESSION['info'];
    echo '</div>';
} ?></td>
          </tr>
          <tr>
            <td width="5%" align="right" style="color: Black;"><i class="fa fa-user fa-2x" aria-hidden="true"></i></td>
            <td width="62%"><input name="username" type="text" size="32" tabindex="1" value="<?php echo
isset($_SESSION['un']) ? $_SESSION['un'] : ''; ?>" /></td>
            <td width="33%" rowspan="3" align="left" valign="middle"><a style="color: Black;" title="Prisijungti" href="javascript:void(0);" onClick="loginForm.submit();"><i class="fa fa-sign-in fa-4x" aria-hidden="true"></i></a></td>
          </tr>
          <tr>
            <td align="right" style="color: Black;"><i class="fa fa-key fa-2x" aria-hidden="true"></i></td>
            <td><input name="password" type="password" size="32" tabindex="2" /></td>
          </tr>
          <tr>
            <td align="right" style="color: Black;"><i class="fa fa-building fa-2x" aria-hidden="true"></i></td>
            <td><select name="company">
                <?php
$companies = $conn->query("SELECT * FROM classifier_company");
while ($row = $companies->fetch_assoc()) {
    echo '<option value="' . $row['CompanyCode'] . '">' . $row['CompanyCode'] .
        '</option>';
}
?>
              </select>
              </div></td>
          </tr>
          <tr>
            <td colspan="3"><div align="center"> <a href="forgotPassword.php" style="color: Black;">Pamiršote slaptažodį?</a> </div></td>
          </tr>
        </table>
  </table>
</form>
<?php include 'footer.php'; ?>
