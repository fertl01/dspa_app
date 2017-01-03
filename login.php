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

  // If the session var is empty, show any error message and the log-in form; otherwise confirm the log-in
  //if ( empty( $_SESSION['user_id'] ) ) {
    //echo '<p class="error">' . $error_msg . '</p>';
?>
  <section id="main-container">
    <div class="row">

      <div class="col s4">
        <div class="container">
          <img class="iphone" src="public/iPhone.png" />
        </div>
      </div>

      <div class="col s4">
        <div class="row">
          <div class="signup-box">
            <form class="signup-form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
              <h6 align="center">Ingresa los datos</h6>
              <div class="section">
                <i class="material-icons prefix">account_circle</i>
                <input placeholder="CURP" type="text" length="18" name="username" value="<?php if ( !empty( $user_username ) ) echo $user_username; ?>" />
              </div>
              <div class="section">
                <i class="material-icons prefix">vpn_key</i>
                <input placeholder="Contraseña" type="password" name="password" />
                <i class="material-icons prefix">dialpad</i>
                <input placeholder="Captura la frase (CAPTCHA)" type="text" length="6" name="verify" />
                <div class="row" align="center">
                  <img align="center" src="captcha.php" alt="Verificación CAPTCHA" />
                </div>
              </div>
              <div class="section" align="center">
                <button class="btn waves-effect waves-light btn-signup" type="submit" name="submit">Iniciar sesión
                  <i class="material-icons right">send</i>
                </button>
              </div>
            </form>
          </div>
        </div>
        <div class="row">
          <div class="login-box">
            <h6 align="center">¿No tienes cuenta? <a href="index.php">Regístrate aquí</a></h6>
          </div>
        </div>
      </div>

      <div class="col s4">
        <div class="container">
          <div class="row">
            <div class="signup-box">
              <div class="container">
                  <?php
                    // If the user isn't logged in, try to log them in
                    if ( !isset( $_SESSION['user_id'] ) ) {
                      if ( isset( $_POST['submit'] ) ) {

                        // Connect to the database
                        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
                        
                        /* check connection */
                        if ( mysqli_connect_errno() ) {
                            printf( "Connect failed: %s\n", mysqli_connect_error() );
                            exit();
                        }
                          
                        /* change character set to utf8 */
                        if ( !$dbc->set_charset( "utf8" ) ) 
                            printf( "Error loading character set utf8: %s\n", $dbc->error );
                        else
                            /*printf( "Current character set: %s\n", $dbc->character_set_name() );*/
                          
                        // Grab the user-entered log-in data
                        $user_username = mysqli_real_escape_string($dbc, trim($_POST['username']));
                        $user_password = mysqli_real_escape_string($dbc, trim($_POST['password']));
                        $user_pass_phrase = SHA1($_POST['verify']);
                        if ( !empty($user_username) && !empty( $user_password )  
                          && ( $_SESSION['pass_phrase'] == $user_pass_phrase ) ) {
                          // Look up the username and password in the database
                          $query = "SELECT user_id, username FROM ctas_usuarios WHERE username = '$user_username' AND password = SHA('$user_password')";
                          $data = mysqli_query($dbc, $query);
                          if ( mysqli_num_rows( $data ) == 1 ) {
                            // The log-in is OK so set the user ID and username session vars (and cookies), and redirect to the home page
                            $row = mysqli_fetch_array($data);
                            $_SESSION['user_id'] = $row['user_id'];
                            $_SESSION['username'] = $row['username'];
                            setcookie('user_id', $row['user_id'], time() + MM_EXPIRE_COOKIE_VAL);
                            setcookie('username', $row['username'], time() + MM_EXPIRE_COOKIE_VAL);
                            $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
                            header('Location: ' . $home_url);
                          }
                          else // The username/password are incorrect so set an error message
                            $error_msg = 'Lo siento, debes capturar un usuario y contraseña válidos para iniciar sesión.';
                        }
                        else  // The username/password weren't entered so set an error message
                            $error_msg = 'Para iniciar una sesión válida, debes capturar todos los datos y la frase de verificación (CAPTCHA) exactamente como se muestra.';
                      }
                    }
                  echo '<p class="error" align="justify">' . $error_msg . '</p>';
                  ?>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>

<?php
  // Insert the page footer
  require_once('footer.php');
?>
