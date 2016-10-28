<?php
  // Start the session
  require_once('startsession.php');

  // Insert the page header
  $page_title = 'Editar Perfil';
  require_once('header.php');

  require_once('appvars.php');
  require_once('connectvars.php');

  // Make sure the user is logged in before going any further.
  if (!isset($_SESSION['user_id'])) {
    echo '<p class="login">Por favor <a href="login.php">inicia sesión</a> para acceder a esta página.</p>';
    exit();
  }

  // Show the navigation menu
  require_once('navmenu.php');

  // Connect to the database
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

  if (isset($_POST['submit'])) {
    // Grab the profile data from the POST
    $first_name = mysqli_real_escape_string($dbc, trim($_POST['firstname']));
    $first_last_name = mysqli_real_escape_string($dbc, trim($_POST['first_lastname']));
    $second_last_name = mysqli_real_escape_string($dbc, trim($_POST['second_lastname']));
    $gender = mysqli_real_escape_string($dbc, trim($_POST['gender']));
    $birthdate = mysqli_real_escape_string($dbc, trim($_POST['birthdate']));
    // $city = mysqli_real_escape_string($dbc, trim($_POST['city']));
    // $state = mysqli_real_escape_string($dbc, trim($_POST['state']));
    $old_picture = mysqli_real_escape_string($dbc, trim($_POST['old_picture']));
    $new_picture = mysqli_real_escape_string($dbc, trim($_FILES['new_picture']['name']));
    $new_picture_type = $_FILES['new_picture']['type'];
    $new_picture_size = $_FILES['new_picture']['size']; 
    
    $error = false;

    // Validate and move the uploaded picture file, if necessary
    if (!empty($new_picture)) {
      list($new_picture_width, $new_picture_height) = getimagesize($_FILES['new_picture']['tmp_name']);
      if ((($new_picture_type == 'image/gif') || ($new_picture_type == 'image/jpeg') || ($new_picture_type == 'image/pjpeg') ||
        ($new_picture_type == 'image/png')) && ($new_picture_size > 0) && ($new_picture_size <= MM_MAXFILESIZE_PROFILE) &&
        ($new_picture_width <= MM_MAXIMGWIDTH_PROFILE) && ($new_picture_height <= MM_MAXIMGHEIGHT_PROFILE)) {
        if ($_FILES['new_picture']['error'] == 0) {
          // Move the file to the target upload folder
          $timetime = time();
          $target = MM_UPLOADPATH_PROFILE . $timetime . " " . basename($new_picture);

          if (move_uploaded_file($_FILES['new_picture']['tmp_name'], $target)) {
            // The new picture file move was successful, now make sure any old picture is deleted
            if (!empty($old_picture) && ($old_picture != $new_picture)) {
              @unlink(MM_UPLOADPATH_PROFILE . $old_picture);
            }
          }
          else {
            // The new picture file move failed, so delete the temporary file and set the error flag
            @unlink($_FILES['new_picture']['tmp_name']);
            $error = true;
            echo '<p class="error">Lo sentimos, hubo un problema al tratar de cargar tu imagen.</p>';
          }
        }
      }
      else {
        // The new picture file is not valid, so delete the temporary file and set the error flag
        @unlink($_FILES['new_picture']['tmp_name']);
        $error = true;
        echo '<p class="error">Tu imagen debe ser un archivo GIF, JPEG, o PNG no mayor de ' . (MM_MAXFILESIZE_PROFILE / 1024) .
          ' KB y ' . MM_MAXIMGWIDTH_PROFILE . 'x' . MM_MAXIMGHEIGHT_PROFILE . ' pixeles de tamaño.</p>';
      }
    }

    // Update the profile data in the database
    if (!$error) {
      //--&& !empty($city) && !empty($state)
      if (!empty($first_name) && !empty($second_last_name) && !empty($gender) && !empty($birthdate) ) {
        // Only set the picture column if there is a new picture
        if (!empty($new_picture)) {
          $query = "UPDATE dspa_user SET first_name = '$first_name', " .
            " first_last_name = '$first_last_name', second_last_name = '$second_last_name', " .
            " gender = '$gender', birthdate = '$birthdate', picture = '$timetime $new_picture' " .
            " WHERE user_id = '" . $_SESSION['user_id'] . "'";
        }
        else {
          $query = "UPDATE dspa_user SET first_name = '$first_name', " .
          " first_last_name = '$first_last_name', second_last_name = '$second_last_name', " .
          " gender = '$gender', birthdate = '$birthdate' " .
          " WHERE user_id = '" . $_SESSION['user_id'] . "'";
        }
        mysqli_query($dbc, $query);

        // Confirm success with the user
        echo '<p>Tu perfil ha sido actualizado correctamente. &iquest;Te gustar&iacute;a <a href="viewprofile.php">ver tu perfil</a>?</p>';

        mysqli_close($dbc);
        exit();
      }
      else {
        echo '<p class="error">Debes capturar toda la informaci&oacute;n del perfil (la imagen es opcional).</p>';
      }
    }
  } // End of check for form submission
  else {
    // Grab the profile data from the database
    $query = "SELECT first_name, first_last_name, second_last_name, gender, birthdate, picture " .
      " FROM dspa_user WHERE user_id = '" . $_SESSION['user_id'] . "'";
    $data = mysqli_query($dbc, $query);
    $row = mysqli_fetch_array($data);

    if ($row != NULL) {
      $first_name = $row['first_name'];
      $first_last_name = $row['first_last_name'];
      $second_last_name = $row['second_last_name'];
      $gender = $row['gender'];
      $birthdate = $row['birthdate'];
      // $city = $row['city'];
      // $state = $row['state'];
      $old_picture = $row['picture'];
    }
    else {
      echo '<p class="error">Hubo un problema al acceder a tu perfil.</p>';
    }
  }

  mysqli_close($dbc);
