<?php // Neištestuota
require_once ('db.php');
require_once ('functions.php');

if (isset($_POST['Submit'])) {
    $eml = $conn->real_escape_string($_POST['email']);
    $temp_pass = rand(1000000, 9999999);

    if (!empty($_POST['email'])) {
        $getUser = $conn->query("SELECT UserID, First_name FROM classifier_user WHERE Email='$eml'");
        if ($getUser->num_rows == 1) {
            $row = $getUser->fetch_assoc();

            $query = $conn->query("UPDATE classifier_user SET Temp_pass='$temp_pass', Temp_pass_activate = 1 WHERE Email='$eml'");

            $hyperlink = "http://vatis.vanagas.pro/confirmPassword.php?UserID=" . $row['UserID'] . "&new=" . $temp_pass;
            
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=utf-8" . "\r\n";
            $headers .= 'From: info@vanagas.pro' . "\r\n" .
                'Reply-To: martynas@vanagas.pro' . "\r\n" . 'X-Mailer: PHP/' . phpversion();
            $subject = "Prašymas atstatyti VAT IS slaptažodį";
            
            $message = "Gerb. " . $row['First_name'] . ",<br />Buvo pareikalauta pakeisti naudotojo slaptažodį.<p>Sugeneruotas naujas laikinasis slaptažodis: <b>$temp_pass</b>.</p><br />Kad patvirtintumėte naują slaptažodį ir jį aktyvuotumėte, prašome paspausti ant šios <a href=&#34;" .
            $hyperlink . "&#34;>nuorodos</a>.<p><b>Nepamirškite pakeisti laikinojoslaptažodžio.</b></p><p>Jeigu Jūs nepareikalavote pakeisti slaptažodį, tiesiog ignoruokite šį laišką.</p><p>Jeigu nuoroda neveikia, nukopijuokite šią eilutę į savo naršyklę:<br />" . $hyperlink;

            if (mail($eml, $subject, $message, $headers)) {
                $_POST['info'] = 'Laikšas sėkmingai išsiųstas. Prašome patikrinti savo el. paštą';
            } else {
                $_POST['info'] = 'Nepavyko išsiųsti laiško!';
            }
        } else {
            $_POST['info'] = 'Naudotojas tokiu el. pašto adresu negzistuoja.';
        }
    } else {
        $_POST['info'] = 'Neteisingai nurodytas el. paštas!';
    }
}

include 'header.php';
?>

<div class="container">
  <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
    <table width="33%" align="center" cellpadding="4" cellspacing="4" style="margin-top:30px;">
      <tr>
        <td colspan="2"><h3>Atstatyti slaptažodį</h3></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><?php if (isset($_POST['info'])) {
				echo '<div style="border: 3px solid #090; margin-bottom: 1em; padding: 10px; border-radius: 15px 40px;">';
  				echo $_POST['info'];
				echo '</div>';
			} ?></td>
      </tr>
      <tr>
        <td>El. paštas</td>
        <td><input type="email" id="txtbox" name="email" size="32" value="" /></td>
      </tr>
      <tr>
        <td colspan="2"><div align="center">
            <p>
              <input type="submit" name="Submit" value="Vykdyti" class="button" />
            </p>
          </div></td>
      </tr>
    </table>
  </form>
</div>
<?php include 'footer.php'; ?>