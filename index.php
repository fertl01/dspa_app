<?php
  // Start the session
  require_once('startsession.php');

  require_once('appvars.php');
  require_once('connectvars.php');

  // Insert the page header
  $page_title = MM_APPNAME;
  require_once('header.php');

  // Show the navigation menu
  require_once('navmenu.php');
?>

  <div class="section no-pad-bot" id="index-banner">
    <div class="container">
      
      <h1 class="header center orange-text">
        <?php
          echo $page_title;
        ?>
      </h1>
      <div class="row center">
        <h5 class="header col s12 light">Bienvenido al sitio de aplicaciones de la División de Soporte a los Procesos de Afiliación (DSPA). Se requiere un usuario autorizado para ingresar al sitio completo</h5>
      </div>
      <div class="row center">
        <h6 class="header col s12 ">Esta página se visualiza mucho mejor en el navegador Google Chrome <a href="./filesctasSINDO/ChromeStandaloneSetup.exe" id="download-button" class="btn waves-effect waves-light orange">Descarga Chrome</a></h6>
      </div>
    </div>
  </div>

  <div class="container">
    <div class="section">
      <!--   Icon Section   -->
      <div class="row">
        <div class="col s12 m4">
          <div class="icon-block">
            <h2 class="center light-blue-text"><i class="material-icons">radio</i></h2>
            <span class="new badge yellow black-text" data-badge-caption="En construcción"></span>
            <h5 class="center">Noticias</h5>
            <p class="light">Podrás consultar los últimos avisos y noticias de caracter general que publique la División; avisos de mantenimiento, de incidencias generales, etc.</p>
          </div>
        </div>

        <div class="col s12 m4">
          <div class="icon-block">
            <h2 class="center light-blue-text"><i class="material-icons">work</i></h2>
            <span class="new badge yellow black-text" data-badge-caption="En construcción"></span>
            <h5 class="center">Herramientas</h5>
            <p class="light">Aquí encontrarás herramientas útiles para las operaciones diarias como consulta de catálogos, validación de archivos (DISPMAG, AYPENT, Infonavit), cálculo de dígito verificador, georeferenciación, etc.</p>
          </div>
        </div>

        <div class="col s12 m4">
          <div class="icon-block">
            <h2 class="center light-blue-text"><i class="material-icons">supervisor_account</i></h2>
            <h5 class="center">Claves de Usuario</h5>
            <p class="light">Toda la información referente a las solicitudes de cuentas SINDO RACF de su delegación. Consulte estatus, inventario, grupos, aplicaciones, etc. Acceso restringido para Jefes de Servicios de Afiliación Cobranza y Jefes de Supervisión de Afiliación Vigencia.</p>
          </div>
        </div>
      </div>

    </div>
    <div class="section">

    </div>
  </div>

<?php
  // Insert the page footer
  require_once('footer.php');
?>
