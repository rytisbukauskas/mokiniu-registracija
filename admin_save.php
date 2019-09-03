<?php

//login.php

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
	if($form_data['action'] == "edit_user")
	{
		if ((isset($form_data['userstatus']) && $form_data['userstatus'] === "0") || !empty($form_data['userstatus']))
		{
		 $data[':userstatus'] = $form_data['userstatus'];
		}
		if(empty($form_data['name']))
		{
		 $error[] = 'Vardas yra privalomas';
		}
		else
		{
		 $data[':name'] = $form_data['name'];
		}
		if(empty($form_data['userid']))
		{
		 $error[] = 'Blogai nurodytas vartotojas';
		}
		else
		{
		 $data[':userid'] = $form_data['userid'];
		}
		if(!empty($form_data['lname']))
		{
		 $data[':lname'] = $form_data['lname'];
		}
		if(!empty($form_data['phone']))
		{
		 $data[':phone'] = $form_data['phone'];
		}	
		if(!empty($form_data['residence']))
		{
		 $data[':residence'] = $form_data['residence'];
		}	
		if(!empty($form_data['personalcode']))
		{
		 $data[':personalcode'] = $form_data['personalcode'];
		}	
		if(!empty($form_data['dateofbirth']))
		{
		 $data[':dateofbirth'] = $form_data['dateofbirth'];
		}		
	}
	elseif($form_data['action'] == "edit_post")
	{
		if(empty($form_data['title']))
		{
		 $error[] = 'Antraštė yra privaloma';
		}
		else
		{
		 $data[':title'] = $form_data['title'];
		}
		if(empty($form_data['postid']))
		{
		 $error[] = 'Blogai nurodyta naujiena';
		}
		else
		{
		 $data[':postid'] = $form_data['postid'];
		}
		if(!empty($form_data['description']))
		{
		 $data[':description'] = $form_data['description'];
		}
		if(!empty($form_data['content']))
		{
		 $data[':content'] = $form_data['content'];
		}	
		if(!empty($form_data['date']))
		{
		 $data[':date'] = $form_data['date'];
		}	
	}
	elseif($form_data['action'] == "add_post")
	{
		if(empty($form_data['title']))
		{
		 $error[] = 'Antraštė yra privaloma';
		}
		else
		{
		 $data[':title'] = $form_data['title'];
		}
		
		if(empty($form_data['description']))
		{
		 $error[] = 'Trumpas aprašymas yra privalomas';
		}
		else
		{
		 $data[':description'] = $form_data['description'];
		}
		
		if(empty($form_data['content']))
		{
		 $error[] = 'Turinys yra privalomas';
		}
		else
		{
		 $data[':content'] = $form_data['content'];
		}
		
		if(empty($form_data['date']))
		{
		 $error[] = 'Data yra privaloma';
		}
		else
		{
		 $data[':date'] = $form_data['date'];
		}
	}
	else
	{
		$error[] = 'Įvyko klaida';
	}
	
}

/*if(empty($form_data->password))
{
 $error[] = 'Slaptažodis yra privalomas';
}*/

if(empty($error))
{
	if($form_data['action'] == "edit_user")
	{
		$status_set = '';
		if ((isset($data[':userstatus']) && $data[':userstatus'] === "0") || !empty($data[':userstatus']))
		{
			$status_set = ", account_status_id='". $data[':userstatus'] ."'";
		}
		$query = "
		UPDATE users SET name='".$data[':name']."', lname='".$data[':lname']."', phone='".$data[':phone']."', residence='".$data[':residence']."', personalcode='".$data[':personalcode']."', dateofbirth='".$data[':dateofbirth']."'". $status_set ." WHERE id = '".$data[':userid']."'
		";
		$statement = $connect->prepare($query);
		if($statement->execute($data))
		{
		$message = 'Išsaugota.';
		}
	}
	if($form_data['action'] == "edit_post")
	{
		$query = "
		UPDATE news_posts SET title='".$data[':title']."', description='".$data[':description']."', content='".$data[':content']."', date='".$data[':date']."' WHERE id = '".$data[':postid']."'
		";
		$statement = $connect->prepare($query);
		if($statement->execute($data))
		{
		$message = 'Išsaugota.';
		}
	}
	if($form_data['action'] == "add_post")
	{
		$query = "
		INSERT INTO news_posts (title, description, content, date) VALUES (:title, :description, :content, :date)
		";
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
