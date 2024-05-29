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
    <title>Event Table</title>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <link rel="stylesheet" href="style/admin.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
      table {
  width: 80%;
  border-collapse: collapse;
  margin-bottom: 20px;
  margin-left: auto;
  margin-right: auto;
  background-color: #f8f8f8;
  box-shadow: 0 2px 3px rgba(0, 0, 0, 0.1);
}
th,
td {
  border: 1px solid #ddd;
  padding: 8px;
  text-align: left;
  width: 12%;
  word-wrap: break-word;
}
th {
  background-color: hsl(23, 100%, 50%, 0.945);
  color: white; 
}
tr:nth-child(even) {
  background-color: #f2f2f2;
}
caption {
  font-size: 30px;
  margin-bottom: 10px;
  text-align: left;
  caption-side: top;
  color: black;
  font-weight: var(--ff-semibold);
}

    </style>
</head>
<body>
<nav class="navbar-container">
      <div class="left-section">
        <div class="web-title">Marimas Factory Tour</div>
      </div>
      <div class="right-section">
        <!-- <div class="navbar-right">Home</div>
        <div class="navbar-right">Order</div> -->
        <a href="#" class="navbar-home" style="text-decoration: none;">List <br>Acara</a>
        <a href="delete_event.php" class="navbar-home" style="text-decoration: none;">Hapus <br>Acara</a>
        <a href="change_date.php" class="navbar-order" style="text-decoration: none;">Ubah <br>Tanggal</a>
        <a href="export_event.php" class="navbar-order" style="text-decoration: none;">Export <br>Acara</a>

         <a href="logout.php" >Sign <br>Out</a>
      </div>
    </nav>

    <h1>List Acara</h1>
    <select id="monthSelect">
        <option value="">Semua Bulan</option>
        <option value="0">Januari</option>
        <option value="1">Februari</option>
        <option value="2">Maret</option>
        <option value="3">April</option>
        <option value="4">Mei</option>
        <option value="5">Juni</option>
        <option value="6">Juli</option>
        <option value="7">Agustus</option>
        <option value="8">September</option>
        <option value="9">Oktober</option>
        <option value="10">November</option>
        <option value="11">Desember</option>
    </select>
    <div id="content"></div>

    <script>
        var months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        var contentDiv = document.getElementById('content');
        var monthSelect = document.getElementById('monthSelect');

        // Fetch data from your PHP script
        fetch('db_display.php')
            .then(response => response.json())
            .then(events => {
                var groupedEvents = {};

                // Group events by month
                events.forEach(function(event) {
                    var date = new Date(event.date);
                    var month = months[date.getMonth()];
                    if (!groupedEvents[month]) {
                        groupedEvents[month] = [];
                    }
                    groupedEvents[month].push(event);
                });

                // Sort months
                var sortedMonths = Object.keys(groupedEvents).sort(function(a, b) {
                    return months.indexOf(a) - months.indexOf(b);
                });

                // Generate tables for each month
                function generateTables(selectedMonth) {
                    contentDiv.innerHTML = ''; // Clear the content div
                    sortedMonths.forEach(function(month) {
                        if (selectedMonth === '' || selectedMonth == months.indexOf(month)) {
                            var table = document.createElement('table');
                            var caption = document.createElement('caption');
                            caption.textContent = "Bulan " + month;
                            table.appendChild(caption);

                            var thead = document.createElement('thead');
                            var tr = document.createElement('tr');
                            ['Tanggal', 'Nama', 'Instansi', 'Email', 'Nomor WA', 'Jumlah Peserta', 'Rentang Umur'].forEach(function(header) {
                                var th = document.createElement('th');
                                th.textContent = header;
                                tr.appendChild(th);
                            });
                            thead.appendChild(tr);
                            table.appendChild(thead);

                            var tbody = document.createElement('tbody');
                            groupedEvents[month].forEach(function(event) {
                                var tr = document.createElement('tr');
                                [event.date, event.name, event.instansi, event.email, event.nomorwa, event.jumlah, event.rentang_umur].forEach(function(data) {
                                    var td = document.createElement('td');
                                    td.textContent = data;
                                    tr.appendChild(td);
                                });
                                tbody.appendChild(tr);
                            });
                            table.appendChild(tbody);

                            contentDiv.appendChild(table);
                        }
                    });
                }

                // Initial table generation
                generateTables('');

                // Regenerate tables when a month is selected
                monthSelect.addEventListener('change', function() {
                    generateTables(this.value);
                });
            })
            .catch(error => console.error('Error:', error));
    </script>
</body>
</html>
