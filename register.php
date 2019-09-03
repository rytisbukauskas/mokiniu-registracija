<?php

//register.php

define('INCLUDE', true);
include_once('database_connection.php');
include_once('functions.php');

$form_data = json_decode(file_get_contents("php://input"));

$message = '';
$validation_error = '';

if(empty($form_data->name))
{
 $error[] = 'Vardas yra privalomas';
}
else
{
 $data[':name'] = $form_data->name;
}

if(empty($form_data->email))
{
 $error[] = 'El.pašto adresas yra privalomas';
}
else
{
 if(!filter_var($form_data->email, FILTER_VALIDATE_EMAIL))
 {
  $error[] = 'Neteisingas el.pašto adreso formatas';
 }
 else
 {
	if(ifValueExists('users', 'email', $form_data->email) > 0)
	{
		$error[] = 'Vartotojas su tokiu el. paštu jau registruotas';
	}
	else
	{
		$data[':email'] = $form_data->email;
	}
 }
}

if(empty($form_data->password))
{
 $error[] = 'Slaptažodis yra privalomas';
}
else
{
 $data[':password'] = password_hash($form_data->password, PASSWORD_DEFAULT);
}

if(empty($error))
{
 $query = "
 INSERT INTO users (name, email, password) VALUES (:name, :email, :password)
 ";
 $statement = $connect->prepare($query);
 if($statement->execute($data))
 {
  $message = 'Registracija sėkminga! Galite prisijungti.';
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