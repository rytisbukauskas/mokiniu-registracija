<?php					
if(!defined('INCLUDE')) { die(); }

$req1 = 'select m1.id, m1.title, m1.timestamp, count(m2.id) as reps, users.id as userid, users.email from pm as m1, pm as m2,users where ((m1.user1="'.$user_id.'" and m1.user1read="no" and users.id=m1.user2) or (m1.user2="'.$user_id.'" and m1.user2read="no" and users.id=m1.user1)) and m1.id2="1" and m2.id=m1.id group by m1.id order by m1.id desc';
$reqq1 = $connect->query($req1);
$reqqq1 = $reqq1->fetch();
if ($res = $connect->query($req1)) {
    if ($res->rowCount() > 0) {
		$req1_count = (int)$res->rowCount();
    }
    else {
        $req1_count = 0;
    }
}
?>

<div class="col-3 col-12-medium col-with-lborder" style="padding: 2em 2em 2em 2em;">
<!-- Paskyra -->
	<section id="cta" class="main special">
		<header class="major">
			<h3>Sveiki, <?php echo $_SESSION["name"];?>!<?php
			if ($super_access == 1) {
				echo'<br><span class="status-badget"><i class="icon fa-buysellads" style="margin-right: 3px;"></i> Super Administratorius</span>';
			}
			elseif ($admin_access == 1)
			{
				echo'<br><span class="status-badget"><i class="icon fa-buysellads" style="margin-right: 3px;"></i> Administratorius</span>';
			}
			elseif ($employee_access == 1) {
				echo'<br><span class="status-badget"><i class="icon fa-buysellads" style="margin-right: 3px;"></i> Darbuotojas</span>';
			}
			?></h3>
				<div class="panel-body">
					<ul class="actions sidebar-menu divide">
						<?php
						if ($admin_access == 1)
						{
							echo'<li><a href="'.$site_url.'administravimas" class="button fit small">Administravimas</a></li>';
						}
						if ($employee_access == 1 || $admin_access == 1) {
							echo'<li><a href="'.$site_url.'dokumentu-perziura" class="button fit small">Dokumentų peržiūra</a></li>';
						}
						?>
						<li><a href="<?php echo $site_url; ?>pranesimai" class="button fit small"<?php if($req1_count > 0){ echo ' style="font-weight: 700"'; } ?>>Pranešimai<?php if($req1_count > 0){ echo ' (' . $req1_count . ')'; } ?></a></li>
						<li><a href="<?php echo $site_url; ?>paskyra" class="button fit small">Paskyra</a></li>
					</ul>
				<ul class="actions sidebar-menu">
					<li><a href="<?php echo $site_url; ?>atsijungti" class="button fit small">Atsijungti</a></li>
				</ul>
				</div>
		</header>
	</section>
</div>