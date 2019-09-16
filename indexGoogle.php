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
  <link href="style.css" rel="stylesheet" type="text/css" />
  <link rel='icon' href='/favicon.ico'>
  <script src="google-demo.js"></script>
  <link rel="script" href="/google-demo.js">
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
    <div class="popupCloseButton">X</div>
    <p>Add any HTML content<br />inside the popup box!</p>
  </div>
</div>

<body class="container">

  <!--Logo corporativo-->
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
      <div id="calendar-status2" class="panel-body" style="font-size:360%">
        <p id="titu"></p>

      </div>
      <a>
        <div class="panel-body" style="font-size:120%;">
          <p style="font-size:200%" id="date"></p>
        </div>
      </a>

      <script>
        document.getElementById("titu").innerHTML = localStorage.getItem("GogleTit");

      </script>

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
          for (var i = 0; i < document.getElementsByClassName("descripcion").length; i++) {
            document.getElementsByClassName("descripcion")[i].style.color = LightenDarkenColor(localStorage.getItem("colorLetra"), +40);
          }

        }, 10);
      </script>
    </b>
    <div class="list-group" id="event-list">
    </div>
    <div id="contenedor"></div>
  </div>

  <!--Add buttons to initiate auth sequence and sign out href="#signout"-->
  <button id="authorize_button" class="btn btn-lg btn-primary" style="display: none;">Autorizar</button>
  </div>


  <script type="text/javascript">

    var CLIENT_ID = '797775691897-js15pneg62n8je6ceut544o7h61bej0m.apps.googleusercontent.com';
    var API_KEY = 'AIzaSyCtX4QnFIbtnJ2XauohImZSNvgrbt89Trs';
    
    

    // Array of API discovery doc URLs for APIs used by the quickstart
    var DISCOVERY_DOCS = ["https://www.googleapis.com/discovery/v1/apis/calendar/v3/rest"];

    // Authorization scopes required by the API; multiple scopes can be
    // included, separated by spaces.
    var SCOPES = "https://www.googleapis.com/auth/calendar.readonly";

    var authorizeButton = document.getElementById('authorize_button');
    var signoutButton = document.getElementById('signout_button');


    /**
     *  On load, called to load the auth2 library and API client library.
     */
    function handleClientLoad() {
      gapi.load('client:auth2', initClient);
    }

    /**
     *  Initializes the API client library and sets up sign-in state
     *  listeners.
     */
    function initClient() {
      gapi.client.init({
        apiKey: API_KEY,
        clientId: CLIENT_ID,
        discoveryDocs: DISCOVERY_DOCS,
        scope: SCOPES
      }).then(function () {
        // Listen for sign-in state changes.
        gapi.auth2.getAuthInstance().isSignedIn.listen(updateSigninStatus);

        // Handle the initial sign-in state.
        updateSigninStatus(gapi.auth2.getAuthInstance().isSignedIn.get());
        authorizeButton.onclick = handleAuthClick;
        signoutButton.onclick = handleSignoutClick;
      }, function (error) {
        appendPre(JSON.stringify(error, null, 2));
      });
    }

    /**
     *  Called when the signed in status changes, to update the UI
     *  appropriately. After a sign-in, the API is called.
     */
    function updateSigninStatus(isSignedIn) {
      if (isSignedIn) {
        authorizeButton.style.display = 'none';
        signoutButton.style.display = 'block';



        listUpcomingEvents();

      } else {
        authorizeButton.style.display = 'block';
        signoutButton.style.display = 'none';

      }
    }

    /**
     *  Sign in the user upon button click.
     */
    function handleAuthClick(event) {
      gapi.auth2.getAuthInstance().signIn();
    }

    /**
     *  Sign out the user upon button click.
     */
    function handleSignoutClick(event) {

      gapi.auth2.getAuthInstance().signOut();
      location.reload();
    }

    /**
     * Append a pre element to the body containing the given message
     * as its text node. Used to display the results of the API call.
     *
     * @param {string} message Text to be placed in pre element.
     */
    function appendPre(message) {
      var pre = document.getElementById('content');
      var textContent = document.createTextNode(message + '\n');
      pre.appendChild(textContent);
    }




    var CalendarioID;
    try {
      CalendarioID = localStorage.getItem("idGoogle");
    } catch (err) {
      CalendarioID = null;
    }

    //Parametros fechas en horas

    var startD = localStorage.getItem("startDataD");
    var startH = localStorage.getItem("startDataH");
    var endD = localStorage.getItem("endDataD");
    var endH = localStorage.getItem("endDataH");

    if (startD == "" || startD == null) {
      startD = 0;
    }

    if (startH == "" || startH == null) {
      startH = 0;
    }

    if (endD == "" || endD == null) {
      endD = 1;
    }

    if (endH == "" || endH == null) {
      endH = 0;
    }


    var cantHorasTotalStart = (startD * 24) + parseInt(startH);
    var cantHorasTotalEnd = (endD * 24) + parseInt(endH);


    var top1 = localStorage.getItem("topData");
    var fechaAux = new Date();
    fechaAux.setHours(fechaAux.getHours() + cantHorasTotalEnd); //este numero son las horas a sumar de fin de corte
    //console.log("Closto nombre almeja: " + fechaAux.toISOString());

    var fechaAux2 = new Date();
    fechaAux2.setHours(fechaAux2.getHours() - cantHorasTotalStart); //este numero son las horas a restar start
    //console.log("Closto nombre almeja: " + fechaAux2.toISOString());


    if (top1 == "" || top1 == null) {
      top1 = 10;
    } else {
    }

    var check = localStorage.getItem("check");

    if (localStorage.getItem("colorDiv") == null || localStorage.getItem("colorDiv") == "") {
      var colorDiv = "rgb(124, 169, 206)";
    } else {
      var colorDiv = localStorage.getItem("colorDiv");
    }




    try {

      var colorLetra = localStorage.getItem("colorLetra");

      if (colorLetra == null || colorLetra == "") {
        colorLetra = "#333333";
      }
      //document.getElementsById("");
      var colorDesc = localStorage.getItem("colorLetra");

      colorDesc = LightenDarkenColor(colorDesc, 40);
      console.log(colorLetra);
      //document.getElementById("event-list").style.color = colorLetra;
      //document.getElementById("ocultablec").style.color = colorDesc;

    } catch (error) {
      console.log(error);
    }




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



    /**
     * Print the summary and start datetime/date of the next ten events in
     * the authorized user's calendar. If no events are found an
     * appropriate message is printed.
     */
    function listUpcomingEvents() {
      gapi.client.calendar.events.list({
        'calendarId': CalendarioID,
        'timeMax': fechaAux.toISOString(),
        'timeMin': fechaAux2.toISOString(),
        'showDeleted': false,
        'singleEvents': true,
        'maxResults': top1,
        'orderBy': 'startTime'
      }).then(function (response) {
        var events = response.result.items;
        if (events.length > 0) {

          for (i = 0; i < events.length; i++) {

            var event = events[i];
            var x = 0;
            var startData = event.start.dateTime;
            var endData = event.end.dateTime;
            console.log("fechas: " + startData + " " + endData)
            if (startData == null || endData == null) {
              console.log("entroooo");
              startData = event.start.date;
              endData = event.end.date;
              startData = new Date(startData);
              endData = new Date(endData);

              var x = ((endData - startData) / (1000 * 60 * 60) / 24);
              var x2 = Math.trunc(x);
            } else {
              startData = new Date(startData);
              endData = new Date(endData);
              startData = startData.getTime();
              endData = endData.getTime();
              //console.log("fechas: "+ startData + " "+ endData);
              var x = ((endData - startData) / (1000 * 60 * 60) / 24);
              var x2 = Math.trunc(x);
            }

            if (x > x2) {
              x2 = x2 + 1;
            }

            if (x2 <= 1) {
              x2 = "0";
            }

            console.log("soyyo: " + x2);

            if (x2 > 1) {
              var event = events[i];
              var start = event.start.dateTime;
              var end = event.end.dateTime;
              var fecha = start;
              var f = new (Date);
              var m = f.getMonth() + 1;
              var d = f.getDate();


              var fecha2;
              fecha2 = new Date();
              var dd = fecha2.getDate();
              var mm = fecha2.getMonth() + 1;
              var yyyy = fecha2.getFullYear();

              if (dd < 10) {
                dd = '0' + dd;
              }
              if (mm < 10) {
                mm = '0' + mm;
              }

              var fecha2 = dd + '/' + mm + '/' + yyyy;
              //console.log("Nueva Fecha"+fecha2);

              var descrip = event.description;
              console.log(event.description);
              if (descrip == null) {
                descrip = "";
              }

              //comprobador

              if (check == null) {
                check = "true";
              }

              if (check == "true") {
                //document.getElementById("ocultablec").style.display = "block";
                //document.getElementById("ocultablec").style.display = "block";
                descrip = descrip;
              }

              if (check == "false") {
                //document.getElementsById("ocultablec").style.display = "none";
                descrip = "";
              }

              //AQUIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIII

              if (fecha == null) {

                console.log("null");
                fecha = event.start.date.substring(0, 10);
                var y2 = fecha.substring(0, 4);
                var m2 = fecha.substring(5, 7);
                var d2 = fecha.substring(8, 10);
                fecha = d2 + "/" + m2 + "/" + y2;

                var div = document.createElement('div');

                div.setAttribute('class', 'list-group-item');
                div.style.backgroundColor = colorDiv;
                div.style.color = colorLetra;
                div.style.fontSize = '230%';
                div.style.marginBottom = '15px';
                document.getElementById('contenedor').appendChild(div);




                if (fecha2 == fecha) {

                  div.innerHTML = ("<b>" + event.summary + " (" + x2 + " días)<p class='descripcion' style='font-size:60% ;color:#555555'>" + descrip + '<p>' + "Todo el Dia" + '\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0' + "Hoy" + '</b>');
                } else {
                  div.innerHTML = ("<b>" + event.summary + " (" + x2 + " días)<p class='descripcion' style='font-size:60% ;color:#555555'>" + descrip + '<p>' + "Todo el Dia" + '\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0' + fecha + '</b>');
                }


              } else {
                fecha = fecha.substring(0, 10);
                if (m < 10) {
                  m = "0" + m;
                }
                if (d < 10) {
                  d = "0" + d;
                }
                f = f.getFullYear() + "-" + m + "-" + d;



                if (!start) {
                  start = event.start.date;
                }
                if (!end) {
                  end = event.end.date;
                }



                start = start.substring(11, 16);
                end = end.substring(11, 16);
                var div = document.createElement('div');
                console.log("fechasComp" + start + " " + end);

                div.setAttribute('class', 'list-group-item');
                div.style.backgroundColor = colorDiv;
                div.style.color = colorLetra;
                //'rgb(124, 169, 206)'
                div.style.fontSize = '230%';
                div.style.marginBottom = '15px';
                if (fecha == f) {
                  if (start == end && start == "00:00") {
                    div.innerHTML = ('<b>' + event.summary + " (" + x2 + " días)<p class='descripcion' style='font-size:60% ;color:#555555'>" + descrip + '<p>' + "Todo el Día" + '\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0' + "Hoy" + '</b>');

                  } else {
                    div.innerHTML = ('<b>' + event.summary + " (" + x2 + " días)<p class='descripcion' style='font-size:60% ;color:#555555'>" + descrip + '<p>' + start + ' - ' + end + '\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0' + "Hoy" + '</b>');
                  }
                } else {
                  var y2 = fecha.substring(0, 4);
                  var m2 = fecha.substring(5, 7);
                  var d2 = fecha.substring(8, 10);
                  fecha = d2 + "/" + m2 + "/" + y2;
                  if (start == end && start == "00:00") {
                    div.innerHTML = ('<b>' + event.summary + " (" + x2 + " días)<p class='descripcion' style='font-size:60% ;color:#555555'>" + descrip + '<p>' + "Todo el Día" + '\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0' + fecha + '</b>');

                  } else {
                    div.innerHTML = ('<b>' + event.summary + " (" + x2 + " días)<p class='descripcion' style='font-size:60% ;color:#555555'>" + descrip + '<p>' + start + ' - ' + end + '\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0' + fecha + '</b>');
                  }
                }


                document.getElementById('contenedor').appendChild(div);

                //document.body.appendChild(div);

              }
            } else {//cond 2

              var event = events[i];
              var start = event.start.dateTime;
              var end = event.end.dateTime;
              var fecha = start;
              var f = new (Date);
              var m = f.getMonth() + 1;
              var d = f.getDate();


              var fecha2;
              fecha2 = new Date();
              var dd = fecha2.getDate();
              var mm = fecha2.getMonth() + 1;
              var yyyy = fecha2.getFullYear();

              if (dd < 10) {
                dd = '0' + dd;
              }
              if (mm < 10) {
                mm = '0' + mm;
              }

              var fecha2 = dd + '/' + mm + '/' + yyyy;
              //console.log("Nueva Fecha"+fecha2);

              var descrip = event.description;
              console.log(event.description);
              if (descrip == null) {
                descrip = "";
              }

              //comprobador

              if (check == null) {
                check = "true";
              }

              if (check == "true") {
                //document.getElementById("ocultablec").style.display = "block";
                //document.getElementById("ocultablec").style.display = "block";
                descrip = descrip;
              }

              if (check == "false") {
                //document.getElementsById("ocultablec").style.display = "none";
                descrip = "";
              }

              //AQUIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIII

              if (fecha == null) {

                console.log("null");
                fecha = event.start.date.substring(0, 10);
                var y2 = fecha.substring(0, 4);
                var m2 = fecha.substring(5, 7);
                var d2 = fecha.substring(8, 10);
                fecha = d2 + "/" + m2 + "/" + y2;

                var div = document.createElement('div');

                div.setAttribute('class', 'list-group-item');
                div.style.backgroundColor = colorDiv;
                div.style.color = colorLetra;
                div.style.fontSize = '230%';
                div.style.marginBottom = '15px';
                document.getElementById('contenedor').appendChild(div);




                if (fecha2 == fecha) {

                  div.innerHTML = ("<b>" + event.summary + "<p class='descripcion' style='font-size:60% ;color:#555555'>" + descrip + '<p>' + "Todo el Dia" + '\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0' + "Hoy" + '</b>');
                } else {
                  div.innerHTML = ("<b>" + event.summary + "<p class='descripcion' style='font-size:60% ;color:#555555'>" + descrip + '<p>' + "Todo el Dia" + '\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0' + fecha + '</b>');
                }


              } else {
                fecha = fecha.substring(0, 10);
                if (m < 10) {
                  m = "0" + m;
                }
                if (d < 10) {
                  d = "0" + d;
                }
                f = f.getFullYear() + "-" + m + "-" + d;



                if (!start) {
                  start = event.start.date;
                }
                if (!end) {
                  end = event.end.date;
                }



                start = start.substring(11, 16);
                end = end.substring(11, 16);
                var div = document.createElement('div');
                console.log("fechasComp" + start + " " + end);

                div.setAttribute('class', 'list-group-item');
                div.style.backgroundColor = colorDiv;
                div.style.color = colorLetra;
                div.style.fontSize = '230%';
                div.style.marginBottom = '15px';
                if (fecha == f) {
                  if (start == end && start == "00:00") {
                    div.innerHTML = ('<b>' + event.summary + "<p class='descripcion' style='font-size:60% ;color:#555555'>" + descrip + '<p>' + "Todo el Día" + '\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0' + "Hoy" + '</b>');

                  } else {
                    div.innerHTML = ('<b>' + event.summary + "<p class='descripcion' style='font-size:60% ;color:#555555'>" + descrip + '<p>' + start + ' - ' + end + '\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0' + "Hoy" + '</b>');
                  }
                } else {
                  var y2 = fecha.substring(0, 4);
                  var m2 = fecha.substring(5, 7);
                  var d2 = fecha.substring(8, 10);
                  fecha = d2 + "/" + m2 + "/" + y2;
                  if (start == end && start == "00:00") {
                    div.innerHTML = ('<b>' + event.summary + "<p class='descripcion' style='font-size:60% ;color:#555555'>" + descrip + '<p>' + "Todo el Día" + '\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0' + fecha + '</b>');

                  } else {
                    div.innerHTML = ('<b>' + event.summary + "<p class='descripcion' style='font-size:60% ;color:#555555'>" + descrip + '<p>' + start + ' - ' + end + '\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0' + fecha + '</b>');
                  }
                }


                document.getElementById('contenedor').appendChild(div);

                //document.body.appendChild(div);

              }
            }

          }
        } else {
          appendPre('No upcoming events found.');
        }
      });
    }



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

  <script async defer src="https://apis.google.com/js/api.js" onload="this.onload=function(){};handleClientLoad()"
    onreadystatechange="if (this.readyState === 'complete') this.onload()">
    </script>


</body>

</html>