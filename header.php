<?php

if(!defined('INCLUDE')) { die(); }

if(!isset($_SESSION)) 
{ 
	session_start();
}

include_once('config.php');
include_once('functions.php');

$title_set = isset($page_title) && $page_title != '' ? $page_title.' - ' : '';

?>
<!DOCTYPE HTML>
<html>
	<head>
		<title><?php echo $title_set; ?>Raseinių gimnazija</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" href="<?php echo $site_url; ?>assets/css/main.css" />
		<link rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
		<link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css">
		<noscript><link rel="stylesheet" href="<?php echo $site_url; ?>assets/css/noscript.css" /></noscript>
		<!-- Favicon -->
		<link rel="apple-touch-icon" sizes="180x180" href="<?php echo $site_url; ?>assets/favicon/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="<?php echo $site_url; ?>assets/favicon/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="<?php echo $site_url; ?>assets/favicon/favicon-16x16.png">
		<link rel="manifest" href="<?php echo $site_url; ?>assets/favicon/site.webmanifest">
		<link rel="mask-icon" href="<?php echo $site_url; ?>assets/favicon/safari-pinned-tab.svg" color="#5bbad5">
		<meta name="msapplication-TileColor" content="#da532c">
		<meta name="theme-color" content="#ffffff"/>
		<!-- Facebook metadata -->
		<meta property="og:image:height" content="1106">
		<meta property="og:image:width" content="2113">
		<meta property="og:image" content="<?php echo $site_url; ?>images/facebook/og-image.jpg">
		<meta property="og:title" content="Raseinių &bdquo;Žemaičio&ldquo; gimnazijos mokinių registravimo sistema">
		<meta property="og:description" content="Raseinių &bdquo;Žemaičio&ldquo; gimnazijos mokinių registravimo sistema palengvina būsimo mokinio stojimą į mokyklą, leidžiant užsiregistruoti ir įkelti reikiamus dokumentus.">
		<meta property="og:url" content="<?php echo $site_url; ?>">
		  
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.7/angular.min.js"></script>
		
		<?php // START Administravimas
		if($page_name == "administravimas")
		{ ?>
		<link rel="stylesheet" type="text/css" href="<?php echo $site_url; ?>assets/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $site_url; ?>assets/vendor/animate/animate.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $site_url; ?>assets/vendor/select2/select2.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $site_url; ?>assets/vendor/perfect-scrollbar/perfect-scrollbar.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $site_url; ?>assets/css/util.css">
		<?php }
		// END Administravimas ?>
		<?php // START Dokumentų peržiūra
		if($page_name == "dokumentu-perziura") { ?>
		<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" />
		<?php }
		// END Dokumentų peržiūra ?>
	
	</head>
	<body class="is-preload">
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
				   
				   
				<div ng-app="login_register_app" ng-controller="login_register_controller" class="form_style">
				   <div id="main" class="login-form">
				   <div class="alert {{alertClass}} alert-dismissible" ng-show="alertMsg">
					<a href="#" onclick="return false;" class="close" ng-click="closeMsg()" aria-label="close">&times;</a>
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
				   </div>
				   <?php
				   
				   include_once('footer.php');
				   
				   die();
				   }
				   else
				   {
				   ?>		
				   
				<!-- PRISIJUNGĘS -->
				
				<?php
				
					/* [Pavadinimas] [Linkas] */
					$menu_items = array( 
						array("Pradinis", ""), 
						array("Naujienos", "naujienos"), 
						array("Dokumentų valdymas", "dokumentu-valdymas"),
						array("Privatumo politika", "privatumo-politika")
					); 


					// Nustatymai anketai
					
					$user_id = getSingleValue('users', 'email', $_SESSION["email"], 'id');
					$admin_access = 0;
					$employee_access = 0;
					$super_access = 0;

					if(getSingleValue('users', 'email', $_SESSION["email"], 'account_status_id') >= 1) $employee_access = 1;
					
					if(getSingleValue('users', 'email', $_SESSION["email"], 'account_status_id') >= 2) $admin_access = 1;
					
					if(getSingleValue('users', 'email', $_SESSION["email"], 'account_status_id') >= 3) $super_access = 1;
					
					
					if($page_name == "administravimas")
					{
						if($admin_access < 1)
						{
							header('Location: '.$site_url);
						}
					}
					
					if($page_name == "dokumentu-perziura")
					{
						if($employee_access < 1)
						{
							header('Location: '.$site_url);
						}
					}
			
			
				?>

				<!-- Mobile Menu -->
				<nav class="menu">
					<ul class="active">
						<?php
						foreach ($menu_items as $menu_item) {
							$add_mi_class_m = $page_name== $menu_item[1] ? ' class="current-item"' : '';
							if($menu_item[1] == "dokumentu-valdymas")
							{
								if(isDataFillingNeeded())
								{
									echo'<li'.$add_mi_class_m.'><a href="'.$site_url.'javascript" id="tt-docs-m" onclick="return false;">'.$menu_item[0].'</a></li>';
								}
								else
								{
									echo'<li'.$add_mi_class_m.'><a href="'.$site_url.''.$menu_item[1].'">'.$menu_item[0].'</a></li>';
								}
							}
							else
							{
								echo'<li'.$add_mi_class_m.'><a href="'.$site_url.''.$menu_item[1].'">'.$menu_item[0].'</a></li>';
							}
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
								$add_mi_class = $page_name == $menu_item[1] ? ' class="active"' : '';
								if($menu_item[1] == "dokumentu-valdymas" && $admin_access < 1)
								{
									if(isDataFillingNeeded())
									{
										echo'<li><a href="'.$site_url.'javascript" id="tt-docs" onclick="return false;"'.$add_mi_class.'>'.$menu_item[0].'</a></li>';
									}
									else
									{
										echo'<li><a href="'.$site_url.''.$menu_item[1].'"'.$add_mi_class.'>'.$menu_item[0].'</a></li>';
									}
								}
								else
								{
									echo'<li><a href="'.$site_url.''.$menu_item[1].'"'.$add_mi_class.'>'.$menu_item[0].'</a></li>';
								}
								
							}
							?>
						</ul>
					</nav>

				<!-- Main -->
					<div id="main">
					<section id="content" class="main" style="padding: 0;">
						<div id="top-notices">
							<?php
							if($admin_access == 0 && $employee_access == 0)
							{
								if(isDataFillingNeeded())
								{
									echo showNotice('Norint įkelti dokumentus ir naudotis kitomis sistemos funkcijomis, turite užpildyti nepateiktus duomenis puslapyje <a href="'.$site_url.'paskyra">„Paskyra“</a>.');
								}
							}
							
							?>
						</div>
						<div class="row">
				   <?php } ?>