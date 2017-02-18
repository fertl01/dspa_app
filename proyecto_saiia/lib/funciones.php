<?php

  //FUNCION DE CONEXIÓN:
  function fnConnect( $dbc )
  {
    // Conectarse a la BD
    //$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    /* check connection */
/*    if ( mysqli_connect_errno () ) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        return "Falló la conexión a base de datos";
        //exit();
    }*/
      
    /* change character set to utf8 */
    if ( !$dbc->set_charset( "utf8" ) ) {
        printf("Error loading character set utf8: %s\n", $dbc->error);
        return "Error al cargar set de caracteres utf8. Contacte al administrador del sitio.";
    } else {
        //printf("Current character set: %s\n", $dbc->character_set_name () );
        return "";
    }
  }

  //FUNCIONES PARA COMBOBOX
  function fnvalijaSelect( $var_valija )
  {
    if ( empty( $_POST['cmbValijas'] ) ) 
      return "";
    else if ( $_POST['cmbValijas'] == $var_valija ) 
      return "selected";
  }

  function fnloteSelect( $var_lote )
  {
    if ( empty( $_POST['cmbLotes'] ) )
      return "";
    else if ( $_POST['cmbLotes'] == $var_lote )
      return "selected";
  }

  function fntipomovimientoSelect( $var_tipomovimiento )
  {
    if ( empty( $_POST['cmbtipomovimiento'] ) ) 
      return 0;    
    else if ( $_POST['cmbtipomovimiento'] == $var_tipomovimiento )
      return "selected";
  }

  function fntdelegacionSelect($var_delegacion)
  {
    if (empty($_POST['cmbDelegaciones']))
      return "";
    else if ($_POST['cmbDelegaciones'] == $var_delegacion)
      return "selected";
  }

  function fntsubdelegacionSelect($var_subdelegacion)
  {
    if ( empty( $_POST['cmbSubdelegaciones'] ) && $_POST['cmbSubdelegaciones'] <> 0 ) {
      return "";
      /*echo "nad";*/
    }
    else if ( $_POST['cmbSubdelegaciones'] == $var_subdelegacion ) {
      return "selected";
      /*echo "els";*/
    }
  }

  function fntcmbgponuevoSelect($var_gponuevo)
  {
    if ( empty( $_POST['cmbgponuevo'] ) )
      return "";
    else if ( $_POST['cmbgponuevo'] == $var_gponuevo )
      return "selected";
  }

  function fntcmbgpoactualSelect($var_gpoactual)
  {
    if ( empty( $_POST['cmbgpoactual'] ) )
      return "";
    else if ( $_POST['cmbgpoactual'] == $var_gpoactual )
      return "selected";
  }

  function fntcmbcausarechazoSelect($var_causarechazo)
  {
    if ( empty( $_POST['cmbcausarechazo'] ) && $_POST['cmbcausarechazo'] <> 0 )
      return "";
    else if ( $_POST['cmbcausarechazo'] == $var_causarechazo )
      return "selected";
  }

?>
