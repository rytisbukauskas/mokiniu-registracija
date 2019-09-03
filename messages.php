<?php 
define('INCLUDE', true);

$page_title = 'Pranešimai';
$page_name = 'pranesimai';
$pg_desc = '';
$key_words = '';

include_once ('header.php');

$action = isset($_GET['action']) ? $_GET['action'] : '';
$id = isset($_GET['id']) ? $_GET['id'] : '';
$id = (int) filter_var($id, FILTER_SANITIZE_NUMBER_INT);
?>

<div class="col-9 col-12-medium" style="padding: 2em 2em 2em 2em;">
	<section>
		<?php if($action == "new") {
/* Start of New message */
	?>
		<h3 class="page-title">
			<span>Naujas pranešimas</span>
		</h3>
		<div class="content">
			<div ng-app="pm_app" ng-controller="pm_controller" class="form_style">
				<div id="main" class="admin-form">
					<div class="panel-body mt-1_2em">
						<form method="post" ng-submit="submitPM()">
							<input type="hidden" name="messageid" ng-model="messageData.messageid" ng-init="messageData.messageid='<?php echo $id; ?>'"  />
							<div class="form-group">
								<label for="title">Antraštė</label>
								<input type="text" name="title" ng-model="messageData.title" ng-init="messageData.title=''" placeholder="Antraštė" />
							</div>
							<div class="form-group">
								<label for="recip">Gavėjas <span class="small">(ID)</span>
								</label>
								<select name="recip" ng-init="messageData.recip=''" ng-model="messageData.recip" placeholder="Gavėjas">
					<?php
					try {

						$stmt = $connect->query("
						SELECT A.id AS account_id, A.name AS account_name, A.lname AS account_lname, A.account_status_id AS account_status_id, B.name AS account_status
						FROM users AS A
						LEFT OUTER JOIN users_status AS B ON A.account_status_id = B.id 
						WHERE A.account_status_id > '0' AND A.account_status_id < '3'
						ORDER BY A.account_status_id ASC
						");
						while($row = $stmt->fetch()){
							$account_id = $row['account_id'];
							$account_name = $row['account_name'];
							$account_lname = $row['account_lname'];
							$account_status_id = $row['account_status_id'];
							$account_status = $row['account_status'];
							echo'<option value="'. $account_id .'">'. $account_name .' '. $account_lname .' - '. $account_status .'</option>';
							
						}

					} catch(PDOException $e) {
						echo $e->getMessage();
					} ?>
								</select>
							</div>
							<div class="form-group">
								<label for="message">Pranešimas</label>
								<input type="text" name="message" ng-model="messageData.message" ng-init="messageData.message=''" placeholder="Pranešimas" />
							</div>
							<footer class="mt-2em">
								<div class="form-group" align="center">
									<div class="alert {{alertClass}} alert-dismissible" ng-show="alertMsg">
										<a href="#" onclick="return false;" class="close" ng-click="closeMsg()" aria-label="close">&times;</a>
									{{alertMessage}}
									</div>
									<ul class="actions special">
										<li>
											<input type="submit" name="save" class="button primary" value="Išsiųsti" />
										</li>
										<li>
											<input type="button" name="cancel" class="button" ng-click="cancelPM('<?php echo $site_url . $page_name; ?>')" value="Atgal" />
										</li>
									</ul>
								</div>
							</footer>
						</form>
					</div>
				</div>
			</div>
		</div>
		<script>
var app = angular.module('pm_app', []);
app.controller('pm_controller', function($scope, $http){
 $scope.closeMsg = function(){
  $scope.alertMsg = false;
 };

 $scope.pm_form = true;

 $scope.cancelPM = function(urlToGo){
	 location.href=urlToGo;
 };

 $scope.submitPM = function(){
  $http({
   method:"POST",
   url:"<?php echo $site_url; ?>send_message.php",
   data:$scope.messageData
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
		window.location.href = "<?php echo $site_url . $page_name; ?>";
	}, 500);
   }
  });
 };

});
		</script>
