<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "event_marimas";


$conn = mysqli_connect($servername, $username, $password, $dbname);


if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
$id = $_POST['id'];
$name = $_POST['name'];
$date = $_POST['date'];
$instansi = $_POST['instansi'];


$sql = "UPDATE `event` SET `date` = '$date' WHERE `no` = '$id'";

if ($conn->query($sql) === TRUE) {
  echo "Event date updated successfully";
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
