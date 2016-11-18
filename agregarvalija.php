<?php

  // Start the session
  require_once('startsession.php');

  // Insert the page header
  $page_title = 'Gesti&oacute;n Cuentas SINDO - Agregar Valija';
  require_once('headerCuentasSINDO.php');

  require_once('appvars.php');
  require_once('connectvars.php');
  
  // Make sure the user is logged in before going any further.
  if ( !isset( $_SESSION['user_id'] ) ) {
    echo '<p class="login">Por favor <a href="login.php">inicia sesi&oacute;n</a> para acceder a esta p&aacutegina.</p>';
    // Insert the page footer
    exit();
  }

  // Show the navigation menu
  require_once('navmenu.php');
  require_once( 'funciones.php' );

  $dbc = mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );

  if ( isset( $_POST['submit'] ) ) {
    // Grab the profile data from the POST

    $num_oficio_ca = $_POST['num_oficio_ca'];
    $num_oficio_del = mysqli_real_escape_string($dbc, trim($_POST['num_oficio_del']));
    $fecha_recepcion_ca = mysqli_real_escape_string($dbc, trim($_POST['fecha_recepcion_ca']));
    $fecha_valija_del = mysqli_real_escape_string($dbc, trim($_POST['fecha_valija_del']));
    $cmbDelegaciones = mysqli_real_escape_string($dbc, trim($_POST['cmbDelegaciones']));
    $comentario = mysqli_real_escape_string($dbc, trim($_POST['comentario']));
    $timetime = time();
    $new_file = mysqli_real_escape_string($dbc, trim($_FILES['new_file']['name']));
    $new_file_type = $_FILES['new_file']['type'];
    $new_file_size = $_FILES['new_file']['size']; 
    $output_form = 'no';

    if ( empty( $num_oficio_ca ) ) {
      echo '<p class="error">Olvidaste capturar un N&uacute;mero de &Aacute;rea de Gesti&oacute;n</p>';
      $output_form = 'yes';
    }
    else {
      if ( !preg_match( '/^[1-9][0-9]*$/', $num_oficio_ca ) ) {
        echo '<p class="error">N&uacute;mero de &Aacute;rea de Gesti&oacute;n inv&aacute;lido.</p>';
        $output_form = 'yes';
      }
    }

    if ( !preg_match( '/^\d{9}$/', $fecha_recepcion_ca ) ) {
      $anio = substr( $fecha_recepcion_ca , 0, 4 );
      $mes  = substr( $fecha_recepcion_ca , 5, 2 );
      $dia  = substr( $fecha_recepcion_ca , 8, 2 );
      
      if ( !checkdate( $mes, $dia, $anio ) ) {
        echo '<p class="error">Fecha de &Aacute;rea de Gesti&oacute;n inv&aacute;lida.';
        echo ' A&ntilde;o:'  . $anio;
        echo ' Mes:'         . $mes;
        echo ' D&iacute;a:'  . $dia  . '<br />';
        $output_form = 'yes';
      }

      if ( $anio < 2015 ) {
        echo '<p class="error">El a&ntilde;o en Fecha de &Aacute;rea de Gesti&oacute;n es inv&aacute;lido.';
        echo ' A&ntilde;o:'  . $anio;
        $output_form = 'yes'; 
      }
    }

    if ( empty( $num_oficio_del ) ) {
      echo '<p class="error">Olvidaste capturar un N&uacute;mero de Oficio Delegaci&oacute;n.</p>';
      $output_form = 'yes';
    }
    else {
      if ( !preg_match( '/^[a-z A-Z0-9\/\._\-]*$/', $num_oficio_del ) ) {
        echo '<p class="error">Caracteres inv&aacute;lidos en N&uacute;mero de Oficio Delegaci&oacute;n.</p>';
        $output_form = 'yes';
      }
    }

    if ( !preg_match( '/^\d{9}$/', $fecha_valija_del ) ) {
      $anio = substr( $fecha_valija_del, 0, 4 );
      $mes  = substr( $fecha_valija_del, 5, 2 );
      $dia  = substr( $fecha_valija_del, 8, 2 );
      
      if ( !checkdate( $mes, $dia, $anio ) ) {
        echo '<p class="error">Fecha de Oficio Delegaci&oacute;n inv&aacute;lida.';
        echo ' A&ntilde;o:'  . $anio;
        echo ' Mes:'         . $mes;
        echo ' D&iacute;a:'  . $dia  . '<br />';
        $output_form = 'yes';
      }

      if ( $anio < 2015 ) {
        echo '<p class="error">El a&ntilde;o en Fecha de Oficio Delegaci&oacute;n es inv&aacute;lido.';
        echo ' A&ntilde;o:'  . $anio;
        $output_form = 'yes'; 
      }
    }

    if ( empty( $cmbDelegaciones ) || 
          ( $cmbDelegaciones == 0) || 
          ( $cmbDelegaciones == -1 ) 
        ) {
      echo '<p class="error">Olvidaste seleccionar una Delegaci&oacute;n.</p>';
      $output_form = 'yes';
    }

    if ( empty( $new_file ) ) {
      echo '<p class="error">Olvidaste adjuntar el documento.</p>';
      $output_form = 'yes';
    }

    if ( $output_form == 'no' ) {

      if ( ( ( $new_file_type == 'application/pdf' ) || ( $new_file_type == 'image/gif' ) || 
            ( $new_file_type == 'image/jpeg' )       || ( $new_file_type == 'image/pjpeg' ) ||
            ( $new_file_type == 'image/png' )
           ) && ( $new_file_size > 0 ) && ( $new_file_size <= MM_MAXFILESIZE_VALIJA ) 
          ) {

        if ( $_FILES['new_file']['error'] == 0 ) {
          //Move the file to the target upload folder
          $timetime = time();

          // Move the file to the target upload folder
          $target = MM_UPLOADPATH_CTASSINDO . $timetime . " " . $new_file;

          if ( move_uploaded_file( $_FILES['new_file']['tmp_name'], $target ) ) {

            $num_oficio_ca  =  $num_oficio_ca;
            $num_oficio_del =  $num_oficio_del;
            echo $comentario;
            echo utf8_encode( $comentario );
            $comentario     = $comentario;

            $pattern = '/[\s]/';
            $replacement = '';
            $new_num_oficio_del = preg_replace($pattern, $replacement, $num_oficio_del);
            
            //Check for existing number
            $query = "SELECT * FROM ctas_valijas WHERE num_oficio_ca = '$num_oficio_ca'";
            $data = mysqli_query( $dbc, $query );

            if ( mysqli_num_rows( $data ) == 0 ) {
              //The number is unique, so insert the data

              $query = "INSERT INTO ctas_valijas 
                ( num_oficio_ca, num_oficio_del, 
                fecha_recepcion_ca, fecha_captura_ca, fecha_valija_del, 
                id_remitente, delegacion, comentario, archivo, user_id)
                VALUES 
                ( '$num_oficio_ca', '$new_num_oficio_del', 
                  '$fecha_recepcion_ca', NOW(), '$fecha_valija_del', 
                  0, '$cmbDelegaciones', '$comentario', '$timetime $new_file', " . $_SESSION['user_id'] . " )";
              mysqli_query( $dbc, $query );
              echo $query;

              // Confirm success with the user
              echo '<p class="nota"><strong>La nueva valija ha sido creada exitosamente. </strong></p><br />';
              echo '# &Aacute;rea de Gesti&oacute;n: ' . $num_oficio_ca . '<br />';
              echo 'Fecha: ' . $fecha_recepcion_ca . '<br /><br />';
              echo '# Oficio: ' . $new_num_oficio_del . '<br />';
              echo 'Fecha: ' . $fecha_valija_del . '<br />';

              $result = mysqli_query( $dbc, "SELECT * FROM ctas_delegaciones WHERE activo = 1 AND delegacion = '$cmbDelegaciones'" );
              while ( $row = mysqli_fetch_array( $result ) )
                echo 'Delegaci&oacute;n: ' . $row['delegacion'] . ' - ' . $row['descripcion'] . '<br /><br />';
              
              echo 'Comentario: ' . $comentario . '<br />';
              echo 'Archivo adjunto final: ' . $timetime . ' ' . $new_file . '<br /></p>';
              
              echo '<p class="titulo2">Agregar <a href="agregarvalija.php">nueva valija</a></p>';
              echo '<p class="nota">Agregar <a href="agregarsolicitud.php">nueva solicitud</a><br />';
              echo '<p>O puedes regresar al <a href="indexCuentasSINDO.php">inicio</a></p>';
              
              mysqli_close( $dbc );
              exit();
            }
            else {
              echo '<p class="error">Ya existe este N&uacute;mero &Aacute;rea de Gesti&oacute;n. Por favor utiliza uno diferente o agrega m&aacute;s solicitudes a la existente.</p>';
            }
          }
          else {
          echo '<p class="error">Lo sentimos, hubo un problema al cargar tu archivo.</p>';
          }
        }
      }
      else {
        echo '<p class="error">El archivo debe ser GIF, JPEG, PNG o PDF no mayor de '. (MM_MAXFILESIZE_VALIJA / 1024) . ' KB de tama&ntilde;o.</p>';
      }

      // Try to delete the temporary screen shot image file
      @unlink($_FILES['new_file']['tmp_name']); 
    
    }
    else {
      echo '<p class="error">Debes ingresar todos los datos v&aacute;lidos y obligatorios para registrar la valija.</p>';
    }
  }

