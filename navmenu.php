<!-- Dropdown Structure -->
<ul id="dropdown1" class="dropdown-content">
  <li><a href="viewprofile.php">Ver Perfil</a></li>
  <li><a href="editprofile.php">Editar Perfil</a></li>
  <li class="divider"></li>
  <li><a href="logout.php">Cerrar Sesión (<?php echo $_SESSION['username']; ?>)</a></li>
</ul>

<nav class="light-blue lighten-1" role="navigation">
  <div class="nav-wrapper container">
    <!-- <a href="#" class="brand-logo">Logo</a> -->
    <a id="logo-container" href="#" class="brand-logo">
      <div class="container">
        <img class="iphone" src="images/logoIMSStransparente2.png" />
      </div>

    </a>
    <ul class="right hide-on-med-and-down">
    <?php
    //Si ha iniciado sesión ...
      if ( isset( $_SESSION['username'] ) ) {
    ?>
        <li><a href="index.php">Inicio</a></li>
        <li><a href="indexCuentasSINDO.php">Gestión Cuentas SINDO</a></li>
        <li><a href="agregarlote.php">Agregar Lote</a></li>
        <li><a href="agregarsolicitud.php">Agregar Solicitud</a></li>
        <!-- Dropdown Trigger -->
        <li><a class="dropdown-button" href="#!" data-activates="dropdown1">Opciones de sesión<i class="material-icons right">arrow_drop_down</i></a></li>
    <?php
      }
      else {
    ?>
        <li><a href="index.php">Home</a></li>
        <li><a href="login.php">Iniciar sesión</a></li>
        <li><a href="signup.php">Registrar nuevo usuario</a></li>
    <?php
      }
    ?>
    </ul>
  </div>
</nav>


