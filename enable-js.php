<?php 
define('INCLUDE', true);

$page_title = 'JavaScript įjungimas';
$page_name = 'javascript';
$pg_desc = '';
$key_words = '';

include_once ('header.php');
?>

<div class="col-9 col-12-medium" style="padding: 2em 2em 2em 2em;">
	<section>
			<h3 class="page-title"><span><?php echo $page_title; ?></span></h3>
			<p class="justify">
				Ši internetinė svetainė yra pagrįsta „JavaScript“. Kai kuriose interneto naršyklėse „JavaScript“ parinktis gali būti išjungta. Norint sklandžiai naudotis internetine svetainė, „JavaScript“ parinktis turi būti įjungta.
			</p>
			<p class="justify">
				Norėdami įjungti „JavaScript“, pavyzdžiui „Mozilla Firefox“ naršyklėje, prašome atlikti šiuos veiksmus:
				<ol>
					<li>Meniu „Įrankiai“ spustelėkite „Parinktys“</li>
					<li>Spustelėkite skirtuką „Turinys“</li>
					<li>Pažymėkite žymės langelį „Įjungti JavaScript“</li>
					<li>Spustelėkite „Taikyti“.</li>
				</ol>
			</p>
	</section>
</div>

<?php
include_once('sidebar.php');
include_once('footer.php');
?>