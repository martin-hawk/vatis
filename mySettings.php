<?php
require_once ('db.php');
require_once ('functions.php');
session_start();
page_protect($conn, '1', $_SESSION['user_company']);

$users = $conn->query("SELECT * FROM classifier_user WHERE UserID = '$_SESSION[user_id]' AND CompanyCode='{$_SESSION['user_company']}'");

include 'header.php';
?>

<div class="container">
  <table align="center" width="60%" style="margin-top:20px; margin-bottom:4em;">
  <form action="phpUpdateUserInfo.php" method="post" name="updateUserInfo">
    <tr>
      <td align="center" colspan="3"><?php if (isset($_SESSION['info'])) {
				echo '<div align="center" style="border: 3px solid #090; margin-bottom: 1em; padding: 10px; border-radius: 15px 40px;">';
  				echo $_SESSION['info'];
				echo '</div>';
			}
			$user = $users->fetch_assoc();
		?></td>
    </tr>
    <tr>
      <td rowspan="6" align="center"><img src="images/user_photo.png" height="155" width="165" /></td>
    </tr>
    <tr>
      <td>Naudotojo vardas</td>
      <td><input name="user_name" type="text" size="30" value="<?php echo $user['UserID']; ?>" disabled /></td>
    </tr>
    <tr>
      <td>El. paštas</td>
      <td><input name="email" type="text" size="30" value="<?php echo $user['Email']; ?>" disabled /></td>
    </tr>
    <tr>
      <td>Vardas<a href="#" style="color: Red; text-decoration:none;" title="Privaloma užpildyti"><strong>*</strong></a></td>
      <td><input name="first_name" type="text" value="<?php echo !empty($_SESSION['name']) ? $_SESSION['name'] : $user['First_name']; ?>" size="30" /></td>
    </tr>
    <tr>
      <td>Pavardė<a href="#" style="color: Red; text-decoration:none;" title="Privaloma užpildyti"><strong>*</strong></a></td>
      <td><input name="last_name" type="text" value="<?php echo !empty($_SESSION['surname']) ? $_SESSION['surname'] : $user['Last_name']; ?>" size="30" /></td>
    </tr>
    <tr>
      <td colspan="2"></td>
    </tr>
    <tr>
      <td></td>
      <td>Senas slaptažodis<a href="#" style="color: Red; text-decoration:none;" title="Privaloma užpildyti"><strong>*</strong></a></td>
      <td><input name="pwd_old" type="password" size="30" value="<?php echo isset($_SESSION['pwd_old']) ? $_SESSION['pwd_old'] : ''; ?>" /></td>
    </tr>
    <tr>
      <td></td>
      <td>Naujas slaptažodis<a href="#" style="color: Red; text-decoration:none;" title="Privaloma užpildyti"><strong>*</strong></a></td>
      <td><input name="pwd_new" type="password" size="30" value="<?php echo isset($_SESSION['pwd_new']) ? $_SESSION['pwd_new'] : ''; ?>" /></td>
    </tr>
    <tr>
      <td></td>
      <td>Patvirtinkite naują slaptažodį<a href="#" style="color: Red; text-decoration:none;" title="Privaloma užpildyti"><strong>*</strong></a></td>
      <td><input name="pwd_new2" type="password" size="30" /></td>
    </tr>
    <tr>
      <td></td>
      <td colspan="2" align="center">
          <a style="color: Black;" title="Keičiant tik vardą arba pavardę, reikia įvesti slaptažodį tris kartus" href="javascript:void(0);" onClick="updateUserInfo.submit();"><i class="fa fa-floppy-o fa-2x" aria-hidden="true"></i></a>
        </td>
    </tr></form>
  </table>
</div>
<?php include 'footer.php'; ?>