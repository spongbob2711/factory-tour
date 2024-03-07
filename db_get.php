<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "event_marimas";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT name, email, date, instansi FROM event";
$result = $conn->query($sql);

$events = array();

if ($result->num_rows > 0) {
  // Output data of each row
  while($row = $result->fetch_assoc()) {
    $events[] = $row;
  }
} else {
  echo "0 results";
}

header('Content-Type: application/json');
echo json_encode($events);

$conn->close();
?>
