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
  	<link href='https://fonts.googleapis.com/css?family=Courgette|Montserrat:400,700|Allerta' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="css/proyecto_saiia.css"/>

	<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
	    <script type="text/javascript">
	    	$("document").ready(function() {
		        $( "#cmbDelegaciones" ).change( 
		        	function() {
						var id = $("#cmbDelegaciones").val();
						$.get('subdelegaciones.php', { param_id:id } )
						.done( 	function( data ) {
								$( "#cmbSubdelegaciones" ).html( data );
							} )
				} )
			} )
	    </script>
	</head>

<body>

<header id="header" class="header background">
    <div class="contenedor">
    	<figure class="logotipo"> <!-- logotipo -->
      		<img src="images/sign_up_256.png" alt="Inicio Proyecto SAIIA"/>
    	</figure>

		<nav class="menu"> <!-- menú -->
			<ul>
				<li>
					<h6><a href="../index.php">Inicio Aplicaciones DSPA</a></h6>
				</li>

				<li>
					<h6><a href="indexSAIIA.php">Inicio Proyecto SAIIA</a></h6>
				</li>

				<!-- <li>
					<a href="" target="_blank"></a>
				</li> -->
			</ul>
		</nav>
    </div>
</header>
<!-- </section> -->



