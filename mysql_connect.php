<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "event_marimas";
 
$link = mysqli_connect($servername, $username, $password, $dbname);
 

if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>