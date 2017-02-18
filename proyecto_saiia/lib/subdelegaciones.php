<?php

	header('Content-Type: text/html; charset=utf8'); // Para que devuelva correctamente los acentos de los registros
	
	require_once('.\lib\appvars.php');
	require_once('.\lib\connectvars.php');

	function subdelegacionSelected( $var_subdelegacion )
	{
		if ( empty( $_POST['cmbSubdelegaciones'] ) ) {
			return "";
		}
		else if ( $_POST['cmbSubdelegaciones'] == $var_subdelegacion) {
			return "selected";
		}
	}

	$id_delegacion = $_GET['param_id'];

	$dbc = mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );

	$result = mysqli_query( $dbc, "SELECT * 
									FROM ctas_subdelegaciones 
									WHERE delegacion = $id_delegacion
									ORDER BY subdelegacion");

	echo '<option value="-1">Seleccione Subdelegación</option>';
	while( $row = mysqli_fetch_array( $result ) ) {
		echo '<option value="' . $row['subdelegacion'] . '" ' . subdelegacionSelected( $row['subdelegacion'] ) . '>' . $row['subdelegacion'] . ' - ' . $row['descripcion'] . '</option>';
	}

