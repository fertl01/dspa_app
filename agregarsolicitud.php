<?php
  // Start the session
  require_once('startsession.php');

  // Insert the page header
  $page_title = 'Gesti&oacute;n Cuentas SINDO - Capturar Solicitud';
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

  //FUNCIONES PARA COMBOBOX
  function fnvalijaSelect( $var_valija )
  {
    if ( empty( $_POST['cmbValijas'] ) ) {
      return "";
    }
    else if ( $_POST['cmbValijas'] == $var_valija ) {
      return "selected";
    }
  }

  function fnloteSelect( $var_lote )
  {
    if ( empty( $_POST['cmbLotes'] ) ) {
      return "";
    }
    else if ( $_POST['cmbLotes'] == $var_lote ) {
      return "selected";
    }
  }

  function fntipomovimientoSelect( $var_tipomovimiento )
  {
    if ( empty( $_POST['cmbtipomovimiento'] ) ) 
      return 0;    
    else if ( $_POST['cmbtipomovimiento'] == $var_tipomovimiento )
      return "selected";
  }

  function fntdelegacionSelect($var_delegacion)
  {
    if (empty($_POST['cmbDelegaciones'])) {
      return "";
    }
    else if ($_POST['cmbDelegaciones'] == $var_delegacion) {
      return "selected";
    }
  }

  function fntsubdelegacionSelect($var_subdelegacion)
    {
      if (empty($_POST['cmbSubdelegacionesII'])) {
        return "";
      }
      else if ($_POST['cmbSubdelegacionesII'] == $var_subdelegacion) {
        return "selected";
      }
    }


  if (isset($_POST['submit'])) {
    // Grab the profile data from the POST
    // Conectarse a la BD
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    $cmbLotes = mysqli_real_escape_string( $dbc, trim( $_POST['cmbLotes'] ) );
    $cmbValijas = mysqli_real_escape_string( $dbc, trim( $_POST['cmbValijas'] ) );
    $fecha_solicitud_del = mysqli_real_escape_string( $dbc, trim( $_POST['fecha_solicitud_del'] ) );
    $cmbtipomovimiento = mysqli_real_escape_string( $dbc, trim( $_POST['cmbtipomovimiento'] ) );
    $cmbDelegaciones = mysqli_real_escape_string( $dbc, trim( $_POST['cmbDelegaciones'] ) );
    $cmbSubdelegacionesII = mysqli_real_escape_string( $dbc, trim( $_POST['cmbSubdelegacionesII'] ) );
    


    $primer_apellido = mysqli_real_escape_string($dbc, strtoupper(trim($_POST['primer_apellido'])));
    $segundo_apellido = mysqli_real_escape_string($dbc, strtoupper(trim($_POST['segundo_apellido'])));
    $nombre = mysqli_real_escape_string($dbc, strtoupper(trim($_POST['nombre'])));
    $matricula = mysqli_real_escape_string($dbc, strtoupper(trim($_POST['matricula'])));
    $curp = mysqli_real_escape_string($dbc, strtoupper(trim($_POST['curp'])));
    $usuario = mysqli_real_escape_string($dbc, strtoupper(trim($_POST['usuario'])));
    $cmbgponuevo = mysqli_real_escape_string($dbc, trim($_POST['cmbgponuevo']));
    $cmbgpoactual = mysqli_real_escape_string($dbc, trim($_POST['cmbgpoactual']));
    $comentario = mysqli_real_escape_string($dbc, trim($_POST['comentario']));
    //$timetime = time();
    //$old_file = mysqli_real_escape_string($dbc, trim($_POST['old_file']));
    $new_file = mysqli_real_escape_string($dbc, trim($_FILES['new_file']['name']));

    //$new_file = $_FILES['new_file']['name'];

    $new_file_type = $_FILES['new_file']['type'];
    $new_file_size = $_FILES['new_file']['size']; 

    //echo 'Archivo nuevo: ' . $new_file . '<br />';
    //echo 'Archivo viejo: ' . $old_file . '<br />';
    //echo 'Tamaño: ' . $new_file_size . '<br />';
    //echo 'Tipo: ' . $new_file_type . '<br />';
    //echo 'Max: ' . MM_MAXFILESIZE_VALIJA . '<br />';

    //$error = false;
    $output_form = 'no';

    //Inician validaciones
    if ( empty( $cmbLotes ) ) {
      //if ($cmbLotes == 0)  {
      echo '<p class="error">Olvidaste seleccionar un Lote.</p>';
      //echo 'Lote:' . $cmbLotes;
      $output_form = 'yes';
    }

    if ( empty( $cmbValijas ) ) {
      echo '<p class="error">Olvidaste seleccionar una Valija.</p>';
      $output_form = 'yes';
    }

    if (!preg_match('/^[0-9]{9}$/', utf8_encode($fecha_solicitud_del) )) {
      $anio        = substr(utf8_encode($fecha_solicitud_del), 0, 4);
      $mes        = substr(utf8_encode($fecha_solicitud_del), 5, 2);
      $dia        = substr(utf8_encode($fecha_solicitud_del), 8, 2);
      
      if (!checkdate($mes, $dia, $anio) ) {
        echo '<p class="error">Fecha de la solicitud inv&aacute;lida. ';
        echo 'A&ntilde;o:'  . $anio;
        echo ' Mes:'         . $mes;
        echo ' D&iacute;a:'  . $dia  . '<br />';
        $output_form = 'yes';
      }
    }

    if (empty($cmbtipomovimiento) || ($cmbtipomovimiento == 0) ) {
      //if ($cmbLotes == 0)  {
      echo '<p class="error">Olvidaste seleccionar un Tipo de movimiento.</p>';
      //echo 'Lote:' . $cmbLotes;
      $output_form = 'yes';
    }

    if ( empty($cmbDelegaciones) || ($cmbDelegaciones == -1) || ($cmbDelegaciones == 0) ) {
      //if ($cmbDelegaciones == 0)  {
      echo '<p class="error">Olvidaste seleccionar una Delegaci&oacute;n.</p>';
      //echo 'Del:' . $cmbDelegaciones;
      //echo 'DelNum:' . $delnum;
      $output_form = 'yes';
    }

    if (empty($primer_apellido)) {
      echo '<p class="error">Olvidaste capturar el Primer Apellido.</p>';
      $output_form = 'yes';
    }

    if (empty($nombre)) {
      echo '<p class="error">Olvidaste capturar el Nombre.</p>';
      $output_form = 'yes';
    }

    if ($cmbtipomovimiento == 2) {
      if (empty($cmbgpoactual) || ($cmbgpoactual == 0) ) {
      //if ($cmbLotes == 0)  {
      echo '<p class="error">Olvidaste seleccionar el Grupo Actual para una solicitud de BAJA.</p>';
      //echo 'Lote:' . $cmbLotes;
      $output_form = 'yes';
      }
    }

    //Si el tipo de movimiento es diferente a BAJA, no se permiten Matrícula y CURP nulas.
    if ( ($cmbtipomovimiento <> 2) && (empty($matricula)) ) {
      echo '<p class="error">Olvidaste capturar la Matr&iacute;cula.</p>';
      $output_form = 'yes';
    }

    if ( ($cmbtipomovimiento <> 2) && (empty($curp)) ) {
      echo '<p class="error">Olvidaste capturar la CURP.</p>';
      $output_form = 'yes';
    }

    if (empty($usuario)) {
      echo '<p class="error">Olvidaste capturar Usuario.</p>';
      $output_form = 'yes';
    }

    if ($cmbtipomovimiento == 1) {
      if (empty($cmbgponuevo) || ($cmbgponuevo == 0)) {
      //if ($cmbLotes == 0)  {
      echo '<p class="error">Olvidaste seleccionar el Grupo Nuevo para una solicitud de ALTA.</p>';
      //echo 'Lote:' . $cmbLotes;
      $output_form = 'yes';
      }
    }

    if ($cmbtipomovimiento == 2) {
      if (empty($cmbgpoactual) || ($cmbgpoactual == 0) ) {
      //if ($cmbLotes == 0)  {
      echo '<p class="error">Olvidaste seleccionar el Grupo Actual para una solicitud de BAJA.</p>';
      //echo 'Lote:' . $cmbLotes;
      $output_form = 'yes';
      }
    }

    if ($cmbtipomovimiento == 3) {
      if (empty($cmbgponuevo) || ($cmbgponuevo == 0)) {
      //if ($cmbLotes == 0)  {
      echo '<p class="error">Olvidaste seleccionar el Grupo Nuevo para una solicitud de CAMBIO.</p>';
      //echo 'Lote:' . $cmbLotes;
      $output_form = 'yes';
      }
    }

    if (empty($new_file)) {
      echo '<p class="error">Olvidaste adjuntar un Archivo.</p>';
      $output_form = 'yes';
    }
/*
    if ($_FILES['file']['error'] == 0) {
      echo '<p class="error">El archivo probablemente est&aacute; corrupto!</p>';
      echo '<p class="error">Tama&ntilde;o del archivo: ' . $new_file_size . ' bytes.<br />';
      $output_form = 'yes';
    }
*/
    if ($new_file_size == 0) {
      echo '<p class="error">El Archivo tiene tama&ntilde;o cero!</p>';
      echo '<p class="error">Tama&ntilde;o del archivo: ' . $new_file_size . ' bytes.<br />';
      @unlink(MM_UPLOADPATH_CTASSINDO . $new_file);
      @unlink(MM_UPLOADPATH_CTASSINDO . $old_file);
      //echo MM_UPLOADPATH_CTASSINDO . $new_file . '|<br />';
      //echo MM_UPLOADPATH_CTASSINDO . $old_file . '||';
      $output_form = 'yes';
    }

    if ( (($new_file_type == 'application/pdf') || ($new_file_type == 'image/gif') || 
          ($new_file_type == 'image/jpeg')      || ($new_file_type == 'image/pjpeg') ||
          ($new_file_type == 'image/png')
         ) && ($new_file_size > 0) && ($new_file_size <= MM_MAXFILESIZE_VALIJA) ) {

      if ($_FILES['new_file']['error'] == 0) {

        $timetime = time();
        //Move the file to the target upload folder
        $target = MM_UPLOADPATH_CTASSINDO . $timetime . " " . basename($new_file);
        
        //echo 'Target:' . $target . '<br />';
        if (move_uploaded_file($_FILES['new_file']['tmp_name'], $target)) {
          //echo 'mover de aqui...:' . $_FILES['new_file']['tmp_name'] . '<br />';
          //echo '...a ac&aacute;:' . $target . '<br />';
          // The new file file move was successful, now make sure any old file is deleted
          
          if (!empty($old_file) && ($old_file != $new_file)) {
            //echo '...a ac&aacute;:' . $old_file . '<br />';
            @unlink(MM_UPLOADPATH_CTASSINDO . $old_file);
          }
        }
        else {
          // The new picture file move failed, so delete the temporary file and set the error flag
          @unlink($_FILES['new_file']['tmp_name']);
          $error = true;
          echo '<p class="error">Lo sentimos, hubo un problema al cargar tu archivo.</p>';
          $output_form = 'yes';
        }
      }
      else {
      // The new picture file is not valid, so delete the temporary file and set the error flag
        @unlink($_FILES['new_file']['tmp_name']);
        $error = true;
        echo '<p class="error">El archivo debe ser GIF, JPEG, PNG o PDF no mayor de '. (MM_MAXFILESIZE_VALIJA / 1024) . ' KB de tama&ntilde;o.</p>';
        echo 'Nombre de Archivo: ' . $new_file . '<br />';
        echo 'Tamaño: ' . $new_file_size . '<br />';
        echo 'Tipo: ' . $new_file_type . '<br /><br />';
        echo 'M&aacute;ximo permitido en bytes: ' . MM_MAXFILESIZE_VALIJA . '<br />';
        $output_form = 'yes';
      }
    }
  
  } //FIN de "if (isset($_POST['submit']))"
  else {
    $output_form = 'yes';
    $old_picture = '';
  }

  //mysqli_close($dbc);

  if ($output_form == 'yes') {
    //$cmbDelegaciones = -1;
    //$cmbSubdelegaciones = -1;
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    //$cmbLotes = -1;
    //$cmbValijas = -1;
    ?>
  
    <p>Por favor captura los datos solicitados para crear una nueva solicitud.</p>
    <form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <fieldset>
        
        <legend>Informaci&oacute;n de la solicitud</legend>

        <label># de Lote:</label>
        <select id="cmbLotes" name="cmbLotes">
        <?php
        $query = "SELECT * 
                  FROM lotes 
                  WHERE user_id = " . $_SESSION['user_id'] . 
                  " ORDER BY fecha_modificacion DESC;";
        $result = mysqli_query($dbc, $query);
        echo '<option value="-1">Seleccione # Lote</option>';  
        while ($row = mysqli_fetch_array($result)) {
            echo '<option value="' . $row['id_lote'] . '" ' . fnloteSelect( $row['id_lote'] ) . '>' . $row['lote_anio'] . '</option>';
        }
        ?>
        </select><br />

        <label># de Valija:</label>
        <?php
          $query = "SELECT valijas.id_valija, 
                    valijas.delegacion AS num_del, 
                    delegaciones.descripcion AS delegacion_descripcion, 
                    valijas.num_oficio_del,
                    valijas.num_oficio_ca, 
                    valijas.user_id
                    FROM valijas, delegaciones 
                    WHERE valijas.delegacion = delegaciones.delegacion 
                    AND valijas.user_id = " . $_SESSION['user_id'] . 
                    " ORDER BY valijas.id_valija DESC LIMIT " . MM_MAXVALIJAS;

          $result = mysqli_query($dbc, $query);
          echo '<select id="cmbValijas" name="cmbValijas">';
          echo '<option value="-1">Seleccione # Valija</option>';

          while ($row = mysqli_fetch_array($result)) {
            echo '<option value="' . $row['id_valija'] . '" ' . fnvalijaSelect( $row['id_valija'] ) . '>' . $row['num_oficio_ca'] . ': ' . $row['num_del'] . '-' . $row['delegacion_descripcion'] . '</option>';
          }
        ?>
        </select><br />

        <label for="fecha_solicitud_del">Fecha solicitud:</label>
        <input type="date" id="fecha_solicitud_del" name="fecha_solicitud_del" value="<?php if (!empty($fecha_solicitud_del)) echo $fecha_solicitud_del; ?>" /><br />

        <label>Tipo de movimiento:</label>
        <?php
          $result = mysqli_query($dbc, "SELECT * FROM movimientos ORDER BY 1 ASC");
          echo '<select id="cmbtipomovimiento" name="cmbtipomovimiento">';
          echo '<option value="0">Seleccione Tipo de Movimiento</option>';  

          while ( $row = mysqli_fetch_array( $result ) ) {
            echo '<option value="' . $row['id_movimiento'] . '" ' . fntipomovimientoSelect( $row['id_movimiento'] ) . '>' . $row['descripcion'] . '</option>';
          }
        ?>
        </select><br />

        <label>Delegaci&oacute;n IMSS:</label>
        
          <?php
          $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
          $result = mysqli_query($dbc, "SELECT * FROM delegaciones WHERE activo = 1 ORDER BY delegacion");
          //echo $result;
          
          echo '<select id="cmbDelegaciones" name="cmbDelegaciones">';

          echo '<option value="-1">Seleccione Delegaci&oacute;n</option>';
          while ($row = mysqli_fetch_array($result)) {
            echo '<option value="' . $row['delegacion'] . '" ' . fntdelegacionSelect( $row['delegacion'] ) . '>' . $row['delegacion'] . ' - ' . $row['descripcion'] . '</option>';
          }
        ?>
        </select><br />

        <label>Subdelegaci&oacute;nes IMSS II:</label>
        <select id="cmbSubdelegacionesII" name="cmbSubdelegacionesII">
        <option value="-11">Seleccione Subdelegaci&oacute;n</option>
        
        <?php
          //echo "|:";
          //echo $_POST['cmbDelegaciones'];
          
          if ( !empty( $_POST['cmbSubdelegacionesII'] ) ) {
            $dbc = mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
            //echo "SELECT * FROM subdelegaciones WHERE delegacion = " . $_POST['cmbDelegaciones'] . " ORDER BY subdelegacion";

            $result = mysqli_query( $dbc, "SELECT * FROM subdelegaciones WHERE delegacion = " . $_POST['cmbDelegaciones'] . " ORDER BY subdelegacion" );
            //echo "|";
            //echo $row['delegacion'];
            //echo "|";
            //echo $row['subdelegacion'];
          
  while ( $row = mysqli_fetch_array( $result ) ) {
    echo '<option value="' . $row['subdelegacion'] . '" ' . fntsubdelegacionSelect( $row['subdelegacion'] ) . '>' . $row['subdelegacion'] . ' - ' . $row['descripcion'] . '</option>';
  }
          }
        ?>
        </select><br />

        <label for="primer_apellido">Primer Apellido:</label>
        <input type="text" id="primer_apellido" name="primer_apellido" value="<?php if (!empty($primer_apellido)) echo $primer_apellido; ?>" /><br />
        <label for="segundo_apellido">Segundo Apellido:</label>
        <input type="text" id="segundo_apellido" name="segundo_apellido" value="<?php if (!empty($segundo_apellido)) echo $segundo_apellido; ?>" /><br />
        <label for="nombre">Nombre(s):</label>
        <input type="text" id="nombre" name="nombre" value="<?php if (!empty($nombre)) echo $nombre; ?>" /><br />

        <label for="matricula">Matr&iacute;cula:</label>
        <input type="text" id="matricula" name="matricula" value="<?php if (!empty($matricula)) echo $matricula; ?>" /><br />
        <label for="curp">CURP:</label>
        <input type="text" id="curp" name="curp" value="<?php if (!empty($curp)) echo $curp; ?>" /><br />

        <label for="usuario">Usuario:</label>
        <input type="text" id="usuario" name="usuario" value="<?php if (!empty($usuario)) echo $usuario; ?>" /><br />
        
        <label>Grupo Nuevo:</label>
        <select id="cmbgponuevo" name="cmbgponuevo"></select><br />
        <label>Grupo Actual:</label>
        <select id="cmbgpoactual" name="cmbgpoactual"></select><br />

        <label for="comentario">Comentario u observaci&oacute;n:</label>
        <textarea id="comentario" name="comentario"><?php if (!empty($comentario)) echo $comentario; ?></textarea><br />

        <input type="hidden" name="old_file" value="<?php if (!empty($old_picture)) echo $old_picture; ?>" />
        <label for="new_file">Archivo:</label>
        <input type="file" id="new_file" name="new_file" />
          
      </fieldset>
      <input type="submit" value="Registra solicitud" name="submit" />
    </form>

    <?php
  }
  else if ($output_form == 'no') {
    
    $query = "INSERT INTO solicitudes 
      ( id_valija, id_lote, 
        fecha_solicitud_del, 
        delegacion, subdelegacion, 
        nombre, primer_apellido, segundo_apellido, matricula, curp, 
        usuario, id_movimiento, id_grupo_nuevo, id_grupo_actual,
        comentario, rechazado, archivo, user_id)
      VALUES 
      ( '$cmbValijas', '$cmbLotes',
        '$fecha_solicitud_del',
        '$cmbDelegaciones', '$cmbSubdelegaciones', 
        '$nombre', '$primer_apellido', '$segundo_apellido', 
        '$matricula', '$curp',
        '$usuario', '$cmbtipomovimiento', '$cmbgponuevo', '$cmbgpoactual',
        '$comentario', 0, '$timetime $new_file', " . $_SESSION['user_id'] . " )";
    //echo $query;
    mysqli_query($dbc, $query);

    // Clear the score data to clear the form

    $cmbLote    = -1;
    $cmbValijas = -1;
    $fecha_solicitud_del = "";
    $cmbtipomovimiento = 0;
    $cmbDelegaciones = -1;
    $cmbSubdelegaciones = -1;

    $nombre = "";
    $primer_apellido = "";
    $segundo_apellido = "";
    $matricula = "";
    $curp = "";
    $cargo = "";
    $usuario = "";
    $cmbgponuevo = "";
    $cmbgpoactual = "";
    $comentario = "";
    $new_file = "";
    
    //mysqli_close($dbc);
    //exit();
    echo '<p class="nota"><strong>La nueva solicitud ha sido creada exitosamente.</strong></p>';
    echo '<p class="titulo2">Puede agregar una<a href="agregarsolicitud.php"> nueva solicitud</a></p>';
    echo '<p class="titulo2">Agregar <a href="agregarvalija.php">nueva valija</a></p>';
    echo '<p >O puede regresar al <a href="indexCuentasSINDO.php">inicio</a></p>';
    //echo '<p>' . $first_name . ' ' . $last_name . ', thanks for registering with Risky Jobs!<br />';
    //$pattern = '/[\(\)\-\s]/';
    //$replacement = '';
    //$new_phone = preg_replace($pattern, $replacement, $phone);
    //echo 'Your phone number has been registered as ' . $new_phone . '.</p>';
  }
    ?>

  <?php
    // Insert the page footer
    require_once('footer.php');
  ?>
