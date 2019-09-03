<?php

session_start();
define('INCLUDE', true);
include_once('config.php');
include_once('functions.php');


$form_data = json_decode(file_get_contents("php://input"));

$message = '';
$validation_error = '';

if(empty($form_data->old_password))
{
 $error[] = 'Įrašykite seną slaptažodį';
}
else
{
	$old_pw_correct = "";
	$old_pw_entered = "";
	$old_pw_correct = getSingleValue('users', 'email', $_SESSION["email"], 'password');
	$old_pw_entered = password_hash($form_data->old_password, PASSWORD_DEFAULT);
	if(password_verify($form_data->old_password, $old_pw_correct))
	{
		$data[':old_password'] = $form_data->old_password;
	}
	else
	{
		$error[] = 'Neteisingas senas slaptažodis';
	}
 
}

if(empty($form_data->new_password_1))
{
 $error[] = 'Įrašykite naują slaptažodį';
}
else
{
  $data[':new_password_1'] = password_hash($form_data->new_password_1, PASSWORD_DEFAULT);
}

if(empty($form_data->new_password_2))
{
 $error[] = 'Pakartokite naują slaptažodį';
}
else
{
	if($form_data->new_password_1 != $form_data->new_password_2)
	{
		$error[] = 'Nauji slaptažodžiai nesutampa';
	}
	else
	{
		$data[':new_password_2'] = password_hash($form_data->new_password_2, PASSWORD_DEFAULT);
	}
}

if(empty($error))
{
 $query = "
 UPDATE users SET password='".$data[':new_password_1']."' WHERE email = '".$_SESSION['email']."'
 ";
 $statement = $connect->prepare($query);
 if($statement->execute($data))
 {
  $message = 'Slaptažodis pakeistas.';
 }
}
else
{
 $validation_error = implode(", ", $error);
}

$output = array(
 'error'  => $validation_error,
 'message' => $message
);

echo json_encode($output);


?>