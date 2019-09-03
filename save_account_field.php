<?php

//error_reporting(0);
//ini_set('display_errors', 0);

session_start();
define('INCLUDE', true);
include_once('database_connection.php');
include_once('functions.php');

$form_data = json_decode(file_get_contents("php://input"));

$message = '';
$validation_error = '';

$what = isset($_GET['what']) ? $_GET['what'] : '';
$what = preg_replace("/[^a-zA-Z0-9]+/", "", $what);
$fieldValue = isset($_GET['value']) ? $_GET['value'] : '';
$good = false;

switch ($what) {
    case "name":
		$fieldValue = clearText($fieldValue);
		if(empty($fieldValue))
		{
		 $error[] = 'Vardas yra privalomas.';
		}
		else
		{
			if(strlen($fieldValue) <= 1)
			{
				$error[] = 'Vardas per trumpas! Mažiausiai dvi raidės.';
			}
			else
			{
				if(strlen($fieldValue) >= 50)
				{
					$error[] = 'Vardas per ilgas! Daugiausiai 50 raidžių .';
				}
				else
				{
					$data[':newValue'] = ucwords($fieldValue);
					$good = true;
				}
			}
		}
        break;
    case "lname":
		$fieldValue = clearText($fieldValue);
		if(empty($fieldValue))
		{
		 $error[] = 'Pavardė yra privaloma.';
		}
		else
		{
			if(strlen($fieldValue) <= 1)
			{
				$error[] = 'Pavardė per trumpa! Mažiausiai dvi raidės.';
			}
			else
			{
				if(strlen($fieldValue) >= 50)
				{
					$error[] = 'Pavardė per ilga! Daugiausiai 50 raidžių .';
				}
				else
				{
					$data[':newValue'] = ucwords($fieldValue);
					$good = true;
				}
			}
		}
        break;
    /*case "email":
		$fieldValue = clearEmail($fieldValue);
		if(empty($fieldValue))
		{
		 $error[] = 'El. paštas yra privalomas.';
		}
		else
		{
			if($fieldValue == "BAD")
			{
			 $error[] = 'El. paštas neteisingo formato.';
			}
			else
			{
				if(strlen($fieldValue) <= 1)
				{
					$error[] = 'El. paštas per trumpas! Mažiausiai dvi raidės.';
				}
				else
				{
					if(strlen($fieldValue) >= 100)
					{
						$error[] = 'El. paštas per ilgas! Daugiausiai 100 raidžių .';
					}
					else
					{
						$data[':newValue'] = $fieldValue;
						$good = true;
					}
				}
			}
		}
        break;*/
    case "phone":
		$fieldValue = clearPhone($fieldValue);
		if(empty($fieldValue))
		{
		 $error[] = 'Tel. nr. yra privalomas.';
		}
		else
		{
			if(strlen($fieldValue) <= 5)
			{
				$error[] = 'Tel. nr. per trumpas!';
			}
			else
			{
				if(strlen($fieldValue) >= 20)
				{
					$error[] = 'Tel. nr. per ilgas!';
				}
				else
				{
					$data[':newValue'] = $fieldValue;
					$good = true;
				}
			}
		}
        break;
    case "residence":
		$fieldValue = clearText($fieldValue);
		if(empty($fieldValue))
		{
		 $error[] = 'Gyvenamoji vieta yra privaloma.';
		}
		else
		{
			if(strlen($fieldValue) <= 1)
			{
				$error[] = 'Gyvenamoji vieta per trumpa!';
			}
			else
			{
				if(strlen($fieldValue) >= 100)
				{
					$error[] = 'Gyvenamoji vieta per ilga!';
				}
				else
				{
					$data[':newValue'] = $fieldValue;
					$good = true;
				}
			}
		}
        break;
    case "personalcode":
		$fieldValue = preg_replace('/[^0-9.]+/', '', $fieldValue);
		if(empty($fieldValue))
		{
		 $error[] = 'Asmens kodas yra privalomas. Tik skaičiai.';
		}
		else
		{
			if(valid_ak($fieldValue) == false)
			{
				$error[] = 'Asmens kodas neteisingas.';
			}
			else
			{
				if(strlen($fieldValue) > 11)
				{
					$error[] = 'Asmens kodas per ilgas!';
				}
				else
				{
					if(strlen($fieldValue) < 11)
					{
						$error[] = 'Gyvenamoji vieta per ilga!';
					}
					else
					{
						$data[':newValue'] = $fieldValue;
						$good = true;
					}
				}
			}
		}
        break;
    case "dateofbirth":
		$fieldValue = preg_replace('/[^0-9-]/', '', $fieldValue);
		$dateTime = new DateTime($fieldValue);
		$enteredDate = $dateTime->format('Y-m-d');
		$dateTime2 = new DateTime('1900-01-01');
		$minDate = $dateTime2->format('Y-m-d');
		$dateTime3 = new DateTime();
		$maxDate = $dateTime3->format('Y-m-d');
		
		if(empty($fieldValue))
		{
		 $error[] = 'Gimimo data yra privaloma.';
		}
		else
		{
			if($enteredDate < $minDate)
			{
				$error[] = 'Gimimo data per ankstyva!';
			}
			else
			{
				if($enteredDate > $maxDate)
				{
					$error[] = 'Gimimo data per tolima!';
				}
				else
				{
					$data[':newValue'] = $fieldValue;
					$good = true;
				}
			}
		}
        break;
}

// Jei klaidų nerasta, bet bandomi keisti nenustatyti duomenys.
if(!$good && empty($error))
{
	$error[] = 'Įvyko klaida. Pabandykite dar kartą.';
}

if(empty($error))
{
	$query = "
	UPDATE users SET ".$what."='".$data[':newValue']."' WHERE email = '".$_SESSION['email']."'
	";
	$statement = $connect->prepare($query);
	if($statement->execute($data))
	{
	$message = 'Išsaugota.';
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

// Asmens kodo tikrinimas
function valid_ak($ak){
 $valid=false;
 if(strlen($ak)==11){
  if($ak[0]>2 && $ak[0]<7){
   if(checkdate(substr($ak,3,2),substr($ak,5,2),substr($ak,1,2))){
    $str=$ak[0]*1+$ak[1]*2+$ak[2]*3+$ak[3]*4+$ak[4]*5+$ak[5]*6+$ak[6]*7+$ak[7]*8+$ak[8]*9+$ak[9]*1;
    $str=$str%11;
    if($str==10){
 
     $str=$ak[0]*3+$ak[1]*4+$ak[2]*5+$ak[3]*6+$ak[4]*7+$ak[5]*8+$ak[6]*9+$ak[7]*1+$ak[8]*2+$ak[9]*3;
     $str=$str%11;
     if($str==10 && substr($ak,10,1)=="0")
      $valid=true;
     elseif($str==substr($ak,10,1))
      $valid=true;
    }
    elseif($str==substr($ak,10,1))
     $valid=true;
   }
  }
 }
 return $valid;
}

?>