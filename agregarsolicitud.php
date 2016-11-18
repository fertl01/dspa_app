<?php

  // Start the session
  require_once( 'startsession.php' );

  // Insert the page header
  $page_title = 'Gesti&oacute;n Cuentas SINDO - Capturar Solicitud';
  require_once( 'headerCuentasSINDO.php' );
  
  require_once( 'appvars.php' );
  require_once( 'connectvars.php' );

  // Make sure the user is logged in before going any further.
  if ( !isset( $_SESSION['user_id'] ) ) {
    echo '<p class="login">Por favor <a href="login.php">inicia sesi&oacute;n</a> para acceder a esta p&aacutegina.</p>';
    exit();
  }

  // Show the navigation menu
  require_once( 'navmenu.php' );
  require_once( 'funciones.php');

  // Connect to the database
  $dbc = mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );

  if ( isset( $_POST['submit'] ) ) {
    $cmbLotes =             mysqli_real_escape_string( $dbc, trim( $_POST['cmbLotes'] ) );
    $cmbValijas =           mysqli_real_escape_string( $dbc, trim( $_POST['cmbValijas'] ) );
    $fecha_solicitud_del =  mysqli_real_escape_string( $dbc, trim( $_POST['fecha_solicitud_del'] ) );
    $cmbtipomovimiento =    mysqli_real_escape_string( $dbc, trim( $_POST['cmbtipomovimiento'] ) );
    $cmbDelegaciones =      mysqli_real_escape_string( $dbc, trim( $_POST['cmbDelegaciones'] ) );
    $cmbSubdelegaciones =   mysqli_real_escape_string( $dbc, trim( $_POST['cmbSubdelegaciones'] ) );
    $primer_apellido =      mysqli_real_escape_string( $dbc, strtoupper( trim( $_POST['primer_apellido'] ) ) );
    $segundo_apellido =     mysqli_real_escape_string( $dbc, strtoupper( trim( $_POST['segundo_apellido'] ) ) );
    $nombre =               mysqli_real_escape_string( $dbc, strtoupper( trim( $_POST['nombre'] ) ) );
    $matricula =            mysqli_real_escape_string( $dbc, strtoupper( trim( $_POST['matricula'] ) ) );
    $curp =                 mysqli_real_escape_string( $dbc, strtoupper( trim( $_POST['curp'] ) ) );
    $usuario =              mysqli_real_escape_string( $dbc, strtoupper( trim( $_POST['usuario'] ) ) );
    $cmbgponuevo =          mysqli_real_escape_string( $dbc, trim( $_POST['cmbgponuevo'] ) );
    $cmbgpoactual =         mysqli_real_escape_string( $dbc, trim( $_POST['cmbgpoactual'] ) );
    $comentario =           mysqli_real_escape_string( $dbc, trim( $_POST['comentario'] ) );
    $new_file =             mysqli_real_escape_string( $dbc, trim( $_FILES['new_file']['name'] ) );
    $new_file_type = $_FILES['new_file']['type'];
    $new_file_size = $_FILES['new_file']['size'];
    //list( $new_picture_width, $new_picture_height ) = getimagesize( $_FILES['new_file']['tmp_name'] );
    $output_form = 'no';
      //if ( !empty( $cmblotes ) && !empty( $cmbValijas ) && !empty( $fecha_solicitud_del ) && !empty( $cmbtipomovimiento ) && !empty( $cmbDelegaciones ) && !empty( $cmbSubdelegaciones ) && !empty( $primer_apellido ) && !empty( $segundo_apellido ) && !empty( $nombre ) && !empty( $matricula ) && !empty( $curp ) && !empty( $usuario ) && ( !empty( $cmbgpoactual ) || !empty( $cmbgponuevo ) ) ) {
    //Inician validaciones
    if ( empty( $cmbLotes ) ) {
      echo '<p class="error">Olvidaste seleccionar un Lote.</p>';
      $output_form = 'yes';
    }

    if ( empty( $cmbValijas ) ) {
      echo '<p class="error">Olvidaste seleccionar una Valija.</p>';
      $output_form = 'yes';
    }

    if ( !preg_match( '/^[0-9]{9}$/', utf8_encode( $fecha_solicitud_del ) ) ) {
      $anio = substr( utf8_encode( $fecha_solicitud_del ), 0, 4 );
      $mes  = substr( utf8_encode( $fecha_solicitud_del ), 5, 2 );
      $dia  = substr( utf8_encode( $fecha_solicitud_del ), 8, 2 );
      
      if ( !checkdate( $mes, $dia, $anio ) ) {
        echo '<p class="error">Fecha de la solicitud inv&aacute;lida.';
        echo ' A&ntilde;o:'  . $anio;
        echo ' Mes:'         . $mes;
        echo ' D&iacute;a:'  . $dia  . '<br />';
        $output_form = 'yes';
      }
    }

    if ( empty( $cmbtipomovimiento ) ) {
      echo '<p class="error">Olvidaste seleccionar un Tipo de Movimiento.</p>';
      $output_form = 'yes';
    }

    if ( empty( $cmbDelegaciones ) || 
            ( $cmbDelegaciones == 0) || 
            ( $cmbDelegaciones == -1 ) 
          ) {
      echo '<p class="error">Olvidaste seleccionar una Delegaci&oacute;n.</p>';
      $output_form = 'yes';
    }

    if ( ( empty( $cmbSubdelegaciones ) || $cmbSubdelegaciones == -1 ) && $cmbSubdelegaciones <> 0 )  {
      echo '<p class="error">Olvidaste seleccionar una Subdelegaci&oacute;n.</p>';
      $output_form = 'yes';
    }

    if ( empty( $primer_apellido ) ) {
      echo '<p class="error">Olvidaste capturar el Primer Apellido.</p>';
      $output_form = 'yes';
    }

    if ( empty( $nombre ) ) {
      echo '<p class="error">Olvidaste capturar el Nombre.</p>';
      $output_form = 'yes';
    }

    // BAJA
    if ( $cmbtipomovimiento == 2 ) { 
      if ( empty( $cmbgpoactual ) || ( $cmbgpoactual == 0 ) ) {
      echo '<p class="error">Olvidaste seleccionar el Grupo Actual para una solicitud de BAJA.</p>';
      $output_form = 'yes';
      }
    }

    //Si el tipo de movimiento es diferente a BAJA, no se permiten Matrícula y CURP nulas.
    if ( ( $cmbtipomovimiento <> 2 ) && ( empty( $matricula ) ) ) {
      echo '<p class="error">Olvidaste capturar la Matr&iacute;cula.</p>';
      $output_form = 'yes';
    }

    if ( ( $cmbtipomovimiento <> 2 ) && ( empty( $curp ) ) ) {
      echo '<p class="error">Olvidaste capturar la CURP.</p>';
      $output_form = 'yes';
    }

    if ( empty( $usuario ) ) {
      echo '<p class="error">Olvidaste capturar Usuario.</p>';
      $output_form = 'yes';
    }

    // ALTA
    if ( $cmbtipomovimiento == 1 ) {
      if ( empty( $cmbgponuevo ) || ( $cmbgponuevo == 0 ) ) {
      echo '<p class="error">Olvidaste seleccionar el Grupo Nuevo para una solicitud de ALTA.</p>';
      $output_form = 'yes';
      }
    }

    // CAMBIO
    if ( $cmbtipomovimiento == 3 ) {
      if ( empty( $cmbgpoactual ) || ( $cmbgpoactual == 0 ) ) {
        echo '<p class="error">Olvidaste seleccionar el Grupo Actual para una solicitud de CAMBIO.</p>';
        $output_form = 'yes';
      }
    }

    if ( $cmbtipomovimiento == 3 ) {
      if ( empty( $cmbgponuevo ) || ( $cmbgponuevo == 0 ) ) {
        echo '<p class="error">Olvidaste seleccionar el Grupo Nuevo para una solicitud de CAMBIO.</p>';
        $output_form = 'yes';
      }
    }

    if ( empty( $new_file ) ) {
      echo '<p class="error">Olvidaste adjuntar un Archivo.</p>';
      $output_form = 'yes';
    }

    if ( $output_form == 'no' ) {

      // Validate and move the uploaded picture file, if necessary
      if ( !empty( $new_file ) ) {

        if ( ( ( $new_file_type == 'application/pdf' ) || ( $new_file_type == 'image/gif' ) || ( $new_file_type == 'image/jpeg' ) || ( $new_file_type == 'image/pjpeg' ) || ( $new_file_type == 'image/png' ) ) && ( ( $new_file_size > 0 ) && ( $new_file_size <= MM_MAXFILESIZE_VALIJA ) ) ) {
          if ( $_FILES['new_file']['error'] == 0 ) {
            $timetime = time();
            //Move the file to the target upload folder
            $target = MM_UPLOADPATH_CTASSINDO . $timetime . " " . basename( $new_file );

              // The new file file move was successful, now make sure any old file is deleted
            if ( move_uploaded_file( $_FILES['new_file']['tmp_name'], $target ) ) {

              //if ( !empty( $old_file ) && $old_file != $new_file ) {
                //@unlink( MM_UPLOADPATH_CTASSINDO . $old_file );
              //}

              // Conectarse a la BD
              $dbc = mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
              $query = "INSERT INTO ctas_solicitudes 
                          ( id_valija, id_lote, 
                            fecha_solicitud_del, 
                            delegacion, subdelegacion, 
                            nombre, primer_apellido, segundo_apellido, matricula, curp, 
                            usuario, id_movimiento, id_grupo_nuevo, id_grupo_actual,
                            comentario, rechazado, archivo, user_id )
                        VALUES 
                        ( '$cmbValijas', '$cmbLotes',
                          '$fecha_solicitud_del',
                          '$cmbDelegaciones', '$cmbSubdelegaciones', 
                          '$nombre', '$primer_apellido', '$segundo_apellido', 
                          '$matricula', '$curp',
                          '$usuario', '$cmbtipomovimiento', '$cmbgponuevo', '$cmbgpoactual',
                          '$comentario', 0, '$timetime $new_file', " . $_SESSION['user_id'] . " )";
              mysqli_query( $dbc, $query );
              echo '<p class="nota"><strong>La nueva solicitud ha sido creada exitosamente.</strong></p>';
              echo '<p class="titulo2">Puede agregar una <a href="agregarsolicitud.php">nueva solicitud</a></p>';
              echo '<p class="titulo2">Agregar <a href="agregarvalija.php">nueva valija</a></p>';
              echo '<p>O puede regresar al <a href="indexCuentasSINDO.php">inicio</a></p>';

              // Clear the score data to clear the form
              $cmbLote    = 0;
              $cmbValijas = 0;
              $fecha_solicitud_del = "";
              $cmbtipomovimiento = 0;
              $cmbDelegaciones = 0;
              $cmbSubdelegaciones = -1;
              $nombre = "";
              $primer_apellido = "";
              $segundo_apellido = "";
              $matricula = "";
              $curp = "";
              $cargo = "";
              $usuario = "";
              $cmbgpoactual = 0;
              $cmbgponuevo = 0;
              $comentario = "";
              $new_file = "";

              mysqli_close( $dbc );
              exit();
            }
            else {
              // The new picture file move failed, so delete the temporary file and set the error flag
              @unlink( $_FILES['new_file']['tmp_name'] );
              $error = true;
              echo '<p class="error">Lo sentimos, hubo un problema al cargar tu archivo.</p>';
            } // if ( move_uploaded_file(...

          } // if ( $_FILES['new_file']['error'] == 0 )...

        }
        else {
        // The new picture file is not valid, so delete the temporary file and set the error flag
          @unlink( $_FILES['new_file']['tmp_name'] );
          $error = true;
          echo '<p class="error">El archivo debe ser PDF, GIF, JPEG o PNG no mayor de '. ( MM_MAXFILESIZE_VALIJA / 1024 ) . ' KB de tama&ntilde;o.</p>';
        } // if ( ( ( $new_file_type == 'application/pdf' )...

      } //FIN de "if (isset($_POST['submit']))"
      else {
        $output_form = 'yes';
      }
    }
    else {
      echo '<p class="error">Debes ingresar todos los datos obligatorios para registrar la solicitud.</p>';
    }
  }

