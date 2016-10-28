<?php
	
	header('Content-Type: text/html; charset=iso-8859-1'); // Para que devuelva correctamente los acentos de los registros

	require_once('appvars.php');
	require_once('connectvars.php');

	$delnum= 15;

	function delegacionSelected($fdelegacion)
	{
		//echo $fdelegacion;
		//echo "delnum: " . $delnum;
//		alert($delnum);
		if (empty($delnum)) {
			return "";
		}

		if (15 == $fdelegacion) {
			alert("ifinterno");
			echo "selected";
			return "selected";
		}
	}
	
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$result = mysqli_query($dbc, "SELECT * FROM delegaciones ORDER BY activo DESC, delegacion");
	
	//echo 'mira:' . delegacionSelected( $row['delegacion'] ) . '|';
	echo '<option value="-1">Seleccione Delegaci&oacute;n</option>';

	while ($row = mysqli_fetch_array($result)) {
		//echo '<option value="' . $row['delegacion'] . '">' .$row['delegacion'] . ' - ' . $row['descripcion'] . '</option>';
		echo '<option value="' . $row['delegacion'] . '" ' . delegacionSelected( $row['delegacion'] ) . '>' . $row['delegacion'] . ' - ' . $row['descripcion'] . '</option>';
	}

?>

	