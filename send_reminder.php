<?php
require_once 'sendgrid_config.php';
require 'vendor/autoload.php';

// Connect to your database
$db = new PDO('mysql:host=localhost;dbname=event_marimas;charset=utf8', 'root', '');

// Get all events happening in 3 days
$stmt = $db->prepare("SELECT * FROM events WHERE date = DATE_ADD(CURDATE(), INTERVAL 3 DAY)");
$stmt->execute();
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($events as $event) {
    // Check if the event still exists and the date hasn't changed
    if (isset($event['name'], $event['email'], $event['date'], $event['instansi'], $event['jumlah'])) {
        $name = $event['name'];
        $emailAddress = $event['email'];
        $date = $event['date'];
        $instansi = $event['instansi'];
        $jumlah = $event['jumlah'];

        // Send reminder email
        $reminderEmail = new \SendGrid\Mail\Mail();
        $reminderEmail->setFrom(FROM_EMAIL, FROM_NAME);
        $reminderEmail->setSubject("Pengingat pemesanan acara Marimas Factory Tour");
        $reminderEmail->addTo($emailAddress, $name);
        $reminderEmail->addContent(
            "text/html",
            "Hallo,<br><br>Halo Bapak/Ibu $name dari instansi $instansi. Kami mengingatkan Anda telah melakukan pemesanan pada tanggal $date dengan jumlah peserta $jumlah orang.<br><br>Terima Kasih atas pemesanannya,<br>Marimas Company"
        );
        $reminderEmail->setSendAt(strtotime($reminderDate));
        $sendgrid = new \SendGrid(SENDGRID_API_KEY);
        $response = $sendgrid->send($reminderEmail);
    }
}
?>
