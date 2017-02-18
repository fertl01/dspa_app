<?php
  // Start the session
  require_once('..\commonfiles\startsession.php');

  require_once('.\lib\appvars.php');
  require_once('.\lib\connectvars.php');

  // Insert the page header
  $page_title = MM_APPNAME;
  require_once('headerSAIIA.php');
  /*<body> This tag came from headerSAIIA.php*/

  // Make sure the user is logged in before going any further.
  if ( !isset( $_SESSION['user_id'] ) ) {
    echo '<p class="error">Por favor <a href="loginSAIIA.php">inicia sesión</a> para acceder a esta página.</p>';
    require_once('footerSAIIA.php');
    exit();
  }

?>

    <section id="eventos" class="eventos contenedor"> <!-- eventos -->
      <h2>Nuestros eventos</h2><!-- titulo -->
      <article class="evento"> <!-- evento 1 -->
        <!-- <img class="derecha" src="images/background 02.png" alt="Close up boardgame 'Colonos de Catan'" width="350"/> -->
        <div class="contenedor-evento-a">
          <h3 class="title-b">Mc MOB</h3>
            <h4>Todos los martes, 18hrs</h4>
            <h4>McDonald´s (Parque Hundido), Insurgentes Sur #1122, Ciudad de México</h4>
            <h5><a class="button" href ="consultainventarioSAIIA.php">Ver Inventario SAIIA</a></h5>
            <!-- <a class="button" href="" target="_blank">Ver asistentes, fotos, detalles...</a> -->
        </div>
      </article>
    </section>
  
  <?php  
    require_once('footerSAIIA.php');
  ?>

  </body>

</html>

