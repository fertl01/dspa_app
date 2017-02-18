<?php
  // Start the session
  require_once( '..\commonfiles\startsession.php' );

  require_once( '.\lib\appvars.php');
  require_once( '.\lib\connectvars.php');

  // Insert the page header
  $page_title = MM_APPNAME;
  require_once( '.\lib\funciones.php' );
  require_once( 'headerSAIIA.php' );
  /*<body> This tag came from headerSAIIA.php*/

  // Make sure the user is logged in before going any further.
  if ( !isset( $_SESSION['user_id'] ) ) {
    echo '<p class="error">Por favor <a href="loginSAIIA.php">inicia sesión</a> para acceder a esta página.</p>';
    require_once( 'footerSAIIA.php' );
    exit();
  }

  // Connect to the database
  $dbc = mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );

  if ( mysqli_connect_errno () ) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    return "Falló la conexión a base de datos";
    require_once('footerSAIIA.php');
    exit(); 

?>
    <form class="signup-form" method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            
        <div class="col s5">
          <div class="signup-box">
            <div class="container">

              <section id="inventarios" class="inventarios contenedor">

                <!-- <div class="input-field"> -->
                  <h2>Delegación</h2>
                  <i class="large material-icons prefix">business</i>
                  <select id="cmbDelegaciones" name="cmbDelegaciones">
                    <option value="0">Seleccione Delegación</option>
                    <?php

                      $result = mysqli_query( $dbc, " SELECT 
                                                                SUBSTR( CONCAT( '00', saiia_delegaciones.delegacion ), -2) AS num_delegacion,
                                                                saiia_delegaciones.descripcion  
                                                      FROM      saiia_delegaciones 
                                                      WHERE     activo = 1 
                                                      ORDER BY  num_delegacion" );
                      while ( $row = mysqli_fetch_array( $result ) )
                        echo '<option value="' . $row['num_delegacion'] . '" ' . fntdelegacionSelect( $row['num_delegacion'] ) . '>' . $row['num_delegacion'] . ' - ' . $row['descripcion'] . '</option>';
                    ?>
                  </select>

              </section>

            </div>
          </div>
        </div>

      </form>
<?php

  }
?>
    <section id="inventarios" class="inventarios contenedor"> <!-- inventario -->
      <article class="detalleinventario"> <!-- detalleinventario 1 -->
        <img class="derecha" src="images/sign_up_256.png" alt="Foto representativa Delegación" width="270"/>
        <div class="contenedor-detalleinventario-a">
            <h2>12-Guerrero</h2>
            <h4>Subdelegación 02-Acapulco</h4>
            <h6>En algún lugar del pacífico mexicano</h6>
            <a class="button" href ="https://goo.gl/maps/Xx588P2E5oz" target="_blank">Ver mapa</a>
            <a class="button" href="http://www.meetup.com/MOB-Mexico-on-Board/about/" target="_blank">Detalles Subdelegación</a>
        </div>
      </article>
    </section>

<?php
  echo '<div class="section no-pad-bot" id="index-banner">';
    echo '<div class="container">';
      echo '<div class="row center">';

  // Conectarse a la BD
/*  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);*/

  //Mostrar solicitudes del penúltimo lote
  // Obtener todas las solicitudes capturadas al momento para el penúltimo lote modificado
  $query = "SELECT 
              saiia_inventario.delegacion AS num_del, saiia_delegaciones.descripcion AS delegacion_descripcion, 
              saiia_inventario.subdelegacion AS num_subdel, saiia_subdelegaciones.descripcion AS subdelegacion_descripcion, 
              saiia_inventario.mac_address,
              CONCAT( SUBSTR( saiia_inventario.mac_address, 1, 2), '-', SUBSTR( saiia_inventario.mac_address, 3, 2), '-',  
                      SUBSTR( saiia_inventario.mac_address, 5, 2), '-', SUBSTR( saiia_inventario.mac_address, 7, 2), '-', 
                      SUBSTR( saiia_inventario.mac_address, 9, 2), '-', SUBSTR( saiia_inventario.mac_address, 11, 2)
              ) AS MAC2,
              mac_address_checked,
              saiia_estatus_equipo.descripcion, 
              saiia_inventario.comentario,
              fecha_captura_ca, fecha_solicitud_del, fecha_modificacion,
              user_id
            FROM saiia_inventario, saiia_delegaciones, saiia_subdelegaciones, saiia_estatus_equipo
            WHERE saiia_inventario.id_estatus_equipo  = saiia_estatus_equipo.id_estatus_equipo
              AND   saiia_inventario.delegacion       = saiia_delegaciones.delegacion
              AND   saiia_inventario.delegacion       = saiia_subdelegaciones.delegacion
              AND   saiia_inventario.subdelegacion    = saiia_subdelegaciones.subdelegacion
            ORDER BY saiia_inventario.delegacion, saiia_inventario.subdelegacion, saiia_estatus_equipo.descripcion, fecha_modificacion, mac_address";
    
  $data = mysqli_query($dbc, $query);

  /*echo '<p class="titulo1">Últimas Solicitudes Capturadas</p>';*/
  
  echo '<table border="1">';
  echo '<tr>';
  echo '<th>Delegación - Subdelegación</th>';
  echo '<th>MAC ADDRESS</th>';
  echo '<th>Fecha Solicitud</th>';
  
  /*echo '<th>¿Verificada?</th>';*/
  echo '<th>Estatus</th>';
  echo '<th>Comentario</th>';
  /*echo '<th>Fecha Captura</th>';*/
  /*echo '<th>Fecha Modificación</th>';*/
  /*echo '<th>Usuario(Mov)</th>';*/
  echo '</tr>';

  if (mysqli_num_rows($data) == 0) {
    echo '</table></br><p class="error">No hay registros en inventario. Revisar con DSPA</p></br>';
    require_once('footerSAIIA.php');
    exit();
  }

  while ( $row = mysqli_fetch_array($data) ) {

    echo '<tr class="dato condensed">';
    echo '<td>(' . $row['num_del'] . ') ' . $row['delegacion_descripcion'] . ' - (' . $row['num_subdel'] . ') ' . $row['subdelegacion_descripcion'] . '</td>';
    echo '<td><p>' . $row['MAC2'] . '</p><p>' . $row['descripcion'] . ' (' . $row['mac_address_checked'] .')</p> </td>';
    echo '<td>' . $row['fecha_solicitud_del'] . '</td>';

    /*echo '<td>' . $row['mac_address_checked'] . '</td>';*/
    echo '<td>' . $row['descripcion'] . '</td>';
    echo '<td>' . $row['comentario'] . '</td>';
    /*echo '<td>' . $row['fecha_captura_ca'] . '</td>';*/
    /*echo '<td>' . $row['fecha_modificacion'] . '</td>';*/
    echo '</tr>';
  }    

  echo '</table></br></br>';
 
    require_once('footerSAIIA.php');
  ?>

  </body>

</html>
