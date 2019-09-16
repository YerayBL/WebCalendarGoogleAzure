<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <script src="//ajax.aspnetcdn.com/ajax/jQuery/jquery-2.2.3.min.js"></script>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Calendaios Angel 24</title>
  <link href="//ajax.aspnetcdn.com/ajax/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" />
  <link href="//ajax.aspnetcdn.com/ajax/bootstrap/3.3.6/css/bootstrap-theme.min.css" rel="stylesheet">
  <link href="style.css" rel="stylesheet" type="text/css" />
  <link rel='icon' href='/favicon.ico'>


  <script src="//ajax.aspnetcdn.com/ajax/bootstrap/3.3.6/bootstrap.min.js"></script>
  <script src="//kjur.github.io/jsrsasign/jsrsasign-latest-all-min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.5/handlebars.min.js"></script>
  <script src="graph-js-sdk-web.js"></script>
  <script src="outlook-demo.js"></script>
  <script src="https://cdn.jsdelivr.net/gh/alfg/ping.js@0.2.2/dist/ping.min.js" type="text/javascript"></script>
  <link rel="script" href="/outlook-demo.js">

</head>

<?php
    if (isset($_SESSION['loggedin'])) {  
    }
    else {
        echo '<script>window.location.href = "login.php";</script>';
    }
    ?>
    
    <div class="hover_bkgr_fricc">
  <span class="helper"></span>
  <div>

    <p><b>Para obtener la id de su calendario es necesario ingresar en su cuenta de google y
        consultar sus calendarios. Posteriormente dirigase a opciones de calendario, como
        se muestra en la siguiente imagen. </b></p>
    <hr>
    <img class="images-pop" src="img/cal.png" alt="image calendar options">
    <b>
      <hr></b>
    <p> <b>
        Finalmente en esta página busque la id del calendario que desea.</b></p>
    <hr>
    <img class="images-pop" src="img/cal2.png" alt="image calendar id">
    <hr>
    <p><b>Ve a tu calendario haciendo clic aquí:</b> <a href="https://calendar.google.com/calendar/r?tab=wc"
        target="_blank"><img data-toggle="tooltip" data-placement="top" title="Obtener Id de su calendario"
          id="calendarIco" src="img/calendarIco.png" alt="Calendario"></a></p>
  </div>
</div>

