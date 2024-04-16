<?php

session_start();
 

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Delete Event</title>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <link rel="stylesheet" href="style/home.css" />
     <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
     <style>
body {
margin-top:70px;
}
</style>

  </head>
 
  <body>  
  <nav class="navbar-container">
      <div class="left-section">
        <div class="web-title">Marimas Factory Tours</div>
      </div>
      <div class="right-section">
        <!-- <div class="navbar-right">Home</div>
        <div class="navbar-right">Order</div> -->
        <a href="#" class="navbar-home" style="text-decoration: none;">Hapus <br>Acara</a>
        <a href="change_date.php" class="navbar-order" style="text-decoration: none;">Ubah Tanggal</a>
        <a href="export_event.php" class="navbar-order" style="text-decoration: none;">Export Acara</a>

         <a href="logout.php" >Sign Out</a>
      </div>
    </nav>

    <h1>Hapus Acara</h1>

    <form id="dateForm">
      <label for="eventSelect">Pilih Acara:</label><br />
      <select id="eventSelect" name="eventSelect"></select
      ><br />
      <input type="submit" value="Submit" />
    </form>

    <div id="calendar"></div>

    <script>
      
      $.ajax({
        url: "db_get.php",
        method: "POST",
        success: function (events) {
          var eventSelect = document.getElementById("eventSelect");
          events.forEach(function (eventData) {
            
            var option = document.createElement("option");
            option.value =
              eventData.extendedProps.id;
            option.text =
            eventData.extendedProps.instansi + " - " + eventData.start + " - " + eventData.extendedProps.jumlah + " orang" ;
            
            eventSelect.add(option);
          });
        },
      });

      document.addEventListener("DOMContentLoaded", function () {
        var calendarEl = document.getElementById("calendar");
        var calendar = new FullCalendar.Calendar(calendarEl, {
          initialView: "dayGridMonth",
          eventDidMount: function (info) {
           
            info.el.querySelector(".fc-event-title").innerHTML =
              '<div style="white-space:normal;">' + info.event.title + "</div>";
          },
          eventSources: [
            {
              events: function (fetchInfo, successCallback, failureCallback) {
                $.ajax({
                  url: "db_get.php", 
                  method: "POST",
                  success: function (events) {
                    var fullCalendarEvents = events.map(function (eventData) {
                      return {
                        title: eventData.extendedProps.instansi + "<br>" + eventData.extendedProps.jumlah + " orang",
                        start: eventData.start,
                        allDay: true,
                        extendedProps: {
                          id: eventData.extendedProps.id,
                          jumlah: eventData.extendedProps.jumlah,
                          email: eventData.extendedProps.email,
                          instansi: eventData.extendedProps.instansi,
                        },
                      };
                    });
                    successCallback(fullCalendarEvents);
                  },
                  error: function (errorThrown) {
                    failureCallback(errorThrown);
                  },
                });
              },
            },
          ],
        });
        calendar.render();

        document
          .getElementById("dateForm")
          .addEventListener("submit", function (e) {
            e.preventDefault();
            var eventChanged =
              document.getElementById("eventSelect").value;

            var events = calendar.getEvents();
            var event = events.find(function (event) {
              var eventCurrent = event.extendedProps.id;
            // console.log("event current: " + eventCurrent);
            // console.log("event current type: " + typeof eventCurrent);
            return eventCurrent === eventChanged;
            });

            if (event) {
              var eventData = {
                id: event.extendedProps.id,               
              };
              
              // Add a confirmation dialog
              var confirmation = confirm("Apakah anda yakin menghapus acara ini?");
              
              if (confirmation) {
                $.ajax({
                  url: "db_delete.php", 
                  method: "POST",
                  data: eventData,
                  success: function (response) {
                    console.log(response);
                    calendar.refetchEvents();
                  },
                });
              } else {
                console.log("Acara tidak jadi dihapus.");
              }
            } else {
              alert(
                "No event found "                 
              );
            }

          });
      });
    </script>
  </body>
</html>
