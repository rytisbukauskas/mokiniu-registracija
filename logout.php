<?php

session_start();
session_destroy();

define('INCLUDE', true);

include_once('config.php');

header("location: ".$site_url."");


?>