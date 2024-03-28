<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
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
      // Fetch the events from the server when the page loads
      $.ajax({
        url: "db_get.php",
        method: "POST",
        success: function (events) {
          var eventSelect = document.getElementById("eventSelect");
          events.forEach(function (eventData) {
            // Create a new option element for each event
            var option = document.createElement("option");
            option.value =
              eventData.title + " - " + eventData.extendedProps.instansi;
            option.text =
              eventData.title + " - " + eventData.extendedProps.instansi;
            // Add the option element to the select element
            eventSelect.add(option);
          });
        },
      });

      document.addEventListener("DOMContentLoaded", function () {
        var calendarEl = document.getElementById("calendar");
        var calendar = new FullCalendar.Calendar(calendarEl, {
          initialView: "dayGridMonth",
          eventDidMount: function (info) {
            // Set the HTML of the event's title element to allow text wrapping
            info.el.querySelector(".fc-event-title").innerHTML =
              '<div style="white-space:normal;">' + info.event.title + "</div>";
          },
          eventSources: [
            {
              events: function (fetchInfo, successCallback, failureCallback) {
                $.ajax({
                  url: "db_get.php", // replace with your server-side script URL
                  method: "POST",
                  success: function (events) {
                    var fullCalendarEvents = events.map(function (eventData) {
                      return {
                        title: eventData.title,
                        start: eventData.start,
                        allDay: true,
                        extendedProps: {
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
            var eventTitleAndInstansi =
              document.getElementById("eventSelect").value;

            var events = calendar.getEvents();
            var event = events.find(function (event) {
              var eventTitleAndInstansiCurrent =
                event.title + " - " + event.extendedProps.instansi;
              console.log("title: " + event.title);
              console.log(eventTitleAndInstansi);
              console.log("current: " + eventTitleAndInstansiCurrent);
              return eventTitleAndInstansiCurrent === eventTitleAndInstansi;
            });

            if (event) {
              var eventData = {
                name: event.title,
                instansi: event.extendedProps.instansi,
              };
              $.ajax({
                url: "db_update.php", // replace with your server-side script URL
                method: "POST",
                data: eventData,
                success: function (response) {
                  // Handle the server's response here
                  console.log(response);
                  // Refetch events from the server
                  calendar.refetchEvents();
                },
              });
            } else {
              alert(
                "No event found with title and instansi: " +
                  eventTitleAndInstansi
              );
            }
          });
      });
    </script>
  </body>
</html>