?>

  <form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <input type="hidden" name="MAX_FILE_SIZE_PROFILE" value="<?php echo MM_MAXFILESIZE_PROFILE; ?>" />
    <fieldset>
      <legend>Informaci&oacute;n Personal</legend>
      <label for="firstname">Nombre(s):</label>
      <input type="text" id="firstname" name="firstname" value="<?php if (!empty($first_name)) echo $first_name; ?>" /><br />
      <label for="first_lastname">Primer apellido:</label>
      <input type="text" id="first_lastname" name="first_lastname" value="<?php if (!empty($first_last_name)) echo $first_last_name; ?>" /><br />
      <label for="second_lastname">Segundo apellido:</label>
      <input type="text" id="second_lastname" name="second_lastname" value="<?php if (!empty($second_last_name)) echo $second_last_name; ?>" /><br />
      <label for="gender">Sexo:</label>
      <select id="gender" name="gender">
        <option value="M" <?php if (!empty($gender) && $gender == 'M') echo 'selected = "selected"'; ?>>Masculino</option>
        <option value="F" <?php if (!empty($gender) && $gender == 'F') echo 'selected = "selected"'; ?>>Femenino</option>
      </select><br />
      <label for="birthdate">Fecha de nacimiento (yyyy-mm-dd):</label>
      <input type="text" id="birthdate" name="birthdate" value="<?php if (!empty($birthdate)) echo $birthdate; else echo 'YYYY-MM-DD'; ?>" /><br />

      <input type="hidden" name="old_picture" value="<?php if (!empty($old_picture)) echo $old_picture; ?>" />
      <label for="new_picture">Imagen:</label>
      <input type="file" id="new_picture" name="new_picture" />
      <?php if (!empty($old_picture)) {
        echo '<img class="profile" src="' . MM_UPLOADPATH_PROFILE . $old_picture . '" alt="Imagen de Perfil" />';
      } ?>
    </fieldset>
    <input type="submit" value="Guardar Perfil" name="submit" />
  </form>

<?php
  // Insert the page footer
  require_once('footer.php');
?>
