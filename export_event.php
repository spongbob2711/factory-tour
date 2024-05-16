<?php
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
  
  session_start();

  if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
      header("location: login.php");
      exit;
  }

  
  ?>
<?php
      require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "event_marimas";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT name, nomorwa, email, date, instansi, jumlah FROM event WHERE date BETWEEN ? AND ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $start_date, $end_date);
    $stmt->execute();
    $result = $stmt->get_result();

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setCellValue('A1', 'Name');
    $sheet->setCellValue('B1', 'Email');
    $sheet->setCellValue('C1', 'Date');
    $sheet->setCellValue('D1', 'Instansi');
    $sheet->setCellValue('E1', 'Jumlah');
    $sheet->setCellValue('F1', 'Nomor WA');



    $rowCount = 2;
    while ($row = $result->fetch_assoc()) {
        $sheet->setCellValue('A' . $rowCount, $row['name']);
        $sheet->setCellValue('B' . $rowCount, $row['email']);
        $sheet->setCellValue('C' . $rowCount, $row['date']);
        $sheet->setCellValue('D' . $rowCount, $row['instansi']);
        $sheet->setCellValue('E' . $rowCount, $row['jumlah']);
        $sheet->setCellValue('F' . $rowCount, $row['nomorwa']);

        $rowCount++;
    }

    $writer = new Xlsx($spreadsheet);
    $filename = 'events_' . $start_date . '_to_' . $end_date . '.xlsx';
    
    // Save the file to the server first
    $writer->save($filename);
    
    // Set the headers and output the file
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="'. $filename .'"');
    header('Cache-Control: max-age=0');
    readfile($filename);
    
    exit;
}
?>

  <!DOCTYPE html>
  <html>
  <head>
      <title>Export to Excel</title>
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
      <link rel="stylesheet" href="style/admin.css" />
  </head>
  <body>
  <nav class="navbar-container">
        <div class="left-section">
          <div class="web-title">Marimas Factory Tour</div>
        </div>
        <div class="right-section">
          <!-- <div class="navbar-right">Home</div>
          <div class="navbar-right">Order</div> -->
          <a href="delete_event.php" class="navbar-home" style="text-decoration: none;">Hapus <br>Acara</a>

          <a href="change_date.php" class="navbar-order" style="text-decoration: none;">Ubah <br>Tanggal</a>
          <a href="#" class="navbar-order" style="text-decoration: none;">Export <br>Acara</a>

          <a href="logout.php" >Sign <br>Out</a>
        </div>
      </nav>
      <div class="form-container">
      <form action="" method="post">
          <label for="start_date">Start Date:</label>
          <input class="exportdate" type="date" id="start_date" name="start_date" required>
          <br>
          <label for="end_date">End Date:</label>
          <input class="exportdate" type="date" id="end_date" name="end_date" required>
          <br>
          <input type="submit" value="Export to Excel">
      </form>
      </div>
    
     
  </body>
  </html>
