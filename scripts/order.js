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
                  title:
                    eventData.title + "\n" + eventData.extendedProps.instansi,
                  start: eventData.start,
                  allDay: true,
                  extendedProps: {
                    nama: eventData.title,
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

  document.getElementById("eventForm").addEventListener("submit", function (e) {
    e.preventDefault();
    var name = document.getElementById("name").value;
    var email = document.getElementById("email").value;
    var date = document.getElementById("date").value;
    var instansi = document.getElementById("instansi").value;
    var jumlah = document.getElementById("jumlah").value;

    // Create Date objects for the selected date and today's date
    var selectedDate = new Date(date);
    var today = new Date();

    // Set the time of both dates to 00:00:00 for a fair comparison
    selectedDate.setHours(0, 0, 0, 0);
    today.setHours(0, 0, 0, 0);

    // Check if the selected date is today or before today
    if (selectedDate <= today) {
      alert("Tanggal harus setelah hari ini.");
      return; // Stop the function here
    }
    let eventsOnSelectedDate = calendar
      .getEvents()
      .filter((event) => event.startStr === date);
    let totalJumlahOnSelectedDate = eventsOnSelectedDate.reduce(
      (total, event) => total + Number(event.extendedProps.jumlah),
      0
    );
    console.log("jumlah peserta hari itu " + totalJumlahOnSelectedDate);
    let eventsama = calendar.getEvents();
    var cekeventsama = eventsama.find(function (event) {
      let namainstansisubmit = name.toLowerCase() + instansi.toLowerCase();
      const namainstansidata =
        event.extendedProps.nama.toLowerCase() +
        event.extendedProps.instansi.toLowerCase();
      return namainstansisubmit === namainstansidata;
    });
    if (cekeventsama) {
      alert("Peserta dengan nama dan instansi ini sudah ada.");
      return;
    }
    if (totalJumlahOnSelectedDate + Number(jumlah) > 150) {
      alert(
        "Jumlah Seluruh Peserta sudah melebihi kapasitas. Silahkan pilih tanggal lain."
      );
    } else {
      var eventData = {
        name: name,
        email: email,
        date: date,
        instansi: instansi,
        jumlah: jumlah,
      };
      $.ajax({
        url: "db_insert.php",
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
          document.getElementById("jumlah").value = "";
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
