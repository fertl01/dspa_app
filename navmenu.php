<?php
  // Generate the navigation menu
  echo '<hr />';
  if (isset($_SESSION['username'])) {
    echo '<a href="index.php">Inicio</a> &#10070; ';
    
    echo '<a href="viewprofile.php">Ver Perfil</a> &#10070; ';
    echo '<a href="editprofile.php">Editar Perfil</a> &#10070; ';

    echo '<a href="indexCuentasSINDO.php">Gestión Cuentas SINDO</a> &#10070; ';

    //echo '<a href="valindex.php">Cargar Archivo Validador-INFONAVIT</a> &#10070; ';
    //if ($_SESSION['username'] = 'TOLF') {
      //echo '<a href="validainfonavit.php">Validador-INFONAVIT</a> &#10070; ';
      //echo '<a href="verarchivosestudiantes.php">Ver Archivos Estudiantes-Enero 2016</a> &#10070; ';
    //}

    //echo '<a href="valreportlist.php">Ver Reportes Validador-INFONAVIT</a> &#10070; ';
    
    echo '&#10070; <a href="logout.php">Cerrar Sesi&oacute;n (' . $_SESSION['username'] . ')</a>';
  }
  else {
    echo '<a href="index.php">Inicio</a> &#10070; ';
    
    echo '<a href="login.php">Iniciar sesi&oacute;n</a> &#10070; ';
    echo '<a href="signup.php">Registrar nuevo usuario</a>';
  }
  echo '<hr />';
?>
