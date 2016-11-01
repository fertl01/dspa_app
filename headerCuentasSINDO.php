<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<!--meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
		<!-- <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />-->
		
		<?php
			echo '<title>' . $page_title . '</title>';
		?>

		<link rel="stylesheet" type="text/css" href="style.css" />
		
		<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
	    <script type="text/javascript">
	    	$("document").ready(function() {
		        // body...
		        //alert("Jquery está listo");
		        //$( "#cmbLotes" ).load( "lotes.php" );
		        //$( "#cmbValijas" ).load( "valijas.php" );
		        //$( "#cmbDelegaciones" ).load( "delegaciones.php" );
		        //$( "#cmbtipomovimiento" ).load( "movimientos.php" );
		        $( "#cmbgponuevo" ).load( "grupos.php");
		        $( "#cmbgpoactual" ).load( "grupos.php");
		        //alert($("#cmbtipomovimiento").val());
		        $( "#cmbDelegaciones" ).change( function() {
		          									var id = $("#cmbDelegaciones").val();
		          									$.get('subdelegaciones.php', { param_id:id } )
		          									.done( 	function( data ) {
		          												//alert($("#cmbDelegaciones").val());
		            											$( "#cmbSubdelegacionesII" ).html( data );
		        											} )
		        								} )
			} )
	    </script>
	</head>

	<body>

		<?php
			echo '<h3>' . $page_title . '</h3>';
		?>