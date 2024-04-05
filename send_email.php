<?php
require_once 'sendgrid_config.php';
require 'vendor/autoload.php';
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['name'], $data['email'], $data['date'], $data['instansi'], $data['jumlah'])) {
    $name = $data['name'];
    $emailAddress = $data['email'];
    $date = $data['date'];
    $instansi = $data['instansi'];
    $jumlah = $data['jumlah'];


    
$email = new \SendGrid\Mail\Mail();
$email->setFrom(FROM_EMAIL, FROM_NAME);
$email->setSubject("Konfirmasi pemesanan acara Marimas Factory Tour");
$email->addTo($emailAddress, $name);
$email->addContent(
    "text/html",
    "Halo,<br><br>Terima kasih atas pemesanannya Bapak/Ibu $name dari instansi $instansi. Anda telah melakukan pemesanan acara Marimas Factory Tour yang memiliki peserta berjumlah $jumlah orang pada tanggal $date.Tunggu konfirmasi lebih lanjut dari kami.<br><br>Terima Kasih atas pemesanannya,<br>Marimas Company"
);

$sendgrid = new \SendGrid(SENDGRID_API_KEY);

$response = $sendgrid->send($email);

//h-3 email
$reminderDate = date('Y-m-d', strtotime($date . ' -3 days'));


$reminderEmail = new \SendGrid\Mail\Mail();
$reminderEmail->setFrom(FROM_EMAIL, FROM_NAME);
$reminderEmail->setSubject("Pengingat pemesanan acara Marimas Factory Tour");
$reminderEmail->addTo($emailAddress, $name);
$reminderEmail->addContent(
    "text/html",
    "Hallo,<br><br>Halo Bapak/Ibu $name dari instansi $instansi. Kami mengingatkan Anda telah melakukan pemesanan pada tanggal $date dengan jumlah peserta $jumlah orang.<br><br>Terima Kasih atas pemesanannya,<br>Marimas Company"
);
$reminderEmail->setSendAt(strtotime($reminderDate));


$response = $sendgrid->send($reminderEmail);

    
} else {
    echo "Form data is not set.";
}


// $name = $_POST['name'];
// $emailAddress = $_POST['email'];
// $date = $_POST['date'];
// $instansi = $_POST['instansi'];

?>
