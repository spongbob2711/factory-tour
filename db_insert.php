<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "event_marimas";


$conn = mysqli_connect($servername, $username, $password, $dbname);


if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$name = $_POST['name'];
$email = $_POST['email'];
$date = $_POST['date'];
$instansi = $_POST['instansi'];
$jumlah = $_POST['jumlah'];

//  $sql = "INSERT INTO 'event' (no,name, email, date,instansi)
//  VALUES ('','$name', '$email', '$date','$instansi')";
  $sql = "INSERT INTO `event` (`no`, `name`, `email`,`date`, `instansi`,`jumlah`)
  VALUES ('', '$name', '$email', '$date', '$instansi','$jumlah')";

//$rs = mysqli_query($conn, $sql);

if ($conn->query($sql) === TRUE) {
  echo "New record created successfully";
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
