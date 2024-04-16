<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "event_marimas";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$name = $_POST['name'];
$email = $_POST['email'];
$date = $_POST['date'];
$instansi = $_POST['instansi'];
$jumlah = $_POST['jumlah'];
// $nomorwa = str_replace(' ', '', $_POST['nomorwa']); // Remove spaces from the phone number

$stmt = $conn->prepare("INSERT INTO `event` (`no`, `name`, `email`,`date`, `instansi`,`jumlah`,`nomorwa`) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssss", $no, $name, $email, $date, $instansi, $jumlah, $nomorwa);

$no = '';

if ($stmt->execute() === TRUE) {
  echo "New record created successfully";
} else {
  echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
