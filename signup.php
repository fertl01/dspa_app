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
                <h6 align="center">Ingresa los datos para registrarte al sistema</h6>
                <div class="section">
                  <i class="material-icons prefix">account_circle</i>
                  <input placeholder="CURP" type="text" length="18" name="username" value="<?php if ( !empty( $user_username ) ) echo $user_username; ?>" />
                  <i class="material-icons prefix">vpn_key</i>
                  <input placeholder="Contraseña" type="password" id="password1" name="password1" />
                  <input placeholder="Contraseña (captura la misma contraseña)" id="password2" type="password" name="password2" />
                  <i class="material-icons prefix">dialpad</i>
                  <input placeholder="Captura la frase (CAPTCHA)" type="text" length="6" id="verify" name="verify" />
                  <div class="row" align="center">
                    <img align="center" src="captcha.php" alt="Verificación CAPTCHA" />
                  </div>
                </div>
                <div class="section" align="center">
                  <button class="btn waves-effect waves-light btn-signup" type="submit" name="submit">Registra Usuario
                    <i class="material-icons right">send</i>
                  </button>
                </div>
              </form>
            </div>
          </div>
          <div class="row">
            <div class="login-box">
              <h6 align="center">¿Ya tienes cuenta? <a href="login.php">Ingresa aquí</a></h6>
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
                          if ( !$dbc->set_charset( "utf8" ) ) {
                              printf( "Error loading character set utf8: %s\n", $dbc->error );
                          }
                          else {
                              /*printf( "Current character set: %s\n", $dbc->character_set_name() );*/
                          }
                            
                          // Grab the user-entered log-in data
                          $username = mysqli_real_escape_string($dbc, trim($_POST['username']));
                          $password1 = mysqli_real_escape_string($dbc, trim($_POST['password1']));
                          $password2 = mysqli_real_escape_string($dbc, trim($_POST['password2']));
                          /*echo $username;
                          echo $password1;
                          echo $password2;*/

                          // Check the CAPTCHA pass-phrase for verification
                          $user_pass_phrase = SHA1($_POST['verify']);
                          //echo '|';
                          //echo $user_pass_phrase;

                          if ($_SESSION['pass_phrase'] == $user_pass_phrase) {

                            if (!empty($username) && !empty($password1) && !empty($password2) && ($password1 == $password2)) {
                              // Make sure someone isn't already registered using this username
                              $query = "SELECT * FROM ctas_usuarios WHERE username = '$username'";
                              //echo $query;
                              $data = mysqli_query($dbc, $query);
                              if (mysqli_num_rows($data) == 0) {
                                // The username is unique, so insert the data into the database
                                $query = "INSERT INTO ctas_usuarios (username, password, join_date) VALUES ('$username', SHA('$password1'), NOW())";
                                //echo $query;
                                mysqli_query($dbc, $query);
                                
                                // Confirm success with the user
                                echo '<p>La nueva cuenta ha sido creada exitosamente. 
                                  Ahora está listo para <a href="login.php">iniciar sesión</a>.</p>';
                                
                                /*mysqli_close($dbc);
                                exit();*/
                              }
                              else {
                                // An account already exists for this username, so display an error message
                                echo '<p class="error">Ya existe una cuenta para este usuario. Por favor utiliza una diferente o
                                  puedes intentar <a href="login.php">iniciar sesión</a>.</p>';
                                $username = "";
                              }
                            }
                            else {
                              echo '<p class="error">Debes ingresar todos los datos para registrarte, incluyendo la contraseña deseada dos veces.</p>';
                            }
                          }
                          else {
                            echo '<p class="error">Por favor, captura la frase de verificación (CAPTCHA) exactamente como se muestra.</p>';
                          }
                        }
                      }
                    /*mysqli_close($dbc);*/
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
