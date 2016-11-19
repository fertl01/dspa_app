<nav class="light-blue lighten-1" role="navigation">
  <div class="nav-wrapper container">
    <!-- <a href="#" class="brand-logo">Logo</a> -->
    <a id="logo-container" href="#" class="brand-logo">Logo</a>
    <ul class="right hide-on-med-and-down">
    <?php
    //Si ha iniciado sesión ...
      if ( isset( $_SESSION['username'] ) ) {
        echo '<li><a href="index.php">Inicio</a></li>';
        echo '<li><a href="viewprofile.php">Ver Perfil</a></li>';
        echo '<li><a href="editprofile.php">Editar Perfil</a></li>';
        echo '<li><a href="indexCuentasSINDO.php">Gestión Cuentas SINDO</a></li>';
        echo '<li><a href="logout.php">Cerrar Sesión (' . $_SESSION['username'] . ')</a></li>';
      }
      else {
        echo '<li><a href="index.php">Home</a></li>';
        echo '<li><a href="login.php">Iniciar sesión</a></li>';
        echo '<li><a href="signup.php">Registrar nuevo usuario</a></li>';
      }
    ?>
    </ul>
  </div>
</nav>


