<?php

if(!defined('INCLUDE')) { die(); }

function getSingleValue($tableName, $prop, $value, $columnName)
{
	$connect = new PDO("mysql:host=localhost;dbname=rasgivho_ev198awf7v8we;charset=utf8", "rasgivho_wavefwe", "9psCDX!$#=EU");
    $q = $connect->query("SELECT `$columnName` FROM `$tableName` WHERE $prop='".$value."'");
    $f = $q->fetch();
    $result = $f[$columnName];
    return $result;
}

function ifValueExists($tableName, $prop, $value)
{
	$result = 0;
	$connect = new PDO("mysql:host=localhost;dbname=rasgivho_ev198awf7v8we;charset=utf8", "rasgivho_wavefwe", "9psCDX!$#=EU");
	$stmt = $connect->prepare("SELECT count(*) FROM `$tableName` WHERE $prop=?");
	$stmt->bindParam(1, $value);
	$stmt->execute();
	$row = $stmt->fetchColumn();

	if($row > 0)
	{
		$result = 1;
	}
    return $result;
}

function clearPasswrod($value){
	$value = trim($value); //remove empty spaces
	$value = strip_tags($value); //remove html tags
	$value = htmlentities($value, ENT_QUOTES,'UTF-8'); //for major security transform some other chars into html corrispective...
	return $value;
}

function clearText($value){
	$value = trim($value); //remove empty spaces
	$value = strip_tags($value); //remove html tags
	$value = filter_var($value, FILTER_SANITIZE_MAGIC_QUOTES); //addslashes();
	$value = filter_var($value, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW); //remove /t/n/g/s
	//$value = filter_var($value, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); //remove é à ò ì ` ecc...
	$value = htmlentities($value, ENT_QUOTES,'UTF-8'); //for major security transform some other chars into html corrispective...
	return $value;
}

function clearEmail($value){
	$value = trim($value); //remove empty spaces
	$value = strip_tags($value); //remove html tags
	$value = filter_var($value, FILTER_SANITIZE_EMAIL); //e-mail filter;
	if($value = filter_var($value, FILTER_VALIDATE_EMAIL))
	{
		$value = htmlentities($value, ENT_QUOTES,'UTF-8');//for major security transform some other chars into html corrispective...
	}
	else
	{
		$value = "BAD";
	}  
	return $value;
}

function clearPhone($phone)
{
     // Allow +, - and . in phone number
     $filtered_phone_number = filter_var($phone, FILTER_SANITIZE_NUMBER_INT);
     // Remove "-" from number
     $phone_to_check = str_replace("-", "", $filtered_phone_number);
     // Check the lenght of number
     // This can be customized if you want phone number from a specific country
     return $phone;
}

function showNotice($noticeText)
{
	$notice = '	<div class="row">
					<div class="top-notice animated fadeIn">'.$noticeText.'</div>
				</div>';
	
    return $notice;
}

function isDataFillingNeeded()
{
	$isNeeded = false;
	if(empty(getSingleValue('users', 'email', $_SESSION["email"], 'name')) 
	|| empty(getSingleValue('users', 'email', $_SESSION["email"], 'lname'))  
	|| empty(getSingleValue('users', 'email', $_SESSION["email"], 'phone'))  
	|| empty(getSingleValue('users', 'email', $_SESSION["email"], 'dateofbirth'))  
	|| empty(getSingleValue('users', 'email', $_SESSION["email"], 'residence'))  
	|| empty(getSingleValue('users', 'email', $_SESSION["email"], 'personalcode')) )
	{
		$isNeeded = true;
	}

    return $isNeeded;
}

?>