<body>
  <!--Logo corporativo-->
  <div id="divLogo" style="background-color:#003764;"><img src="/img/a24.png" alt="a24"
      style="display:block;margin:auto;height:130px;"></div>
  <nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
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

          <!--<li id='inbox-nav'><a href="#">Calendario</a></li>-->
          <li id='inbox-nav'><a id="botHome" class="glyphicon glyphicon-home" style="font-size:28px"
              href="#signout"></a></li>
          <li id='inbox-nav2'><a id="botConf" class="glyphicon glyphicon-cog" style="font-size:28px"
              href="Ajustes.html"></a></li>

        </ul>
        <ul class="nav navbar-nav navbar-right authed-nav" style="margin-right:0px;background-color:black">
          <li><a href="logout.php" style="background-color:black">Sign out</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <div id="snackbar"></div>

  <!--Texto página principal-->
  <div class="container main-container">
    <div id="signin-prompt" class="jumbotron page">
      <h1>Acceda a los eventos de su calendario</h1>
      <br>
      <p>Elija en que plataforma se encuentra el calendario que desea mostrar.</p>
      <hr><br>

      <script>
        function comprobarId() {
          if (document.getElementById("inputId").value.length > 0) {
            try {
              //document.getElementById("nula").setAttribute("id","connect-button");
              document.getElementById("nula").style.display = "none";
              document.getElementById("connect-button").style.display = "inline-block";
              if (document.getElementById("inputId").value != localStorage.getItem("idGrap")) {
                console.log("son diferentes");
                document.getElementById("snackbar").innerHTML = "Se ha guardado su ID";
                alert("se ha guardado su ID");
                location.reload();
              }
              localStorage.setItem("idGrap", document.getElementById("inputId").value);
              console.log(localStorage);
            } catch (err) {
            }
          }
        }
      </script>

      <script>$(window).load(function () {
          $(".trigger_popup_fricc").click(function () {
            $('.hover_bkgr_fricc').show();
          });
          $('.hover_bkgr_fricc').click(function () {
            $('.hover_bkgr_fricc').hide();
          });
          $('.popupCloseButton').click(function () {
            $('.hover_bkgr_fricc').hide();
          });
        });</script>

      <script>
        var bool2 = localStorage.getItem("checkL");
        console.log(bool2);
        if (bool2 == "true") {
          document.getElementById("divLogo").style.display = "none";
        } else {
          document.getElementById("divLogo").style.display = "block";
        }

      </script>

      <p>
        <!--<a href="pagina.com" target="_blank">Título del enlace</a>-->
        <a class="btn btn-lg btn-primary pruebas" onclick="comprobarId()" style= "width:245px" href="#" role="button" id="nula">
          Verificar ID para Office365</a>
        <a class="btn btn-lg btn-primary pruebas" role="button" style="display:none;width:245px"
          id="connect-button">Conectar a Outlook [ICO]</a>
        <input id="inputId" type="text" placeholder="Escriba la Id de Cliente"><a href="tutoOffice.html"
          class="trigger_popup_fricc2"><img data-toggle="tooltip" data-placement="top"
            title="configuración de cuentas de Azure" id="questionTuto" src="./img/ico.png" alt="?"></a>
      </p>
      <hr>
      <p>
        <a class="btn btn-lg btn-primary" style= "width:245px" href="homeGoogle.php" role="button" id="connGoogle">  Conectar a Google   </a>
        <input id="inputIdGoogle" type="text" placeholder="Escriba la Id de Calendario"><a style="margin-left:30px" class="trigger_popup_fricc"><img
            data-toggle="tooltip" data-placement="top" title="Obtener Id de su calendario" id="question"
            src="./img/ico.png" alt="?"></a>
      </p>

    </div>

    <!-- Texto estando logeado -->
    <div id="logged-in-welcome" class="jumbotron page">
      <h1>Calendarios de Office365</h1>
      <br>
      <hr>
      <p> Correo de la Sala:</p> <input title="Introduzca el correo de la sala" id="salita" name="Room" type="text" />
      <br>
      <br>
      <p>Nombre de la Sala:</p> <input title="Introduzca el nombre de la sala" id="salaRoom" name="RoomName"
        type="text" />
      <br>
      <hr>
      <a href="#cal1"><button class="btn btn-lg btn-primary" id="butsal">Guardar y Cargar</button></a>
    </div>

    <!-- Mensaje de error para navegadores incompatibles -->
    <div id="unsupported" class="jumbotron page">
      <h1>Oops....</h1>
      <p>This page requires browser support for <a
          href="https://developer.mozilla.org/en-US/docs/Web/API/Web_Storage_API">session storage</a> and <a
          href="https://developer.mozilla.org/en-US/docs/Web/API/RandomSource/getRandomValues"><code>crypto.getRandomValues</code></a>.
        Unfortunately, your browser does not support one or both features. Please visit this page using a different
        browser.</p>
    </div>

    <!-- Error -->
    <div id="error-display" class="page panel panel-danger">
      <div class="panel-heading">
        <h3 class="panel-title" id="error-name"></h3>
      </div>
      <div class="panel-body">
        <pre><code id="error-desc"></code></pre>
      </div>
    </div>

    <!-- calendar display -->
    <div id="calendar" class="page panel panel-default">
      <div class="panel-heading">
        <h1 class="panel-title" style="font-size: 160%">Última conexión: <a id="h5">■
            <script>
              function reloj() {
                var n = new Date();
                var y = n.getFullYear();
                var m = n.getMonth() + 1;
                var d = n.getDate();
                var hh = n.getHours();
                var mm = n.getMinutes();
                var ss = n.getSeconds();
                if (hh < 10) {
                  hh = "0" + hh
                }
                if (ss < 10) {
                  ss = "0" + ss
                }
                if (mm < 10) {
                  mm = "0" + mm
                }
                if (d < 10) {
                  d = "0" + d
                }
                if (m < 10) {
                  m = "0" + m
                }
                var datee = d + "/" + m + "/" + y + " - " + hh + ":" + mm + ":" + ss
                document.getElementById("h5").innerHTML = datee;
              }
              reloj();
            </script></a>
          <!--ultima actualizacion-->
        </h1>
      </div>
      <b>
        <div id="calendar-status" class="panel-body" style="font-size:360%">

        </div>

        <div class="panel-body" style="font-size:120%;">
          <a>
            <p style="font-size:200%" id="date"></p>
          </a>
        </div>


        <script>
          
          setInterval(function () {
            var n = new Date();
            var y = n.getFullYear();
            var m = n.getMonth() + 1;
            var d = n.getDate();
            var hh = n.getHours();
            var mm = n.getMinutes();
            var ss = n.getSeconds();
            if (hh < 10) {
              hh = "0" + hh
            }
            if (ss < 10) {
              ss = "0" + ss
            }
            if (mm < 10) {
              mm = "0" + mm
            }
            if (d < 10) {
              d = "0" + d
            }
            if (m < 10) {
              m = "0" + m
            }
            var datee = d + "/" + m + "/" + y + " - " + hh + ":" + mm + ":" + ss
            document.getElementById("date").innerHTML = datee;
            actualizarColor();

          }, 10);
        </script>

        <script>

          function actualizarColor() {
            //localStorage.removeItem("colorDiv");
            var color = localStorage.getItem("colorDiv");
            //console.log("colorsitochingon ",color);
            if (color == null || color == "") {
              //console.log("entro aqui");
              color = "#7ca9ce";
              /*try {
                document.getElementById("listaDiv").style.backgroundColor = color;
                document.getElementById("listaDiv").setAttribute("id", "colreado");
              } catch (error) {

              }*/

            } 

              try {
                //var color = localStorage.getItem("colorDiv");
                //document.getElementById("listaDiv").style.backgroundColor = color;
                //document.getElementById("listaDiv").setAttribute("id", "colreado");
                var colorLetra = localStorage.getItem("colorLetra");
                if (colorLetra == null || colorLetra == "") {
                  colorLetra = "#333333";
                }

                for (var i = 0; i < document.getElementsByClassName("list-group-item").length; i++) {
                  document.getElementsByClassName("list-group-item")[i].style.backgroundColor = color;
                }

                for (var i = 0; i < document.getElementsByClassName("list-group-item").length; i++) {
                  document.getElementsByClassName("list-group-item")[i].style.color = colorLetra;
                }

                for (var i = 0; i < document.getElementsByClassName("descripcion").length; i++) {
                  document.getElementsByClassName("descripcion")[i].style.color = LightenDarkenColor(localStorage.getItem("colorLetra"), +40);
                }

                //var colorDesc = localStorage.getItem("colorLetra");
                //colorDesc = LightenDarkenColor(colorDesc, 40);
                //document.getElementById("event-list").style.color = colorLetra;
                //document.getElementById("ocultablec").style.color = colorDesc;
              } catch (error) {

              }
            

          }
        </script>

        <script>
          function LightenDarkenColor(col, amt) {

            var usePound = false;

            if (col[0] == "#") {
              col = col.slice(1);
              usePound = true;
            }

            var num = parseInt(col, 16);

            var r = (num >> 16) + amt;

            if (r > 255) r = 255;
            else if (r < 0) r = 0;

            var b = ((num >> 8) & 0x00FF) + amt;

            if (b > 255) b = 255;
            else if (b < 0) b = 0;

            var g = (num & 0x0000FF) + amt;

            if (g > 255) g = 255;
            else if (g < 0) g = 0;

            return (usePound ? "#" : "") + (g | (b << 8) | (r << 16)).toString(16);

          }
        </script>

        <div class="list-group" id="event-list">
        </div>
    </div>



    <!-- Handlebars template for event CALENDARIO list -->
    <script id="event-list-template" type="text/x-handlebars-template" style="background-color:blue;">
    {{#each events}}
      {{#if (cond this.start.dateTime)}}
    <div class="list-group-item listaDiv" id="listaDiv" style=" font-size:150%">
      <div style = "display:none">{{formatDate this.start.dateTime}} - {{formatDate1 this.end.dateTime}}</div>
      <h4 id="event-subject" class="list-group-item-heading" style="font-size:160%"> <b>{{this.subject}} {{dias this.start.dateTime this.end.dateTime}}</b> <b><p id="ocultablec" class="descripcion" style="font-size:60%;margin-top:10px; color:#555555">{{comprob this.bodyPreview}}</p></b> </h4>
      {{#ifCond "Todo el Dia" '!=' (formatDate1 this.end.dateTime)}}

        <b><p id="event-start" class="list-group-item-heading" style="font-size:150%">{{formatDate this.start.dateTime}} - {{formatDate1 this.end.dateTime this.start.dateTime}}</p></b>
      {{else}}
        <b><p id="event-start" class="list-group-item-heading" style="font-size:150%">{{formatDate1 this.end.dateTime}}{{fecha2 this.start.dateTime}}</p></b>
        {{/ifCond}}
        
      </div>
    <br>
    {{/if}}
    {{ocult}}
    {{setColor}}
    {{/each}}
    


  </script>

    <script>
      Handlebars.registerHelper('ifu', function (v1, v2) {
        if (v1 == v2) {
          return true;
        }
        return false;
      });
    </script>

  </div>
</body>

</html>