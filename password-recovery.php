<!doctype html>
<html lang="en">
	<head>		
    	<title>Password Recovery</title>
    	<!-- Required meta tags -->
    	<meta charset="utf-8">
    	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    	<!-- Bootstrap CSS -->
    	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
  </head>
<body>
<div class="container">
	<div class="row">
		<div class="col-sm-12 col-md-12 col-lg-12">
			
			<?php
			include 'conn.php';

			function generarCodigo($longitud) {
				$key = '';
				$pattern = '123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
				$max = strlen($pattern)-1;
				for($i=0;$i < $longitud;$i++) $key .= $pattern{mt_rand(0,$max)};
				return $key;
			 }
		
			$email = $_POST['email'];	
			$email = filtroInject($email);			
			
			$connectionInfo = array( "Database"=>$dbname, "UID"=>$dbuser, "PWD"=>$dbpass);
			$conn = sqlsrv_connect( $dbhost, $connectionInfo);
		  
			  // Check connection
			  if (!$conn) {
				  echo "Conexión no se pudo establecer.<br>";
			   die( print_r( sqlsrv_errors(), true));
			  }
				
			$sql = "SELECT Email, Password, Rec_Cod FROM dbo.users WHERE Email='$email'";
			$result = sqlsrv_query($conn, $sql);

			//$passHash = password_hash($pass, PASSWORD_DEFAULT);

			if (sqlsrv_has_rows($result) === true) {
				$code = generarCodigo(5);

				$sql2 = "UPDATE dbo.users SET Rec_Cod = ('$code') WHERE Email='$email'";
				$result2 = sqlsrv_query($conn, $sql2);



				$row = sqlsrv_fetch_array($result);
				
				$subject = "Your recovery code for PHP Login";
				$body = "Use este enlace para reestablecer su contraseña: http://localhost:8000/changePass.php?id=$code&email=$email";
				$headers = 'From: salastevealexandre@gmail.com' . "\r\n" .
				'Reply-To: Becarios' . "\r\n" .
				'X-Mailer: PHP/' . phpversion();
				
				if(mail($email, $subject, $body, $headers)){
					//if(true){
					echo"Has recibido un código";
				}	else{
					echo "email no enviado";
				}			
				
				echo "<div class='alert alert-success alert-dismissible mt-4' role='alert'>
				<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
				<span aria-hidden='true'>&times;</span></button>
				<p>Email esta en la base de datos</p>
				<p><a class='alert-link' href=login.php>Login</a></p></div>";
			} else {
				echo "We are sorry, but that email is not in our data base.";
			}
			?>
		</div>
	</div>
</div>
<!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
	</body>
</html>