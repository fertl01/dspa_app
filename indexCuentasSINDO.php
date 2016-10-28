<?php
  // Start the session
  require_once('startsession.php');

  // Insert the page header
  $page_title = 'Gesti&oacute;n Cuentas SINDO';
  require_once('headerCuentasSINDO.php');

  // Show the navigation menu
  require_once('navmenu.php');

  // Make sure the user is logged in before going any further.
  if (!isset($_SESSION['user_id'])) {
    echo '<p class="login">Por favor <a href="login.php">inicia sesi&oacute;n</a> para acceder a esta p&aacute;gina.</p>';
    // Insert the page footer
    require_once('footer.php');
    exit();
  }

  require_once('appvars.php');
  require_once('connectvars.php');

  // Conectarse a la BD
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

  //Mostrar lotes
    
  // Obtener los últimos lotes capturados al momento
  $query = "SELECT lotes.id_lote, lotes.lote_anio, 
    lotes.fecha_modificacion, lotes.fecha_creacion, lotes.comentario,
    (SELECT COUNT(*) FROM solicitudes WHERE solicitudes.id_lote = lotes.id_lote) AS num_solicitudes,
    CONCAT(dspa_user.first_name, ' ', dspa_user.first_last_name) AS creado_por, lotes.num_oficio_ca, 
    lotes.fecha_oficio_ca, lotes.num_ticket_mesa, lotes.fecha_atendido
    FROM lotes, dspa_user
    WHERE lotes.user_id = dspa_user.user_id
    ORDER BY lotes.fecha_modificacion DESC LIMIT 20";

  $data = mysqli_query($dbc, $query);

