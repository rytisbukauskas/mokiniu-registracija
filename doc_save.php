<?php

include_once('database_connection.php');

session_start();

$form_data = json_decode(file_get_contents("php://input"), true);

$validation_error = '';

if(empty($form_data['action']))
{
	$error[] = 'Įvyko klaida';
}
else
{

if($form_data['action'] == "edit_doc")
	{
		$desc_set = 0;
		if(empty($form_data['name']))
		{
		 $error[] = 'Pavadinimas yra privalomas';
		}
		else
		{
		 $data[':name'] = $form_data['name'];
		}
		if(empty($form_data['docid']))
		{
		 $error[] = 'Blogai nurodytas dokumentas';
		}
		else
		{
		 $data[':docid'] = $form_data['docid'];
		}
		if(!empty($form_data['description']))
		{
		 $data[':description'] = $form_data['description'];
		 $desc_set = 1;
		}
	}
	else
	{
		$error[] = 'Įvyko klaida';
	}
	
}

if(empty($error))
{
	if($form_data['action'] == "edit_doc")
	{
		if($desc_set == 1)
		{
			$query = "UPDATE docs SET name='".$data[':name']."', description='".$data[':description']."', status_id='1' WHERE id = '".$data[':docid']."'";
		}
		else
		{
			$query = "UPDATE docs SET name='".$data[':name']."', status_id='1' WHERE id = '".$data[':docid']."'";
		}
		$statement = $connect->prepare($query);
		if($statement->execute($data))
		{
		$message = 'Išsaugota.';
		}
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
