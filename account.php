<?php 
define('INCLUDE', true);

$page_title = 'Paskyra';
$page_name = 'paskyra';
$pg_desc = '';
$key_words = '';

include_once ('header.php');
?>

<div class="col-9 col-12-medium" style="padding: 2em 2em 2em 2em;">
	<section ng-app="account_app">
			<h3 class="page-title"><span><?php echo $page_title; ?></span></h3>
			<section ng-controller="edit_data_controller">
				<h4>Paskyros duomenys</h4>
				<div class="table-wrapper">
					<table class="alt" id="account-data">
						<tbody ng-norepeat="field in account.fields">
							<tr>
								<td>El. paštas</td>
								<td>
									<span>{{account.email}}</span>
									<!--<a href="<?php echo $site_url; ?>javascript" onclick="return false;" class="icon fa-edit" ng-click="editForm('email')"><span class="label">Keisti</span></a>-->
								</td>
							</tr>
							<tr>
								<td width="35%">Vardas</td>
								<td width="65%">
									<span>{{account.name}}</span>
									<a href="<?php echo $site_url; ?>javascript" onclick="return false;" class="icon fa-edit" ng-click="editForm('name')"><span class="label">Keisti</span></a>
								</td>
							</tr>
							<tr>
								<td>Pavardė</td>
								<td>
									<span>{{account.lname}}</span>
									<a href="<?php echo $site_url; ?>javascript" onclick="return false;" class="icon fa-edit" ng-click="editForm('lname')"><span class="label">Keisti</span></a>
								</td>
							</tr>
							<tr>
								<td>Tel. nr.</td>
								<td>
									<span>{{account.phone}}</span>
									<a href="<?php echo $site_url; ?>javascript" onclick="return false;" class="icon fa-edit" ng-click="editForm('phone')"><span class="label">Keisti</span></a>
								</td>
							</tr>
							<tr>
								<td>Gimimo data</td>
								<td>
								<?php
								$dateofbirth = "{{account.dateofbirth}}";
								$dateofbirth_display = "";
								if($dateofbirth >= '1900-01-1')
								{
									$dateofbirth_display = $dateofbirth;
								}
								echo '<span>'.$dateofbirth_display.'</span>';
								?>
									<a href="<?php echo $site_url; ?>javascript" onclick="return false;" class="icon fa-edit" ng-click="editForm('dateofbirth')"><span class="label">Keisti</span></a>
							</tr>
							<tr>
								<td>Gyvenamoji vieta</td>
								<td>
									<span>{{account.residence}}</span>
									<a href="<?php echo $site_url; ?>javascript" onclick="return false;" class="icon fa-edit" ng-click="editForm('residence')"><span class="label">Keisti</span></a>
								</td>
							</tr>
							<tr>
								<td>Asmens kodas</td>
								<td>
									<span>{{account.personalcode}}</span>
									<a href="<?php echo $site_url; ?>javascript" onclick="return false;" class="icon fa-edit" ng-click="editForm('personalcode')"><span class="label">Keisti</span></a>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</section>
			<hr>
			<div class="form_style">
				<section ng-controller="change_pw_controller">
					<h4>Slaptažodžio keitimas</h4>
				    <div class="alert {{alertClass}} alert-dismissible" ng-show="alertMsg">
						<a href="#" onclick="return false;" class="close" ng-click="closeMsg()" aria-label="close">&times;</a>
						{{alertMessage}}
				    </div>
					<form method="post" ng-submit="submitChangePw()">
						<div class="row gtr-uniform" ng-show="change_pw_form">
							<div class="col-6 col-12-xsmall">
								<input type="password" name="old_password" id="old_password" ng-model="registerData.old_password" value="" placeholder="Senas slaptažodis">
							</div>
							&nbsp;
							<div class="col-6 col-12-xsmall">
								<input type="password" name="new_password_1" id="new_password_1" ng-model="registerData.new_password_1" value="" placeholder="Naujas slaptažodis">
							</div>
							<div class="col-6 col-12-xsmall">
								<input type="password" name="new_password_2" id="new_password_2" ng-model="registerData.new_password_2" value="" placeholder="Naujas slaptažodis pakartotinai">
							</div>

							<div class="col-12">
								<span id="err"></span>
								<ul class="actions">
									<li><input type="submit" value="Keisti" class="primary"></li>
									<li><input type="reset" value="Iš naujo"></li>
								</ul>
							</div>
						</div>
					</form>
				</section>
			</div>
	</section>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
