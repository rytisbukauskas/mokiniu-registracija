<?php 
define('INCLUDE', true);

$page_title = 'Dokumentų peržiūra';
$page_name = 'dokumentu-perziura';
$pg_desc = '';
$key_words = '';

include_once ('header.php');

if($employee_access < 1)
{
	header('Location: '.$site_url.'paskyra');
}
	
$action = isset($_GET['action']) ? $_GET['action'] : '';
$id = isset($_GET['id']) ? $_GET['id'] : '';
$id = (int) filter_var($id, FILTER_SANITIZE_NUMBER_INT);
?>

<div class="col-9 col-12-medium" style="padding: 2em 2em 2em 2em;">
	<section>
		<h3 class="page-title"><span><?php echo $page_title; ?></span></h3>
		<!-- Document accept -->
		<?php if($action == "accept" && $id != "" && getSingleValue('docs', 'id', $id, 'id') != "") {
			$success_message = '';
			$alert_message = '';
			if(getSingleValue('docs', 'id', $id, 'status_id') > 1)
			{
				try {
					$query = "
					UPDATE docs SET status_id='3' WHERE id = '".$id."'
					";
					$statement = $connect->prepare($query);
					if($statement->execute())
					{
						$success_message = 'Dokumentas #'. $id .' patvirtintas sėkmingai.';
					}
				} catch(PDOException $e) {
					$alert_message = 'Dokumento #'. $id .' patvirtinti nepavyko. Prašome susisiekti su administratoriumi.';
				}
			}
			else
			{
				$alert_message = 'Dokumento #'. $id .' patvirtinti negalima.';
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
		<!-- End of Document accept -->
		<!-- Document reject -->
		<?php if($action == "reject" && $id != "" && getSingleValue('docs', 'id', $id, 'id') != "") {
			$success_message = '';
			$alert_message = '';
			if(getSingleValue('docs', 'id', $id, 'status_id') > 1)
			{
				try {
					$query = "
					UPDATE docs SET status_id='4' WHERE id = '".$id."'
					";
					$statement = $connect->prepare($query);
					if($statement->execute())
					{
						$success_message = 'Dokumentas #'. $id .' atmestas sėkmingai.';
					}
				} catch(PDOException $e) {
					$alert_message = 'Dokumento #'. $id .' atmesti nepavyko. Prašome susisiekti su administratoriumi.';
				}
			}
			else
			{
				$alert_message = 'Dokumento #'. $id .' atmesti negalima.';
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
		<!-- End of Document reject -->
		<table id="dDocs" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
		  <thead>
			<tr class="trDocs">
			  <th class="th-sm">El. paštas
			  </th>
			  <th class="th-sm">Vardas pavardė
			  </th>
			  <th class="th-sm">Dokumento pavadinimas
			  </th>
			  <th class="th-sm">Dokumento statusas
			  </th>
			  <th class="th-sm">Data
			  </th>
			  <th class="th-sm">Veiksmai
			  </th>
			</tr>
		  </thead>
		  <tbody>
  
		<?php
		$count_rows = 0;
		try {

			$stmt = $connect->query("
			SELECT A.id, A.name, A.file_address, A.status_id AS status_id, B.name AS status, B.description AS about_status, C.email as user_email, C.name as user_name, C.lname as user_lname, A.date AS data
			FROM docs AS A
			LEFT OUTER JOIN docs_status AS B ON A.status_id = B.id
			LEFT OUTER JOIN users AS C ON A.user_id = C.id
			WHERE A.status_id > 1
			ORDER BY A.id DESC
			");
			while($row = $stmt->fetch()){
				$fmt = new \IntlDateFormatter('lt_LT', NULL, NULL);
				$fmt->setPattern('yyyy \'m\'. MMMM d \'d\'.'); 
				
				$doc_status_id = $row['status_id'];
				$doc_status_name = $row['status'];
				$doc_status_desc = $row['about_status'];
				$doc_name = '';
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
					$doc_name = '';
				}
				
				?>
				<tr>
					<td>
						<?=$row['user_email']?>
					</td>
					<td>
						<?=$row['user_name']?> <?=$row['user_lname']?>
					</td>
					<td>
						<?=$doc_name?>
					</td>
					<td>
						<?=$doc_status_name?>
					</td>
					<td style="white-space: nowrap;">
						<?=$row['data']?>
					</td>
					<td style="white-space: nowrap;">
						<a href="<?= $site_url . $page_name . '/patvirtinti/' . $row['id'] ?>" style="color: #41868f; font-weight: 500;"><i class="icon fa-check" style="margin-right: 3px;"></i> Patvirtinti</a><br>
						<a href="<?= $site_url . $page_name . '/atmesti/' . $row['id'] ?>"><i class="icon fa-ban" style="margin-right: 3px;"></i> Atmesti</a><br>
						<a href="<?= $site_url . $row['file_address']; ?>" target="_blank"><i class="icon fa-download" style="margin-right: 3px;"></i> Atsisiųsti</a>
					</td>
				</tr>
				<?php
			}

		} catch(PDOException $e) {
			echo $e->getMessage();
		}

		?>
	 
		  </tbody>
		  <tfoot>
			<tr class="trDocs">
			  <th>El. paštas
			  </th>
			  <th>Vardas Pavardė
			  </th>
			  <th>Dokumento pavadinimas
			  </th>
			  <th>Dokumento statusas
			  </th>
			  <th>Data
			  </th>
			  <th>Veiksmai
			  </th>
			</tr>
		  </tfoot>
		</table>

	</section>
</div>
<?php
include_once('sidebar.php');
include_once('footer.php');
?>