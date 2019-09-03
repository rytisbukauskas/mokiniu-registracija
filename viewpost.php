<?php 
define('INCLUDE', true);

$page_title = 'Naujiena';
$page_name = 'naujiena';
$pg_desc = '';
$key_words = '';

include_once ('header.php');

$stmt = $connect->prepare('SELECT id, title, content, date FROM news_posts WHERE id = :postID');
$stmt->execute(array(':postID' => $_GET['id']));
$row = $stmt->fetch();

if($row['id'] == ''){
	header('Location: '.$site_url.'naujienos');
	exit;
}

$fmt = new \IntlDateFormatter('lt_LT', NULL, NULL);
$fmt->setPattern('yyyy \'m\'. MMMM d \'d\'.'); 

?>

<div class="col-9 col-12-medium" style="padding: 2em 2em 2em 2em; background-color: #f7f7f7;">
	<section>
		<div class="blogpostcategory">
			<div class="topBlog">
				<div class="blog-category"> </div>
				<h2 class="title">
							<a href="<?php echo $site_url.'naujiena/'.$row['id']; ?>" onclick="return false;" rel="bookmark" title="Permanent" link="" to="" exploring="" my="" surroundings"=""><?php echo $row['title']; ?></a>
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
							<p><?php echo $row['content']; ?></p>
						</div>
					</div>

				</div>
			</div>
		</div>
	</section>
</div>

<?php
include_once('sidebar.php');
include_once('footer.php');
?>