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

$name = $_POST['name'];
$date = $_POST['date'];
$instansi = $_POST['instansi'];

// SQL query to update the event's date
$sql = "UPDATE `event` SET `date` = '$date' WHERE `name` = '$name' AND `instansi` = '$instansi'";

if ($conn->query($sql) === TRUE) {
  echo "Event date updated successfully";
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>