<?php 
	global $options, $post; 
	require_once("social/facebook/src/facebook.php");
?>
	<span class="title">Share to social networks. <?php _e('Customize your share. !TITLE = inserts post title, !URL = inserts post URL'); ?></span>
	<span class="social facebook clearfix">
		<?php include '/social/facebook.php'; ?>
	</span>
	<span class="social twitter clearfix">
		<?php include '/social/twitter.php'; ?>
	</span>