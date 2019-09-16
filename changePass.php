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

    $code = $_GET['id'];
    $email = $_GET['email'];
    $email = filtroInject($email);

    $connectionInfo = array( "Database"=>$dbname, "UID"=>$dbuser, "PWD"=>$dbpass);
	$conn = sqlsrv_connect( $dbhost, $connectionInfo);
		  
	// Check connection
	if (!$conn) {
		echo "Conexión no se pudo establecer.<br>";
		die( print_r( sqlsrv_errors(), true));
		}
            
    $sql = "SELECT Email, Rec_Cod FROM dbo.users WHERE Email='$email'";
    $result = sqlsrv_query($conn, $sql);

    if (sqlsrv_has_rows($result) === true) {
        $row = sqlsrv_fetch_array($result);
    }else{
        echo"Correo incorrecto.";
    }
    if($row['Rec_Cod'] == $code){
        echo"cuadro de update pass

            
            <form method='post' action='updatePass.php'>
            <p>Correo:</p> 
            <input type='text' name='email' value='$email' > <br/>
            <p>Código:</p> 
            <input type='text' name='rec_cod' value='$code' > <br/>
            <p>Password:</p>
            <input name='password' required='required' type='password' id='password' />
            <p>Confirm Password:</p>
            <input name='password_confirm' required='required' type='password' id='password_confirm' oninput='check(this)' />
            <script language='javascript' type='text/javascript'>
            function check(input) {
                if (input.value != document.getElementById('password').value) {
                    input.setCustomValidity('Password Must be Matching.');
                } else {
                    // input is valid -- reset the error message
                    input.setCustomValidity('');
                }
            }
            </script>
            <br /><br />
            <input type='submit' value='Confirmar Código' />
            </form>
            
        ";
    }else{
        echo"Código incorrecto.";
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