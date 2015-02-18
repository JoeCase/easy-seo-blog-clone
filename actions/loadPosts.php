<?php
require_once 'read.php';
$user = $GLOBALS['options']['user'];
$class = "";
if(numberOfposts(null, array("postdate" => date("Y-m-d  H:i:s"))) == 0) {
	$user = 'demo';
	$class = 'dummy'; 	
}
$posts = loadPosts($GLOBALS['current_page'], null, true, $GLOBALS['url'], $user);
for ($i=0; $i<sizeof($posts); $i++) {
	echo getPostHTML($posts[$i], isset($GLOBALS['url']['slug']), $GLOBALS['options']['postLayout'], $class);
	if((($i+1) % $GLOBALS['options']['page_columns']) == 0){
		echo '<div class="clearfix"></div>';
	}
}
echo '<div class="clearfix"></div>';
?>