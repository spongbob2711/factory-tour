function formatInput(input) {
  // Remove any non-digit characters
  var num = input.value.replace(/\D/g, "");

  // Add a space after every 4th digit
  num = num.replace(/(\d{4})/g, "$1 ").trim();

  // Update the input value
  input.value = num;
}
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
                  title:
                    eventData.extendedProps.instansi +
                    "<br>" +
                    eventData.extendedProps.jumlah +
                    " org",
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
    var nomorwa = document.getElementById("nomorwa").value;

    var selectedDate = new Date(date);
    var today = new Date();

    selectedDate.setHours(0, 0, 0, 0);
    today.setHours(0, 0, 0, 0);

    if (selectedDate <= today) {
      alert("Tanggal harus setelah hari ini.");
      return;
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
        nomorwa: nomorwa,
      };
      $.ajax({
        url: "db_insert.php",
        method: "POST",
        data: eventData,
        success: function (response) {
          console.log(response);

          calendar.refetchEvents();

          document.getElementById("name").value = "";
          document.getElementById("email").value = "";
          document.getElementById("date").value = "";
          document.getElementById("instansi").value = "";
          document.getElementById("jumlah").value = "";
          document.getElementById("nomorwa").value = "";
        },
      });
      //sendgrid
      var xhr = new XMLHttpRequest();
      xhr.open("POST", "send_email.php");
      xhr.setRequestHeader("Content-Type", "application/json");
      xhr.onload = function () {
        console.log(this.response);
      };
      xhr.send(JSON.stringify(eventData));
    }
  });

  calendar.render();
});
