<?php 
define('INCLUDE', true);

$page_title = 'Naujienos';
$page_name = 'naujienos';
$pg_desc = '';
$key_words = '';

include_once ('header.php');

/* Pagination */

if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

$no_of_records_per_page = 3;
$offset = ($page-1) * $no_of_records_per_page; 

$total_pages_sql = "SELECT COUNT(*) FROM news_posts";
$statement = $connect->query($total_pages_sql)->fetch();

$total_pages = ceil($statement[0] / $no_of_records_per_page);

/* End of Pagination */

?>

<div class="col-9 col-12-medium" style="padding: 2em 2em 2em 2em; background-color: #f7f7f7;">
	<section>
		<h3 class="page-title"><span><?php echo $page_title; ?></span></h3>
		<?php
		try {

			$stmt = $connect->query('SELECT id, title, description, date FROM news_posts ORDER BY date DESC LIMIT '.$offset.', '.$no_of_records_per_page.'');
			while($row = $stmt->fetch()){
				
				$fmt = new \IntlDateFormatter('lt_LT', NULL, NULL);
				$fmt->setPattern('yyyy \'m\'. MMMM d \'d\'.'); 
				
				/*echo '<div>';
					echo '<h2><a href="'.$site_url.'naujiena/'.$row['id'].'">'.$row['title'].'</a></h2>';
					echo '<p>Paskelbta '.$fmt->format(new \DateTime($row['date'])).'</p>';
					echo '<p>'.$row['description'].'</p>';                
					echo '<p><a href="'.$site_url.'naujiena/'.$row['id'].'">Daugiau</a></p>';                
				echo '</div>';*/
				
				?>
				
		<div class="blogpostcategory">
			<div class="topBlog">
				<div class="blog-category"> </div>
				<h2 class="title">
							<a href="<?php echo $site_url.'naujiena/'.$row['id']; ?>" rel="bookmark" title="Permanent" link="" to="" exploring="" my="" surroundings"=""><?php echo $row['title']; ?></a>
												</h2>
			</div>
			<div class="post-meta">
				<a class="post-meta-time" href="<?php echo $site_url; ?>/javascript" onclick="return false;"><?php echo $fmt->format(new \DateTime($row['date'])); ?></a>
			</div>
			<!-- end of post meta -->

			<div class="entry">
				<div class="meta">
					<div class="blogContent">
						<div class="blogcontent">
							<p><?php echo $row['description']; ?></p>
							<div class="lavander-read-more"><a class="more-link" href="<?php echo $site_url.'naujiena/'.$row['id']; ?>">Skaityti</a></div>
						</div>
					</div>

				</div>
			</div>
		</div>
				
				<?php

			}

		} catch(PDOException $e) {
			echo $e->getMessage();
		}
		?>
		<ul class="pagination">
			<li><a href="<?php echo $site_url; ?>naujienos/1">Pirmas</a></li>
			<li class="<?php if($page <= 1){ echo 'disabled'; } ?>">
				<a href="<?php if($page <= 1){ echo $site_url.'javascript" onclick="return false;'; } else { echo $site_url."naujienos/".($page - 1); } ?>">Ankstesnis</a>
			</li>
			<li class="<?php if($page >= $total_pages){ echo 'disabled'; } ?>">
				<a href="<?php if($page >= $total_pages){ echo $site_url.'javascript" onclick="return false;'; } else { echo $site_url."naujienos/".($page + 1); } ?>">Kitas</a>
			</li>
			<li><a href="<?php echo $site_url; ?>naujienos/<?php echo $total_pages; ?>">Paskutinis</a></li>
		</ul>
	</section>
</div>

<?php
include_once('sidebar.php');
include_once('footer.php');
?>