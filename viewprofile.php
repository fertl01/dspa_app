<?php
  // Start the session
  require_once('startsession.php');

  // Insert the page header
  $page_title = 'Ver Perfil';
  require_once('header.php');

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
  
  // Connect to the database
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

  // Grab the profile data from the database
  if (!isset($_GET['user_id'])) {
    $query = "SELECT username, first_name, first_last_name, second_last_name, gender, birthdate, picture FROM dspa_user WHERE user_id = '" . $_SESSION['user_id'] . "'";
  }
  else {
    $query = "SELECT username, first_name, first_last_name, second_last_name, gender, birthdate, picture FROM dspa_user WHERE user_id = '" . $_GET['user_id'] . "'";
  }
  $data = mysqli_query($dbc, $query);

  if (mysqli_num_rows($data) == 1) {
    // The user row was found so display the user data
    $row = mysqli_fetch_array($data);
    echo '<table>';
    if (!empty($row['username'])) {
      echo '<tr><td class="label">Usuario(CURP):</td><td>' . $row['username'] . '</td></tr>';
    }
    if (!empty($row['first_name'])) {
      echo '<tr><td class="label">Nombre(s):</td><td>' . $row['first_name'] . '</td></tr>';
    }
    if (!empty($row['first_last_name'])) {
      echo '<tr><td class="label">Primer apellido:</td><td>' . $row['first_last_name'] . '</td></tr>';
    }
    if (!empty($row['second_last_name'])) {
      echo '<tr><td class="label">Segundo apellido:</td><td>' . $row['second_last_name'] . '</td></tr>';
    }
    if (!empty($row['gender'])) {
      echo '<tr><td class="label">Sexo:</td><td>';
      if ($row['gender'] == 'M') {
        echo 'Masculino';
      }
      else if ($row['gender'] == 'F') {
        echo 'Femenino';
      }
      else {
        echo '?';
      }
      echo '</td></tr>';
    }
    if (!empty($row['birthdate'])) {
      if (!isset($_GET['user_id']) || ($_SESSION['user_id'] == $_GET['user_id'])) {
        // Show the user their own birthdate
        echo '<tr><td class="label">Fecha de nacimiento:</td><td>' . $row['birthdate'] . '</td></tr>';
      }
      else {
        // Show only the birth year for everyone else
        //list($day, $month, $year) = explode('-', $row['birthdate']);
        list($year, $month, $day) = explode('-', $row['birthdate']);
        echo '<tr><td class="label">A&ntilde;o de nacimiento:</td><td>' . $year . '</td></tr>';
      }
    }
    // if (!empty($row['city']) || !empty($row['state'])) {
    //   echo '<tr><td class="label">Location:</td><td>' . $row['city'] . ', ' . $row['state'] . '</td></tr>';
    // }
    if (!empty($row['picture'])) {
      echo '<tr><td class="label">Imagen:</td><td><img src="' . MM_UPLOADPATH_PROFILE . $row['picture'] .
        '" alt="Imagen de Perfil" /></td></tr>';
    }
    echo '</table>';
    if (!isset($_GET['user_id']) || ($_SESSION['user_id'] == $_GET['user_id'])) {
      echo '<p>&iquest;Te gustar&iacute;a <a href="editprofile.php">editar tu perfil</a>?</p>';
    }
  } // End of check for a single row of user results
  else {
    echo '<p class="error">Hubo un problema al acceder a los datos de tu perfil.</p>';
  }

  mysqli_close($dbc);
?>

<?php
  // Insert the page footer
  require_once('footer.php');
?>
