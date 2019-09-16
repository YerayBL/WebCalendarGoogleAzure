$(function  () {

    document.getElementById("h5").style.color = "#17a58d"

    //Si el navegador pierde conexión actualiza a rojo el color de fuente de la última actualización
    setInterval(function () {
      document.getElementById("h5").style.color = "red"
    }, 60000);
  
    //function to reload info every 5sec if the navigator is online, else
    //it displays the information done.
    setInterval(function () {
      if (navigator.onLine) {
        location.reload();
  
      }
    }, 50000); // 120000 ~> 2 minutes 5000 ~> 5 seconds 
    console.log("reinicio");

});