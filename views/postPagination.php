<?php
require_once 'actions/database.php';
require_once 'actions/read.php';
$prewPost = '';
$nextPost = '';

$olderThan = date("Y-m-d  H:i:s");
$mysqli = connect();
$where = " AND `p`.`slug`='".$GLOBALS['url']['slug']."'";
$user = (numberOfposts(null, array("postdate" => date("Y-m-d  H:i:s"))) == 0) ? "demo" : $GLOBALS['options']['user'];

/* Load actual post data */
$result = $mysqli->query("SELECT `p`.* FROM `post` AS p WHERE p.`user`= '".$user."'".$where." ORDER BY postdate DESC LIMIT 1");
$posts = array();
while ($row = $result->fetch_object()) {
	$posts[] = $row;
}
$result->close();	
/* Load previous and next posts data */
if(!empty($posts)){
$actPostDate = $posts[0]->postdate;
$result = $mysqli->query("(SELECT slug, postdate FROM `post` WHERE `user` = '".$user."' AND `postdate` < '".$actPostDate."' AND published = 1 ORDER BY postdate DESC LIMIT 1)
							UNION 
						  (SELECT slug, postdate FROM `post` WHERE `user` = '".$user."' AND `postdate` > '".$actPostDate."' AND published = 1 ORDER BY postdate ASC LIMIT 1)");
while ($row = $result->fetch_object()) {
	if(strtotime($row->postdate) < strtotime($actPostDate)){
		$prewPost = $row->slug; 	
	} else {
		$nextPost = $row->slug;
	}
}
$result->close();	
close($mysqli);
?>
<?php if(isset($options['paginationShowNextPrevPost']) && $options['paginationShowNextPrevPost']): ?>
	<?php if($nextPost != "") : ?>
		<span class="pagelink next">
			<a target="_top" href="<?php echo $GLOBALS['section-url'].$nextPost; ?>"><?php echo isset($options['nextPost']) && $options['nextPost'] ? $options['nextPost'] : __('nextPost'); ?></a>
		</span>
	<?php else: ?>
		<span class="pagelink previous inactive">
			<?php echo isset($options['nextPost']) && $options['nextPost'] ? $options['nextPost'] : __('nextPost'); ?>
		</span>
	<?php endif; ?>
	<?php if($prewPost != "") : ?>
		<span class="pagelink previous">
			<a target="_top" href="<?php echo $GLOBALS['section-url'].$prewPost; ?>"><?php echo isset($options['prevPost']) && $options['prevPost'] ? $options['prevPost'] : __('prevPost'); ?></a>
		</span>
	<?php else: ?>
		<span class="pagelink previous inactive">
			<?php echo isset($options['prevPost']) && $options['prevPost'] ? $options['prevPost'] : __('prevPost'); ?>
		</span>
	<?php endif; ?>
<?php endif; }?>



