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
    <title>Change Event Date</title>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style/admin.css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>


  </head>
  <body>
  <nav class="navbar-container">
      <div class="left-section">
        <div class="web-title">Marimas Factory Tour</div>
      </div>
      <div class="right-section">
        <!-- <div class="navbar-right">Home</div>
        <div class="navbar-right">Order</div> -->
        <a href="delete_event.php" class="navbar-home" style="text-decoration: none;">Hapus <br>Acara</a>

        <a href="#" class="navbar-order" style="text-decoration: none;">Ubah <br>Tanggal</a>
        <a href="export_event.php" class="navbar-order" style="text-decoration: none;">Export <br>Acara</a>

         <a href="logout.php" >Sign <br>Out</a>
      </div>
    </nav>
    <h1>Ubah Tanggal</h1>
    <form id="dateForm">
      <label for="eventSelect">Pilih Acara:</label><br />
      <select id="eventSelect" name="eventSelect"></select
      ><br />
      <label for="newDate">Tanggal Baru:</label><br />
      <input type="date" id="newDate" name="newDate" /><br />
      <input class="submit-button" type="submit" value="Submit" />
    </form>

    <div id="calendar"></div>

    <script>
    $(document).ready(function() {
    $('#eventSelect').select2({
        width: 'auto'
        
    });
});

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
                        title: eventData.extendedProps.instansi + " - "+ eventData.extendedProps.jumlah + " orang",
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
              console.log("event changed: " + eventChanged);
              console.log("event changed type: " + typeof eventChanged);
            var newDate = document.getElementById("newDate").value;

            var events = calendar.getEvents();
            var event = events.find(function (event) {
            var eventCurrent = event.extendedProps.id;
            // console.log("event current: " + eventCurrent);
            // console.log("event current type: " + typeof eventCurrent);
            return eventCurrent === eventChanged;
          });


            if (event) {
            var newStartDate = new Date(newDate);
            event.setStart(newStartDate, { maintainDuration: true });
            var eventData = {
              id: event.extendedProps.id,
              date: newDate,
            };  
            $.ajax({
              url: "db_update.php", 
              method: "POST",
              data: eventData,
              success: function (response) {
                console.log(response);
                calendar.refetchEvents();

      // Make another AJAX request to get the total number of people for the new date
      $.ajax({
        url: "db_get.php",
        method: "POST",
        data: { date: newDate },
        success: function (events) {
          var totalPeople = 0;
          events.forEach(function (eventData) {
            totalPeople += Number(eventData.extendedProps.jumlah);
          });

          // Display an alert with the new date and the total number of people
          alert(
            "The date for the event '" +
              event.title +
              "' has been changed to " +
              newDate +
              ". The total number of people for this date is now " +
              totalPeople +
              "."
          );
        },
      });
    },
  });
} else {
  alert(
    "No event found"
  );
}

          });
      });
    </script>
  </body>
</html>