<script>
var app = angular.module('account_app', []);

// Edit Account Data
app.controller('edit_data_controller', function($scope, $http){

	$scope.editForm = function(whatWhat)
	{
		var fromTitle = ""
		var inputType = "text"
		var enteredValue = ""
		var isDate = 0
		
		switch(whatWhat) {
		  case "name":
			fromTitle = "Vardo"
			enteredValue = $scope.account.name
			break;
		  case "lname":
			fromTitle = "Pavardės"
			enteredValue = $scope.account.lname
			break;
		  /*case "email":
			fromTitle = "El. pašto"
			inputType = "email"
			enteredValue = $scope.account.email
			break;*/
		  case "phone":
			fromTitle = "Tel. nr."
			enteredValue = $scope.account.phone
			break;
		  case "dateofbirth":
			fromTitle = "Gimimo datos"
			enteredValue = $scope.account.dateofbirth
			isDate = 1
			break;
		  case "residence":
			fromTitle = "Gyvenamosios vietos"
			enteredValue = $scope.account.residence
			break;
		  case "personalcode":
			fromTitle = "Asmens kodo"
			enteredValue = $scope.account.personalcode
			break;
		}

		if(isDate == 0)
		{
			(async function getResult (arghh) {
				
				var tempValue = enteredValue
				if(arghh)
				{
					tempValue = arghh
				}
				
				var isConfirm = false
				
				const {value: storeValue} = await Swal.fire({
				  title: fromTitle + ' keitimas',
				  input: inputType,
				  inputValue: tempValue,
				  showCancelButton: true,
				  confirmButtonText: 'Išsaugoti',
				  cancelButtonText: 'Atšaukti',
				  inputValidator: (value) => {
					  isConfirm = true
					if (!value) {
					  return 'Tuščias laukelis.'
					}
				  }
				})

				const queryAPI = '<?php echo $site_url; ?>save_account_field.php?what=' + whatWhat + '&value=' +  storeValue

				if(isConfirm == true)
				{
				const inputValue = await fetch(queryAPI)
				  .then(response => response.json())
				  .then(data => data.error)
				  
				  if(storeValue)
				  {
					if(inputValue == "")
					{
						const {value: queryValue} = await Swal.fire(
							{
								type: 'success',
								title: `Išsaugota!`,
								showCancelButton: false,
								showConfirmButton: false
							})
							
							switch(whatWhat) {
								case "name":
									$scope.account.name = storeValue
								break;
								case "lname":
									$scope.account.lname = storeValue
								break;
								/*case "email":
									$scope.account.email = storeValue
								break;*/
								case "phone":
									$scope.account.phone = storeValue
								break;
								case "dateofbirth":
									$scope.account.dateofbirth = storeValue
								break;
								case "residence":
									$scope.account.residence = storeValue
								break;
								case "personalcode":
									$scope.account.personalcode = storeValue
								break;
							}
							$scope.$apply();
							updateNotices();
							updateNav();
					}
					else
					{
						const {value: queryValue} = await Swal.fire(
							{
								type: 'error',
								title: `${inputValue}`,
								showCancelButton: false,
								showConfirmButton: false
							})
							getResult (storeValue)
					}
				  }
				}

				
			})()
		}
		else
		{
			(async function getResultDate (arghh) {
				
				var tempValue = enteredValue
				if(arghh)
				{
					tempValue = arghh
				}
				
				/*<input type="date" id="exampleInput" name="date" ng-model="account.setDate" placeholder="yyyy-MM-dd" min="1900-01-01" max="<?php echo date("Y-m-d"); ?>" required />
				<div role="alert">
				 <span class="error" ng-show="input.$error.required">Privalomas</span>
				 <span class="error" ng-show="input.$error.date">Neteisinga data.</span>
				</div>*/
				const {value: storeValue} = await Swal.fire({
				  title: fromTitle + ' keitimas',
				  html:
					'<input type="date" id="storeValue" class="swal2-input" style="color: #545454;" name="storeValue" value="' + tempValue + '" placeholder="yyyy-MM-dd" min="1900-01-01" max="<?php echo date("Y-m-d"); ?>" required />',
				  showCancelButton: true,
				  confirmButtonText: 'Išsaugoti',
				  cancelButtonText: 'Atšaukti',
				  inputValidator: (value) => {
					if (!value) {
					  return 'Tuščias laukelis.'
					}
				  },
				preConfirm: function() {
					return new Promise((resolve, reject) => {
						// get your inputs using their placeholder or maybe add IDs to them
						resolve({
							Date: $('input[id="storeValue"]').val(),
						});
						// maybe also reject() on some condition
					});
				}
				})

				const queryAPI = '<?php echo $site_url; ?>save_account_field.php?what=' + whatWhat + '&value=' +  storeValue.Date

				const inputValue = await fetch(queryAPI)
				  .then(response => response.json())
				  .then(data => data.error)
				  
				  if(storeValue)
				  {
					if(inputValue == "")
					{
						const {value: queryValue} = await Swal.fire(
							{
								type: 'success',
								title: `Išsaugota!`,
								showCancelButton: false,
								showConfirmButton: false
							})

							$scope.account.dateofbirth = storeValue.Date
							$scope.$apply();
							updateNotices();
							updateNav();						
					}
					else
					{
						const {value: queryValue} = await Swal.fire(
							{
								type: 'error',
								title: `${inputValue}`,
								showCancelButton: false,
								showConfirmButton: false
							})
							getResultDate (storeValue.Date)
					}
				  }

				
			})()
		}

	};
	
    $scope.account = {
        name: '<?php echo getSingleValue('users', 'email', $_SESSION["email"], 'name'); ?>',
		lname: '<?php echo getSingleValue('users', 'email', $_SESSION["email"], 'lname'); ?>',
		email: '<?php echo getSingleValue('users', 'email', $_SESSION["email"], 'email'); ?>',
		phone: '<?php echo getSingleValue('users', 'email', $_SESSION["email"], 'phone'); ?>',
		dateofbirth: '<?php echo getSingleValue('users', 'email', $_SESSION["email"], 'dateofbirth'); ?>',
		residence: '<?php echo getSingleValue('users', 'email', $_SESSION["email"], 'residence'); ?>',
		personalcode: '<?php echo getSingleValue('users', 'email', $_SESSION["email"], 'personalcode'); ?>',
		setDate: new Date()
    };
	
	$scope.showEditField = function(whatWhat){
		switch(whatWhat) {
		  case "name":
			$scope.edit_name = 1;
			break;
		  case "lname":
			$scope.edit_lname = 1;
			break;
		  case "email":
			$scope.edit_email = 1;
			break;
		  case "phone":
			$scope.edit_phone = 1;
			break;
		  case "dateofbirth":
			$scope.edit_dateofbirth = 1;
			break;
		  case "residence":
			$scope.edit_residence = 1;
			break;
		  case "personalcode":
			$scope.edit_personalcode = 1;
			break;
		  default:
			// hmm...
		}
	};
		 
	$scope.saveField = function(whatWhat){
		  $http({
		   method:"POST",
		   url:"save_account_field.php?what="+whatWhat,
		   data:$scope.account
		  }).success(function(data){
			switch(whatWhat) {
			  case "name":
				$scope.alertMsg_name = true;
				break;
			  case "lname":
				$scope.alertMsg2 = true;
				break;
			  case "email":
				$scope.alertMsg3 = true;
				break;
			  case "phone":
				$scope.alertMsg4 = true;
				break;
			  case "dateofbirth":
				$scope.alertMsg5 = true;
				break;
			  case "residence":
				$scope.alertMsg6 = true;
				break;
			  case "personalcode":
				$scope.alertMsg7 = true;
				break;
			}
		   if(data.error != '')
		   {
			switch(whatWhat) {
			  case "name":
				$scope.alertClass_name = 'alert-danger';
				$scope.alertMessage_name = data.error;
				break;
			  case "lname":
				$scope.alertClass2 = 'alert-danger';
				$scope.alertMessage2 = data.error;
				break;
			  case "email":
				$scope.alertClass3 = 'alert-danger';
				$scope.alertMessage3 = data.error;
				break;
			  case "phone":
				$scope.alertClass4 = 'alert-danger';
				$scope.alertMessage4 = data.error;
				break;
			  case "dateofbirth":
				$scope.alertClass5 = 'alert-danger';
				$scope.alertMessage5 = data.error;
				break;
			  case "residence":
				$scope.alertClass6 = 'alert-danger';
				$scope.alertMessage6 = data.error;
				break;
			  case "personalcode":
				$scope.alertClass7 = 'alert-danger';
				$scope.alertMessage7 = data.error;
				break;
			}
		   }
		   else
		   {
			switch(whatWhat) {
			  case "name":
				$scope.alertClass_name = 'alert-success';
				$scope.alertMessage_name = data.message;
				break;
			  case "lname":
				$scope.alertClass2 = 'alert-success';
				$scope.alertMessage2 = data.message;
				break;
			  case "email":
				$scope.alertClass3 = 'alert-success';
				$scope.alertMessage3 = data.message;
				break;
			  case "phone":
				$scope.alertClass4 = 'alert-success';
				$scope.alertMessage4 = data.message;
				break;
			  case "dateofbirth":
				$scope.alertClass5 = 'alert-success';
				$scope.alertMessage5 = data.message;
				break;
			  case "residence":
				$scope.alertClass6 = 'alert-success';
				$scope.alertMessage6 = data.message;
				break;
			  case "personalcode":
				$scope.alertClass7 = 'alert-success';
				$scope.alertMessage7 = data.message;
				break;
			}
			$scope.registerData = {};
			
			// Paslepiam redagavimo laukelius ir mygtukus, nes norime išsaugoti duomenis
			switch(whatWhat) {
			  case "name":
				$scope.edit_name = 0;
				break;
			  case "lname":
				$scope.edit_lname = 0;
				break;
			  case "email":
				$scope.edit_email = 0;
				break;
			  case "phone":
				$scope.edit_phone = 0;
				break;
			  case "dateofbirth":
				$scope.edit_dateofbirth = 0;
				break;
			  case "residence":
				$scope.edit_residence = 0;
				break;
			  case "personalcode":
				$scope.edit_personalcode = 0;
				break;
			}
		   }
		  });
		
	};
	
	$scope.justHideEdit = function(whatWhat){
		switch(whatWhat) {
		  case "name":
			$scope.edit_name = 0;
			break;
		  case "lname":
			$scope.edit_lname = 0;
			break;
		  case "email":
			$scope.edit_email = 0;
			break;
		  case "phone":
			$scope.edit_phone = 0;
			break;
		  case "dateofbirth":
			$scope.edit_dateofbirth = 0;
			break;
		  case "residence":
			$scope.edit_residence = 0;
			break;
		  case "personalcode":
			$scope.edit_personalcode = 0;
			break;
		  default:
			// hmm...
		}
	};

});


// Change Password
app.controller('change_pw_controller', function($scope, $http){
 $scope.closeMsg = function(){
  $scope.alertMsg = false;
 };

 $scope.change_pw_form = true;

 $scope.showChangePw = function(){
  $scope.change_pw_form = true;
  $scope.alertMsg = false;
 };

 $scope.submitChangePw = function(){
  $http({
   method:"POST",
   url:"change_password.php",
   data:$scope.registerData
  }).success(function(data){
   $scope.alertMsg = true;
   if(data.error != '')
   {
	$scope.alertClass = 'alert-danger';
	$scope.alertMessage = data.error;
   }
   else
   {
	$scope.alertClass = 'alert-success';
	$scope.alertMessage = data.message;
	$scope.registerData = {};
   }
  });
 };

});
</script>


<?php
include_once('sidebar.php');
include_once('footer.php');
?>