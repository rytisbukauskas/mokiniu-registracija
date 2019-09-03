<?php
define('INCLUDE', true);
include_once('database_connection.php');
session_start();
include_once('functions.php');
$user_id = getSingleValue('users', 'email', $_SESSION["email"], 'id');

$form_data = json_decode(file_get_contents("php://input"), true);

$validation_error = '';

if(empty($form_data['title']))
{
 $error[] = 'Antraštė yra privaloma';
}
else
{
 $data[':title'] = $form_data['title'];
}
if(empty($form_data['recip']))
{
 $error[] = 'Nenurodytas gavėjas';
}
else
{
	if($form_data['recip'] == $user_id)
	{
		$error[] = 'Siųsti pranešimų sau negalima.';
	}
	else
	{
		if(ifValueExists('users', 'id', $form_data['recip']) == 0)
		{
			$error[] = 'Nurodyto gavėjo nėra.';
		}
		else
		{
			$data[':recip'] = $form_data['recip'];
		}
	}
}
if(empty($form_data['message']))
{
 $error[] = 'Pranešimo turinys yra privalomas';
}
else
{
 $data[':message'] = $form_data['message'];
}
	
if(empty($error))
{
	 $query = "
	 INSERT INTO pm (id1, id2, title, user1, user2, message, timestamp, user1read, user2read) VALUES ( (SELECT (MAX( id1 ) + 1) FROM (SELECT id1 FROM pm) AS tmptable), '1', :title, '". $user_id ."', :recip, :message, '". time() ."', 'yes', 'no')
	 ";
	$statement = $connect->prepare($query);
	if($statement->execute($data))
	{
		$message = 'Pranešimas išsiųstas.';
	}
}
else
{
 $validation_error = implode(", ", $error);
}

$output = array(
 'error' => $validation_error
);

echo json_encode($output);

?>
