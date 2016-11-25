<?php

  // Start the session
  require_once( 'startsession.php' );

  require_once( 'appvars.php' );
  require_once( 'connectvars.php' );
  
  // Insert the page header
  $page_title = 'Gesti&oacute;n Cuentas SINDO - Capturar Solicitud';
  require_once( 'header.php' );
  
  // Show the navigation menu
  require_once( 'navmenu.php' );
  require_once( 'funciones.php');

  $error_msg = "";

  // Make sure the user is logged in before going any further.
  if ( !isset( $_SESSION['user_id'] ) ) {
    echo '<p class="error">Por favor <a href="login.php">inicia sesi&oacute;n</a> para acceder a esta p&aacutegina.</p>';
    require_once('footer.php');
    exit();
  } else {
    // Connect to the database
    $dbc = mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );

    if ( mysqli_connect_errno () ) {
      printf("Connect failed: %s\n", mysqli_connect_error());
      return "Falló la conexión a base de datos";
      require_once('footer.php');
      exit(); 
    }
    $error_msg = fnConnect( $dbc );
  }

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
    $cmbcausarechazo =      mysqli_real_escape_string( $dbc, trim( $_POST['cmbcausarechazo'] ) );
    $comentario =           mysqli_real_escape_string( $dbc, trim( $_POST['comentario'] ) );
    $new_file =             mysqli_real_escape_string( $dbc, trim( $_FILES['new_file']['name'] ) );
    $new_file_type = $_FILES['new_file']['type'];
    $new_file_size = $_FILES['new_file']['size'];

    $output_form = 'no';
  }

  ?>
  <section id="main-container">
    <div class="row">
      <form class="signup-form" method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>">

        <div class="col s5">
          <div class="signup-box">
            <div class="container">

              <div class="input-field">
                <i class="material-icons prefix">view_quilt</i>
                <select id="cmbLotes" name="cmbLotes">
                  <option value="0">Seleccione # de Lote</option>
                <?php
                  $query = "SELECT * 
                                FROM ctas_lotes 
                                WHERE user_id = " . $_SESSION['user_id'] . 
                                " ORDER BY fecha_modificacion DESC;";
                      $result = mysqli_query($dbc, $query);
                      while ( $row = mysqli_fetch_array( $result ) )
                          echo '<option value="' . $row['id_lote'] . '" ' . fnloteSelect( $row['id_lote'] ) . '>' . $row['lote_anio'] . '</option>';
                    ?>
                </select>
                <label>Número de Lote</label>
              </div>

              <div class="input-field">
                <i class="material-icons prefix">description</i>
                <select id="cmbValijas" name="cmbValijas">
                  <option value="0">Seleccione # de Valija/Oficio</option>
                  <?php
                    $query = "SELECT ctas_valijas.id_valija, 
                                ctas_valijas.delegacion AS num_del, 
                                ctas_delegaciones.descripcion AS delegacion_descripcion, 
                                ctas_valijas.num_oficio_del,
                                ctas_valijas.num_oficio_ca, 
                                ctas_valijas.user_id
                              FROM ctas_valijas, ctas_delegaciones 
                              WHERE ctas_valijas.delegacion = ctas_delegaciones.delegacion
                              ORDER BY ctas_valijas.id_valija DESC";
                    $result = mysqli_query( $dbc, $query );
                    while ( $row = mysqli_fetch_array( $result ) )
                      echo '<option value="' . $row['id_valija'] . '" ' . fnvalijaSelect( $row['id_valija'] ) . '>' . $row['num_oficio_ca'] . ': ' . $row['num_del'] . '-' . $row['delegacion_descripcion'] . '</option>';
                  ?>
                </select>
                <label>Número de Valija/Oficio</label>
              </div>

              <div class="input-field">
                <i class="material-icons prefix">today</i>
                <input id="fecha_solicitud_del" required type="text" name="fecha_solicitud_del" type="date" class="datepicker picker__input" value="<?php if ( !empty( $fecha_solicitud_del ) ) echo $fecha_solicitud_del; ?>"/>
                <label data-error="Error" for="fecha_solicitud_del">Fecha de la Solicitud</label>
              </div>

              <div class="input-field">
                <i class="material-icons prefix">view_list</i>
                  <select id="cmbtipomovimiento" name="cmbtipomovimiento">
                    <option value="0">Seleccione Tipo de Movimiento</option>
                    <?php
                      $result = mysqli_query( $dbc, "SELECT * 
                                                      FROM ctas_movimientos 
                                                      ORDER BY 1 ASC" );
                      while ( $row = mysqli_fetch_array( $result ) )
                        echo '<option value="' . $row['id_movimiento'] . '" ' . fntipomovimientoSelect( $row['id_movimiento'] ) . '>' . $row['descripcion'] . '</option>';
                    ?>
                  </select>
                  <label for="cmbtipomovimiento">Tipo de Movimiento</label>
              </div>

              <div class="input-field">
                <i class="large material-icons prefix">business</i>
                <select id="cmbDelegaciones" name="cmbDelegaciones">
                  <option value="0">Seleccione Delegación</option>
                  <?php
                    $result = mysqli_query( $dbc, "SELECT * 
                                                    FROM ctas_delegaciones 
                                                    WHERE activo = 1 
                                                    ORDER BY delegacion" );
                    while ( $row = mysqli_fetch_array( $result ) )
                      echo '<option value="' . $row['delegacion'] . '" ' . fntdelegacionSelect( $row['delegacion'] ) . '>' . $row['delegacion'] . ' - ' . $row['descripcion'] . '</option>';
                  ?>
                </select>
                <label>Delegación IMSS</label>
              </div>

              <div class="input-field">
                <i class="material-icons prefix">store</i>
                <select class="active validate" id="cmbSubdelegaciones" name="cmbSubdelegaciones">
                  <option value="-1" >Seleccione Subdelegación</option>
                  <?php
                    if ( !empty( $_POST['cmbSubdelegaciones'] ) || $_POST['cmbSubdelegaciones'] == "0" ) 
                    $result = mysqli_query( $dbc, "SELECT * 
                                                    FROM ctas_subdelegaciones 
                                                    WHERE delegacion = " . $_POST['cmbDelegaciones'] . " ORDER BY subdelegacion" );
                    while ( $row = mysqli_fetch_array( $result ) )
                      echo '<option value="' . $row['subdelegacion'] . '" ' . fntsubdelegacionSelect( $row['subdelegacion'] ) . '>' . $row['subdelegacion'] . ' - ' . $row['descripcion'] . '</option>';
                  ?>
                </select>
                <label data-error="Error" for="cmbSubdelegaciones">Subdelegación IMSS</label>
              </div>

              <div class="input-field">
                <i class="material-icons prefix">perm_identity</i>
                <input type="text" required class="active validate" name="primer_apellido" id="primer_apellido" length="32" value="<?php if ( !empty( $primer_apellido ) ) echo $primer_apellido; ?>"/>
                <label data-error="Error" for="primer_apellido">Primer apellido</label>
              </div>

              <div class="input-field">
                <i class="material-icons prefix">perm_identity</i>
                <input type="text" class="active validate" name="segundo_apellido" id="segundo_apellido" length="32" value="<?php if ( !empty( $segundo_apellido ) ) echo $segundo_apellido; ?>"/>
                <label data-error="Error" for="segundo_apellido">Segundo apellido</label>
              </div>

              <div class="input-field">
                <i class="material-icons prefix">perm_identity</i>
                <input type="text" required class="active validate" name="nombre" id="nombre" length="32" value="<?php if ( !empty( $nombre ) ) echo $nombre; ?>"/>
                <label data-error="Error" for="nombre">Nombre(s)</label>
              </div>


            </div>
          </div>
        </div>

        <div class="col s5">
          <div class="signup-box">
            <div class="container">

              <div class="input-field">
                <i class="material-icons prefix">assignment_ind</i>
                <input type="text" required class="active validate" name="matricula" id="matricula" length="32" value='<?php if ( !empty( $matricula ) ) echo $matricula; ?>'/>
                <label data-error="Error" for="matricula">Matrícula</label>
              </div>

              <div class="input-field">
                <!-- <div class="section"> -->
                  <i class="material-icons prefix">account_circle</i>
                  <input type="text" required class="active validate" name="curp" id="curp" length="18" value="<?php if ( !empty( $curp ) ) echo $curp; ?>" />
                  <label data-error="Error" for="curp">CURP</label>
                <!-- </div> -->
              </div>

              <div class="input-field">
                <i class="material-icons prefix">assignment</i>
                <input type="text" required class="active validate" name="usuario" id="usuario" length="7" value="<?php if ( !empty( $usuario ) ) echo $usuario; ?>" />
                <label data-error="Error" for="usuario">Usuario</label>
              </div>

              <div class="input-field">
                <i class="material-icons prefix">label_outline</i>
                <select id="cmbgpoactual" class="active validate" name="cmbgpoactual">
                  <option value="0">Seleccione Grupo Actual</option>
                  <?php
                    $result = mysqli_query( $dbc, "SELECT * 
                                                    FROM ctas_grupos 
                                                    WHERE id_grupo <> 0
                                                    ORDER BY descripcion ASC" );
                    while ( $row = mysqli_fetch_array( $result ) )
                      echo '<option value="' . $row['id_grupo'] . '" ' . fntcmbgpoactualSelect( $row['id_grupo'] ) .'>' . $row['descripcion'] . '</option>';
                  ?>
                </select>
                <label>Grupo Actual</label>
              </div>

              <div class="input-field">
                <i class="material-icons prefix">label</i>
                <select id="cmbgponuevo" name="cmbgponuevo">
                  <option value="0">Seleccione Grupo Nuevo</option>
                  <?php
                    $result = mysqli_query( $dbc, "SELECT * 
                                                    FROM ctas_grupos 
                                                    WHERE id_grupo <> 0
                                                    ORDER BY descripcion ASC" );
                    while ( $row = mysqli_fetch_array( $result ) )
                      echo '<option value="' . $row['id_grupo'] . '" ' . fntcmbgponuevoSelect( $row['id_grupo'] ) .'>' . $row['descripcion'] . '</option>';
                  ?>
                </select>
                <label>Grupo Nuevo</label>
              </div>

              <div class="input-field">
                <i class="material-icons prefix">report_problem</i>
                <select id="cmbcausarechazo" name="cmbcausarechazo">
                  <option value="-1">Seleccione Causa de Rechazo</option>
                  <?php
                    $result = mysqli_query( $dbc, "SELECT * 
                                                    FROM ctas_causasrechazo
                                                    ORDER BY id_causarechazo ASC" );
                    while ( $row = mysqli_fetch_array( $result ) )
                      echo '<option value="' . $row['id_causarechazo'] . '" ' . fntcmbcausarechazoSelect( $row['id_causarechazo'] ) .'>' . $row['id_causarechazo'] . ' - ' . $row['descripcion'] . '</option>';
                  ?>
                </select>
                <label>Causa de Rechazo</label>
              </div>
                  
              <div class="input-field">
                <i class="material-icons prefix">comment</i>
                <textarea class="materialize-textarea" class="validate" id="comentario" length="256" name="comentario"><?php if ( !empty( $comentario ) ) echo $comentario; ?></textarea>
                <label data-error="Error" for="comentario">Comentario</label>
              </div>

              <div class="input-field">
                <div class="file-field input-field">
                  <div class="btn">
                    <span>Archivo</span>
                    <input type="file" id="new_file" name="new_file">
                  </div>
                  <div class="file-path-wrapper">
                    <input class="file-path validate" type="text">
                  </div>
                </div>
              </div>

              <div class="section" align="center">
                <button class="btn waves-effect waves-light btn-signup" type="submit" name="submit">Registra Solicitud<i class="material-icons right">send</i>
                </button>
              </div>

            </div>
          </div>
        </div>

        <div class="col s2">
          <div class="signup-box">
            <div class="container">
          
            <?php
              // Connect to the database
              $dbc = mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );

              if ( mysqli_connect_errno () ) {
                printf("Connect failed: %s\n", mysqli_connect_error());
                return "Falló la conexión a base de datos";
                require_once('footer.php');
                exit(); 
              }

              $error_msg = fnConnect( $dbc );

              if ( isset( $_POST['submit'] ) ) {
                
                //Inician validaciones
                if ( empty( $cmbLotes ) ) {
                  echo '<p class="error">Olvidaste seleccionar un Lote.</p>';
                  $output_form = 'yes';
                }

                if ( empty( $cmbValijas ) ) {
                  echo '<p class="error">Olvidaste seleccionar una Valija.</p>';
                  $output_form = 'yes';
                }
                
                if ( !preg_match( '/^[0-9]{9}$/', $fecha_solicitud_del ) ) {
                  //'dd/mm/yyyy'
                  $anio = substr( $fecha_solicitud_del, 6, 4 );
                  $mes  = substr( $fecha_solicitud_del, 3, 2 );
                  $dia  = substr( $fecha_solicitud_del, 8, 2 );
                  
                  if ( !checkdate( $mes*1, $dia*1, $anio*1 ) ) {
                    echo '<p class="error">Fecha de la solicitud inválida.';
                    echo ' Año:'  . $anio;
                    echo ' Mes:'         . $mes;
                    echo ' Día:'  . $dia  . '<br />';
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

                if ( $cmbcausarechazo == -1 ) {
                  echo '<p class="error">Olvidaste capturar Causa de Rechazo</p>';
                  $output_form = 'yes';
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

                          // Conectarse a la BD
                          $dbc = mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
                          $query = "INSERT INTO ctas_solicitudes 
                                      ( id_valija, id_lote, 
                                        fecha_solicitud_del, 
                                        delegacion, subdelegacion, 
                                        nombre, primer_apellido, segundo_apellido, matricula, curp, 
                                        usuario, id_movimiento, id_grupo_nuevo, id_grupo_actual,
                                        comentario, id_causarechazo, archivo, user_id )
                                    VALUES 
                                    ( '$cmbValijas', '$cmbLotes',
                                      STR_TO_DATE('$fecha_solicitud_del', '%d/%m/%Y'),
                                      '$cmbDelegaciones', '$cmbSubdelegaciones', 
                                      '$nombre', '$primer_apellido', '$segundo_apellido', 
                                      '$matricula', '$curp',
                                      '$usuario', '$cmbtipomovimiento', '$cmbgponuevo', '$cmbgpoactual',
                                      '$comentario', $cmbcausarechazo, '$timetime $new_file', " . $_SESSION['user_id'] . " )";
                          //echo $query;
                          mysqli_query( $dbc, $query );

                          $query = "SELECT LAST_INSERT_ID()";
                          $result = mysqli_query( $dbc, $query );
                                        
                          $data = mysqli_query( $dbc, $query );

                          if ( mysqli_num_rows( $data ) == 1 ) {
                            // The user row was found so display the user data
                            $row = mysqli_fetch_array($data);

                          echo '<p class="nota"><strong>La nueva <a href="versolicitud.php?id_solicitud=' . $row['LAST_INSERT_ID()'] . '">solicitud</a> ha sido creada exitosamente.</strong></p>';

                          }                                                        

                            echo '<p class="titulo2">Puede agregar una <a href="agregarsolicitud.php">nueva solicitud</a></p>';
                            echo '<p class="titulo2">Agregar <a href="agregarvalija.php">nueva valija</a></p>';
                            echo '<p>O puede regresar al <a href="indexCuentasSINDO.php">inicio</a></p>';

                          // Clear the score data to clear the form
                          $_POST['cmbLote']    = 0;
                          $_POST['cmbValijas'] = 0;
                          $_POST['fecha_solicitud_del'] = "";
                          $_POST['cmbtipomovimiento'] = 0;
                          $_POST['cmbDelegaciones'] = 0;
                          $_POST['cmbSubdelegaciones'] = -1;
                          $_POST['nombre'] = "";
                          $_POST['primer_apellido'] = "";
                          $_POST['segundo_apellido'] = "";
                          $_POST['matricula'] = "";
                          $_POST['curp'] = "";
                          $_POST['cargo'] = "";
                          $_POST['usuario'] = "";
                          $_POST['cmbgpoactual'] = 0;
                          $_POST['cmbgponuevo'] = 0;
                          $_POST['cmbcausarechazo'] = -1;
                          $_POST['comentario'] = "";
                          $_POST['new_file'] = "";

                          mysqli_close( $dbc );
                          //exit();
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

            //$cmbDelegaciones = 0;
            //$cmbSubdelegaciones = -1;
            ?>
              
            </div>
          </div>
        </div>

      </form>
    </div>
    
  </section>
  <?php
    //mysqli_close( $dbc );
    // Insert the page footer
    require_once('footer.php');
  ?>


