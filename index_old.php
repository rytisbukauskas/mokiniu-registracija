<?php

session_start();

include_once('config.php');

?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Raseinių gimnazija - Mokinių registravimo sistema</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" href="<?php echo $site_url; ?>assets/css/main.css" />
		<noscript><link rel="stylesheet" href="<?php echo $site_url; ?>assets/css/noscript.css" /></noscript>
		  
		  <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.5.7/angular.min.js"></script>
	</head>
	<body class="is-preload">
	<div ng-app="login_register_app" ng-controller="login_register_controller" class="form_style">
		<!-- Wrapper -->
			<div id="wrapper">

				<!-- Header -->
					<header id="header" class="alt">
						<span class="logo"><img src="<?php echo $site_url; ?>images/logo.svg" alt="" /></span>
					</header>

				
				  <!-- NEPRISIJUNGĘS -->
				   <?php
				   
				   $id = isset($_GET['id']) ? $_GET['id'] : '';
				   
				   if(!isset($_SESSION["name"]))
				   {
				   ?>
				   
				   

				   <div id="main" class="login-form">
				   <div class="alert {{alertClass}} alert-dismissible" ng-show="alertMsg">
					<a href="#" class="close" ng-click="closeMsg()" aria-label="close">&times;</a>
					{{alertMessage}}
				   </div>
				   
				   <div class="panel panel-default" ng-show="login_form">
						<div class="panel-heading">
							<h3 class="panel-title">Prisijungimas</h3>
						</div>
						<div class="panel-body mt-1_2em">
							 <form method="post" ng-submit="submitLogin()">
								  <div class="form-group">
									   <input type="text" name="email" value="" placeholder="El. paštas" ng-model="loginData.email" />
								  </div>
								  <div class="form-group">
									   <input type="password" name="password" value="" placeholder="Slaptažodis" ng-model="loginData.password" />
								  </div>
								  <footer class="mt-2em">
									  <div class="form-group" align="center">
										  <ul class="actions special">
											   <li><input type="submit" name="login" class="button primary" value="Prisijungti" /></li>
											   <li><input type="button" name="register_link" class="button" ng-click="showRegister()" value="Registracija" /></li>
										   </ul>
									  </div>
								  </footer>
							 </form>
						</div>
				   </div>

				   <div class="panel panel-default" ng-show="register_form">
					<div class="panel-heading">
					 <h3 class="panel-title">Registracija</h3>
					</div>
					<div class="panel-body mt-1_2em">
					 <form method="post" ng-submit="submitRegister()">
					  <div class="form-group">
					   <input type="text" name="name" ng-model="registerData.name" value="" placeholder="Vardas" />
					  </div>
					  <div class="form-group">
					   <input type="text" name="email" ng-model="registerData.email" value="" placeholder="El. paštas" />
					  </div>
					  <div class="form-group">
					   <input type="password" name="password" ng-model="registerData.password" value="" placeholder="Slaptažodis" />
					  </div>
					<footer class="mt-2em">
						<div class="form-group" align="center">
							<ul class="actions special">
								<li><input type="submit" name="register" class="button primary" value="Registruotis" /></li>
								<li><input type="button" name="login_link" class="button" ng-click="showLogin()" value="Prisijungimas" /></li>
							</ul>
						</div>
					</footer>
					 </form>
					</div>
				   </div>
				   </div>
				   <?php
				   }
				   else
				   {
				   ?>		
				   
				<!-- PRISIJUNGĘS -->
				
				<?php
				
					/* [Pavadinimas] [Linkas] */
					$menu_items = array( 
						array("Naujienos", "naujienos"), 
						array("Dokumentų valdymas", "dokumentu-valdymas")
					); 

					
				?>

				<!-- Mobile Menu -->
				<nav class="menu">
					<ul class="active">
						<?php	
						foreach ($menu_items as $menu_item) {
							$add_mi_class_m = $id == $menu_item[1] ? ' class="current-item"' : '';
							echo'<li'.$add_mi_class_m.'><a href="'.$site_url.''.$menu_item[1].'">'.$menu_item[0].'</a></li>';
						}
						?>
					</ul>

					<a class="toggle-nav" href="#">&#9776;</a>
				</nav>
				<!-- End Of Mobile Menu -->
				
				<!-- Nav -->
					<nav id="nav">
						<ul>
							<?php
							foreach ($menu_items as $menu_item) {
								$add_mi_class = $id == $menu_item[1] ? ' class="active"' : '';
								echo'<li><a href="'.$site_url.''.$menu_item[1].'"'.$add_mi_class.'>'.$menu_item[0].'</a></li>';
							}
							?>
						</ul>
					</nav>

				<!-- Main -->
					<div id="main">
					<section id="content" class="main" style="padding: 0;">
						<div class="row">
							<div class="col-9 col-12-medium" style="padding: 2em 2em 2em 2em;">
							<!-- Paskyra -->
								<section id="cta" class="main special">
									<header class="major">
										<h2>Paskyra</h2>
										<p>Donec imperdiet consequat consequat. Suspendisse feugiat congue</p>
											<div class="panel-body">
											 <h1>Sveiki, <?php echo $_SESSION["name"];?> !</h1>
											<ul class="actions">
												<li><a href="<?php echo $site_url; ?>atsijungti" class="button">Atsijungti</a></li>
											</ul>
											</div>
									</header>
								</section>
							</div>
							<div class="col-3 col-12-medium col-with-lborder" style="padding: 2em 2em 2em 2em;">
							<!-- Paskyra -->
								<section id="cta" class="main special">
									<header class="major">
										<h2>Sveiki, <?php echo $_SESSION["name"];?> !</h2>
											<div class="panel-body">
												<ul class="actions sidebar-menu divide">
													<li><a href="<?php echo $site_url; ?>pranesimai" class="button fit">Pranešimai</a></li>
													<li><a href="<?php echo $site_url; ?>paskyra" class="button fit">Paskyra</a></li>
												</ul>
											<ul class="actions sidebar-menu">
												<li><a href="<?php echo $site_url; ?>atsijungti" class="button fit">Atsijungti</a></li>
											</ul>
											</div>
									</header>
								</section>
							</div>	
						</div>
					</section>
					


					</div>
				
					<?php
				   }
				   ?>
				
				<!-- Footer -->
					<footer id="footer">
						<section>
							<h2>Apie sistemą</h2>
							<p>Raseinių „Žemaičio“ gimnazijos mokinių registravimo sistema palengvina būsimo mokinio stojimą į mokyklą, leidžiant užsiregistruoti ir įkelti reikiamus dokumentus.</p>
							<ul class="actions">
								<li><a href="http://www.raseiniugimnazija.lt" target="_blank" class="button">RaseiniuGimnazija.lt</a></li>
							</ul>
						</section>
						<section>
							<h2>Kontaktai</h2>
							<dl class="alt">
								<dt>Adresas</dt>
								<dd>Kalnų g. 3, LT-60136 Raseiniai</dd>
								<dt>Telefonas</dt>
								<dd>(8 428) 51 969</dd>
								<dt>El.paštas</dt>
								<dd><a href="#">raseiniugimnazija@raseiniai.lt</a></dd>
							</dl>
							<ul class="icons">
								<li><a href="https://www.facebook.com/Raseini%C5%B3-Prezidento-Jono-%C5%BDemai%C4%8Dio-gimnazija-314690308555317/" target="_blank" class="icon fa-facebook alt"><span class="label">Facebook</span></a></li>
								<li><a href="https://www.instagram.com/raseiniugimnazija/" target="_blank" class="icon fa-instagram alt"><span class="label">Instagram</span></a></li>
							</ul>
						</section>
						<p class="copyright">&copy; Prezidento Jono Žemaičio gimnazija, <?php echo date("Y"); ?>. Visos teisės saugomos.<br />
						Kopijuoti turinį be raštiško gimnazijos sutikimo griežtai draudžiama.</p>
					</footer>

				
			</div>

		<!-- Scripts -->

		<script>
		var app = angular.module('login_register_app', []);
		app.controller('login_register_controller', function($scope, $http){
		 $scope.closeMsg = function(){
		  $scope.alertMsg = false;
		 };

		 $scope.login_form = true;

		 $scope.showRegister = function(){
		  $scope.login_form = false;
		  $scope.register_form = true;
		  $scope.alertMsg = false;
		 };

		 $scope.showLogin = function(){
		  $scope.register_form = false;
		  $scope.login_form = true;
		  $scope.alertMsg = false;
		 };

		 $scope.submitRegister = function(){
		  $http({
		   method:"POST",
		   url:"register.php",
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

		 $scope.submitLogin = function(){
		  $http({
		   method:"POST",
		   url:"login.php",
		   data:$scope.loginData
		  }).success(function(data){
		   if(data.error != '')
		   {
			$scope.alertMsg = true;
			$scope.alertClass = 'alert-danger';
			$scope.alertMessage = data.error;
		   }
		   else
		   {
			location.reload();
		   }
		  });
		 };

		});
		</script>
			<script src="<?php echo $site_url; ?>assets/js/jquery.min.js"></script>
			<script src="<?php echo $site_url; ?>assets/js/jquery.scrollex.min.js"></script>
			<script src="<?php echo $site_url; ?>assets/js/jquery.scrolly.min.js"></script>
			<script src="<?php echo $site_url; ?>assets/js/browser.min.js"></script>
			<script src="<?php echo $site_url; ?>assets/js/breakpoints.min.js"></script>
			<script src="<?php echo $site_url; ?>assets/js/util.js"></script>
			<script src="<?php echo $site_url; ?>assets/js/main.js"></script>
	</div>
	</body>
</html>