$cmbDelegaciones = 0;
$cmbSubdelegaciones = -1;
?>

<form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
  <p>Por favor captura los datos solicitados para crear una nueva solicitud.</p>
  <fieldset>
    <legend>Informaci&oacute;n de la solicitud</legend>
    <label># de Lote:</label>
    <select id="cmbLotes" name="cmbLotes">
      <option value="0">Seleccione # Lote</option>
      <?php
        $query = "SELECT * 
                  FROM ctas_lotes 
                  WHERE user_id = " . $_SESSION['user_id'] . 
                  " ORDER BY fecha_modificacion DESC;";
        $result = mysqli_query($dbc, $query);
        while ( $row = mysqli_fetch_array( $result ) )
            echo '<option value="' . $row['id_lote'] . '" ' . fnloteSelect( $row['id_lote'] ) . '>' . $row['lote_anio'] . '</option>';
      ?>
    </select><br />

    <label># de Valija:</label>
    <select id="cmbValijas" name="cmbValijas">
      <option value="0">Seleccione # Valija</option>
      <?php
        $query = "SELECT ctas_valijas.id_valija, 
                  ctas_valijas.delegacion AS num_del, 
                  ctas_delegaciones.descripcion AS delegacion_descripcion, 
                  ctas_valijas.num_oficio_del,
                  ctas_valijas.num_oficio_ca, 
                  ctas_valijas.user_id
                  FROM ctas_valijas, ctas_delegaciones 
                  WHERE ctas_valijas.delegacion = ctas_delegaciones.delegacion 
                  AND ctas_valijas.user_id = " . $_SESSION['user_id'] . 
                  " ORDER BY ctas_valijas.id_valija DESC LIMIT " . MM_MAXVALIJAS;
        $result = mysqli_query( $dbc, $query );
        while ( $row = mysqli_fetch_array( $result ) )
          echo '<option value="' . $row['id_valija'] . '" ' . fnvalijaSelect( $row['id_valija'] ) . '>' . $row['num_oficio_ca'] . ': ' . $row['num_del'] . '-' . $row['delegacion_descripcion'] . '</option>';
      ?>
    </select><br />

    <label for="fecha_solicitud_del">Fecha solicitud:</label>
    <input type="date" id="fecha_solicitud_del" name="fecha_solicitud_del" value="<?php if ( !empty( $fecha_solicitud_del ) ) echo $fecha_solicitud_del; ?>" /><br />

    <label>Tipo de movimiento:</label>
    <select id="cmbtipomovimiento" name="cmbtipomovimiento">
      <option value="0">Seleccione Tipo de Movimiento</option>  
      <?php
        $result = mysqli_query( $dbc, "SELECT * FROM ctas_movimientos ORDER BY 1 ASC" );
        while ( $row = mysqli_fetch_array( $result ) )
          echo '<option value="' . $row['id_movimiento'] . '" ' . fntipomovimientoSelect( $row['id_movimiento'] ) . '>' . $row['descripcion'] . '</option>';
      ?>
    </select><br />

    <label>Delegaci&oacute;n IMSS:</label>
    <select id="cmbDelegaciones" name="cmbDelegaciones">
      <option value="0">Seleccione Delegaci&oacute;n</option>
      <?php
        $result = mysqli_query( $dbc, "SELECT * FROM ctas_delegaciones WHERE activo = 1 ORDER BY delegacion" );
        while ( $row = mysqli_fetch_array( $result ) )
          echo '<option value="' . $row['delegacion'] . '" ' . fntdelegacionSelect( $row['delegacion'] ) . '>' . $row['delegacion'] . ' - ' . $row['descripcion'] . '</option>';
      ?>
    </select><br />

    <label>Subdelegaciones IMSS:</label>
    <select id="cmbSubdelegaciones" name="cmbSubdelegaciones">
      <option value="-1">Seleccione Subdelegaci&oacute;n</option>
      <?php
        if ( !empty( $_POST['cmbSubdelegaciones'] ) || $_POST['cmbSubdelegaciones'] == -1 ) 
          $result = mysqli_query( $dbc, "SELECT * FROM ctas_subdelegaciones 
                                          WHERE delegacion = " . $_POST['cmbDelegaciones'] . " ORDER BY subdelegacion" );
          while ( $row = mysqli_fetch_array( $result ) )
            echo '<option value="' . $row['subdelegacion'] . '" ' . fntsubdelegacionSelect( $row['subdelegacion'] ) . '>' . $row['subdelegacion'] . ' - ' . $row['descripcion'] . '</option>';
      ?>
    </select><br />

    <label for="primer_apellido">Primer Apellido:</label>
    <input type="text" id="primer_apellido" name="primer_apellido" value="<?php if ( !empty( $primer_apellido ) ) echo $primer_apellido; ?>" /><br />

    <label for="segundo_apellido">Segundo Apellido:</label>
    <input type="text" id="segundo_apellido" name="segundo_apellido" value="<?php if ( !empty( $segundo_apellido ) ) echo $segundo_apellido; ?>" /><br />

    <label for="nombre">Nombre(s):</label>
    <input type="text" id="nombre" name="nombre" value="<?php if ( !empty( $nombre ) ) echo $nombre; ?>" /><br />

    <label for="matricula">Matr&iacute;cula:</label>
    <input type="text" id="matricula" name="matricula" value="<?php if ( !empty( $matricula ) ) echo $matricula; ?>" /><br />

    <label for="curp">CURP:</label>
    <input type="text" id="curp" name="curp" value="<?php if ( !empty( $curp ) ) echo $curp; ?>" /><br />

    <label for="usuario">Usuario:</label>
    <input type="text" id="usuario" name="usuario" value="<?php if ( !empty( $usuario ) ) echo $usuario; ?>" /><br />
    
    <label>Grupo Actual:</label>
    <select id="cmbgpoactual" name="cmbgpoactual">
      <option value="0">Seleccione Grupo Actual</option>
      <?php
        $result = mysqli_query( $dbc, "SELECT * FROM ctas_grupos WHERE id_grupo <> 0 ORDER BY descripcion ASC" );
        while ( $row = mysqli_fetch_array( $result ) )
          echo '<option value="' . $row['id_grupo'] . '" ' . fntcmbgpoactualSelect( $row['id_grupo'] ) .'>' . $row['descripcion'] . '</option>';
      ?>
    </select><br />

    <label>Grupo Nuevo:</label>
    <select id="cmbgponuevo" name="cmbgponuevo">
      <option value="0">Seleccione Grupo Nuevo</option>
      <?php
        $result = mysqli_query( $dbc, "SELECT * FROM ctas_grupos WHERE id_grupo <> 0 ORDER BY descripcion ASC" );
        while ( $row = mysqli_fetch_array( $result ) )
          echo '<option value="' . $row['id_grupo'] . '" ' . fntcmbgponuevoSelect( $row['id_grupo'] ) .'>' . $row['descripcion'] . '</option>';
      ?>
    </select><br />
    
    <label for="comentario">Comentario u observaci&oacute;n:</label>
    <textarea id="comentario" name="comentario">
    <?php 
      if ( !empty( $comentario ) ) 
        echo $comentario;
    ?>
    </textarea><br />

    <label for="new_file">Archivo:</label>
    <input type="file" id="new_file" name="new_file" />
      
  </fieldset>
  <input type="submit" value="Registra solicitud" name="submit" />
</form>

<?php

  mysqli_close( $dbc );

  // Insert the page footer
  require_once( 'footer.php' );
?>
