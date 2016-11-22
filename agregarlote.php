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

  // Make sure the user is logged in before going any further.
  if ( !isset( $_SESSION['user_id'] ) ) {
    echo '<p class="login">Por favor <a href="login.php">inicia sesión</a> para acceder a esta página.</p>';
    // Insert the page footer
    require_once('footer.php');
    exit();
  }

  require_once( 'funciones.php');

  // Conectarse a la BD
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  if ( mysqli_connect_errno () ) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    return "Falló la conexión a base de datos";
    require_once('footer.php');
    exit(); 
  }

  $mensaje = fnConnect( $dbc );

  if ( $mensaje <> "" ) {
    echo '<p class="error" align="justify">' . $mensaje . '</p>';
    require_once('footer.php');
    exit(); 
  }

  /* check connection */
  /*if (mysqli_connect_errno()) {
      printf("Connect failed: %s\n", mysqli_connect_error());
      exit();
  }*/
    
  /* change character set to utf8 */
  /*if (!$dbc->set_charset("utf8")) {
      printf("Error loading character set utf8: %s\n", $dbc->error);
  } else {
      printf("Current character set: %s\n", $dbc->character_set_name());
  }*/

  if ( isset( $_POST['submit'] ) ) {

    //$anio_actual = mysqli_real_escape_string($dbc, trim($_POST['anio_actual']));
    $new_lote    = mysqli_real_escape_string($dbc, trim($_POST['new_lote']));
    $comentario = mysqli_real_escape_string($dbc, trim($_POST['comentario']));
    $error = false;

    if ( !empty( $comentario ) && !empty( $new_lote ) ) {

      $query = "INSERT INTO ctas_lotes 
        ( lote_anio, fecha_creacion, fecha_modificacion, comentario, user_id )
        VALUES 
        ( '$new_lote', NOW(), NOW(), '$comentario', " . $_SESSION['user_id'] . ")";

      mysqli_query($dbc, $query);
      //echo $query;

      // Confirm success with the user
      echo '<p><strong>El nuevo lote ' . $new_lote . ' ha sido creado exitosamente.</strong></p>';
      
      $new_lote = "";
      $comentario = "";
    }
    else {
      echo '<p class="error">Debes ingresar todos los datos obligatorios para registrar el lote.</p>';
    }
  }

  //$anio_actual = new DateTime("now");
  //$anio_actual = $anio_actual->format("Y");

  ?>

  <section id="main-container">

    <div class="row">
      <div class="col s4">
      </div>

      <form class="col s8" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <div class="row">
            
          <div class="input-field col s4">
            <i class="material-icons prefix">account_circle</i>
            <input type="text" name="new_lote" id="new_lote" length="9" placeholder="D099/2099" value="<?php if ( !empty( $new_lote ) ) echo $new_lote; ?>" />
            <label for="new_lote">Nuevo Lote</label>
          </div>
            
        </div>
        <div class="row">
          <div class="input-field col s4">
            <i class="material-icons prefix">account_circle</i>
            <textarea class="materialize-textarea" id="comentario" length="100" name="comentario"><?php if ( !empty( $comentario ) ) echo $comentario; ?></textarea>
            <label for="comentario">Comentario</label>
          </div>
        </div>
            
        <div class="row">
          <div class="col s4" align="center">
            <button class="btn waves-effect waves-light btn-signup" type="submit" name="submit">Crear lote<i class="material-icons right">send</i>
            </button>
          </div>
        </div>
      </form>

      <div class="col s2">
      </div>

    </div>
    
  </section>

  <?php
  
    // Insert the page footer
    require_once('footer.php');
  ?>
