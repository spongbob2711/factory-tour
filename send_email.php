<?php
require_once 'sendgrid_config.php';
require 'vendor/autoload.php';
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['name'], $data['email'], $data['date'], $data['instansi'])) {
    $name = $data['name'];
    $emailAddress = $data['email'];
    $date = $data['date'];
    $instansi = $data['instansi'];

    // Create a new email object
$email = new \SendGrid\Mail\Mail();
$email->setFrom(FROM_EMAIL, FROM_NAME);
$email->setSubject("Konfirmasi pemesanan acara Marimas Factory Tour");
$email->addTo($emailAddress, $name);
$email->addContent(
    "text/html",
    "Hallo,<br><br>Terima kasih atas pemesanannya Bapak/Ibu $name. Anda telah melakukan pemesanan acara Marimas Factory Tour pada tanggal $date.<br><br>Terima Kasih atas pemesanannya,<br>Marimas Company"
);
// Create a new SendGrid instance
$sendgrid = new \SendGrid(SENDGRID_API_KEY);

// Send the email
$response = $sendgrid->send($email);

// Calculate the date three days before the event
$reminderDate = date('Y-m-d', strtotime($date . ' -3 days'));

// Create a new email object for the reminder
$reminderEmail = new \SendGrid\Mail\Mail();
$reminderEmail->setFrom(FROM_EMAIL, FROM_NAME);
$reminderEmail->setSubject("Pengingat pemesanan acara Marimas Factory Tour");
$reminderEmail->addTo($emailAddress, $name);
$reminderEmail->addContent(
    "text/html",
    "Halo Bapak/Ibu $name,<br><br>Kami mengingatkan Anda bahwa acara Marimas Factory Tour yang anda pesan dilaksanakan pada tanggal $date.<br><br>Terima Kasih Atas Perhatiannya<br>Marimas Company"
);
$reminderEmail->setSendAt(strtotime($reminderDate));

// Schedule the reminder email
$response = $sendgrid->send($reminderEmail);

    
} else {
    echo "Form data is not set.";
}

// // Get form data
// $name = $_POST['name'];
// $emailAddress = $_POST['email'];
// $date = $_POST['date'];
// $instansi = $_POST['instansi'];

?>