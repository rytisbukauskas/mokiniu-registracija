<?php

//database_connection.php

$connect = new PDO("mysql:host=localhost;dbname=rasgivho_ev198awf7v8we;charset=utf8", "rasgivho_wavefwe", "9psCDX!$#=EU");
$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//set timezone
date_default_timezone_set('Europe/Vilnius');
?>