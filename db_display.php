<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "event_marimas";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT name, email, date, instansi, nomorwa, jumlah, umur_minimum, umur_maksimal FROM event";
$result = $conn->query($sql);

$events = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $event = array(
          'date' => $row['date'],
            'name' => $row['name'],
            'email' => $row['email'],           
            'instansi' => $row['instansi'],
            'nomorwa' => $row['nomorwa'],
            'jumlah' => $row['jumlah'],
            'rentang_umur' => $row['umur_minimum'] . ' - ' . $row['umur_maksimal'],
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
