<?php 
define('INCLUDE', true);

$page_title = 'Mokinių registravimo sistema';
$page_name = '';
$pg_desc = '';
$key_words = '';

include_once ('header.php');
?>

<div class="col-9 col-12-medium" style="padding: 2em 2em 2em 2em;">
	<section>
			<h3 class="page-title"><span><?php echo $page_title; ?></span></h3>
			<p class="justify">Sveiki, tai Raseinių „Žemaičio“ gimnazijos mokinių registravimo sistema, kuri palengvina būsimo mokinio stojimą į mokyklą, leidžiant užsiregistruoti ir įkelti reikiamus dokumentus.</p>
	</section>
</div>

<?php
include_once('sidebar.php');
include_once('footer.php');
?>