<?php
/* End of New message */
} elseif($action == "read" && $id != "" && getSingleValue('pm', 'id1', $id, 'id1') != "" && (getSingleValue('pm', 'id1', $id, 'user1') == $user_id || getSingleValue('pm', 'id1', $id, 'user2') == $user_id)) {
/* Start of Read message */
?>
		<h3 class="page-title"><span>Pranešimas</span></h3>
		<div class="inline-btns-center mb-1em"><a href="<?= $site_url . $page_name ?>" class="button small">Visi pranešimai</a></div>
<?php
$req1 = $connect->query("SELECT title, user1, user2 from pm WHERE id1='".$id ."' AND id2='1'");
$dn1 = $req1->fetch();

//The discussion will be placed in read messages
if($dn1['user1']==$user_id)
{
	try {
		$query = "
		UPDATE pm SET user1read='yes' WHERE id1='". $id ."' AND id2='1'
		";
		$statement = $connect->prepare($query);
		if($statement->execute())
		{
			
		}
	} catch(PDOException $e) {
		
	}
	$user_partic = 2;
}
else
{
	try {
		$query = "
		UPDATE pm SET user2read='yes' WHERE id1='". $id ."' AND id2='1'
		";
		$statement = $connect->prepare($query);
		if($statement->execute())
		{
			
		}
	} catch(PDOException $e) {
		
	}
    $user_partic = 1;
}
//We get the list of the messages
$req2 = 'select m1.id1, m1.title, m1.timestamp, count(m2.id1) as reps, users.id as userid, users.email, users.name, users.lname from pm as m1, pm as m2,users where ((m1.user1="'.$user_id.'" and m1.user1read="no" and users.id=m1.user2) or (m1.user2="'.$user_id.'" and m1.user2read="no" and users.id=m1.user1)) and m1.id2="1" and m2.id1=m1.id1 group by m1.id1 order by m1.id1 desc';
$req2 = $connect->query($req2);
$dn2 = $req2->fetch();
if ($req2->rowCount() > 0) {
	$req2_count = (int)$req2->rowCount();
}
else {
	$req2_count = 0;
}
	
//We check if the form has been sent
if(isset($_POST['message']) and $_POST['message']!='')
{
        $message = $_POST['message'];
        //We remove slashes depending on the configuration
        if(get_magic_quotes_gpc())
        {
            $message = stripslashes($message);
        }
        //We protect the variables
        $message = clearText($message);
        //We send the message and we change the status of the discussion to unread for the recipient
		 $query = "
		 INSERT INTO pm (id1, id2, title, user1, user2, message, timestamp, user1read, user2read)values('". $id ."', '". ($req2_count+1) ."', '', '". $user_id ."', '', '". $message ."', '". time() ."', '', '')
		 ";
		 $statement = $connect->prepare($query);
		 if($statement->execute())
		 {
			$success_message = 'Atsakymas įkeltas.';
		 }
		 else
		 {
			$alert_message = 'Atsakymo įkelti nepavyko.';
		 }
		 
		 $query = "
		 UPDATE pm SET user". $user_partic ."read='yes' WHERE id1='". $id ."' AND id2='1'
		 ";
		 $statement = $connect->prepare($query);
		 if($statement->execute())
		 {
		  
		 }
}
//We display the messages
?>
<div class="content">
<?php
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
?>
<h2><?php echo $dn1['title']; ?></h2>
<div class="table100 ver1 m-b-110">
	<div class="table100-head">
		<table>
			<thead>
				<tr class="row100 head">
					<th class="cell100 column1" style="width: 30%">Vartotojas</th>
					<th class="cell100 column2" style="width: 70%">Pranešimas</th>
				</tr>
			</thead>
		</table>
	</div>
	<div class="table100-body js-pscroll ps ps--active-y">
		<table>
			<tbody>
<?php
$count_rows = 0;
try {

	$stmt = $connect->query("
	select pm.timestamp, pm.message, users.id as userid, users.email, users.name, users.lname from pm, users where pm.id1='". $id ."' and users.id=pm.user1 order by pm.id2
	");
	while($row = $stmt->fetch()){
	?>
		<tr class="row100 body">
			<td class="cell100 column1 center" style="width: 30%">
			<?php echo $row['name']; ?> <?php echo $row['lname']; ?> (<?php echo $row['email']; ?>)</td>
			<td class="cell100 column2 left" style="width: 70%"><div class="date">Išsiųsta: <?php echo date('m/d/Y H:i:s' ,$row['timestamp']); ?></div>
			<?php echo $row['message']; ?></td>
		</tr>
	<?php
	}
} catch(PDOException $e) {
						echo $e->getMessage();
					}
//We display the reply form
?>
			</tbody>
		</table>
	</div>
</div>
<br />
<h3>Atsakymas</h3>
<div class="center">
    <form action="<?= $site_url . $page_name . '/skaityti/' . $id; ?>" method="post">
        <textarea cols="40" rows="5" name="message" id="message"></textarea><br />
        <input type="submit" value="Atsakyti" />
    </form>
</div>
</div>
<div class="inline-btns-center mb-1em"><a href="<?= $site_url . $page_name ?>" class="button small">Visi pranešimai</a></div>
<?php
/* End of Read message */
} else { ?>
		<h3 class="page-title"><span><?php echo $page_title; ?></span></h3>
			
<?php

$req1 = 'select m1.id1, m1.title, m1.timestamp, count(m2.id1) as reps, users.id as userid, users.email, users.name, users.lname from pm as m1, pm as m2,users where ((m1.user1="'.$user_id.'" and m1.user1read="no" and users.id=m1.user2) or (m1.user2="'.$user_id.'" and m1.user2read="no" and users.id=m1.user1)) and m1.id2="1" and m2.id1=m1.id1 group by m1.id1 order by m1.id1 desc';
$req2 = 'select m1.id1, m1.title, m1.timestamp, count(m2.id1) as reps, users.id as userid, users.email, users.name, users.lname from pm as m1, pm as m2,users where ((m1.user1="'.$user_id.'" and m1.user1read="yes" and users.id=m1.user2) or (m1.user2="'.$user_id.'" and m1.user2read="yes" and users.id=m1.user1)) and m1.id2="1" and m2.id1=m1.id1 group by m1.id1 order by m1.id1 desc';

$reqq1 = $connect->query($req1);
$reqqq1 = $reqq1->fetch();
$reqq2 = $connect->query($req2);
$reqqq2 = $reqq2->fetch();


if ($res = $connect->query($req1)) {
    if ($res->rowCount() > 0) {
		$req1_count = (int)$res->rowCount();
    }
    else {
        $req1_count = 0;
    }
}
if ($res2 = $connect->query($req2)) {
    if ($res2->rowCount() > 0) {
		$req2_count = (int)$res2->rowCount();
    }
    else {
        $req2_count = 0;
    }
}

?>
<div class="inline-btns-center mb-1em"><a href="<?= $site_url . $page_name . '/naujas'?>" class="button small">Naujas pranešimas</a></div>
<h3>Neperskaityti pranešimai(<?php echo $req1_count; ?>):</h3>
<div class="table100 ver1 m-b-110">
	<div class="table100-head">
		<table>
			<thead>
				<tr class="row100 head">
					<th class="cell100 column1">Antraštė</th>
					<th class="cell100 column2">Atsakymai</th>
					<th class="cell100 column3">Siuntėjas</th>
					<th class="cell100 column4">Sukūrimo data</th>
				</tr>
			</thead>
		</table>
	</div>
	<div class="table100-body js-pscroll ps ps--active-y">
		<table>
			<tbody>
			<?php
			foreach ($connect->query($req1) as $row) {
			?>
				<tr class="row100 body">
					<td class="cell100 column1 left"><a href="<?= $site_url . $page_name . '/skaityti/' . $row['id1'] ?>"><?php echo htmlentities($row['title'], ENT_QUOTES, 'UTF-8'); ?></a></td>
					<td class="cell100 column2"><?php echo $row['reps']-1; ?></td>
					<td class="cell100 column3"><?php echo htmlentities($row['name'], ENT_QUOTES, 'UTF-8') . ' ' . htmlentities($row['lname'], ENT_QUOTES, 'UTF-8') . ' (' . htmlentities($row['email'], ENT_QUOTES, 'UTF-8') . ')'; ?></td>
					<td class="cell100 column4"><?php echo date('Y/m/d H:i:s' ,$row['timestamp']); ?></td>
				</tr>
			<?php
			}
			if($req1_count==0)
			{
			?>
					<tr>
					<td colspan="4" class="cell100 column1" class="center">Neturite neperskaitytų pranešimų.</td>
				</tr>
			<?php
			}
			?>
			</tbody>
		</table>
	</div>
</div>
<br />
<h3>Perskaityti pranešimai(<?php echo $req2_count; ?>):</h3>
<div class="table100 ver1 m-b-110">
	<div class="table100-head">
		<table>
			<thead>
				<tr class="row100 head">
					<th class="cell100 column1">Antraštė</th>
					<th class="cell100 column2">Atsakymai</th>
					<th class="cell100 column3">Siuntėjas</th>
					<th class="cell100 column4">Sukūrimo data</th>
				</tr>
			</thead>
		</table>
	</div>
	<div class="table100-body js-pscroll ps ps--active-y">
		<table>
			<tbody>
			<?php
			foreach ($connect->query($req2) as $row) {
			?>
				<tr>
					<td class="cell100 column1 left"><a href="<?= $site_url . $page_name . '/skaityti/' . $row['id1'] ?>"><?php echo htmlentities($row['title'], ENT_QUOTES, 'UTF-8'); ?></a></td>
					<td class="cell100 column2"><?php echo $row['reps']-1; ?></td>
					<td class="cell100 column3"><?php echo htmlentities($row['name'], ENT_QUOTES, 'UTF-8') . ' ' . htmlentities($row['lname'], ENT_QUOTES, 'UTF-8') . ' (' . htmlentities($row['email'], ENT_QUOTES, 'UTF-8') . ')'; ?></td>
					<td class="cell100 column4"><?php echo date('Y/m/d H:i:s' ,$row['timestamp']); ?></td>
				</tr>
			<?php
			}
			if($req2_count==0)
			{
			?>
				<tr>
					<td colspan="4" class="cell100 column1" class="center">Neturite perskaitytų pranešimų.</td>
				</tr>
			<?php
			}
			?>
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