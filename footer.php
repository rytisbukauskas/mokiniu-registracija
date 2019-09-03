<?php
					
if(!defined('INCLUDE')) { die(); }
					
				   if(isset($_SESSION["name"]))
				   {
				   ?>
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
						<p class="copyright">&copy; D.K., Prezidento Jono Žemaičio gimnazija, <?php echo date("Y"); ?>. Visos teisės saugomos.<br />
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
		   url:"<?php echo $site_url; ?>register.php",
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
			$scope.showLogin();
			$scope.alertMsg = true;
			$scope.alertClass = 'alert-success';
			$scope.alertMessage = data.message;
			$scope.registerData = {};
		   }
		  });
		 };

		 $scope.submitLogin = function(){
		  $http({
		   method:"POST",
		   url:"<?php echo $site_url; ?>login.php",
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
		
			<!--<script src="<?php //echo $site_url; ?>assets/js/jquery.min.js"></script>-->
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
			<script type="text/javascript" src="//code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
			<script type="text/javascript" language="javascript">
			$(document).ready(function() {
			   updateNotices = function() {
				  $('#top-notices').load('index.php #top-notices > *', function() {
					   
				  });
			   };
			   updateNav = function() {
				  $('nav#nav').load('account.php nav#nav > *', function() {
					   
				  });
				  $('nav.menu').load('account.php nav.menu > *', function() {
					jQuery('.toggle-nav').click(function(e) {
						jQuery(this).toggleClass('active');
						jQuery('.menu ul').toggleClass('active');

						e.preventDefault();
					});
				  });
			   };
			   
				$('#tt-docs').on({
				  "click": function() {
					$(this).tooltip({ items: "#tt-docs", content: "Dokumentus valdyti galima tik užpildžius visus paskyros duomenis."});
					$(this).tooltip("open");
				  },
				  "mouseout": function() {      
					 $(this).tooltip("disable");   
				  }
				});
				
				$('#tt-docs-m').on({
				  "click": function() {
					$(this).tooltip({ items: "#tt-docs-m", content: "Dokumentus valdyti galima tik užpildžius visus paskyros duomenis."});
					$(this).tooltip("open");
				  },
				  "mouseout": function() {      
					 $(this).tooltip("disable");   
				  }
				});
				
			});
			</script>
			<?php // START Dokumentų valdymas
			if($page_name == "dokumentu-valdymas") { ?>
			<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
			<script type="text/javascript" language="javascript">
			$(document).ready(function() {
				$('a.submit-doc').on({
				  "click": function() {
					 event.preventDefault();
					 var href = jQuery(this).attr('href');
					Swal.fire({
					  title: 'Pateikti dokumentą?',
					  text: "Dokumentas bus pateiktas peržiūrai.",
					  type: 'warning',
					  showCancelButton: true,
					  confirmButtonColor: '#3085d6',
					  cancelButtonColor: '#d33',
					  confirmButtonText: 'Pateikti'
					}).then((result) => {
					  if (result.value) {
						window.location = href;
					  }
					})
				  }
				});
				$('a.delete-doc').on({
				  "click": function() {
					 event.preventDefault();
					 var href = jQuery(this).attr('href');
					Swal.fire({
					  title: 'Panaikinti dokumentą?',
					  text: "Dokumentas bus panaikintas neatgrąžinamai.",
					  type: 'warning',
					  showCancelButton: true,
					  confirmButtonColor: '#3085d6',
					  cancelButtonColor: '#d33',
					  confirmButtonText: 'Panaikinti'
					}).then((result) => {
					  if (result.value) {
						window.location = href;
					  }
					})
				  }
				});
			});
			</script>
			<?php }
			// END Dokumentų valdymas ?>
			<?php // START Dokumentų peržiūra
			if($page_name == "dokumentu-perziura") { ?>
			<script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
			<script type="text/javascript" language="javascript">
			$(document).ready(function () {
				$('#dDocs').DataTable({
				"scrollX": true,
				  "language": {
					"search": "",
					"searchPlaceholder": "Ieškoti...",
					"decimal":        "",
					"emptyTable":     "Įrašų nėra",
					"info":           "Rodoma nuo _START_ iki _END_ iš _TOTAL_ įrašų",
					"infoEmpty":      "Rodoma nuo 0 iki 0 iš 0 įrašų",
					"infoFiltered":   "(išfiltruota iš _MAX_ visų įrašų)",
					"infoPostFix":    "",
					"thousands":      ",",
					"lengthMenu":     "Rodyti _MENU_ įrašus (-ų)",
					"loadingRecords": "Kraunasi...",
					"processing":     "Apdorojama...",
					"zeroRecords":    "Įrašų nerasta",
					"paginate": {
						"first":      "Pirmas",
						"last":       "Paskutinis",
						"next":       "Kitas",
						"previous":   "Ankstesnis"
					},
					"aria": {
						"sortAscending":  ": įjungti stulpelių rūšiavimą didėjančia tvarka",
						"sortDescending": ": įjungti stulpelių rūšiavimą mažėjančia tvarka"
					}
				  },
				  initComplete: function () {
					var countC = 0;
					this.api().columns().every( function () {
						if(countC != 2 && countC != 5)
						{
							var column = this;
							var select = $('<select><option value=""></option></select>')
								.appendTo( $(column.footer()).empty() )
								.on( 'change', function () {
									var val = $.fn.dataTable.util.escapeRegex(
										$(this).val()
									);
			 
									column
										.search( val ? '^'+val+'$' : '', true, false )
										.draw();
								} );
			 
							column.data().unique().sort().each( function ( d, j ) {
								select.append( '<option value="'+d+'">'+d+'</option>' )
							} );
						}
						countC++;
					} );
				}
				});
				$('.dataTables_length').addClass('bs-select');
			});
			</script>
			<?php }
			// END Dokumentų peržiūra ?>
			<script src="<?php echo $site_url; ?>assets/js/jquery.scrollex.min.js"></script>
			<script src="<?php echo $site_url; ?>assets/js/jquery.scrolly.min.js"></script>
			<?php // START Administravimas
			if($page_name == "administravimas") { ?>
			<script type="text/javascript" src="https://canvasjs.com/assets/script/jquery.canvasjs.min.js"></script>
			<?php }
			// END Administravimas ?>
			<script src="<?php echo $site_url; ?>assets/js/browser.min.js"></script>
			<script src="<?php echo $site_url; ?>assets/js/breakpoints.min.js"></script>
			<script src="<?php echo $site_url; ?>assets/js/util.js"></script>
			<script src="<?php echo $site_url; ?>assets/js/main.js"></script>
			<script type="text/javascript" id="cookieinfo"
				src="//cookieinfoscript.com/js/cookieinfo.min.js"
				data-close-text="Supratau"
				data-linkmsg="Plačiau"
				data-moreinfo="<?php echo $site_url.'slapuku-politika'; ?>"
				data-message="Ši svetainė naudoja slapukus norint užtikrinti, kad jūsų naršymas būtų sklandus. Tęsiant naršymą parodysite, kad jūs su tuo sutinkate."
				data-divlinkbg="#8cc9f0"
				data-divlink="#ffffff">
			</script>

	</div>
	</body>
</html>