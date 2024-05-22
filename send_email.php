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
    $nomorwa = $data['nomorwa'];
    $max_umur = $data['max_umur'];
    $min_umur = $data['min_umur'];


    
$email = new \SendGrid\Mail\Mail();
$email->setFrom(FROM_EMAIL, FROM_NAME);
$email->setSubject("Konfirmasi pemesanan acara Marimas Factory Tour");
$email->addTo($emailAddress, $name);
$email->addContent(
    "text/html",
    "Halo,<br><br>Terima kasih atas pemesanannya Bapak/Ibu $name dari instansi $instansi. Anda telah melakukan pemesanan acara Marimas Factory Tour yang memiliki peserta berjumlah $jumlah orang pada tanggal $date. Informasi lebih lanjut akan disampaikan melalui nomor Whatsapp yang sudah didaftarkan.<br><br>Terima Kasih atas pemesanannya,<br>Tim Marimas Factory Tour"
);
$email_admin = new \SendGrid\Mail\Mail();
$email_admin->setFrom(FROM_EMAIL, FROM_NAME);
$email_admin->setSubject("Acara tanggal $date");
$email_admin->addTo(EMAIL_ADMIN, 'Admin Marimas Factory Tour');
$email_admin->addContent(
    "text/html",
    "Nama Pendaftar: $name <br> Instansi: $instansi <br> Jumlah Peserta: $jumlah <br> Tanggal: $date <br> Email Pendaftar: $emailAddress <br> Nomor Whatsapp: $nomorwa <br> Rentang Usia Pendaftar: $min_umur - $max_umur "
);
$sendgrid = new \SendGrid(SENDGRID_API_KEY);
$response = $sendgrid->send($email);
$send_admin = $sendgrid->send($email_admin);


    
} else {
    echo "Form data is not set.";
}


// $name = $_POST['name'];
// $emailAddress = $_POST['email'];
// $date = $_POST['date'];
// $instansi = $_POST['instansi'];

?>
