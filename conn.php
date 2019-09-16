<?php
// Connection variables
$dbhost	= "JTB6032\SQLEXPRESSPRUEBA";	   // localhost or IP
//$dbuser = "usuario3";
//$dbpass = "pruebausuario3";
$dbuser	= "usuariophp";		  // database username
$dbpass	= "usuario1234";		     // database password
$dbname	= "DBO.BECARIOS";    // database name


function filtroInject($data) {
    if ( !isset($data) or empty($data) ) return '';
    if ( is_numeric($data) ) return $data;


    $non_displayables = array(
        '/%0[0-8bcef]/',            // url encoded 00-08, 11, 12, 14, 15
        '/%1[0-9a-f]/',             // url encoded 16-31
        '/[\x00-\x08]/',            // 00-08
        '/\x0b/',                   // 11
        '/\x0c/',                   // 12
        '/[\x0e-\x1f]/',             // 14-31
        '/\27/'
    );
    foreach ( $non_displayables as $regex )
        $data = preg_replace( $regex, '', $data );
    $reemplazar = array('"',"'",'=','--',' ',';');
    $data = str_replace($reemplazar, "", $data ); 
    return $data;
}

?>
