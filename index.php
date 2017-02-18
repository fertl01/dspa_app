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
      <div class="row center">
      
        <h1 class="header center orange-text">
      <!-- <a id="logo-container" href="" class="brand-logo"></a> -->
          <?php
            echo $page_title;
          ?>
        </h1>
        <h5 class="header col s12">Bienvenido a la página de la División de Soporte a los Procesos de Afiliación</h5>
        <!-- <h6 class="header col s12">Se requiere un usuario autorizado para ingresar al sitio completo</h6> -->
      <!-- </div> -->
      <!-- <div class="row center"> -->
      </div>
    </div>
  </div>

  <div class="container">
    <!-- <div class="section"> -->
      <!--   Icon Section   -->
      <div class="row">

        <div class="col s12 m4">
          <div class="icon-block">
            <h2 class="center light-blue-text"><i class="material-icons">radio</i></h2>
            <p class="center">
              <a href="./resources/ChromeStandaloneSetup.zip" id="download-button" class="btn waves-effect waves-light orange">Descarga</a>
            </p>
            <!-- <span class="new badge yellow black-text" data-badge-caption="Requerido"></span> -->
            <!-- <h5 class="center">.</h5> -->
            <p class="light">Se visualiza mejor en Google Chrome. Se requiere un usuario autorizado para ingresar al sitio.</p>
          </div>
        </div>
        
        <div class="col s12 m4">
          <div class="icon-block">
            <a href="./proyecto_saiia/indexSAIIA.php" target="_blank">
              <h2 class="center light-blue-text"><i class="material-icons">work</i></h2>
              <span class="new badge yellow black-text" ></span>
              <h5 class="center">Proyecto SAIIA</h5>
              <p class="light">Control de inventario subdelegacional de equipos de cómputo con la aplicación SAIIA.</p>
            </a>
          </div>
        </div>

        <div class="col s12 m4">
          <div class="icon-block">
            <a href="indexCuentasSINDO.php" target="_blank">
            <h2 class="center light-blue-text"><i class="material-icons">supervisor_account</i></h2>
              <h5 class="center">Claves de Usuario</h5>
              <p class="light">Información sobre solicitudes de cuentas SINDO, estatus, inventario, grupos, etc.</p>
            </a>
          </div>
        </div>
      </div>

    <!-- </div> -->
    <!-- <div class="section">
    </div> -->
  </div>

<?php
  // Insert the page footer
  require_once('footer.php');
?>
