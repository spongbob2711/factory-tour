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
              // Convert the events data to the format expected by FullCalendar
              var fullCalendarEvents = events.map(function (eventData) {
                return {
                  title: eventData.name + "\n" + eventData.instansi,
                  start: eventData.date,
                  allDay: true,
                };
              });
              successCallback(fullCalendarEvents);
            },
            error: function (jqXHR, textStatus, errorThrown) {
              failureCallback(errorThrown);
            },
          });
        },
      },
    ],
  });

  document.getElementById("eventForm").addEventListener("submit", function (e) {
    e.preventDefault();
    var name = document.getElementById("name").value;
    var email = document.getElementById("email").value;
    var date = document.getElementById("date").value;
    var instansi = document.getElementById("instansi").value;

    let isDuplicate = calendar
      .getEvents()
      .some((event) => event.startStr === date);
    if (isDuplicate) {
      alert("An event already exists on this date.");
    } else {
      var eventData = {
        name: name,
        email: email,
        date: date,
        instansi: instansi,
      };
      $.ajax({
        url: "db_insert.php", // replace with your server-side script URL
        method: "POST",
        data: eventData,
        success: function (response) {
          // Handle the server's response here
          console.log(response);
          // Refetch events from the server
          calendar.refetchEvents();
          // Clear the form fields
          document.getElementById("name").value = "";
          document.getElementById("email").value = "";
          document.getElementById("date").value = "";
          document.getElementById("instansi").value = "";
        },
      });
      // AJAX call to SendGrid PHP file
      var xhr = new XMLHttpRequest();
      xhr.open("POST", "send_email.php"); // replace with your SendGrid PHP file
      xhr.setRequestHeader("Content-Type", "application/json"); // Set the content type to JSON
      xhr.onload = function () {
        console.log(this.response);
      };
      xhr.send(JSON.stringify(eventData));
    }
  });

  // Render the calendar
  calendar.render();
});
