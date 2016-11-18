<?php
  // Start the session
  require_once('startsession.php');

  // Insert the page header
  $page_title = 'Gesti&oacute;n Cuentas SINDO - Agregar Lote';
  require_once('headerCuentasSINDO.php');

  // Show the navigation menu
  require_once('navmenu.php');

  // Make sure the user is logged in before going any further.
  if (!isset($_SESSION['user_id'])) {
    echo '<p class="login">Por favor <a href="login.php">inicia sesi&oacute;n</a> para acceder a esta p&aacutegina.</p>';
    // Insert the page footer
    require_once('footer.php');
    exit();
  }

  require_once('appvars.php');
  require_once('connectvars.php');

  // Conectarse a la BD
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

  if (isset($_POST['submit'])) {

    $anio_actual = mysqli_real_escape_string($dbc, trim($_POST['anio_actual']));

    $new_lote    = mysqli_real_escape_string($dbc, trim($_POST['new_lote']));
    $comentario = mysqli_real_escape_string($dbc, trim($_POST['comentario']));
    $error = false;

    if ( !empty($comentario) && !empty($new_lote) ) {

      $query = "INSERT INTO ctas_lotes 
        ( lote_anio, fecha_creacion, fecha_modificacion, comentario, user_id )
        VALUES 
        ( '$new_lote', NOW(), NOW(), '$comentario', " . $_SESSION['user_id'] . ")";

      mysqli_query($dbc, $query);
      //echo $query;

      // Confirm success with the user
      echo '<p><strong>El nuevo lote ' . $new_lote . ' ha sido creado exitosamente con el usuario ' . $_SESSION['user_id'] . '</strong></p>';
      echo '<p>Regresa al <a href="indexCuentasSINDO.php">inicio</a></p>';

      $anio_actual = "";
      
      $new_lote = "";
      $comentario = "";
      //$valija_nueva = 1;
      //mysqli_close($dbc);
      //exit();
    }
    else {
      echo '<p class="error">Debes ingresar todos los datos obligatorios para registrar el lote.</p>';
    }
  }

  $anio_actual = new DateTime("now");
  $anio_actual = $anio_actual->format("Y");

  ?>

  <p>Por favor captura los datos solicitados para crear un nuevo lote.</p>
  <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <fieldset>
      <legend>Informaci&oacute;n del lote</legend>
      <input type="hidden" name="anio_actual" value="<?php echo $anio_actual; ?>" />
      
      <label for="new_lote"># Lote (ejemplo D010/2015):</label>
      <input type="text" id="new_lote" name="new_lote" value="<?php if (!empty($new_lote)) echo $new_lote; ?>" /><br />

      <label for="comentario">Comentario u observaci&oacute;n:</label>
      <textarea id="comentario" name="comentario"><?php if (!empty($comentario)) echo $comentario; ?></textarea><br />

    </fieldset>
    <input type="submit" value="Crea lote" name="submit" />
  </form>
  

  <?php
  /*
    // Obtener los últimos 10 lotes capturados al momento
    $query = "SELECT lotes.id_lote, lotes.anio, 
      lotes.fecha_modificacion, lotes.fecha_creacion, lotes.comentario,
      (SELECT COUNT(*) FROM solicitudes WHERE solicitudes.id_lote = lotes.id_lote AND solicitudes.anio_lote = lotes.anio) AS num_solicitudes
      FROM lotes ORDER BY lotes.fecha_modificacion DESC LIMIT 10";

    $data = mysqli_query($dbc, $query);

    echo '<p class="titulo1">&Uacute;ltimos diez lotes modificados</p>';

    echo '<table border="1">';
    echo '<tr class="dato"><th># Lote</th>';
    echo '<th>Fecha modificaci&oacute;n</th>';
    echo '<th>Fecha creaci&oacute;n</th>';
    echo '<th># de valijas</th><th>Comentario</th>';
    echo '</tr>';

    if (mysqli_num_rows($data) == 0) {
      echo '</table></br><p class="error">No hay lotes capturados</p></br>';
      require_once('footer.php');
      exit();
    }

    while ( $row = mysqli_fetch_array($data) ) {
      $id_lote = $row['id_lote'];
      echo '<tr class="dato"><td class="lista"><a href="editarlote.php?id_lote=' . $row['id_lote'] . '">' . $row['id_lote'] . ' / ' . $row['anio'] . '</a></td>';
      echo '<td class="lista">' . $row['fecha_modificacion'] . '</td>';
      echo '<td class="lista">' . $row['fecha_creacion'] . '</td>';
      echo '<td class="lista">' . $row['num_solicitudes']  . '</td>';
      echo '<td class="lista">' . $row['comentario'] . '</td></tr>';
    }

    echo '</table></br></br>';

    //Mostrar valijas ya capturadas
    // Obtener todas las valijas capturadas al momento
    $query = "SELECT valijas.id_valija, valijas.delegacion AS num_del, delegaciones.descripcion AS delegacion_descripcion, 
      valijas.num_oficio_ca, valijas.fecha_recepcion_ca, valijas.num_oficio_del, 
      valijas.fecha_valija_del, valijas.comentario, valijas.archivo,
      (SELECT COUNT(*) FROM solicitudes WHERE solicitudes.id_valija = valijas.id_valija) AS num_solicitudes
    FROM valijas, delegaciones WHERE valijas.delegacion = delegaciones.delegacion ORDER BY valijas.fecha_captura_ca DESC";

    $data = mysqli_query($dbc, $query);

    echo '</br><hr><p class="titulo1">Valijas capturadas</p>';

    echo '<table border="1">';
    echo '<tr class="dato"><th># Valija</th>';
    echo '<th>Fecha recepci&oacute;n</th>';
    echo '<th>Delegaci&oacute;n que env&iacute;a</th>';
    echo '<th># &Aacute;rea de Gesti&oacute;n</th>';
    echo '<th># Valija</th><th>Fecha valija</th>';
    echo '<th>Archivo</th>';
    echo '<th># de solicitudes</th>';
    echo '<th>Comentario</th>';
    echo '</tr>';

    if (mysqli_num_rows($data) == 0) {
      echo '</table></br><p class="error">No hay valijas capturadas</p></br>';
      require_once('footer.php');
      exit();
    }

    while ( $row = mysqli_fetch_array($data) ) {
      $id_valija = $row['id_valija'];
      echo '<tr class="dato"><td class="lista"><a href="editarvalija.php?id_valija=' . $row['id_valija'] . '">' . $row['id_valija'] . '</a></td>';
      echo '<td class="lista">' . $row['fecha_recepcion_ca'] . '</td>';
      echo '<td class="lista">' . '(' . $row['num_del'] . ')' . $row['delegacion_descripcion'] . '</td>';
      echo '<td class="lista">' . $row['num_oficio_ca'] . '</td>';
      echo '<td class="lista">' . $row['num_oficio_del'] . '</td>';
      echo '<td class="lista">' . $row['fecha_valija_del'] . '</td>';
      echo '<td class="lista">' . $row['archivo'] . '</td>';
      echo '<td class="lista">' . $row['num_solicitudes']  . '</td>';
      echo '<td class="lista">' . $row['comentario'] . '</td></tr>';
    }

    echo '</table></br></br>';
*/
    // Insert the page footer
    require_once('footer.php');
  ?>
