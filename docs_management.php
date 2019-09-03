<?php 
define('INCLUDE', true);

$page_title = 'Dokumentų valdymas';
$page_name = 'dokumentu-valdymas';
$pg_desc = '';
$key_words = '';

if(!isset($_SESSION)) 
{ 
	session_start();
}

include_once ('config.php');
include_once ('functions.php');

include_once ('header.php');

if(isDataFillingNeeded() == true)
{
	if($admin_access < 1)
	{
		header('Location: '.$site_url.'paskyra');
	}
}

$action = isset($_GET['action']) ? $_GET['action'] : '';
$id = isset($_GET['id']) ? $_GET['id'] : '';
$id = (int) filter_var($id, FILTER_SANITIZE_NUMBER_INT);
?>

<div class="col-9 col-12-medium" style="padding: 2em 2em 2em 2em;">
	<section>

<?php if($action == "edit" && $id != "" && getSingleValue('docs', 'id', $id, 'id') != "" && getSingleValue('docs', 'id', $id, 'user_id') == $user_id) { ?>
	<section id="sistemos-nustatymai">
		<h3 class="page-title"><span>Dokumento redagavimas</span></h3>
		<div ng-app="doc_app" ng-controller="doc_controller" class="form_style">
		   <div id="main" class="doc-form">
		   <div ng-show="post_edit_form">
			 Dokumentas #<?php echo getSingleValue('docs', 'id', $id, 'id'); ?> <a href="<?php echo $site_url . getSingleValue('docs', 'id', $id, 'file_address'); ?>" target="_blank" class="download-button"><i class="icon fa-download" style="margin-right: 3px;"></i> Atsisiųsti</a>
			<div class="panel-body mt-1_2em">
			 <form method="post" ng-submit="submitPostEdit()">
			 <input type="hidden" name="action" ng-model="postData.action" ng-init="postData.action='edit_doc'"  />
			 <input type="hidden" name="docid" ng-model="postData.docid" ng-init="postData.docid='<?php echo $id; ?>'"  />
			  <div class="form-group">
				<label for="name">Pavadinimas</label>
			   <input type="text" name="name" ng-model="postData.name" ng-init="postData.name='<?php echo getSingleValue('docs', 'id', $id, 'name'); ?>'" placeholder="Pavadinimas" />
			  </div>
			  <div class="form-group">
				<label for="description">Aprašymas</label>
			   <input type="text" name="description" ng-model="postData.description" ng-init="postData.description='<?php echo getSingleValue('docs', 'id', $id, 'description'); ?>'" placeholder="Aprašymas" />
			  </div>
			<footer class="mt-2em">
				<div class="form-group" align="center">
				   <div class="alert {{alertClass}} alert-dismissible" ng-show="alertMsg">
					<a href="#" onclick="return false;" class="close" ng-click="closeMsg()" aria-label="close">&times;</a>
					{{alertMessage}}
				   </div>
					<ul class="actions special">
						<li><input type="submit" name="save" class="button primary" value="Išsaugoti" /></li>
						<li><input type="button" name="cancel" class="button" ng-click="cancelEdit('<?php echo $site_url; ?>dokumentu-valdymas')" value="Atgal" /></li>
					</ul>
				</div>
			</footer>
			 </form>
			</div>
		   </div>
		 </div>
	</section>
	<script>
	var app = angular.module('doc_app', []);
	app.controller('doc_controller', function($scope, $http){
	 $scope.closeMsg = function(){
	  $scope.alertMsg = false;
	 };

	 $scope.post_edit_form = true;

	 $scope.cancelEdit = function(urlToGo){
		 location.href=urlToGo;
	 };

	 $scope.submitPostEdit = function(){
	  $http({
	   method:"POST",
	   url:"<?php echo $site_url; ?>doc_save.php",
	   data:$scope.postData
	  }).success(function(data){
	   if(data.error != '')
	   {
		$scope.alertMsg = true;
		$scope.alertClass = 'alert-danger';
		$scope.alertMessage = data.error;
	   }
	   else
	   {
		$scope.alertMsg = true;
		$scope.alertClass = 'alert-success';
		$scope.alertMessage = 'Išsaugota!';
		setTimeout(function () {
		   window.location.href = "<?php echo $site_url; ?>dokumentu-valdymas";
		}, 500);
	   }
	  });
	 };

	});
	</script>
<?php } else { ?>
<!-- Document submit -->
<?php if($action == "submit" && $id != "" && getSingleValue('docs', 'id', $id, 'id') != "" && getSingleValue('docs', 'id', $id, 'user_id') == $user_id) {
	$success_message = '';
	$alert_message = '';
	if(getSingleValue('docs', 'id', $id, 'status_id') == 1)
	{
		try {
			$query = "
			UPDATE docs SET status_id='2' WHERE id = '".$id."'
			";
			$statement = $connect->prepare($query);
			if($statement->execute())
			{
				$success_message = 'Ačiū! Dokumentas pateiktas, greitu metu peržiūrėsime.';
			}
		} catch(PDOException $e) {
			$alert_message = 'Dokumento pateikti nepavyko. Prašome susisiekti su administratoriumi.';
		}
	}
	else
	{
		$alert_message = 'Dokumentas neatitinka reikalavimų arba jau buvo pateiktas.';
	}
	if(!empty($success_message))
	{
		echo'<div class="alert alert-dismissible alert-success">
			'. $success_message .'
		</div>';
	}
	if(!empty($alert_message))
	{
		echo'<div class="alert alert-dismissible alert-danger">
			'. $alert_message .'
		</div>';
	}
} ?>
<!-- End of Document submit -->
<!-- Document delete -->
<?php
 if($action == "delete" && $id != "" && getSingleValue('docs', 'id', $id, 'id') != "" && getSingleValue('docs', 'id', $id, 'user_id') == $user_id) {
	$success_message = '';
	$alert_message = '';
	if(getSingleValue('docs', 'id', $id, 'status_id') < 2)
	{
		$del_file_url = getSingleValue('docs', 'id', $id, 'file_address');
		try {
			$query = "
			DELETE FROM docs WHERE id = '".$id."'
			";
			$statement = $connect->prepare($query);
			if($statement->execute())
			{
				if (file_exists($del_file_url)) unlink($del_file_url);
				$success_message = 'Dokumentas panaikintas sėkmingai.';
			}
		} catch(PDOException $e) {
			$alert_message = 'Dokumento panaikinti nepavyko. Prašome susisiekti su administratoriumi.';
		}
	}
	else
	{
		$alert_message = 'Dokumento panaikinti negalima.';
	}
	if(!empty($success_message))
	{
		echo'<div class="alert alert-dismissible alert-success">
			'. $success_message .'
		</div>';
	}
	if(!empty($alert_message))
	{
		echo'<div class="alert alert-dismissible alert-danger">
			'. $alert_message .'
		</div>';
	}
} ?>
<!-- End of Document delete -->
<!-- Upload -->
<h3 class="page-title"><span>Dokumentų įkėlimas</span></h3>

<?php // RAY_upload_example.php
error_reporting(E_ALL);


$next_increment = -1;

try {

	$stmt = $connect->query("show table status from ".$sql_db." where name='docs'");
	while($row = $stmt->fetch()){
		$next_increment = $row['Auto_increment'];
	}

} catch(PDOException $e) {
	echo $e->getMessage();
}

// ESTABLISH THE NAME OF THE 'uploads' DIRECTORY
$uploads = 'uploads';

// ESTABLISH THE BIGGEST FILE SIZE WE CAN ACCEPT
$max_file_size = '21192000';  // EIGHT MEGABYTE LIMIT ON UPLOADS

// ESTABLISH THE KINDS OF FILE EXTENSIONS WE CAN ACCEPT
$file_exts = array('docx', 'doc', 'pdf', 'jpg', 'jpeg');

// ESTABLISH THE MAXIMUM NUMBER OF FILES WE CAN UPLOAD
$nf = 1;



// THIS IS A LIST OF THE POSSIBLE ERRORS THAT CAN BE REPORTED IN $_FILES[]["error"]
$errors    = array(
    0 => "Įkėlimas pavyko!",
    1 => "Failo dydis per didelis, didžiausias failo dydis 20 MB",
    2 => "Failo dydis per didelis, didžiausias failo dydis 20 MB",
    3 => "Failas įkeltas tik pusiau",
    4 => "Nebuvo įkeltas joks failas",
    6 => "Nerastas laikinasis aplankas",
    7 => "Failo įrašyti į talpyklą nepavyko"
);




// IF THERE IS NOTHING IN $_POST, PUT UP THE FORM FOR INPUT

    ?>

    <!--
        SOME THINGS TO NOTE ABOUT THIS FORM...
        NOTE THE CHOICE OF ENCTYPE IN THE HTML FORM STATEMENT
        MAX_FILE_SIZE MUST PRECEDE THE FILE INPUT FIELD
        INPUT NAME= IN TYPE=FILE DETERMINES THE NAME YOU FIND IN $_FILES ARRAY
    -->

    <?php
 
// END OF THE FORM SCRIPT

$alert_message = '';
$success_message = '';
$doc_save_success = 0;
$doc_save_success_but = 0;

if (!empty($_POST))
{
	$uploaded_count = 0;
	$stmt = $connect->prepare("SELECT count(*) FROM docs WHERE user_id = '". $user_id ."'");
	$stmt->execute();
	$uploaded_count = $stmt->fetchColumn();
	if( $uploaded_count < 5 )
	{
	// THERE IS POST DATA - PROCESS IT

	// ACTIVATE THIS TO SEE WHAT IS COMING THROUGH
	 //  echo "<pre>"; var_dump($_FILES); var_dump($_POST); echo "</pre>\n";

	// ITERATE OVER THE CONTENTS OF $_FILES
		foreach ($_FILES as $my_uploaded_file)
		{

	// SKIP OVER EMPTY SPOTS - NOTHING UPLOADED
			$error_code    = $my_uploaded_file["error"];
			if ($error_code == 4) continue;

	// SYNTHESIZE THE NEW FILE NAME
			$f_type    = explode( '.', basename($my_uploaded_file['name'] ));
			$f_type    = trim(strtolower(end    ($f_type)));
			$f_name    = trim(strtolower(current(explode( '.', basename($my_uploaded_file['name'] )))));
			$my_new_file = getcwd() . '/' . $uploads . '/' . $next_increment . '_' . $f_name .'.'. $f_type;
			$my_file     = $uploads . '/' . $next_increment . '_' . $f_name .'.'. $f_type;
			
			$doc_name = isset($_POST['name']) ? $_POST['name'] : '';
			$doc_description = isset($_POST['description']) ? $_POST['description'] : '';

	// OPTIONAL TEST FOR ALLOWABLE EXTENSIONS
			if (!in_array($f_type, $file_exts))
			{
				$alert_message .= "Atsiprašome, tačiau ". $f_type ." failai draudžiami. ";
				break;
			}

	// IF THERE ARE ERRORS
			if ($error_code != 0)
			{
				$error_message = $errors[$error_code];
				$alert_message .= "Klaidos kodas: ". $error_code .": ". $error_message .". ";
				break;
			}

	// GET THE FILE SIZE
			$file_size    = number_format($my_uploaded_file["size"]);

	// MOVE THE FILE INTO THE DIRECTORY
	// IF THE FILE IS NEW
			if (!file_exists($my_new_file))
			{
				if (move_uploaded_file($my_uploaded_file['tmp_name'], $my_new_file))
				{
					$upload_success = 1;
					$query = "
					INSERT INTO docs (name, description, file_address, status_id, user_id, date) VALUES ('".$doc_name."', '".$doc_description."', '".$my_file."', '0', '".getSingleValue('users', 'email', $_SESSION["email"], 'id')."', CAST('". date("Y-m-d") ."' AS DATE) )
					";
					$statement = $connect->prepare($query);
					if($statement->execute())
					{
						//$success_message .= "Išsaugota. ";
						$doc_save_success = 1;
					}
				}
				else
				{
					$upload_success = -1;
				}

	// IF THE FILE ALREADY EXISTS
			}
			else
			{
				//$alert_message .= "". $my_file ."</i></b> already exists. ";

	// SHOULD WE OVERWRITE THE FILE? IF NOT
				if (empty($_POST["overwrite"]))
				{
					$upload_success = 0;

	// IF WE SHOULD OVERWRITE THE FILE, TRY TO MAKE A BACKUP
				}
				else
				{
					$now    = date('Y-m-d');
					$my_bak = $my_new_file . '.' . $now . '.bak';
					if (!copy($my_new_file, $my_bak))
					{
						//$alert_message .= "Attempted Backup Failed! ";
					}
					if (move_uploaded_file($my_uploaded_file['tmp_name'], $my_new_file))
					{
						$upload_success = 2;
					}
					else
					{
						$upload_success = -1;
					}
				}
			}

	// REPORT OUR SUCCESS OR FAILURE
			if ($upload_success == 2) { /*$success_message .= "It has been overwritten. ";*/ }
			if ($upload_success == 1) { /*$success_message .= "<i>". $my_file ."</i> has been saved. ";*/ }
			if ($upload_success == 0) { /*$alert_message .= "It was NOT overwritten. ";*/ $doc_save_success_but = 1; }
			if ($upload_success < 0)  { /*$alert_message .= "ERROR <i>". $my_file ."</i> NOT SAVED - SEE WARNING FROM move_uploaded_file() COMMAND. ";*/ $doc_save_success_but = 0; }
			if ($upload_success > 0)
			{
				
				//header('Location: '.$site_url . $page_name . '/pranesimas/ikelta');
				if (!chmod ($my_new_file, 0755))
				{
					/*$alert_message .= "chmod(0755) FAILED: fileperms() = ";
					$alert_message .= "". substr(sprintf('%o', fileperms($my_new_file)), -4) .". ";*/
				}
			}
	// END ITERATOR
		}
		
		if($doc_save_success == 1)
		{
			if($doc_save_success_but == 0)
			{
				$success_message = "Dokumentas įkeltas sėkmingai.";
			}
			else
			{
				$alert_message = "Dokumentas išsaugotas duomenų bazėje, tačiau įvyko nesklandumų įkeliant failą, prašome bandyti dar kartą arba susisiekti su administratorium. Atsiprašome. " . $alert_message;
			}
		}
		else
		{
			$alert_message = "Įkėlimas nepavyko. " . $alert_message;
		}

		if(!empty($success_message))
		{
			echo'<div class="alert alert-dismissible alert-success">
				'. $success_message .'
			</div>';
		}
		if(!empty($alert_message))
		{
			echo'<div class="alert alert-dismissible alert-danger">
				'. $alert_message .'
			</div>';
		}
	}
	else
	{
		echo'<div class="alert alert-dismissible alert-danger">
			Pasiektas įkeltų dokumentų limitas.
		</div>';
	}
}
?>
<?php $uploaded_count = 0;
	$stmt = $connect->prepare("SELECT count(*) FROM docs WHERE user_id = '". $user_id ."'");
	$stmt->execute();
	$uploaded_count = $stmt->fetchColumn();
	
if( $uploaded_count < 5 )
{
?>
    <form name="UploadForm" enctype="multipart/form-data" action="<?=$site_url . $page_name?>" method="POST">
    <p>
    Pasirinkite dokumentą, kurį norite įkelti ir paspauskite mygtuką „Įkelti“ esantį žemiau.
    </p>

    <?php for ($n = 0; $n < $nf; $n++)
        {
            echo "<input name=\"userfile$n\" type=\"file\" size=\"80\" /><br>";
			?><div class="form-group">
			   <input type="text" name="name" placeholder="Pavadinimas" />
			</div>
			<div class="form-group">
			   <input type="text" name="description" placeholder="Aprašymas (neprivaloma)" />
			</div><?php
        }
    ?>

    <input type="submit" name="_submit" value="Įkelti" />
    </form>
<?php
}
else
{
?>
	<div style="margin-bottom: 1.5em">Jūs esate pasiekę 5 dokumentų limitą. Norėdami įkelti kiti dokumentą, panaikinkite nereikalingą dokumentą.</div>
<?php
}

$uploaded_count_color = '#000000c7';
if($uploaded_count < 5)
{
	$uploaded_count_color = $color_ok;
}
else
{
	$uploaded_count_color = $color_error;
}
?>
<h3 class="page-title"><span>Įkelti dokumentai (<font color="<?= $uploaded_count_color ?>"><?= $uploaded_count ?></font>/5)</span></h3>

				<div class="table100 ver1 m-b-110">
					<div class="table100-head">
						<table>
							<thead>
								<tr class="row100 head">
									<th class="cell100 column1">Dokumentas</th>
									<th class="cell100 column2">Būsena</th>
								</tr>
							</thead>
						</table>
					</div>

					<div class="table100-body js-pscroll">
						<table>
							<tbody>
					<?php
					$count_rows = 0;
					try {

						$stmt = $connect->query("
						SELECT A.id, A.name, A.file_address, A.status_id AS status_id, B.name AS status, B.description AS about_status
						FROM docs AS A
						LEFT OUTER JOIN docs_status AS B ON A.status_id = B.id 
						WHERE user_id = '". $user_id ."'
						ORDER BY A.id DESC
						");
						while($row = $stmt->fetch()){
							$fmt = new \IntlDateFormatter('lt_LT', NULL, NULL);
							$fmt->setPattern('yyyy \'m\'. MMMM d \'d\'.'); 
							
							$doc_status_id = $row['status_id'];
							$doc_status_name = $row['status'];
							$doc_status_desc = $row['about_status'];
							$doc_name = '<i class="icon fa-exclamation-circle" style="margin-right: 3px; color: #ff0000"> Neužpildytas dokumentas</i>';
							if($row['name'] != "")
							{
								$doc_name = $row['name'];
								if($doc_status_id == 0)
								{
									$query = "
									UPDATE docs SET status_id='1' WHERE id = '".$row['id']."'
									";
									$statement = $connect->prepare($query);
									if($statement->execute())
									{
										$doc_status_id = 1;
										$doc_status_name = getSingleValue('docs_status', 'id', $doc_status_id, 'name');
										$doc_status_desc = getSingleValue('docs_status', 'id', $doc_status_id, 'description');
									}
								}
							}
							elseif($doc_status_id > 1)
							{
								$doc_name = '<i>Dokumentas be pavadinimo</i>';
							}
							
							?>
							<tr class="row100 body">
								<td class="cell100 column1">
									<?php echo $doc_name; ?><br>
									<?php if($doc_status_id == 1) { echo'<a href="'. $site_url . $page_name . '/pateikti/' . $row['id'] .'" class="submit-doc" style="color: #41868f; font-weight: 500;"><i class="icon fa-check-square" style="margin-right: 3px;"></i> Pateikti</a><br>'; } ?>
									<?php if($doc_status_id < 2) { echo'<a href="'. $site_url . $page_name . '/redaguoti/' . $row['id'] .'"><i class="icon fa-edit" style="margin-right: 3px;"></i> Redaguoti</a><br>'; } ?>
									<a href="<?php echo $site_url . $row['file_address']; ?>" target="_blank"><i class="icon fa-download" style="margin-right: 3px;"></i> Atsisiųsti</a>
									<?php if($doc_status_id < 2) { echo'<br><a href="'. $site_url . $page_name . '/panaikinti/' . $row['id'] .'" class="delete-doc"><i class="icon fa-remove" style="margin-right: 3px;"></i> Panaikinti</a><br>'; } ?>

								</td>
								<td class="cell100 column2">
									
									<div class="tooltip-text"><?php echo $doc_status_name; ?>
										<span class="tooltiptext-text"><?php echo $doc_status_desc; ?></span>
									</div>
								</td>
							</tr>
							<?php
							$count_rows += 1;
						}

					} catch(PDOException $e) {
						echo $e->getMessage();
					}
					
					if($count_rows <= 0){ ?>
							<tr class="row100 body">
								<td class="cell100 column1">
									-
								</td>
								<td class="cell100 column2">
									-
								</td>
							</tr>
					<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
<?php } ?>
	</section>
</div>

<?php
include_once('sidebar.php');
include_once('footer.php');
?>