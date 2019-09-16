<?php

// Connection info. file
include 'conn.php';	
			
$connectionInfo = array( "Database"=>$dbname, "UID"=>$dbuser, "PWD"=>$dbpass);
$conn = sqlsrv_connect( $dbhost, $connectionInfo);

  // Check connection
  if (!$conn) {
      echo "Conexión no se pudo establecer.<br>";
   die( print_r( sqlsrv_errors(), true));
  }

  $code = $_GET['id'];
  $email = $_GET['email'];
  $email = filtroInject($email);
  
  $sql = "SELECT Email_Val FROM dbo.users WHERE Email='$email'";
  $result = sqlsrv_query($conn, $sql);

  if (sqlsrv_has_rows($result) === true) {
    $row = sqlsrv_fetch_array($result);
}else{
    echo"Código incorrecto.";
}
if($row['Email_Val'] == $code){
    $act = 1;
    $sql = "UPDATE dbo.users SET Activation = ($act) WHERE Email='$email'";
    $result = sqlsrv_query($conn, $sql);

    echo"Cuenta activada. <br>";
    echo"<a href='login.php' title='Login Here'>Login here!</a></p>";
}else{
    echo"El código no coincide.";
}

?>