<?php

include 'conn.php';

$pass = $_POST['password'];
$email = $_POST['email'];
$email = filtroInject($email);
$code = $_POST['rec_cod'];
$code = filtroInject($code);
$passHash = password_hash($pass, PASSWORD_DEFAULT);

	$connectionInfo = array( "Database"=>$dbname, "UID"=>$dbuser, "PWD"=>$dbpass);
  $conn = sqlsrv_connect( $dbhost, $connectionInfo);

	// Check connection
	if (!$conn) {
		echo "Conexión no se pudo establecer.<br>";
     die( print_r( sqlsrv_errors(), true));
    }
    


    $sql = "UPDATE dbo.users SET Password = ('$passHash') WHERE Email='$email' AND Rec_Cod='$code'";
    $result = sqlsrv_query($conn, $sql);
    
    if (sqlsrv_rows_affected($result) > 0) {
      echo"Contraseña actualizada. <br>";
      echo"<a href='login.php' title='Login Here'>Login here!</a></p>";
    }else{
      echo"Error al actualizar contraseña.";
    }
    
?>