/*
  $mi_pdf = MM_UPLOADPATH_CTASSINDO . '\\' . '1452713181 60953_3.pdf';
  header('Content-type: application/pdf');
  header('Content-Disposition: attachment; filename="'. $mi_pdf . '"');
  readfile($mi_pdf);
  */

  echo '<p class="titulo1">&Uacute;ltimos 20 lotes</p>';
  
  //$t=time();
  //echo($t . "<br>");
  //echo(date("Y-m-d H:i:s",$t));

  //$t=time();
  //$t=$t-25200;//menos 7 horas
  //echo($t . "<br>Tiempo actual<br>");
  //echo(date("Y-m-d H:i:s",$t));

  //$t=time();
  //$t="1467671939";
  //echo($t . "<br>");
  //echo(date("Y-m-d H:i:s",$t));
  //echo($t . "<br>");
  //echo("<br> Tiempo archivo<br>");
  //$t=$t-86400;
  //$t=$t+18000;
  //echo(date("Y-m-d H:i:s",$t));
  //echo(ADDTIME(date("Y-m-d H:i:s",$t) , '02:00:00'));


  echo '<p class="titulo2">Agregar <a href="agregarlote.php">nuevo lote</a></p>';

  echo '<table border="1">';
  echo '<tr class="dato">';
    echo '<th># Lote</th>';
    echo '<th># Oficio CA</th>';
    echo '<th>Fecha oficio</th>';
    echo '<th># Ticket MSI</th>';
    echo '<th>Fecha de atenci&oacute;n</th>';
    echo '<th>Fecha modificaci&oacute;n</th>';
    echo '<th>Fecha creaci&oacute;n</th>';
    echo '<th>Cantidad de Solicitudes</th><th>Comentario</th>';
    echo '<th>Creado por</th>';
  echo '</tr>';

  if (mysqli_num_rows($data) == 0) {
    echo '</table></br><p class="error">No hay lotes capturados</p></br>';
    require_once('footer.php');
    exit();
  }

  while ( $row = mysqli_fetch_array($data) ) {
    $id_lote = $row['id_lote'];
    //echo '<tr class="dato"><td class="lista"><a href="editarlote.php?id_lote=' . $row['id_lote'] . '">' . $row['id_lote'] . ' / ' . $row['anio'] . '</a></td>';
    echo '<tr class="dato">';
      echo '<td class="lista">' . $row['lote_anio'] . '</td>';
      echo '<td class="lista">' . $row['num_oficio_ca'] . '</td>';
      echo '<td class="lista">' . $row['fecha_oficio_ca'] . '</td>';
      echo '<td class="lista">' . $row['num_ticket_mesa'] . '</td>';
      echo '<td class="lista">' . $row['fecha_atendido'] . '</td>';
      echo '<td class="lista">' . $row['fecha_modificacion'] . '</td>';
      echo '<td class="lista">' . $row['fecha_creacion'] . '</td>';
      echo '<td class="lista">' . $row['num_solicitudes']  . '</td>';
      echo '<td class="lista">' . $row['comentario'] . '</td>';
      echo '<td class="lista">' . $row['creado_por'] . '</td>';
    echo '</tr>';
  }

  echo '</table></br></br>';

  //Mostrar valijas
  // Obtener todas las valijas capturadas al momento
  $query = "SELECT valijas.id_valija, valijas.delegacion AS num_del, delegaciones.descripcion AS delegacion_descripcion, 
    valijas.num_oficio_ca, valijas.fecha_recepcion_ca, valijas.num_oficio_del, 
    valijas.fecha_valija_del, valijas.comentario, valijas.archivo,
    (SELECT COUNT(*) FROM solicitudes WHERE solicitudes.id_valija = valijas.id_valija) AS num_solicitudes,
    CONCAT(dspa_user.first_name, ' ', dspa_user.first_last_name) AS creada_por
  FROM valijas, delegaciones, dspa_user
  WHERE valijas.delegacion = delegaciones.delegacion 
  AND   valijas.user_id = dspa_user.user_id
  ORDER BY valijas.fecha_captura_ca DESC LIMIT 10";

  $data = mysqli_query($dbc, $query);

  echo '<p class="titulo1">&Uacuteltimas valijas capturadas</p>';
  echo '<p class="titulo2">Agregar <a href="agregarvalija.php">nueva valija</a></p>';
  
  echo '<table border="1">';
  //echo '<tr class="dato"><th># Valija</th>';
  echo '<tr class="dato">';
  echo '<th># &Aacute;rea de Gesti&oacute;n</th>';
  echo '<th>Fecha &Aacute;rea de Gesti&oacute;n</th>';
  
  echo '<th># Oficio Delegaci&oacute;n</th>';
  echo '<th>Fecha Oficio Delegaci&oacute;n</th>';

  echo '<th>Delegaci&oacute;n que env&iacute;a</th>';
  echo '<th>Comentario</th>';
  echo '<th>Archivo</th>';
  echo '<th>Cantidad de solicitudes</th>';
  echo '<th>Creada por</th>';
  echo '</tr>';

  if (mysqli_num_rows($data) == 0) {
    echo '</table></br><p class="error">No hay valijas capturadas</p></br>';
    require_once('footer.php');
    exit();
  }

  while ( $row = mysqli_fetch_array($data) ) {
    //$id_valija = $row['id_valija'];
    //echo '<tr class="dato"><td class="lista"><a href="editarvalija.php?id_valija=' . $row['id_valija'] . '">' . $row['id_valija'] . '</a></td>';
    echo '<tr class="dato">';
    //echo '<td class="lista">' . $row['id_valija'] . '</td>';
    echo '<td class="lista">' . $row['num_oficio_ca'] . '</td>';
    echo '<td class="lista">' . $row['fecha_recepcion_ca'] . '</td>';

    echo '<td class="lista">' . $row['num_oficio_del'] . '</td>';
    echo '<td class="lista">' . $row['fecha_valija_del'] . '</td>';

    echo '<td class="lista">' . '(' . $row['num_del'] . ')' . $row['delegacion_descripcion'] . '</td>';
    
    echo '<td class="lista">' . $row['comentario'] . '</td>';    
    //echo '<td class="lista">' . $row['archivo'] . '</td>';
    if (!empty($row['archivo'])) {
      echo '<td class="lista"><a href="' . MM_UPLOADPATH_CTASSINDO . '\\' . $row['archivo'] . '"  target="_blank">Ver</a></td>';
    }
    else {
      echo '<td class="lista">(Vac&iacute;o)</a></td>';
    }
    echo '<td class="lista">' . $row['num_solicitudes']  . '</td>';
    echo '<td class="lista">' . $row['creada_por'] . '</td>';
    echo '</tr>';
  }

  echo '</table></br></br>';
  
  //Mostrar solicitudes
  // Obtener todas las solicitudes capturadas al momento para el último lote modificado
  $query = "SELECT 
    solicitudes.id_solicitud, solicitudes.id_valija, valijas.num_oficio_ca, 
    solicitudes.fecha_captura_ca, solicitudes.fecha_solicitud_del, solicitudes.fecha_modificacion,
    lotes.lote_anio AS num_lote_anio, 
    solicitudes.delegacion AS num_del, delegaciones.descripcion AS delegacion_descripcion, 
    solicitudes.subdelegacion AS num_subdel, subdelegaciones.descripcion AS subdelegacion_descripcion, 
    solicitudes.nombre, solicitudes.primer_apellido, solicitudes.segundo_apellido, 
    solicitudes.matricula, solicitudes.curp, solicitudes.curp_correcta, solicitudes.cargo, solicitudes.usuario, 
    movimientos.descripcion AS movimiento_descripcion, 
    grupos1.descripcion AS grupo_nuevo, grupos2.descripcion AS grupo_actual, 
    solicitudes.comentario, solicitudes.rechazado, solicitudes.archivo,
    CONCAT(dspa_user.first_name, ' ', dspa_user.first_last_name) AS creada_por
    FROM solicitudes, valijas, lotes, delegaciones, subdelegaciones, movimientos, grupos grupos1, grupos grupos2, dspa_user
    WHERE solicitudes.id_lote       = lotes.id_lote
    AND   solicitudes.id_valija     = valijas.id_valija
    AND   solicitudes.delegacion    = subdelegaciones.delegacion
    AND   solicitudes.subdelegacion = subdelegaciones.subdelegacion
    AND   solicitudes.delegacion    = delegaciones.delegacion
    AND   solicitudes.id_movimiento = movimientos.id_movimiento
    AND   solicitudes.id_grupo_nuevo= grupos1.id_grupo
    AND   solicitudes.id_grupo_actual= grupos2.id_grupo
    AND   solicitudes.user_id = dspa_user.user_id
    AND   solicitudes.id_lote = (SELECT id_lote from lotes ORDER BY fecha_creacion DESC LIMIT 1)
    ORDER BY solicitudes.usuario ASC, solicitudes.fecha_modificacion DESC, solicitudes.usuario ASC";
    //ORDER BY solicitudes.id_solicitud DESC, solicitudes.usuario ASC, solicitudes.fecha_modificacion DESC";
    //ORDER BY solicitudes.id_movimiento ASC, solicitudes.usuario ASC, solicitudes.fecha_modificacion DESC, solicitudes.usuario ASC";
    //ORDER BY solicitudes.id_movimiento ASC, solicitudes.usuario ASC, solicitudes.fecha_modificacion DESC, solicitudes.id_movimiento ASC, solicitudes.usuario ASC";
    //AND   solicitudes.id_lote = 83
    //AND   solicitudes.rechazado <> 1


    
    
    
    //movimientos.descripcion, solicitudes.usuario,
    //AND   solicitudes.rechazado <> 1
    //AND   solicitudes.rechazado <> 1
  $data = mysqli_query($dbc, $query);

  echo '<p class="titulo1">Solicitudes del &uacute;ltimo lote</p>';
  echo '<p class="titulo2">Agregar <a href="agregarsolicitud.php">nueva solicitud</a></p>';

  echo '<table border="1">';
  echo '<tr class="dato">';
  //echo '<th># Solicitud</th>';
  //echo '<th># Valija</th>';
  echo '<th># de Lote</th>';
  echo '<th># &Aacute;rea de Gesti&oacute;n</th>';
  //echo '<th>Fecha captura</th>';
  echo '<th>Delegaci&oacute;n - Subdelegaci&oacute;n</th>';
  echo '<th>Nombre completo</th>';
  echo '<th>Matr&iacute;cula</th>';
  echo '<th>CURP</th>';
  //echo '<th>CURP correcta</th>';
  //echo '<th>Cargo</th>';
  echo '<th>Usuario</th>';
  echo '<th>Movimiento</th><th>Grupo actual</th><th>Grupo nuevo</th>';
  echo '<th>Comentario</th><th>Rechazado</th><th>Archivo</th>';
  echo '<th>Creada por</th>';
  echo '</tr>';  

  if (mysqli_num_rows($data) == 0) {
    echo '</table><p class="error">No hay solicitudes capturadas para el &uacute;ltimo lote.</p></br>';
    //require_once('footer.php');
    //exit();
  }

  while ( $row = mysqli_fetch_array($data) ) {

    //echo '<tr class="dato"><td class="lista"><a href="editarvalija.php?id_valija=' . $row['id_valija'] . '">' . $row['id_valija'] . '</a></td>';
    echo '<tr class="dato">';
    //echo '<td class="lista"><a href="versolicitud.php?id_solicitud=' . $row['id_solicitud'] . '">' . $row['id_solicitud'] . '</a></td>';
    //echo '<td class="lista">' . $row['id_solicitud'] . '</td>';
    //echo '<td class="lista">' . $row['id_valija'] . '</td>';
    echo '<td class="lista">' . $row['num_lote_anio'] . '</td>';
    echo '<td class="lista">' . $row['num_oficio_ca'] . '</td>';
    //echo '<td class="lista">' . $row['fecha_captura_ca'] . '</td>';
    echo '<td class="lista">(' . $row['num_del'] . ')' . $row['delegacion_descripcion'] . ' - (' . $row['num_subdel'] . ')' . $row['subdelegacion_descripcion'] . '</td>';
    echo '<td class="lista">' . $row['primer_apellido'] . '-' . $row['segundo_apellido'] . '-' . $row['nombre'] . '</td>';
    //echo '<td class="lista">' . $row['primer_apellido'] . '</td>'; 
    //echo '<td class="lista">' . $row['segundo_apellido'] . '</td>'; 
    //echo '<td class="lista">' . $row['nombre'] . '</td>';
    echo '<td class="lista">' . $row['matricula'] . '</td>'; 
    echo '<td class="lista">' . $row['curp'] . '</td>'; 
    //echo '<td class="lista">' . $row['curp_correcta'] . '</td>'; 
    //echo '<td class="lista">' . $row['cargo'] . '</td>';
    echo '<td class="lista">' . $row['usuario'] . '</td>';
    echo '<td class="lista">' . $row['movimiento_descripcion'] . '</td>'; 
    echo '<td class="lista">' . $row['grupo_actual'] . '</td>';
    echo '<td class="lista">' . $row['grupo_nuevo'] . '</td>'; 
    echo '<td class="lista">' . $row['comentario'] . '</td>';
    echo '<td class="lista">' . $row['rechazado'] . '</td>';
    if (!empty($row['archivo'])) {
      echo '<td class="lista"><a href="' . MM_UPLOADPATH_CTASSINDO . '\\' . $row['archivo'] . '"  target="_new">Ver</a></td>';
    }
    else {
      echo '<td class="lista">(Vac&iacute;o)</a></td>';
    } 
    echo '<td class="lista">' . $row['creada_por'] . '</td>';
    echo '</tr>';
    //$archivox = $row['archivo'];
  }    

  echo '</table>';




