<?php

  // Start the session
  require_once( 'startsession.php' );

  require_once( 'appvars.php' );
  require_once( 'connectvars.php' );
  
  // Insert the page header
  $page_title = 'Gestión Cuentas SINDO - Capturar Solicitud';
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

  $query = "SELECT 
      ctas_solicitudes.id_solicitud, ctas_solicitudes.id_valija, 
      ctas_solicitudes.fecha_captura_ca, ctas_solicitudes.fecha_solicitud_del, ctas_solicitudes.fecha_modificacion, ctas_solicitudes.id_lote,
      ctas_solicitudes.delegacion, ctas_solicitudes.subdelegacion, 
      ctas_solicitudes.nombre, ctas_solicitudes.primer_apellido, ctas_solicitudes.segundo_apellido, 
      ctas_solicitudes.matricula, ctas_solicitudes.curp, ctas_solicitudes.curp_correcta, ctas_solicitudes.cargo, ctas_solicitudes.usuario, 
      ctas_solicitudes.id_movimiento, ctas_solicitudes.id_grupo_actual, ctas_solicitudes.id_grupo_nuevo, 
      ctas_solicitudes.comentario, ctas_solicitudes.id_causarechazo, ctas_solicitudes.archivo,
      CONCAT(ctas_usuarios.first_name, ' ', ctas_usuarios.first_last_name) AS creada_por
      FROM ctas_solicitudes, ctas_grupos grupos1, ctas_grupos grupos2, ctas_usuarios
      WHERE ctas_solicitudes.id_grupo_nuevo= grupos1.id_grupo
      AND   ctas_solicitudes.id_grupo_actual= grupos2.id_grupo
      AND   ctas_solicitudes.user_id = ctas_usuarios.user_id ";

    if ( !isset( $_GET['id_solicitud'] ) ) {
      $query = $query . "AND ctas_solicitudes.id_solicitud = '" . $_SESSION['id_solicitud'] . "'";
      
    } else {
      $query = $query . "AND ctas_solicitudes.id_solicitud = '" . $_GET['id_solicitud'] . "'";
    }

    /*echo $query;*/
    $data = mysqli_query( $dbc, $query );

    if ( mysqli_num_rows( $data ) == 1 ) {
      // The user row was found so display the user data
      $row = mysqli_fetch_array($data);

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
      } else {
        $_POST['cmbLotes']    = $row['id_lote'];
        $_POST['cmbValijas'] = $row['id_valija'];
        /*$_POST['fecha_solicitud_del'] = $row['fecha_solicitud_del'];*/
        $_POST['cmbtipomovimiento'] = $row['id_movimiento'];
        $_POST['cmbDelegaciones'] = $row['delegacion'];
        $_POST['cmbSubdelegaciones'] = $row['subdelegacion'];
        /*$_POST['nombre'] = $row['nombre'];
        $_POST['primer_apellido'] = $row['primer_apellido'];
        $_POST['segundo_apellido'] = $row['segundo_apellido'];
        $_POST['matricula'] = $row['matricula'];
        $_POST['curp'] = $row['curp'];
        $_POST['usuario'] = $row['usuario'];*/
        $_POST['cmbcausarechazo'] = $row['id_causarechazo'];
        $_POST['cmbgpoactual'] = $row['id_grupo_actual'];
        $_POST['cmbgponuevo'] = $row['id_grupo_nuevo'];
        /*$_POST['comentario'] = $row[''];*/
        /*$_POST['new_file'] = $row['archivo'];*/
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
                <select id="cmbLotes" name="cmbLotes" >
                <option value="0">Seleccione # de Lote</option>
                <?php
                  $query = "SELECT id_lote, lote_anio
                            FROM ctas_lotes
                            ORDER BY 1 DESC";
                      $result = mysqli_query( $dbc, $query );
                      while ( $row2 = mysqli_fetch_array( $result ) )
                        echo '<option value="' . $row2['id_lote'] . '" ' . fnloteSelect( $row2['id_lote'] ) . '>' . $row2['lote_anio'] . '</option>';
                    ?>
                </select>
                <label>Número de Lote</label>
              </div>

                <div class="input-field">
                  <i class="material-icons prefix">description</i>
                  <select id="cmbValijas" name="cmbValijas" >
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
                              ORDER BY ctas_valijas.id_valija";
                      $result = mysqli_query( $dbc, $query );
                      while ( $row2 = mysqli_fetch_array( $result ) )
                        echo '<option value="' . $row2['id_valija'] . '" ' . fnvalijaSelect( $row2['id_valija'] ) . '>' . $row2['num_oficio_ca'] . ': ' . $row2['num_del'] . '-' . $row2['delegacion_descripcion'] . '</option>';
                    ?>
                  </select>
                  <label>Número de Valija/Oficio</label>
                </div>

                <div class="input-field">
                  <i class="material-icons prefix">today</i>
                  
                  <input id="fecha_solicitud_del"  type="text" name="fecha_solicitud_del" type="date" format="d-m-y" class="datepicker picker__input" value="<?php if ( !empty( $row['fecha_solicitud_del'] ) ) echo $row['fecha_solicitud_del']; ?>"/>
                  <label data-error="Error" for="fecha_solicitud_del">Fecha de la Solicitud</label>
                </div>

                <div class="input-field">
                  <i class="material-icons prefix">view_list</i>
                  <select id="cmbtipomovimiento" name="cmbtipomovimiento" >
                  <option value="0">Seleccione Tipo de Movimiento</option>
                    <?php
                      $result = mysqli_query( $dbc, "SELECT * 
                                                      FROM ctas_movimientos 
                                                      ORDER BY 1 ASC" );
                      while ( $row2 = mysqli_fetch_array( $result ) )
                        echo '<option value="' . $row2['id_movimiento'] . '" ' . fntipomovimientoSelect( $row2['id_movimiento'] ) . '>' . $row2['descripcion'] . '</option>';
                    ?>
                  </select>
                  <label for="cmbtipomovimiento">Tipo de Movimiento</label>
                </div>

                <div class="input-field">
                  <i class="large material-icons prefix">business</i>
                  <select id="cmbDelegaciones" name="cmbDelegaciones" >
                  <option value="0">Seleccione Delegación</option>
                    <?php
                      $query = "SELECT * 
                                FROM ctas_delegaciones 
                                WHERE activo = 1 
                                ORDER BY delegacion";
                      $result = mysqli_query( $dbc, $query );
                      while ( $row2 = mysqli_fetch_array( $result ) )
                        echo '<option value="' . $row2['delegacion'] . '" ' . fntdelegacionSelect( $row2['delegacion'] ) . '>' . $row2['delegacion'] . ' - ' . $row2['descripcion'] . '</option>';
                    ?>
                  </select>
                  <label>Delegación IMSS</label>
                </div>

                <div class="input-field">
                  <i class="material-icons prefix">store</i>
                  <select class="active validate" id="cmbSubdelegaciones" name="cmbSubdelegaciones" >
                  <option value="-1" >Seleccione Subdelegación</option>
                    <?php
                    if ( !empty( $_POST['cmbSubdelegaciones'] ) || $_POST['cmbSubdelegaciones'] == "0" ) 
                    $result = mysqli_query( $dbc, "SELECT * 
                                                    FROM ctas_subdelegaciones 
                                                    WHERE delegacion = " . $_POST['cmbDelegaciones'] . " ORDER BY subdelegacion" );
                    while ( $row2 = mysqli_fetch_array( $result ) )
                      echo '<option value="' . $row2['subdelegacion'] . '" ' . fntsubdelegacionSelect( $row2['subdelegacion'] ) . '>' . $row2['subdelegacion'] . ' - ' . $row2['descripcion'] . '</option>';
                  ?>
                  </select>
                  <label data-error="Error" for="cmbSubdelegaciones">Subdelegación IMSS</label>
                </div>

                <div class="input-field">
                  <i class="material-icons prefix">perm_identity</i>
                  <input type="text" class="active validate" name="primer_apellido" id="primer_apellido" length="32" value="<?php if ( !empty( $row['primer_apellido'] ) ) echo $row['primer_apellido']; ?>"/>
                  <label data-error="Error" for="primer_apellido">Primer apellido</label>
                </div>

                <div class="input-field">
                  <i class="material-icons prefix">perm_identity</i>
                  <input type="text"  class="active validate" name="segundo_apellido" id="segundo_apellido" length="32" value="<?php if ( !empty( $row['segundo_apellido'] ) ) echo $row['segundo_apellido']; ?>"/>
                  <label data-error="Error" for="segundo_apellido">Segundo apellido</label>
                </div>

                <div class="input-field">
                  <i class="material-icons prefix">perm_identity</i>
                  <input type="text" required  class="active validate" name="nombre" id="nombre" length="32" value="<?php if ( !empty( $row['nombre'] ) ) echo $row['nombre']; ?>"/>
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
                  <input type="text" required  class="active validate" name="matricula" id="matricula" length="32" value='<?php if ( !empty( $row['matricula'] ) ) echo $row['matricula']; ?>'/>
                  <label data-error="Error" for="matricula">Matrícula</label>
                </div>
                
                <div class="input-field">
                  <!-- <div class="section"> -->
                    <i class="material-icons prefix">account_circle</i>
                    <input type="text" required  class="active validate" name="curp" id="curp" length="18" value="<?php if ( !empty( $row['curp'] ) ) echo $row['curp']; ?>" />
                    <label data-error="Error" for="curp">CURP</label>
                </div>

                <div class="input-field">
                  <i class="material-icons prefix">assignment</i>
                  <input type="text" required  class="active validate" name="usuario" id="usuario" length="7" value="<?php if ( !empty( $row['usuario'] ) ) echo $row['usuario']; ?>" />
                  <label data-error="Error" for="usuario">Usuario</label>
                </div>

                <div class="input-field">
                  <i class="material-icons prefix">label_outline</i>
                  <select id="cmbgpoactual" class="active validate" name="cmbgpoactual" >
                    <option value="0">Seleccione Grupo Actual</option>
                    <?php
                      $query = "SELECT * 
                                FROM ctas_grupos 
                                WHERE id_grupo <> 0
                                ORDER BY descripcion ASC";
                      $result = mysqli_query( $dbc, $query );
                      while ( $row2 = mysqli_fetch_array( $result ) )
                        echo '<option value="' . $row2['id_grupo'] . '" ' . fntcmbgpoactualSelect( $row2['id_grupo'] ) .'>' . $row2['descripcion'] . '</option>';
                    ?>
                  </select>
                  <label>Grupo Actual</label>
                </div>

                <div class="input-field">
                  <i class="material-icons prefix">label</i>
                  <select id="cmbgponuevo" name="cmbgponuevo" >
                  <option value="0">Seleccione Grupo Nuevo</option>
                    <?php
                      $query = "SELECT * 
                                FROM ctas_grupos 
                                WHERE id_grupo <> 0
                                ORDER BY descripcion ASC";
                      $result = mysqli_query( $dbc, $query );
                      while ( $row2 = mysqli_fetch_array( $result ) )
                        echo '<option value="' . $row2['id_grupo'] . '" ' . fntcmbgponuevoSelect( $row2['id_grupo'] ) .'>' . $row2['descripcion'] . '</option>';
                    ?>
                  </select>
                  <label>Grupo Nuevo</label>
                </div>

                <div class="input-field">
                  <i class="material-icons prefix">report_problem</i>
                  <select id="cmbcausarechazo" name="cmbcausarechazo">
                  <!-- <option value="-1">Seleccione Causa de Rechazo</option> -->
                  <?php
                      $query = "SELECT * 
                                FROM ctas_causasrechazo
                                ORDER BY id_causarechazo ASC";

                      $result = mysqli_query( $dbc, $query );
                      while ( $row2 = mysqli_fetch_array( $result ) )
                        echo '<option value="' . $row2['id_causarechazo'] . '"" ' . fntcmbcausarechazoSelect( $row2['id_causarechazo'] ) .'>' . $row2['id_causarechazo'] . ' - ' . $row2['descripcion'] . '</option>';
                    ?>
                    <option value="-1">Seleccione Causa de Rechazo</option>
                  </select>
                  <label>Causa de Rechazo</label>
                </div>
                    
                <div class="input-field">
                  <i class="material-icons prefix">comment</i>
                  <textarea class="materialize-textarea"  class="validate" id="comentario" length="256" name="comentario"><?php if ( !empty( $row['comentario'] ) ) echo $row['comentario']; ?></textarea>
                  <label data-error="Error" for="comentario">Comentario</label>
                </div>

                <div>
                  <i class="material-icons prefix">description</i>
                  <label data-error="Error" for="usuario">Archivo</label>
                  <div class="section" align="right">
                    <?php 
                      if ( !empty( $row['archivo'] ) ) 
                        echo '<a href="' . MM_UPLOADPATH_CTASSINDO . '\\' . $row['archivo'] . '"  target="_new">' . $row['archivo'] . '</a>';
                      else echo '(Vacío)';
                    ?>
                  </div>
                </div>

                <div class="input-field">
                  <div class="file-field input-field">
                    <div class="btn">
                      <span>Archivo</span>
                      <input type="file" id="new_file" name="new_file" value="fer.php">
                    </div>
                    <div class="file-path-wrapper">
                      <input class="file-path validate" type="text">
                    </div>
                  </div>
                </div>

                <div class="section" align="center">
                  <button class="btn waves-effect waves-light btn-signup" type="submit" name="submit">Actualiza Solicitud<i class="material-icons right">send</i>
                  </button>
                </div>

              </div>
            </div>
          </div>

          <div class="col s2">
            <div class="signup-box">
              <div class="container">

                <div class="input-field">

                  <label>Capturada por <?php if ( !empty( $row['creada_por'] ) ) echo $row['creada_por']; ?></label>

                </div>
                <div class="section" align="center">

                  <?php
                    if (!isset($_GET['user_id']) || ($_SESSION['user_id'] == $_GET['user_id'])) 
                      echo '<p>¿Deseas editar esta <a href="editarsolicitud.php?id_solicitud=' . $row['id_solicitud'] . '">solicitud</a>?</p>';
                  ?>

                </div>
              </div>
            </div>
          </div>
        </form>

      </div>
      
    </section>
    <?php
    }
      // Insert the page footer
      require_once('footer.php');
    ?>


