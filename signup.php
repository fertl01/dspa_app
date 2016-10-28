<?php
  session_start();
?>

<?php
  // Insert the page header
  $page_title = 'Registro de Usuario';
  require_once('header.php');

  // Show the navigation menu
  require_once('navmenu.php');

  require_once('appvars.php');
  require_once('connectvars.php');

  // Connect to the database
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

  if (isset($_POST['submit'])) {
    // Grab the profile data from the POST
    $username = mysqli_real_escape_string($dbc, trim($_POST['username']));
    $password1 = mysqli_real_escape_string($dbc, trim($_POST['password1']));
    $password2 = mysqli_real_escape_string($dbc, trim($_POST['password2']));
    //$user_pass_phrase = mysqli_real_escape_string($dbc, trim($_POST['verify']));

    // Check the CAPTCHA pass-phrase for verification
    $user_pass_phrase = SHA1($_POST['verify']);
    if ($_SESSION['pass_phrase'] == $user_pass_phrase) {

      if (!empty($username) && !empty($password1) && !empty($password2) && ($password1 == $password2)) {
        // Make sure someone isn't already registered using this username
        $query = "SELECT * FROM dspa_user WHERE username = '$username'";
        $data = mysqli_query($dbc, $query);
        if (mysqli_num_rows($data) == 0) {
          // The username is unique, so insert the data into the database
          $query = "INSERT INTO dspa_user (username, password, join_date) VALUES ('$username', SHA('$password1'), NOW())";
          mysqli_query($dbc, $query);
          
          // Confirm success with the user
          echo '<p>La nueva cuenta ha sido creada exitosamente. 
            Ahora est&aacute;s listo para <a href="login.php">iniciar sesi&oacute;n</a>.</p>';
          
          mysqli_close($dbc);
          exit();
        }
        else {
          // An account already exists for this username, so display an error message
          echo '<p class="error">Ya existe una cuenta para este usuario. Por favor utiliza una diferente o
            puedes intentar <a href="login.php">iniciar sesi&oacute;n</a>.</p>';
          $username = "";
        }
      }
      else {
        echo '<p class="error">Debes ingresar todos los datos para registrarte, incluyendo la contrase&ntilde;a deseada dos veces.</p>';
      }
    }
    else {
      echo '<p class="error">Por favor, captura la frase de verificaci&oacute;n (CAPTCHA) exactamente como se muestra.</p>';
    }
  }

  mysqli_close($dbc);
?>

  <p>Por favor captura tu CURP como nombre de usuario, la contrase&ntilde;a deseada y el valor de verificaci&oacute;n para registrarte al sistema.</p>
  <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <fieldset>
      <legend>Informaci&oacute;n de Registro de Usuario</legend>
      <label for="username">Usuario (CURP):</label>
      <input type="text" id="username" name="username" value="<?php if (!empty($username)) echo $username; ?>" /><br />
      <label for="password1">Contrase&ntilde;a:</label>
      <input type="password" id="password1" name="password1" /><br />
      <label for="password2">Contrase&ntilde;a (recaptura):</label>
      <input type="password" id="password2" name="password2" /><br />
      <label for="verify">Verificaci&oacute;n:</label>
      <input type="text" id="verify" name="verify" value="Captura la frase(CAPTCHA)" /> <img src="captcha.php" alt="Verificación CAPTCHA" />
     
    </fieldset>
    <input type="submit" value="Registra Usuario" name="submit" />
  </form>

<?php
  // Insert the page footer
  require_once('footer.php');
?>
