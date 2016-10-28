<?php
// Start the session
  require_once('startsession.php');

  require_once('appvars.php');
  require_once('connectvars.php');

  // Insert the page header
  $page_title = MM_APPNAME;
  require_once('header.php');

  // Show the navigation menu
  require_once('navmenu.php');


  // Clear the error message
  $error_msg = "";

  // If the user isn't logged in, try to log them in
  if (!isset($_SESSION['user_id'])) {
    if (isset($_POST['submit'])) {
      // Connect to the database
      $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

      // Grab the user-entered log-in data
      $user_username = mysqli_real_escape_string($dbc, trim($_POST['username']));
      $user_password = mysqli_real_escape_string($dbc, trim($_POST['password']));

      $user_pass_phrase = SHA1($_POST['verify']);
      if ($_SESSION['pass_phrase'] == $user_pass_phrase) {

        if (!empty($user_username) && !empty($user_password)) {

          if (  ($user_username <> 'MA') &&
                ($user_username <> 'TO') &&
                ($user_username <> 'TOL') ) {
            //echo $user_username;
            echo '<p class="error">Usuario no aprobado por el Administrador. Por favor <a href="login.php">inicia sesi&oacute;n</a> con un usuario pre-aprobado para acceder a esta p&aacute;gina.</p>';
            // Insert the page footer
            require_once('footer.php');
            exit();
          }


          // Look up the username and password in the database
          $query = "SELECT user_id, username FROM dspa_user WHERE username = '$user_username' AND password = SHA('$user_password')";
          $data = mysqli_query($dbc, $query);

          if (mysqli_num_rows($data) == 1) {
            // The log-in is OK so set the user ID and username session vars (and cookies), and redirect to the home page
            $row = mysqli_fetch_array($data);
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['username'] = $row['username'];
            setcookie('user_id', $row['user_id'], time() + MM_EXPIRE_COOKIE_VAL);    // expires in 1 hour
            setcookie('username', $row['username'], time() + MM_EXPIRE_COOKIE_VAL);  // expires in 1 hour
            $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
            header('Location: ' . $home_url);
          }
          else {
            // The username/password are incorrect so set an error message
            $error_msg = 'Lo siento, debes capturar un usuario y contrase&ntilde;a v&aacute;lidos para iniciar sesi&oacute;n.';
          }
        }
        else {
          // The username/password weren't entered so set an error message
          $error_msg = 'Lo siento, debes capturar un usuario y password para iniciar sesi&oacute;n.';
        }
      }
      else {
      echo '<p class="error">Por favor, captura la frase de verificaci&oacute;n (CAPTCHA) exactamente como se muestra.</p>';
      }

    }
  }

  // Insert the page header
  $page_title = 'Iniciar sesión';
  require_once('header.php');

  // If the session var is empty, show any error message and the log-in form; otherwise confirm the log-in
  if (empty($_SESSION['user_id'])) {
    echo '<p class="error">' . $error_msg . '</p>';
?>

  <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <fieldset>
      <legend>Iniciar sesi&oacute;n</legend>
      <label for="username">Usuario (CURP):</label>
      <input type="text" name="username" value="<?php if (!empty($user_username)) echo $user_username; ?>" /><br />
      <label for="password">Contrase&ntilde;a:</label>
      <input type="password" name="password" /><br />
      <label for="verify">Verificaci&oacute;n:</label>
      <input type="text" id="verify" name="verify" value="Captura la frase(CAPTCHA)" /> <img src="captcha.php" alt="Verificación CAPTCHA" />
    </fieldset>
    <input type="submit" value="Inicia sesi&oacute;n" name="submit" />
  </form>

<?php
  }
  else {
    // Confirm the successful log-in
    echo('<p class="login">Has iniciado sesi&oacute;n como ' . $_SESSION['username'] . '.</p>');
  }
?>

<?php
  // Insert the page footer
  require_once('footer.php');
?>
