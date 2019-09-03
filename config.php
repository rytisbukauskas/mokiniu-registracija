<?php

if(!defined('INCLUDE')) { die(); }

$use_sts = true;

// iis sets HTTPS to 'off' for non-SSL requests
if ($use_sts && isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
    header('Strict-Transport-Security: max-age=31536000');
} elseif ($use_sts) {
    header('Location: https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], true, 301);
    // we are in cleartext at the moment, prevent further execution and output
    die();
}

include_once('database_connection.php');

if (isset($_SERVER['HTTPS']) &&
    ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
    isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
    $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
  $protocol = '//';
}
else {
  $protocol = 'http://';
}

$site_url = '//' . $_SERVER['HTTP_HOST'].'/';
$sql_host = 'localhost';
$sql_db = '';
$sql_usr = '';
$sql_pw = '';

$color_accent_1 = '#41868f';
$color_accent_2 = '#db495e';
$color_accent_3 = '#4e3d5f';
$color_error = '#db495e';
$color_ok = '#41868f';

?>
