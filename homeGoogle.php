<?php
session_start();
?>
<!DOCTYPE html>
<html>

<head>
  <title>Google Calendar API Quickstart</title>
  <meta charset="utf-8" />
  <title>Calendaios Angel 24</title>
  <link href="//ajax.aspnetcdn.com/ajax/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" />
  <link href="//ajax.aspnetcdn.com/ajax/bootstrap/3.3.6/css/bootstrap-theme.min.css" rel="stylesheet">
  <script src="//ajax.aspnetcdn.com/ajax/jQuery/jquery-2.2.3.min.js"></script>


  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>


  <link href="style.css" rel="stylesheet" type="text/css" />
  <link rel='icon' href='/favicon.ico'>
  <script src="homeGoogle-demo.js"></script>
  <link rel="script" href="/homeGoogle-demo.js">

</head>

<?php
    if (isset($_SESSION['loggedin'])) {  
    }
    else {
        echo '<script>window.location.href = "login.php";</script>';
    }
    ?>
  <div id="divLogo" style="background-color:#003764;"><img src="/img/a24.png" alt="a24"
      style="display:block;margin:auto;height:130px;"></div>
  <nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container main-container ">

      <div class="navbar-header">

        <!--Menú-->
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
          aria-expanded="false" aria-controls="navbar">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
      </div>

      <!--Información del menú-->
      <div id="navbar" class="navbar-collapse collapse" style="background-color:black">
        <ul class="nav navbar-nav authed-nav">

          <!--<li id='inbox-nav'><a href="#cal1">Leonardo Da Vinci</a></li>-->
          <li id='inbox-nav'><a class="glyphicon glyphicon-home" style="font-size:28px" href="index.php"></a></li>
          <li id='inbox-nav3'><a id="botConf2" class="glyphicon glyphicon-cog" style="font-size:28px"
              href="Ajustes.html"></a></li>

        </ul>
        <ul class="nav navbar-nav navbar-right authed-nav" style="margin-right:0px;background-color:black">
          <li><a href="logout.php" id="signout_button" style="background-color:black">Sign out</a></li>
        </ul>
      </div>

  </nav>

  <script>$(function () {
      $('[data-toggle="tooltip"]').tooltip()
    })</script>

  <!--Texto página principal-->
  <div class="container main-container">
    <div id="signin-prompt" class="jumbotron page">
      <h1>Calendarios de Google</h1>
      <br>
      <hr>


      <p>Nombre de la Sala:</p> <input title="Introduzca el nombre de la sala" id="salaGog" name="RoomGog"
        type="text" />
      <br>

      <hr>
      <a href="indexGoogle.php"><button class="btn btn-lg btn-primary" id="butGog">Guardar y Cargar</button></a>


    </div>

    <script>
      try {
        document.getElementById("salaGog").value = localStorage.getItem("GogleTit");
      } catch (error) {

      }

      document.getElementById("butGog").onclick = function () {
        var inputGog = document.getElementById("salaGog").value;
        //console.log(inputGog);
        localStorage.setItem("GogleTit", inputGog);
      };
    </script>


    <script>
      var bool2 = localStorage.getItem("checkL");
      console.log(bool2);
      if (bool2 == "true") {
        document.getElementById("divLogo").style.display = "none";
      } else {
        document.getElementById("divLogo").style.display = "block";
      }

    </script>





</body>

</html>