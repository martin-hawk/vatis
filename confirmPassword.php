<?php
require_once ('db.php');

if (isset($_GET['UserID'])) {
    $query = $conn->query("SELECT * FROM classifier_user WHERE UserID = '" .
        $conn->real_escape_string($_GET['UserID']) . "'");

    if ($query->num_rows == 1) {
        $row = $query->fetch_assoc();
        if ($row['Temp_pass'] == $conn->real_escape_string($_GET['new']) && $row['Temp_pass_activate'] == 1) {
            $update = $conn->query("UPDATE classifier_user SET Password = '" . md5($conn->real_escape_string($_GET['new'])) . "', Temp_pass_activate = 0, Temp_pass = NULL WHERE UserID = '" . $conn->real_escape_string($_GET['UserID']) . "'");
            $_GET['info'] = 'Laikinasis slaptažodis buvo patvirtintas. <a href="index.php">Prisijunkite</a> naudodami šį slaptažodį';
        } else {
            $_GET['info'] = 'Naujasis slaptažodis yra neteisingas arba jau buvo patvirtintas';
        }
    } else {
        $_GET['info'] = 'Jūs bandote patvirtinti negzistuojančio naudotojo laikinąjį slaptažodį';
    }
}

include 'header.php';
?>

<table width="33%" align="center" cellpadding="4" cellspacing="4" style="margin-top:30px;">
  <tr>
    <td><h3>Laikinojo slaptažodžio patvirtinimas</h3></td>
  </tr>
  <tr>
    <td align="center"><?php if (isset($_GET['info'])) {
				echo '<div style="border: 3px solid #090; margin-bottom: 1em; padding: 10px; border-radius: 15px 40px;">';
  				echo $_GET['info'];
				echo '</div>';
			} ?></td>
  </tr>
</table>
<?php include 'footer.php'; ?>