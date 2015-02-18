<?php
require_once 'actions/options.php';

global $options;

$config = HTMLPurifier_Config::createDefault();
$config->set('Output.TidyFormat', true);
$GLOBALS['prf'] = new HTMLPurifier($config);

$user = $options['user'];
$class = "";
if (numberOfposts(null, array("postdate" => date("Y-m-d  H:i:s"))) == 0) {
	$user = 'demo';
	$class = 'dummy';
}
$posts = loadPosts($GLOBALS['current_page'], null, true, array_merge($GLOBALS['url'], array("postdate" => date("Y-m-d  H:i:s"))), $user);
?>
<div class="clearfix <?php echo $class; ?> displayedPosts">
	<?php 
		for ($i=0; $i<sizeof($posts); $i++) {
			$posts[$i]->{'layout'} = $options['postLayout'];
			$posts[$i]->{'permalink'} = $GLOBALS['section-url'].$posts[$i]->slug;
			$GLOBALS['post'] = $posts[$i];
			switch ($posts[$i]->type) {
				case "text": {
					include 'views/posts/text.php';
					break;
				}
				case "image": {
					include 'views/posts/image.php';
					break;
				}
				case "video": {
					include 'views/posts/video.php';
					break;
				}
				case "link": {
					include 'views/posts/link.php';
					break;
				}
			}
			if((($i+1) % $options['page_columns']) == 0) {
				echo '<div class="clearfix"></div>';
			}
		}
	?>
</div>
