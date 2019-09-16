
$(function () {


  try {
    document.getElementById("inputId").value = localStorage.getItem("idGrap");
    document.getElementById("inputIdGoogle").value = localStorage.getItem("idGoogle");
  } catch (error) {

  }

  var startD = localStorage.getItem("startDataD");
  var startH = localStorage.getItem("startDataH");
  var endD = localStorage.getItem("endDataD");
  var endH = localStorage.getItem("endDataH");
  var check = localStorage.getItem("check");


  console.log("resultado  " + check);



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


  // App configuration
  var authEndpoint = 'https://login.microsoftonline.com/common/oauth2/v2.0/authorize?';
  var redirectUri = 'http://localhost:8000';
  //var redirectUri = 'https://192.168.60.16:8000';
  var appId = localStorage.getItem("idGrap");
  //f48b36b2-06be-41fb-9cc7-6fe124456f3f
  var scopes = 'openid profile User.Read Mail.Read Calendars.Read Contacts.Read Calendars.Read.Shared';

  document.getElementById("h5").style.color = "#17a58d"
  document.getElementById("salita").value = localStorage.getItem("sala");
  document.getElementById("salaRoom").value = localStorage.getItem("sala2");


  //Si el navegador pierde conexión actualiza a rojo el color de fuente de la última actualización
  setInterval(function () {
    document.getElementById("h5").style.color = "red"
  }, 625000);

  //function to reload info every 5sec if the navigator is online, else
  //it displays the information done.
  $(function () {
    $('[data-toggle="tooltip"]').tooltip()
  })

  setInterval(function () {
    if (navigator.onLine) {
      location.reload();

    }
  }, 600000); // 120000 ~> 2 minutes 5000 ~> 5 seconds 
  console.log("reinicio");

  // Check for browser support for sessionStorage
  if (typeof (Storage) === 'undefined') {
    render('#unsupportedbrowser');
    return;
  }

  // Check for browser support for crypto.getRandomValues
  var cryptObj = window.crypto || window.msCrypto; // For IE11
  if (cryptObj === undefined || cryptObj.getRandomValues === 'undefined') {
    render('#unsupportedbrowser');
    return;
  }
  render(window.location.hash);

  $(window).on('hashchange', function () {
    render(window.location.hash);
  });

  function render(hash) {
    var action = hash.split('=')[0];

    // Hide everything
    $('.main-container .page').hide();


    // Check for presence of access token
    var isAuthenticated = (sessionStorage.accessToken != null && sessionStorage.accessToken.length > 0);
    renderNav(isAuthenticated);
    renderTokens();

    var pagemap = {
      // Welcome page
      '': function () {
        renderWelcome(isAuthenticated);
      },

      // Receive access token
      '#access_token': function () {
        handleTokenResponse(hash);
      },

      // Signout
      '#signout': function () {
        clearUserState();

        // Redirect to home page
        window.location.hash = '#';
      },

      // Error display
      '#error': function () {
        var errorresponse = parseHashParams(hash);
        if (errorresponse.error === 'login_required' ||
          errorresponse.error === 'interaction_required') {
          // For these errors redirect the browser to the login
          // page.
          window.location = buildAuthUrl();
        } else {
          renderError(errorresponse.error, errorresponse.error_description);
        }
      },

      // Display calendar 1
      '#cal1': function () {
        if (isAuthenticated) {
          renderCal1();
        } else {
          // Redirect to home page
          window.location.hash = '#';
        }
      },

      // Display calendar 2
      /*
      '#cal2': function () {
        if (isAuthenticated) {
          renderCal2();
        } else {
          // Redirect to home page
          window.location.hash = '#';
        }
      },

      // Display calendar 3
      '#cal3': function () {
        if (isAuthenticated) {
          renderCal3();
        } else {
          // Redirect to home page
          window.location.hash = '#';
        }
      },

      //Display calendar 4
      '#cal4': function () {
        if (isAuthenticated) {
          renderCal4();
        } else {
          // Redirect to home page
          window.location.hash = '#';
        }
      },
      */
      // Shown if browser doesn't support session storage
      '#unsupportedbrowser': function () {
        $('#unsupported').show();
      }
    }

    if (pagemap[action]) {
      pagemap[action]();
    } else {
      // Redirect to home page
      window.location.hash = '#';
    }
  }

  function setActiveNav(navId) {
    $('#navbar').find('li').removeClass('active');
    $(navId).addClass('active');
  }

  function renderNav(isAuthed) {
    if (isAuthed) {
      $('.authed-nav').show();
    } else {
      $('.authed-nav').show();
     // $('.authed-nav').hide();
    }
  }


  function renderTokens() {
    if (sessionStorage.accessToken) {
      // For demo purposes display the token and expiration
      var expireDate = new Date(parseInt(sessionStorage.tokenExpires));
      $('#token', window.parent.document).text(sessionStorage.accessToken);
      $('#expires-display', window.parent.document).text(expireDate.toLocaleDateString() + ' ' + expireDate.toLocaleTimeString());
      if (sessionStorage.idToken) {
        $('#id-token', window.parent.document).text(sessionStorage.idToken);
      }
      $('#token-display', window.parent.document).show();
    } else {
      $('#token-display', window.parent.document).hide();
    }
  }

  function renderError(error, description) {
    $('#error-name', window.parent.document).text('An error occurred: ' + decodePlusEscaped(error));
    $('#error-desc', window.parent.document).text(decodePlusEscaped(description));
    $('#error-display', window.parent.document).show();
  }

  function renderWelcome(isAuthed) {

    if (isAuthed) {
      //console.log("hago esto");
      $('#username').text(sessionStorage.userDisplayName);
      $('#logged-in-welcome').show();
    } else {
      $('#connect-button').attr('href', buildAuthUrl());
      $('#signin-prompt').show();
    }
  }

  document.getElementById("nula").addEventListener("click", function () {
    idGraph = document.getElementById("inputId").value;
    localStorage.setItem("idGrap", idGraph);
    appId = localStorage.getItem("idGrap");
    //posible reload?
    if (localStorage.getItem("idGrap") != idGraph) {
      location.reload();
    }
    console.log(localStorage.getItem("idGrap"));
  });





  document.getElementById("connGoogle").addEventListener("click", function () {
    idGoogle = document.getElementById("inputIdGoogle").value;
    localStorage.setItem("idGoogle", idGoogle);
    GoogleID = localStorage.getItem("idGoogle");
    //posible reload?
    console.log(localStorage.getItem("idGoogle"));
  });





  document.getElementById("butsal").addEventListener("click", function () {
    salita = document.getElementById("salita").value;
    localStorage.setItem("sala", salita)
    console.log(salita);
    salaRoom = document.getElementById("salaRoom").value;
    localStorage.setItem("sala2", salaRoom)
    //alert("Se ha guardado:\n\nCorreo: " + salita + "\nNombre de sala: "+salaRoom);
    document.getElementById("snackbar").innerHTML = "Se ha guardado:<br><hr style='color:white'>Correo: " + salita + "<br>Nombre de sala: " + salaRoom;
    //document.getElementById("nombreSala").innerHTML(salaRoom);
    timeout();
  });

  function timeout() {
    var x = document.getElementById("snackbar");
    x.className = "show";
    setTimeout(function () { x.className = x.className.replace("show", ""); }, 5000);
  }

  var botonH = document.getElementById("botHome");
  botonH.onclick = function () {
    console.log("ADIOSSSSSSSSSSSSSS");
    window.location.hash = "#";
  }


  function renderCal1() {
    setActiveNav('#inbox-nav');
    $('#calendar-status').text('Loading...');
    $('#event-list').empty();
    $('#calendar').show();

    getUserEvents3(function (events, error) {
      if (error) {
        renderError('getUserEvents failed', error);
      } else {
        $('#calendar-status').text(localStorage.getItem("sala2"));
        var templateSource = $('#event-list-template').html();
        var template = Handlebars.compile(templateSource);

        var eventList = template({ events: events });
        $('#event-list').append(eventList);
      }
    });
  }

  // OAUTH FUNCTIONS =============================
  function buildAuthUrl() {
    //location.reload();
    // Generate random values for state and nonce
    sessionStorage.authState = guid();
    sessionStorage.authNonce = guid();
    appId = localStorage.getItem("idGrap");

    var authParams = {
      response_type: 'id_token token',
      client_id: localStorage.getItem("idGrap"),
      redirect_uri: redirectUri,
      scope: scopes,
      state: sessionStorage.authState,
      nonce: sessionStorage.authNonce,
      response_mode: 'fragment'
    };

    return authEndpoint + $.param(authParams);
  }

  function handleTokenResponse(hash) {
    // If this was a silent request remove the iframe
    //console.log("hola2");
    $('#auth-iframe').remove();

    // clear tokens
    sessionStorage.removeItem('accessToken');
    sessionStorage.removeItem('idToken');

    var tokenresponse = parseHashParams(hash);

    // Check that state is what we sent in sign in request
    if (tokenresponse.state != sessionStorage.authState) {
      sessionStorage.removeItem('authState');
      sessionStorage.removeItem('authNonce');
      // Report error on back
      //window.location.hash = '#error=Invalid+state&error_description=The+state+in+the+authorization+response+did+not+match+the+expected+value.+Please+try+signing+in+again.';
      window.location.hash = '#';
      return;
    }

    sessionStorage.authState = '';
    sessionStorage.accessToken = tokenresponse.access_token;

    // Get the number of seconds the token is valid for,
    // Subract 5 minutes (300 sec) to account for differences in clock settings
    // Convert to milliseconds
    var expiresin = (parseInt(tokenresponse.expires_in) - 300) * 1000;
    var now = new Date();
    var expireDate = new Date(now.getTime() + expiresin);
    sessionStorage.tokenExpires = expireDate.getTime();

    sessionStorage.idToken = tokenresponse.id_token;

    validateIdToken(function (isValid) {
      if (isValid) {
        // Re-render token to handle refresh
        renderTokens();

        // Redirect to home page
        window.location.hash = '#';
      } else {
        clearUserState();
        // Report error
        window.location.hash = '#error=Invalid+ID+token&error_description=ID+token+failed+validation,+please+try+signing+in+again.';
      }
    });
  }

  function validateIdToken(callback) {
    // Per Azure docs (and OpenID spec), we MUST validate
    // the ID token before using it. However, full validation
    // of the signature currently requires a server-side component
    // to fetch the public signing keys from Azure. This sample will
    // skip that part (technically violating the OpenID spec) and do
    // minimal validation

    if (null == sessionStorage.idToken || sessionStorage.idToken.length <= 0) {
      callback(false);
    }

    // JWT is in three parts seperated by '.'
    var tokenParts = sessionStorage.idToken.split('.');
    if (tokenParts.length != 3) {
      callback(false);
    }

    // Parse the token parts
    var header = KJUR.jws.JWS.readSafeJSONString(b64utoutf8(tokenParts[0]));
    var payload = KJUR.jws.JWS.readSafeJSONString(b64utoutf8(tokenParts[1]));

    // Check the nonce
    if (payload.nonce != sessionStorage.authNonce) {
      sessionStorage.authNonce = '';
      callback(false);
    }

    sessionStorage.authNonce = '';

    // Check the audience
    if (payload.aud != appId) {
      callback(false);
    }

    // Check the issuer
    // Should be https://login.microsoftonline.com/{tenantid}/v2.0
    if (payload.iss !== 'https://login.microsoftonline.com/' + payload.tid + '/v2.0') {
      callback(false);
    }

    // Check the valid dates
    var now = new Date();
    // To allow for slight inconsistencies in system clocks, adjust by 5 minutes
    var notBefore = new Date((payload.nbf - 300) * 1000);
    var expires = new Date((payload.exp + 300) * 1000);
    if (now < notBefore || now > expires) {
      callback(false);
    }

    // Now that we've passed our checks, save the bits of data
    // we need from the token.

    sessionStorage.userDisplayName = payload.name;
    sessionStorage.userSigninName = payload.preferred_username;

    // Per the docs at:
    // https://azure.microsoft.com/en-us/documentation/articles/active-directory-v2-protocols-implicit/#send-the-sign-in-request
    // Check if this is a consumer account so we can set domain_hint properly
    sessionStorage.userDomainType =
      payload.tid === '9188040d-6c67-4c5b-b112-36a304b66dad' ? 'consumers' : 'organizations';

    callback(true);
  }

  function makeSilentTokenRequest(callback) {
    // Build up a hidden iframe
    var iframe = $('<iframe/>');
    iframe.attr('id', 'auth-iframe');
    iframe.attr('name', 'auth-iframe');
    iframe.appendTo('body');
    iframe.hide();

    iframe.load(function () {
      callback(sessionStorage.accessToken);
    });

    iframe.attr('src', buildAuthUrl() + '&prompt=none&domain_hint=' +
      sessionStorage.userDomainType + '&login_hint=' +
      sessionStorage.userSigninName);
  }

  // Helper method to validate token and refresh
  // if needed
  function getAccessToken(callback) {
    var now = new Date().getTime();
    var isExpired = now > parseInt(sessionStorage.tokenExpires);
    // Do we have a token already?
    if (sessionStorage.accessToken && !isExpired) {
      // Just return what we have
      if (callback) {
        callback(sessionStorage.accessToken);
      }
    } else {
      // Attempt to do a hidden iframe request
      makeSilentTokenRequest(callback);
    }
  }

  // OUTLOOK API FUNCTIONS =======================
  // a key map of allowed keys
  var allowedKeys = {
    37: 'left',
    38: 'up',
    39: 'right',
    40: 'down',
    65: 'a',
    66: 'b'
  };

  // the 'official' Konami Code sequence
  var konamiCode = ['up', 'up', 'down', 'down', 'left', 'right', 'left', 'right', 'b', 'a'];

  // a variable to remember the 'position' the user has reached so far.
  var konamiCodePosition = 0;

  // add keydown event listener
  document.addEventListener('keydown', function (e) {
    // get the value of the key code from the key map
    var key = allowedKeys[e.keyCode];
    // get the value of the required key from the konami code
    var requiredKey = konamiCode[konamiCodePosition];

    // compare the key with the required key
    if (key == requiredKey) {

      // move to the next key in the konami code sequence
      konamiCodePosition++;

      // if the last key is reached, activate cheats
      if (konamiCodePosition == konamiCode.length) {
        activateCheats();
        konamiCodePosition = 0;
      }
    } else {
      konamiCodePosition = 0;
    }
  });

  function activateCheats() {
    alert("Creado por Christian Campos y Yeray Blanco");
  }


  function getUserEvents3(callback) {
    getAccessToken(function (accessToken) {
      if (accessToken) {
        // Create a Graph client
        var client = MicrosoftGraph.Client.init({
          authProvider: (done) => {
            // Just return the token
            done(null, accessToken);
          }
        });

        var cantHorasTotalStart = (startD * 24) + parseInt(startH);
        console.log("hasdiuflhajsd" + cantHorasTotalStart);

        console.log("soy mas cosas" + endD + "/" + endH);
        var cantHorasTotalEnd = (endD * 24) + parseInt(endH);


        // Set base of the calendar view to today at midnight
        const base = new Date(new Date());
        // Set base of the calendar view to today at midnight
        const start = new Date(new Date(base).setHours(base.getHours() - cantHorasTotalStart));
        //start = start.setHours(start.getHours()-3);
        // Set end of the calendar view to 30 days from start
        console.log("soy end:" + cantHorasTotalEnd);
        const end = new Date(new Date(base).setHours(base.getHours() + cantHorasTotalEnd));

        var top1 = localStorage.getItem("topData");


        //TODO

        if (top1 == "" || top1 == null) {
          top1 = 10;
        } else {
        }


        start.toISOString();
        end.toISOString();
        console.log(start.toISOString());
        // Get the 10 newest events
        //for(var i=0;i<4;i++){
        client
          .api('/users/' + localStorage.getItem('sala') + '/calendarView?startDateTime=' + start.toISOString() + '&endDateTime=' + end.toISOString())
          .top(top1)
          .orderby("start/dateTime")
          .select('subject,start,end,createdDateTime,bodyPreview')
          .get((err, res) => {
            if (err) {
              callback(null, err);
            } else {
              callback(res.value);
            }
          });
      } else {
        var error = { responseText: 'Could not retrieve access token' };
        callback(null, error);
      }
    });
  }


  // HELPER FUNCTIONS ============================
  function guid() {
    var buf = new Uint16Array(8);
    cryptObj.getRandomValues(buf);
    function s4(num) {
      var ret = num.toString(16);
      while (ret.length < 4) {
        ret = '0' + ret;
      }
      return ret;
    }
    return s4(buf[0]) + s4(buf[1]) + '-' + s4(buf[2]) + '-' + s4(buf[3]) + '-' +
      s4(buf[4]) + '-' + s4(buf[5]) + s4(buf[6]) + s4(buf[7]);
  }

  function parseHashParams(hash) {
    var params = hash.slice(1).split('&');

    var paramarray = {};
    params.forEach(function (param) {
      param = param.split('=');
      paramarray[param[0]] = param[1];
    });
    return paramarray;
  }

  function decodePlusEscaped(value) {
    // decodeURIComponent doesn't handle spaces escaped
    // as '+'
    if (value) {
      return decodeURIComponent(value.replace(/\+/g, ' '));
    } else {
      return '';
    }
  }

  function clearUserState() {
    // Clear session
    sessionStorage.clear();
  }

  Handlebars.registerHelper('ifCond', function (v1, operator, v2, options) {

    switch (operator) {
      case '==':
        return (v1 == v2) ? options.fn(this) : options.inverse(this);
      case '===':
        return (v1 === v2) ? options.fn(this) : options.inverse(this);
      case '!=':
        return (v1 != v2) ? options.fn(this) : options.inverse(this);
      case '!==':
        return (v1 !== v2) ? options.fn(this) : options.inverse(this);
      case '<':
        return (v1 < v2) ? options.fn(this) : options.inverse(this);
      case '<=':
        return (v1 <= v2) ? options.fn(this) : options.inverse(this);
      case '>':
        return (v1 > v2) ? options.fn(this) : options.inverse(this);
      case '>=':
        return (v1 >= v2) ? options.fn(this) : options.inverse(this);
      case '&&':
        return (v1 && v2) ? options.fn(this) : options.inverse(this);
      case '||':
        return (v1 || v2) ? options.fn(this) : options.inverse(this);
      default:
        return options.inverse(this);
    }
  });


  //TODO
  Handlebars.registerHelper("fecha2", function (datetime) {
    var date = new Date(datetime);
    //posible bug al testear
    date.setHours(date.getHours() + 2);
    var date2 = new Date();

    var diasd;
    var mesesm;
    var anosa;

    var diasd2;
    var mesesm2;
    var anosa2;

    diasd = date.getDate();
    console.log(diasd);
    mesesm = date.getMonth() + 1;
    anosa = date.getFullYear();

    diasd2 = date2.getDate();
    mesesm2 = date2.getMonth() + 1;
    anosa2 = date2.getFullYear();

    var fechaconf1 = diasd + "" + mesesm + "" + anosa;
    var fechaconf2 = diasd2 + "" + mesesm2 + "" + anosa2;
    console.log("Fechas igualar: " + fechaconf1 + " " + fechaconf2);
    if (fechaconf1 == fechaconf2) {
      return '\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0' + "Hoy";
    }

    if (diasd < 10) {
      diasd = "0" + diasd;
    }
    if (mesesm < 10) {
      mesesm = "0" + mesesm;
    }

    return '\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0' + diasd + "/" + mesesm + "/" + anosa;
  });

  //kilerpa menu

  Handlebars.registerHelper("formatDate", function (datetime) {
    // Dates from API look like:
    // 2016-06-27T14:06:13Z

    var date = new Date(datetime);
    date.setHours(date.getHours() + 2);
    //return date.toLocaleDateString() + ' ' + date.toLocaleTimeString();
    if (date.getMinutes() < 10) {
      var minutes = "0" + date.getMinutes();
    } else {
      var minutes = date.getMinutes() + "";
    }

    if (date.getHours() < 10) {
      var hours = "0" + date.getHours();
    } else {
      var hours = date.getHours() + "";
    }

    dateSave = hours + ":" + minutes;
    console.log("date save" + dateSave);
    return hours + ":" + minutes;
  });
  var dateSave;
  var datee2

  Handlebars.registerHelper("formatDate1", function (datetime, datetime2) {
    // Dates from API look like:
    // 2016-06-27T14:06:13Z

    var date = new Date(datetime);
    date.setHours(date.getHours() + 2);
    var dateIni = new Date(datetime2);
    dateIni.setHours(dateIni.getHours() + 2);


    //return date.toLocaleDateString() + ' ' + date.toLocaleTimeString();
    if (date.getMinutes() < 10) {
      var minutes = "0" + date.getMinutes();
    } else {
      var minutes = date.getMinutes() + "";
    }
    if (date.getHours() < 10) {
      var hours = "0" + date.getHours();
    } else {
      var hours = date.getHours() + "";
    }

    var d = date.getDate();
    var dateHoy = new Date();

    var dateHoy23 = new Date();
    dateHoy23.setHours() - 2;

    dateHoy = dateHoy23.getDate();

    if (d == dateHoy) {
      return hours + ":" + minutes + '\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0' + "Hoy";
    } else {
      //ini
      var yIni = dateIni.getFullYear();
      var mIni = dateIni.getMonth() + 1;
      var dIni = dateIni.getDate();
      if (dIni < 10) {
        dIni = "0" + dIni
      }
      if (mIni < 10) {
        mIni = "0" + mIni
      }
      var dateeIni = dIni + "/" + mIni + "/" + yIni;


      var y = date.getFullYear();
      var m = date.getMonth() + 1;
      var d = date.getDate();
      if (d < 10) {
        d = "0" + d
      }
      if (m < 10) {
        m = "0" + m
      }
      var datee = d + "/" + m + "/" + y;

      if (dateSave == (hours + ":" + minutes)) {
        if (dateSave == "00:00" || dateSave == "02:00") {
          //console.log("AAAAAAAAAAAWWWWWWWWWWWWW YEAAAAAAAAAAAAAh");
          datee2 = datee;
          var dateeCoser = datee2.substr(0, 2);
          dateeCoser = dateeCoser - 1;
          datee2 = dateeCoser + datee.substr(2, 8);
          return "Todo el Dia";
        }
      }

      var dateHoyNueva = new Date();
      var ddd = dateHoyNueva.getDate()
      var mmm = dateHoyNueva.getMonth() + 1
      var yyy = dateHoyNueva.getFullYear();
      if (ddd < 10) {
        dd = "0" + ddd;
      }
      if (mmm < 10) {
        mmm = "0" + mmm;
      }

      dateHoyNueva = ddd + "/" + mmm + "/" + yyy

      if (dateeIni == dateHoyNueva) {
        return hours + ":" + minutes + '\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0' + "Hoy";
      } else {
        return hours + ":" + minutes + '\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0' + dateeIni;
      }
    }

  });


  function ocultar() {

    console.log("HE LLEGADO");
    if (check == null) {
      check = "true";
    }

    if (check == "true") {
      //document.getElementById("ocultablec").style.display = "block";
      //document.getElementById("ocultablec").style.display = "block";

    }

    if (check == "false") {
      //document.getElementsById("ocultablec").style.display = "none";
      document.getElementById("ocultablec").setAttribute("id", "ocultao");
    }
  }


  Handlebars.registerHelper("cond", function (datetime) {
    // Dates from API look like:
    // 2016-06-27T14:06:13Z
    var date = new Date(datetime);
    date.setHours(date.getHours() + 2);
    var days = date.getDate();
    //return date.toLocaleDateString() + ' ' + date.toLocaleTimeString();
    var hours = date.getHours() + 2;
    var date2 = new Date();
    var days2 = date2.getDate();
    var hours2 = date2.getHours();
    console.log(hours, days, hours2, days2);
    if (days2 == days) {
      if (hours < hours2) {
        return true;
      }
      return true
    }
    return true
  });




  /**
         if (localStorage.getItem("colorDiv") == null || localStorage.getItem("colorDiv") == "") {
          var colorDiv = "rgb(124, 169, 206)";
        } else {
          var colorDiv = localStorage.getItem("colorDiv");
        }
        document.getElementsByClassName("listaDiv").style.backgroundColor = localStorage.getItem("colorDiv");
   */




  Handlebars.registerHelper("setColor", function () {



    console.log("ENTROOOOO1")
    if (localStorage.getItem("colorDiv") == null || localStorage.getItem("colorDiv") == "") {
      var colorDiv = "rgb(124, 169, 206)";
    } else {
      var colorDiv = localStorage.getItem("colorDiv");
    }
    try {
      document.getElementsByClassName("list-group-item").style.backgroundColor = "#888888";

    } catch (error) {
    }

  });












  Handlebars.registerHelper("dias", function (datetime, datetime2) {
    let fecha1 = new Date(datetime);


    //fecha1.setHours(fecha1.getHours() + 200);


    let fecha2 = new Date(datetime2);


    //fecha2.setHours(fecha2.getHours() + 2);


    let resta = fecha2.getTime() - fecha1.getTime()
    var x = resta / (1000 * 60 * 60);
    x = x / 24;
    var x2 = Math.trunc(x);
    if (x > x2) {
      x2 = x2 + 1;
    }
    if (x2 == 0) {
      return "(Mismo Día)";
    }

    if (x2 == 1) {
      x2 = "";
    } else {
      x2 = " (" + x2 + " días" + ")"
    }

    return x2;
  });

















  Handlebars.registerHelper("comprob", function (String) {
    if (check == null) {
      check = "true";
    }

    if (check == "true") {
      //document.getElementById("ocultablec").style.display = "block";
      //document.getElementById("ocultablec").style.display = "block";
      return String;

    }

    if (check == "false") {
      //document.getElementsById("ocultablec").style.display = "none";
      return "";
    }
  });

});
