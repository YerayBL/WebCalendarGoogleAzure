<?php
session_start();
?>

<!doctype html>
<html lang="en">
	<head>
		<title>Check Login and create session</title>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
	</head>
	<body>
		<div class="container">
		
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
			
			// data sent from form login.html 
			$email = $_POST['email']; 
			$email = filtroInject($email);
			$password = $_POST['password'];
			
			// Query sent to database
			$result = sqlsrv_query($conn, "SELECT Email, Password, Activation, Name FROM users WHERE Email = '$email'");
			
			// Variable $row hold the result of the query
			//$row = mysqli_fetch_assoc($result);
			$row = sqlsrv_fetch_array($result);
			
			// Variable $hash hold the password hash on database
			$hash = $row['Password'];
			/* 
			password_Verify() function verify if the password entered by the user
			match the password hash on the database. If everything is OK the session
			is created for one minute. Change 1 on $_SESSION[start] to 5 for a 5 minutes session.
			*/
			if (password_verify($_POST['password'], $hash)) {	
			if	($row['Activation'] == 1){
				$_SESSION['loggedin'] = true;
				$_SESSION['name'] = $row['Name'];
				//$_SESSION['start'] = time();
				//$_SESSION['expire'] = $_SESSION['start'] + (1 * 60) ;						
				
				echo "<div class='alert alert-success mt-4' role='alert'><strong>Welcome!</strong> $row[Name]			
				<p><a href='edit-profile.php'>Edit Profile</a></p>	
				<p><a href='logout.php'>Logout</a></p></div>";	
				echo '<script>window.location.href = "index.php";</script>';
			}else {
				echo "<div class='alert alert-danger mt-4' role='alert'>¡Esta cuenta no está activada, revise su correo y activela!
				<p><a href='login.php'><strong>Please try again!</strong></a></p></div>";			
			}
			} else {
				echo "<div class='alert alert-danger mt-4' role='alert'>Email o Contraseña incorrectos!
				<p><a href='login.php'><strong>Please try again!</strong></a></p></div>";			
			}	
			?>

		</div>
		<!-- Optional JavaScript -->
		<!-- jQuery first, then Popper.js, then Bootstrap JS -->
		<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>

	</body>
</html>