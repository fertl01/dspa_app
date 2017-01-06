<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>

	<?php
		echo '<title>' . MM_APPNAME . '</title>';
		header('Content-Type: text/html; charset=utf-8');
	?>

	<!-- CSS  -->
  	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  	<link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection"/>
  	<link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>

  		<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
	    <script type="text/javascript">
		$(document).ready(function() {

		    $('select').material_select();
		    $('#cmbSubdelegaciones').material_select();

			$('.datepicker').pickadate( {
			    selectMonths: true, // Creates a dropdown to control month
			    selectYears: 15,  // Creates a dropdown of 15 years to control year
			    format: 'dd/mm/yyyy',
				formatSubmit: 'yyyy/mm/dd'
			  });
		  });

		$('document').ready(function() {
	        $( '#cmbDelegaciones' ).change( 
	        	function() {
					var id = $('#cmbDelegaciones').val();
					$.get('subdelegaciones.php', { param_id:id } )
					.done( 	function( data ) {
						$( '#cmbSubdelegaciones' ).html( data );
						$('select').material_select();
					} )
				} 
			)
		} )

	    </script>
</head>

<body>