//Mostrar solicitudes del penúltimo lote
  // Obtener todas las solicitudes capturadas al momento para el penúltimo lote modificado
  $query = "SELECT 
    solicitudes.id_solicitud, solicitudes.id_valija, valijas.num_oficio_ca, 
    solicitudes.fecha_captura_ca, solicitudes.fecha_solicitud_del, solicitudes.fecha_modificacion,
    lotes.lote_anio AS num_lote_anio, 
    solicitudes.delegacion AS num_del, delegaciones.descripcion AS delegacion_descripcion, 
    solicitudes.subdelegacion AS num_subdel, subdelegaciones.descripcion AS subdelegacion_descripcion, 
    solicitudes.nombre, solicitudes.primer_apellido, solicitudes.segundo_apellido, 
    solicitudes.matricula, solicitudes.curp, solicitudes.curp_correcta, solicitudes.cargo, solicitudes.usuario, 
    movimientos.descripcion AS movimiento_descripcion, 
    grupos1.descripcion AS grupo_nuevo, grupos2.descripcion AS grupo_actual, 
    solicitudes.comentario, solicitudes.rechazado, solicitudes.archivo,
    CONCAT(dspa_user.first_name, ' ', dspa_user.first_last_name) AS creada_por
    FROM solicitudes, valijas, lotes, delegaciones, subdelegaciones, movimientos, grupos grupos1, grupos grupos2, dspa_user
    WHERE solicitudes.id_lote       = lotes.id_lote
    AND   solicitudes.id_valija     = valijas.id_valija
    AND   solicitudes.delegacion    = subdelegaciones.delegacion
    AND   solicitudes.subdelegacion = subdelegaciones.subdelegacion
    AND   solicitudes.delegacion    = delegaciones.delegacion
    AND   solicitudes.id_movimiento = movimientos.id_movimiento
    AND   solicitudes.id_grupo_nuevo= grupos1.id_grupo
    AND   solicitudes.id_grupo_actual= grupos2.id_grupo
    AND   solicitudes.user_id = dspa_user.user_id
    AND   solicitudes.id_lote = (SELECT id_lote - 1 from lotes ORDER BY fecha_creacion DESC LIMIT 1)
    ORDER BY solicitudes.usuario ASC, solicitudes.fecha_modificacion DESC, solicitudes.id_movimiento ASC";
    //ORDER BY solicitudes.id_movimiento ASC, solicitudes.usuario ASC, solicitudes.fecha_modificacion DESC";
    //AND   solicitudes.rechazado <> 1
    
    //AND   solicitudes.id_lote = 79
    //AND   solicitudes.id_movimiento=2
    //ORDER BY solicitudes.id_solicitud DESC, solicitudes.usuario ASC, solicitudes.fecha_modificacion DESC";
    
    
    //movimientos.descripcion, solicitudes.usuario,
    //AND   solicitudes.rechazado <> 1
    //AND   solicitudes.id_lote = 4
    //AND   solicitudes.rechazado <> 1
  $data = mysqli_query($dbc, $query);

  echo '<p class="titulo1">Solicitudes del pen&uacute;ltimo lote</p>';
  //echo '<p class="titulo2">Agregar <a href="agregarsolicitud.php">nueva solicitud</a></p>';

  echo '<table border="1">';
  echo '<tr class="dato">';
  //echo '<th># Solicitud</th>';
  //echo '<th># Valija</th>';
  echo '<th># de Lote</th>';
  echo '<th># &Aacute;rea de Gesti&oacute;n</th>';
  //echo '<th>Fecha captura</th>';
  echo '<th>Delegaci&oacute;n - Subdelegaci&oacute;n</th>';
  echo '<th>Nombre completo</th>';
  echo '<th>Matr&iacute;cula</th>';
  echo '<th>CURP</th>';
  //echo '<th>CURP correcta</th>';
  //echo '<th>Cargo</th>';
  echo '<th>Usuario</th>';
  echo '<th>Movimiento</th><th>Grupo actual</th><th>Grupo nuevo</th>';
  echo '<th>Comentario</th><th>Rechazado</th><th>Archivo</th>';
  echo '<th>Creada por</th>';
  echo '</tr>';  

  if (mysqli_num_rows($data) == 0) {
    echo '</table></br><p class="error">No hay solicitudes capturadas para el pen&uacute;ltimo lote.</p></br>';
    require_once('footer.php');
    exit();
  }

  while ( $row = mysqli_fetch_array($data) ) {

    //echo '<tr class="dato"><td class="lista"><a href="editarvalija.php?id_valija=' . $row['id_valija'] . '">' . $row['id_valija'] . '</a></td>';
    echo '<tr class="dato">';
    //echo '<td class="lista"><a href="versolicitud.php?id_solicitud=' . $row['id_solicitud'] . '">' . $row['id_solicitud'] . '</a></td>';
    //echo '<td class="lista">' . $row['id_solicitud'] . '</td>';
    //echo '<td class="lista">' . $row['id_valija'] . '</td>';
    echo '<td class="lista">' . $row['num_lote_anio'] . '</td>';
    echo '<td class="lista">' . $row['num_oficio_ca'] . '</td>';
    //echo '<td class="lista">' . $row['fecha_captura_ca'] . '</td>';
    echo '<td class="lista">(' . $row['num_del'] . ')' . $row['delegacion_descripcion'] . ' - (' . $row['num_subdel'] . ')' . $row['subdelegacion_descripcion'] . '</td>';
    echo '<td class="lista">' . $row['primer_apellido'] . '-' . $row['segundo_apellido'] . '-' . $row['nombre'] . '</td>';
    //echo '<td class="lista">' . $row['primer_apellido'] . '</td>'; 
    //echo '<td class="lista">' . $row['segundo_apellido'] . '</td>'; 
    //echo '<td class="lista">' . $row['nombre'] . '</td>';
    echo '<td class="lista">' . $row['matricula'] . '</td>'; 
    echo '<td class="lista">' . $row['curp'] . '</td>'; 
    //echo '<td class="lista">' . $row['curp_correcta'] . '</td>'; 
    //echo '<td class="lista">' . $row['cargo'] . '</td>';
    echo '<td class="lista">' . $row['usuario'] . '</td>';
    echo '<td class="lista">' . $row['movimiento_descripcion'] . '</td>'; 
    echo '<td class="lista">' . $row['grupo_actual'] . '</td>';
    echo '<td class="lista">' . $row['grupo_nuevo'] . '</td>'; 
    echo '<td class="lista">' . $row['comentario'] . '</td>';
    echo '<td class="lista">' . $row['rechazado'] . '</td>';
    if (!empty($row['archivo'])) {
      echo '<td class="lista"><a href="' . MM_UPLOADPATH_CTASSINDO . '\\' . $row['archivo'] . '"  target="_new">Ver</a></td>';
    }
    else {
      echo '<td class="lista">(Vac&iacute;o)</a></td>';
    } 
    echo '<td class="lista">' . $row['creada_por'] . '</td>';
    echo '</tr>';
    //$archivox = $row['archivo'];
  }    

  echo '</table></br></br>';

  //echo 'Ver archivo <a href="' . MM_UPLOADPATH_CTASSINDO . '\\' . $archivox . '">' . $archivox . '</a>';

  mysqli_close($dbc);
    
  // Insert the page footer
  require_once('footer.php');
?>
