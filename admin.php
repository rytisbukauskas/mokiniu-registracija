<?php 
define('INCLUDE', true);

$page_title = 'Administravimas';
$page_name = 'administravimas';
$pg_desc = '';
$key_words = '';

include_once ('header.php');

$url = parse_url($site_url);  
$url = $url['host'];

if (isset($_GET['id'])) {
	$id = $_GET['id'];
} else {
    $id = "";
}

if (isset($_GET['uid'])) {
	$uid = $_GET['uid'];
} else {
    $uid = "";
}

if($id == "redaguoti_vartotoja" && $uid != "" && getSingleValue('users', 'id', $uid, 'email') != "")
{
$current_status_id = getSingleValue('users', 'id', $uid, 'account_status_id');
?>
<div class="col-9 col-12-medium" style="padding: 2em 2em 2em 2em;">
	<section>
			<section id="sistemos-nustatymai">
				<h3 class="page-title"><span>Vartotojo redagavimas</span></h3>
				<div ng-app="admin_app" ng-controller="admin_controller" class="form_style">
				   <div id="main" class="admin-form">
					 <h3><?php echo getSingleValue('users', 'id', $uid, 'email'); ?></h3>
					 <div ng-show="post_edit_form">
					<div class="panel-body mt-1_2em">
					 <form method="post" ng-submit="submitPostEdit()">
					 <input type="hidden" name="action" ng-model="postData.action" ng-init="postData.action='edit_user'"  />
					 <input type="hidden" name="userid" ng-model="postData.userid" ng-init="postData.userid='<?php echo $uid; ?>'"  />
					<div class="form-group">
						<label for="userstatus">Statusas
						</label>
						<select name="userstatus" ng-init="postData.userstatus='<?= $current_status_id ?>'" ng-model="postData.userstatus" placeholder="Statusas">
			<?php
			try {
				$stmt = $connect->query("
				SELECT id, name
				FROM users_status
				ORDER BY id ASC
				");
				while($row = $stmt->fetch()){
					$status_id = $row['id'];
					$status_name = $row['name'];
					if($status_id != 3)
					{
						echo'<option value="'. $status_id .'">'. $status_name .'</option>';
					}
					
				}

			} catch(PDOException $e) {
				echo $e->getMessage();
			} ?>
						</select>
					</div>
					  <div class="form-group">
						<label for="name">Vardas</label>
					   <input type="text" name="name" ng-model="postData.name" ng-init="postData.name='<?php echo getSingleValue('users', 'id', $uid, 'name'); ?>'" placeholder="Vardas" />
					  </div>
					  <div class="form-group">
						<label for="lname">Pavardė</label>
					   <input type="text" name="lname" ng-model="postData.lname" ng-init="postData.lname='<?php echo getSingleValue('users', 'id', $uid, 'lname'); ?>'" placeholder="Pavardė" />
					  </div>
					  <div class="form-group">
						<label for="phone">Tel.nr.</label>
					   <input type="text" name="phone" ng-model="postData.phone" ng-init="postData.phone='<?php echo getSingleValue('users', 'id', $uid, 'phone'); ?>'" placeholder="Tel.nr." />
					  </div>
					  <div class="form-group">
						<label for="residence">Gyvenamoji vieta</label>
					   <input type="text" name="residence" ng-model="postData.residence" ng-init="postData.residence='<?php echo getSingleValue('users', 'id', $uid, 'residence'); ?>'" placeholder="Gyvenamoji vieta" />
					  </div>
						<div class="form-group">
						<label for="personalcode">Asmens kodas</label>
					   <input type="text" name="personalcode" ng-model="postData.personalcode" ng-init="postData.personalcode='<?php echo getSingleValue('users', 'id', $uid, 'personalcode'); ?>'" placeholder="Asmens kodas" />
					  </div>
					  <div class="form-group">
						<label for="dateofbirth">Gimimo data</label>
					   <input type="text" name="dateofbirth" ng-model="postData.dateofbirth" ng-init="postData.dateofbirth='<?php echo getSingleValue('users', 'id', $uid, 'dateofbirth'); ?>'" placeholder="Gimimo data" />
					  </div>
					<footer class="mt-2em">
						<div class="form-group" align="center">
						   <div class="alert {{alertClass}} alert-dismissible" ng-show="alertMsg">
							<a href="#" onclick="return false;" class="close" ng-click="closeMsg()" aria-label="close">&times;</a>
							{{alertMessage}}
						   </div>
							<ul class="actions special">
								<li><input type="submit" name="save" class="button primary" value="Išsaugoti" /></li>
								<li><input type="button" name="cancel" class="button" ng-click="cancelEdit('<?php echo $site_url; ?>administravimas#vartotoju-valdymas')" value="Atgal" /></li>
							</ul>
						</div>
					</footer>
					 </form>
					</div>
					</div>
				 </div>
			</section>
	</section>
</div>

<?php
}
elseif($id == "redaguoti_naujiena" && $uid != "" && getSingleValue('news_posts', 'id', $uid, 'id') != "")
{
?>
<div class="col-9 col-12-medium" style="padding: 2em 2em 2em 2em;">
	<section>
			<section id="sistemos-nustatymai">
				<h3 class="page-title"><span>Naujienos redagavimas</span></h3>
				<div ng-app="admin_app" ng-controller="admin_controller" class="form_style">
				   <div id="main" class="admin-form">
					<h3>Naujienos įrašas #<?php echo getSingleValue('news_posts', 'id', $uid, 'id'); ?></h3>
					<div ng-show="post_edit_form">
					<div class="panel-body mt-1_2em">
					 <form method="post" ng-submit="submitPostEdit()">
					 <input type="hidden" name="action" ng-model="postData.action" ng-init="postData.action='edit_post'"  />
					 <input type="hidden" name="postid" ng-model="postData.postid" ng-init="postData.postid='<?php echo $uid; ?>'"  />
					  <div class="form-group">
						<label for="title">Antraštė</label>
					   <input type="text" name="title" ng-model="postData.title" ng-init="postData.title='<?php echo getSingleValue('news_posts', 'id', $uid, 'title'); ?>'" placeholder="Antraštė" />
					  </div>
					  <div class="form-group">
						<label for="description">Trumpas aprašymas</label>
					   <input type="text" name="description" ng-model="postData.description" ng-init="postData.description='<?php echo getSingleValue('news_posts', 'id', $uid, 'description'); ?>'" placeholder="Trumpas aprašymas" />
					  </div>
					  <div class="form-group">
						<label for="content">Turinys</label>
					   <input type="text" name="content" ng-model="postData.content" ng-init="postData.content='<?php echo getSingleValue('news_posts', 'id', $uid, 'content'); ?>'" placeholder="Turinys" />
					  </div>
					  <div class="form-group">
						<label for="date">Data</label>
					   <input type="text" name="date" ng-model="postData.date" ng-init="postData.date='<?php echo getSingleValue('news_posts', 'id', $uid, 'date'); ?>'" placeholder="Data" />
					  </div>
					<footer class="mt-2em">
						<div class="form-group" align="center">
						   <div class="alert {{alertClass}} alert-dismissible" ng-show="alertMsg">
							<a href="#" onclick="return false;" class="close" ng-click="closeMsg()" aria-label="close">&times;</a>
							{{alertMessage}}
						   </div>
							<ul class="actions special">
								<li><input type="submit" name="save" class="button primary" value="Išsaugoti" /></li>
								<li><input type="button" name="cancel" class="button" ng-click="cancelEdit('<?php echo $site_url; ?>administravimas#naujienu-valdymas')" value="Atgal" /></li>
							</ul>
						</div>
					</footer>
					 </form>
					</div>
					</div>
				 </div>
			</section>
	</section>
</div>

<?php
}
elseif($id == "prideti_naujiena")
{
?>
<div class="col-9 col-12-medium" style="padding: 2em 2em 2em 2em;">
	<section>
			<section id="sistemos-nustatymai">
				<h3 class="page-title"><span>Pridėti naujieną</span></h3>
				<div ng-app="admin_app" ng-controller="admin_controller" class="form_style">
				   <div id="main" class="admin-form">
				   <div ng-show="post_edit_form">
					<div class="panel-body mt-1_2em">
					 <form method="post" ng-submit="submitPostEdit()">
					 <input type="hidden" name="action" ng-model="postData.action" ng-init="postData.action='add_post'"  />
					 <input type="hidden" name="postid" ng-model="postData.postid" ng-init="postData.postid='<?php echo $uid; ?>'"  />
					  <div class="form-group">
						<label for="title">Antraštė</label>
					   <input type="text" name="title" ng-model="postData.title" ng-init="postData.title=''" placeholder="Antraštė" />
					  </div>
					  <div class="form-group">
						<label for="description">Trumpas aprašymas</label>
					   <input type="text" name="description" ng-model="postData.description" ng-init="postData.description=''" placeholder="Trumpas aprašymas" />
					  </div>
					  <div class="form-group">
						<label for="content">Turinys</label>
					   <input type="text" name="content" ng-model="postData.content" ng-init="postData.content=''" placeholder="Turinys" />
					  </div>
					  <div class="form-group">
						<label for="date">Data</label>
					   <input type="text" name="date" ng-model="postData.date" ng-init="postData.date=''" placeholder="Data" />
					  </div>
					<footer class="mt-2em">
						<div class="form-group" align="center">
						   <div class="alert {{alertClass}} alert-dismissible" ng-show="alertMsg">
							<a href="#" onclick="return false;" class="close" ng-click="closeMsg()" aria-label="close">&times;</a>
							{{alertMessage}}
						   </div>
							<ul class="actions special">
								<li><input type="submit" name="save" class="button primary" value="Pridėti" /></li>
								<li><input type="button" name="cancel" class="button" ng-click="cancelEdit('<?php echo $site_url; ?>administravimas#naujienu-valdymas')" value="Atgal" /></li>
							</ul>
						</div>
					</footer>
					 </form>
					</div>
					</div>
				 </div>
			</section>
	</section>
</div>

<?php
}
else
{
?>
<div class="col-9 col-12-medium" style="padding: 2em 2em 2em 2em;">
	<section>
<?php

$statuses = array();
try {

	$stmt = $connect->query('SELECT name FROM docs_status ORDER BY id ASC');
	while($row = $stmt->fetch()){
		$statuses[] = $row['name'];
	}

} catch(PDOException $e) {
	echo $e->getMessage();
}

?>
<script type="text/javascript">
window.onload = function() {

var options = {
	title: {
		text: "<?php echo date("Y"); ?> m. dokumentų būsenos"
	},
	data: [{
			type: "pie",
			startAngle: 45,
			showInLegend: "true",
			legendText: "{label}",
			indexLabel: "{label} ({y})",
			yValueFormatString:"#,##0.#"%"",
			dataPoints: [
			<?php $status_nr = 0;
				foreach ($statuses as &$status_i) {
				$stmt = $connect->prepare("SELECT count(*) FROM docs WHERE status_id = ? AND Year(date) = '". date("Y") ."'");
				$stmt->execute([$status_nr]);
				$count = $stmt->fetchColumn();
				echo '{ label: "'. $status_i .'", y: '. $count .' }, ';
				$status_nr++;
				 } ?>
			]
	}]
};
$("#chartContainer").CanvasJSChart(options);

}
</script>
			<div class="inline-btns-center mb-1em">
				<a href="#sistemos-nustatymai" class="button small">Sistema</a>
				<a href="#naujienu-valdymas" class="button small">Naujienų valdymas</a>
				<a href="#vartotoju-valdymas" class="button small">Vartotojų valdymas</a>
			</div>
			<section id="sistemos-nustatymai">
				<h3 class="page-title"><span>Sistema</span></h3>
				<div id="chartContainer" style="height: 370px; width: 100%; margin-bottom: 100px;"></div>
				<!--<p class="justify">...</p>-->
			</section>
			
			<section id="naujienu-valdymas">
				<h3 class="page-title"><span>Naujienų valdymas</span></h3>
				<div class="inline-btns-center mb-1em">
					<a href="<?php echo $site_url; ?>administravimas/prideti_naujiena" class="button small">Pridėti naujieną</a>
				</div>
				<div class="table100 ver1 m-b-110">
					<div class="table100-head">
						<table>
							<thead>
								<tr class="row100 head">
									<th class="cell100 column1">Antraštė</th>
									<th class="cell100 column2">Veiksmas</th>
								</tr>
							</thead>
						</table>
					</div>
		
					<div class="table100-body js-pscroll">
						<table>
							<tbody>
					<?php
					try {

						$stmt = $connect->query('SELECT id, title, date FROM news_posts ORDER BY id DESC');
						while($row = $stmt->fetch()){
							
							$fmt = new \IntlDateFormatter('lt_LT', NULL, NULL);
							$fmt->setPattern('yyyy \'m\'. MMMM d \'d\'.'); 
							
							?>
							<tr class="row100 body">
								<td class="cell100 column1"><?php echo $row['title']; ?> <span style="font-size: 15px">[<?php echo $row['date']; ?>]</span></td>
								<td class="cell100 column2"><a href="<?php echo $site_url; ?>administravimas/redaguoti_naujiena/<?php echo $row['id']; ?>">Redaguoti</a></td>
							</tr>
							<?php

						}

					} catch(PDOException $e) {
						echo $e->getMessage();
					}
					?>
							</tbody>
						</table>
					</div>
				</div>
			</section>
			
			<section id="vartotoju-valdymas">
				<h3 class="page-title"><span>Vartotojų valdymas</span></h3>
<div class="table-wrapper">

				<div class="table100 ver1 m-b-110">
					<div class="table100-head">
						<table>
							<thead>
								<tr class="row100 head">
									<th class="cell100 column1">El. paštas</th>
									<th class="cell100 column2">Veiksmas</th>
								</tr>
							</thead>
						</table>
					</div>

					<div class="table100-body js-pscroll">
						<table>
							<tbody>
					<?php
					try {

						$stmt = $connect->query('SELECT id, email FROM users ORDER BY id DESC');
						while($row = $stmt->fetch()){
							
							$fmt = new \IntlDateFormatter('lt_LT', NULL, NULL);
							$fmt->setPattern('yyyy \'m\'. MMMM d \'d\'.'); 
							
							?>
							<tr class="row100 body">
								<td class="cell100 column1"><?php echo $row['email']; ?></td>
								<td class="cell100 column2"><a href="<?php echo $site_url; ?>administravimas/redaguoti_vartotoja/<?php echo $row['id']; ?>">Redaguoti</a></td>
							</tr>
							<?php

						}

					} catch(PDOException $e) {
						echo $e->getMessage();
					}
					?>
							</tbody>
						</table>
					</div>
				</div>
				

			</section>
	</section>
</div>

<?php
}
?>

		<script>
		var app = angular.module('admin_app', []);
		app.controller('admin_controller', function($scope, $http){
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
		   url:"<?php echo $site_url; ?>admin_save.php",
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
				window.location.href = "<?php echo $site_url . $page_name; ?>";
			}, 500);
		   }
		  });
		 };

		});
		</script>

<!--===============================================================================================-->	
	<script src="<?php echo $site_url; ?>assets/vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="<?php echo $site_url; ?>assets/vendor/bootstrap/js/popper.js"></script>
	<script src="<?php echo $site_url; ?>assets/vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="<?php echo $site_url; ?>assets/vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="<?php echo $site_url; ?>assets/vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
	<script>
		$('.js-pscroll').each(function(){
			var ps = new PerfectScrollbar(this);

			$(window).on('resize', function(){
				ps.update();
			})
		});
			
		
	</script>
<!--===============================================================================================-->
	<script src="<?php echo $site_url; ?>assets/js/main-table.js"></script>

<?php
include_once('sidebar.php');
include_once('footer.php');
?>