<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "event_marimas";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get the date parameter from the request
$date = $_POST['date'];

// Modify the SQL query to filter events based on the date
$sql = "SELECT no,name, email, date, instansi, jumlah FROM event WHERE date = '$date'";
$result = $conn->query($sql);

$events = array();

if ($result->num_rows > 0) {
  
    while($row = $result->fetch_assoc()) {
      $event = array(
        'title' => $row['name'],
        'start' => $row['date'],
        'extendedProps' => array(
          'id' => $row['no'],
          'jumlah' => $row['jumlah'],
          'email' => $row['email'],
          'instansi' => $row['instansi'],
        ),
      );
    $events[] = $event;
  }
} else {
  echo "0 results";
}

header('Content-Type: application/json');
echo json_encode($events);

$conn->close();
?>
