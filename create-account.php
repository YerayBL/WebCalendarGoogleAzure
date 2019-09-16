<!doctype html>
<html lang="en">
  <head>
    <title>Create account on database</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
  </head>
<body>

<div class="container">

	<?php

	include 'conn.php';

	function generarCodigo($longitud) {
		$key = '';
		$pattern = '123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$max = strlen($pattern)-1;
		for($i=0;$i < $longitud;$i++) $key .= $pattern{mt_rand(0,$max)};
		return $key;
	 }

	$connectionInfo = array( "Database"=>$dbname, "UID"=>$dbuser, "PWD"=>$dbpass);
  $conn = sqlsrv_connect( $dbhost, $connectionInfo);

	// Check connection
	if (!$conn) {
		echo "Conexión no se pudo establecer.<br>";
     die( print_r( sqlsrv_errors(), true));
	}
	$email = $_POST['email'];
	$email = filtroInject($email);
	// Query to check if the email already exist
	$checkEmail = "SELECT * FROM dbo.users WHERE Email = '$email' ";

	// Variable $result hold the connection data and the query
	$result = sqlsrv_query( $conn, $checkEmail);
	 
	// Variable $count hold the result of the query
	$count = sqlsrv_has_rows($result);

	// If count == 1 that means the email is already on the database
	if ($count === true) {
	echo "<div class='alert alert-warning mt-4' role='alert'>
					<p>That email is already in our database.</p>
					<p><a href='login.php'>Please login here</a></p>
				</div>";
	} else {	

	/*
	If the email don't exist, the data from the form is sended to the
	database and the account is created
	*/
	$name = $_POST['name'];
	$email = $_POST['email'];
	$pass = $_POST['password'];
	$name = filtroInject($name);
	$email = filtroInject($email);
	$recCod = generarCodigo(5);
	$act = 0;
	$code = generarCodigo(6);
	
	// The password_hash() function convert the password in a hash before send it to the database
	$passHash = password_hash($pass, PASSWORD_DEFAULT);
	
	// Query to send Name, Email and Password hash to the database
	$query = "INSERT INTO dbo.users (Name, Email, Password, Rec_Cod, Activation, Email_Val) VALUES ('$name', '$email', '$passHash', '$recCod', $act, '$code')";

	//mysqli_query($conn, $query)
	if (sqlsrv_query($conn, $query)) {

		$subject = "Calendarios Angel24 confirmación de cuenta.";
		$body = "Active su cuenta: http://localhost:8000/EmailConfirm.php?id=$code&email=$email";
		$headers = 'From: salastevealexandre@gmail.com' . "\r\n" .
		'Reply-To: Becarios' . "\r\n" .
		'X-Mailer: PHP/' . phpversion();
		
		if(mail($email, $subject, $body, $headers)){

		echo "<div class='alert alert-success mt-4' role='alert'><h3>Recibiras un correo para confirmar la creación de tu cuenta.</h3>
		<a class='btn btn-outline-primary' href='login.php' role='button'>Login</a></div>";	
		}	
		} else {
			echo "Error: " . $query . "<br>" ;
			//. sqlsrv_errors($conn)
			//mysqli_error($conn);
		}	
	}	
	//mysqli_close($conn);
	sqlsrv_close($conn);
	?>
</div>
	<!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
  </body>
</html>