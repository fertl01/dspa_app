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

  // Connect to the database 
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME); 

  /* check connection */
  if (mysqli_connect_errno()) {
      printf("Connect failed: %s\n", mysqli_connect_error());
      exit();
  }

  /* change character set to utf8 */
  if (!$dbc->set_charset("utf8")) {
      printf("Error loading character set utf8: %s\n", $dbc->error);
  } else {
      printf("Current character set: %s\n", $dbc->character_set_name());
  }

  // Retrieve the user data from MySQL
  $query = "SELECT user_id, first_name, picture 
    FROM ctas_usuarios 
    WHERE first_name IS NOT NULL 
    ORDER BY join_date DESC LIMIT 5";
  $data = mysqli_query($dbc, $query);

  if (mysqli_connect_errno()) {
      printf("Connect failed: %s\n", mysqli_connect_error());
      exit();
  }

  // Loop through the array of user data, formatting it as HTML
  echo '<h4>Últimos usuarios con perfil:</h4>';
  echo '<table>';
  while ($row = mysqli_fetch_array($data)) {
    if (is_file(MM_UPLOADPATH_PROFILE . $row['picture']) 
      && filesize(MM_UPLOADPATH_PROFILE . $row['picture']) > 0) {
      echo '<tr><td><img src="' . MM_UPLOADPATH_PROFILE . $row['picture'] . '" alt="' . $row['first_name'] . '" /></td>';
    }
    else {
      echo '<tr><td><img src="' . MM_UPLOADPATH_PROFILE . 'nopic.jpg' . '" alt="' . $row['first_name'] . '" /></td>';
    }
    if (isset($_SESSION['user_id'])) {
      echo '<td><a href="viewprofile.php?user_id=' . $row['user_id'] . '">' . $row['first_name'] . '</a></td></tr>';
    }
    else {
      echo '<td>' . $row['first_name'] . '</td></tr>';
    }
  }
  echo '</table>';

  mysqli_close($dbc);
?>

<?php
  // Insert the page footer
  require_once('footer.php');
?>
