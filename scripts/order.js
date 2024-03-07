document.addEventListener("DOMContentLoaded", function () {
  var calendarEl = document.getElementById("calendar");

  var calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: "dayGridMonth",
    eventDidMount: function (info) {
      // Set the HTML of the event's title element to allow text wrapping
      info.el.querySelector(".fc-event-title").innerHTML =
        '<div style="white-space:normal;">' + info.event.title + "</div>";
    },
  });

  var events = calendar.getEvents();

  // $.ajax({
  //   url: "db_get.php", // replace with your server-side script URL
  //   method: "POST",
  //   data: {
  //     /* any data you want to send to the server */
  //   },
  //   success: function (response) {
  //     // Parse the response into a JavaScript object
  //     var events = JSON.parse(response);

  //     // Add each event to the calendar
  //     events.forEach(function (eventData) {
  //       calendar.addEvent({
  //         title: eventData.name + " (" + eventData.email + ")",
  //         start: eventData.date,
  //         allDay: true,
  //       });
  //     });
  //   },
  // });
  $.ajax({
    url: "db_get.php", // replace with your server-side script URL
    method: "POST",

    success: function (events) {
      // Log the raw events data to the console
      //console.log("Raw events data:", events);

      // Parse the events data into a JavaScript object
      // events = JSON.parse(events);

      // Log the parsed events data to the console

      // Add each event to the calendar
      events.forEach(function (eventData) {
        calendar.addEvent({
          title: eventData.name + "\n" + eventData.instansi,
          start: eventData.date,
          allDay: true,
        });
      });
      // Now that the events have been added, you can get them
      var calendarEvents = calendar.getEvents();

      document
        .getElementById("eventForm")
        .addEventListener("submit", function (e) {
          e.preventDefault();
          var name = document.getElementById("name").value;
          var email = document.getElementById("email").value;
          var date = document.getElementById("date").value;
          var instansi = document.getElementById("instansi").value;
          // Now you can use these values to create an event on your calendar
          // You might want to create a function that checks if an event already exists on this date
          let isDuplicate = calendarEvents.some(
            (event) => event.startStr === date
          );
          if (isDuplicate) {
            alert("An event already exists on this date.");
          } else {
            calendar.addEvent({
              title: name + "\n" + instansi,
              start: date,
              allDay: true,
            });
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
              },
            });
          }
        });
      calendar.render();
    },
  });

  // document.getElementById("eventForm").addEventListener("submit", function (e) {
  //   e.preventDefault();
  //   var name = document.getElementById("name").value;
  //   var email = document.getElementById("email").value;
  //   var date = document.getElementById("date").value;
  //   var instansi = document.getElementById("instansi").value;
  //   // Now you can use these values to create an event on your calendar
  //   // You might want to create a function that checks if an event already exists on this date
  //   let isDuplicate = events.some((event) => event.start === date);

  //   if (isDuplicate) {
  //     alert("An event already exists on this date.");
  //   } else {
  //     calendar.addEvent({
  //       title: name + " (" + email + ")",
  //       start: date,
  //       allDay: true,
  //     });
  //     var eventData = {
  //       name: name,
  //       email: email,
  //       date: date,
  //       instansi: instansi,
  //     };
  //     $.ajax({
  //       url: "db_insert.php", // replace with your server-side script URL
  //       method: "POST",
  //       data: eventData,
  //       success: function (response) {
  //         // Handle the server's response here
  //         console.log(response);
  //       },
  //     });
  //   }
  // });
});
