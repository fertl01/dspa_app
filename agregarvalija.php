<?php
  // Start the session
  require_once('startsession.php');

  // Insert the page header
  $page_title = 'Gesti&oacute;n Cuentas SINDO - Agregar Valija';
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


  if (isset($_POST['submit'])) {
    // Grab the profile data from the POST
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    $num_oficio_ca = mysqli_real_escape_string($dbc, trim($_POST['num_oficio_ca']));
    $num_oficio_del = mysqli_real_escape_string($dbc, trim($_POST['num_oficio_del']));
    $fecha_recepcion_ca = mysqli_real_escape_string($dbc, trim($_POST['fecha_recepcion_ca']));
    $fecha_valija_del = mysqli_real_escape_string($dbc, trim($_POST['fecha_valija_del']));

    $cmbDelegaciones = mysqli_real_escape_string($dbc, trim($_POST['cmbDelegaciones']));

    $comentario = mysqli_real_escape_string($dbc, trim($_POST['comentario']));
    $timetime = time();
    
    $new_file = mysqli_real_escape_string($dbc, trim($_FILES['new_file']['name']));
    
    $new_file_type = $_FILES['new_file']['type'];
    $new_file_size = $_FILES['new_file']['size']; 
    $error = false;

    if (!empty($num_oficio_ca) && !empty($num_oficio_del) && 
        !empty($fecha_recepcion_ca) && !empty($fecha_valija_del) && !empty($cmbDelegaciones) &&  $cmbDelegaciones!=-1 && !empty($new_file)) {

      if ( (($new_file_type == 'application/pdf') || ($new_file_type == 'image/gif') || 
            ($new_file_type == 'image/jpeg')      || ($new_file_type == 'image/pjpeg') ||
            ($new_file_type == 'image/png')
           ) && ($new_file_size > 0) && ($new_file_size <= MM_MAXFILESIZE_VALIJA) ) {

        if ($_FILES['new_file']['error'] == 0) {
          //Move the file to the target upload folder
          $timetime = time();

          // Move the file to the target upload folder
          $target = MM_UPLOADPATH_CTASSINDO . $timetime . " " . $new_file;

          //$target = MM_UPLOADPATH_CTASSINDO . basename($new_file);
          //echo 'Target:' . $target . '<br />';

          if (move_uploaded_file($_FILES['new_file']['tmp_name'], $target)) {

            // Conectarse a la BD
            $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
            // The new file file move was successful, now make sure any old file is deleted
            //if (!empty($old_file) && ($old_file != $new_file)) {
              //@unlink(MM_UPLOADPATH_CTASSINDO . $old_file);
              //echo 'unlink';

              //echo 'New_file_insert:' . $new_file . '<br />';
              $query = "INSERT INTO valijas 
                ( num_oficio_ca, num_oficio_del, 
                fecha_recepcion_ca, fecha_captura_ca, fecha_valija_del, 
                id_remitente, delegacion, comentario, archivo, user_id)
                VALUES 
                ( '$num_oficio_ca', '$num_oficio_del', 
                  '$fecha_recepcion_ca', NOW(), '$fecha_valija_del', 
                  0, '$cmbDelegaciones', '$comentario', '$timetime $new_file', " . $_SESSION['user_id'] . " )";
              //echo $query;

              mysqli_query($dbc, $query);

              // Confirm success with the user
              echo '<p class="nota"><strong>La nueva valija ha sido creada exitosamente. </strong>Agregar <a href="agregarsolicitud.php">nueva solicitud</a></p>';
              echo '<p class="titulo2">Agregar <a href="agregarvalija.php">nueva valija</a></p>';
              echo '<p>O puedes regresar al <a href="indexCuentasSINDO.php">inicio</a></p>';
              // Clear the score data to clear the form
              $num_oficio_ca = "";
              $num_oficio_del = "";
              $fecha_recepcion_ca = "";
              $fecha_valija_del = "";
              $cmbDelegaciones = -1;
              $comentario = "";
              $new_file = "";
              
              mysqli_close($dbc);
              exit();
            //}
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
      echo '<p class="error">Debes ingresar todos los datos obligatorios para registrar la valija.</p>';
    }
  //mysqli_close($dbc);
  }

  $cmbDelegaciones = -1;
  ?>


  <p>Por favor captura los datos solicitados para crear una nueva valija.</p>
    <form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
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
        
          <?php
          $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
          $result = mysqli_query($dbc, "SELECT * FROM delegaciones WHERE activo = 1 ORDER BY delegacion");
          //echo $result;
          
          echo '<select id="cmbDelegaciones" name="cmbDelegaciones">';

          echo '<option value="-1">Seleccione Delegaci&oacute;n</option>';
          while ($row = mysqli_fetch_array($result)) {
            echo '<option value="' . $row['delegacion'] . '" ' . delegacionSel( $row['delegacion'] ) . '>' . $row['delegacion'] . ' - ' . $row['descripcion'] . '</option>';
          }
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

  mysqli_close($dbc);

  function delegacionSel($dele)
  {
    if (empty($_POST['cmbDelegaciones'])) {
      return -1;
    }
    else if ($_POST['cmbDelegaciones'] == $dele) {
      return "selected";
    }
  }

  require_once('footer.php');

  ?>