?>

<form enctype="multipart/form-data" method="post" accept-charset="utf-8" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<p>Por favor captura los datos solicitados para crear una nueva valija.</p>
  <fieldset>
    <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo MM_MAXFILESIZE_VALIJA; ?>" />
    <legend>Informaci&oacute;n de la valija</legend>
    <label for="num_oficio_ca"># &Aacute;rea de Gesti&oacute;n:</label>
    <input type="text" id="num_oficio_ca" name="num_oficio_ca" value="<?php if (!empty($num_oficio_ca)) echo $num_oficio_ca; ?>" /><br />
    <label for="fecha_recepcion_ca">Fecha &Aacute;rea de Gesti&oacute;n</label>
    <input type="date" id="fecha_recepcion_ca" name="fecha_recepcion_ca" value="<?php if (!empty($fecha_recepcion_ca)) echo $fecha_recepcion_ca; ?>" /><br />
    <label for="num_oficio_del"># Oficio Delegaci&oacute;n:</label>
    <input type="text" id="num_oficio_del" name="num_oficio_del" value="<?php if (!empty($num_oficio_del)) echo $num_oficio_del; ?>" /><br />
    <label for="fecha_valija_del">Fecha Oficio Delegaci&oacute;n:</label>
    <input type="date" id="fecha_valija_del" name="fecha_valija_del" value="<?php if (!empty($fecha_valija_del)) echo $fecha_valija_del; ?>" /><br />
    <label>Delegaci&oacute;n IMSS:</label>
    <select id="cmbDelegaciones" name="cmbDelegaciones" display=true>
      <option value="-1">Seleccione Delegaci&oacute;n</option>
      <?php
        //$dbc = mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
        $result = mysqli_query( $dbc, "SELECT * FROM ctas_delegaciones WHERE activo = 1 ORDER BY delegacion" );
        while ( $row = mysqli_fetch_array( $result ) )
          echo '<option value="' . $row['delegacion'] . '" ' . fntdelegacionSelect( $row['delegacion'] ) . '>' . $row['delegacion'] . ' - ' . $row['descripcion'] . '</option>';
      ?>
    </select><br />
    <label for="comentario">Comentario u observaci&oacute;n:</label>
    <textarea id="comentario" name="comentario"><?php if (!empty($comentario)) echo $comentario; ?></textarea><br />
    <label for="new_file">Archivo:</label>
    <input type="file" id="new_file" name="new_file"/>
  </fieldset>
  <input type="submit" value="Registra valija" name="submit" />
</form>
  
<?php
  mysqli_close( $dbc );
  require_once('footer.php');
?>
