<?php global $options, $post; ?>
<?php if (isset($options['showRecentPosts']) && $options['showRecentPosts']) {
	require_once 'views/sidebar/recentPosts.php';	
} ?>
<?php if (isset($options['showTagCloud']) && $options['showTagCloud']) {
	require_once 'views/sidebar/tagCloud.php';	
} ?>