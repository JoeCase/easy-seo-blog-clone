<?php 
require_once 'actions/read.php';

$current_page = isset($_REQUEST['esb_page']) && $_REQUEST['esb_page'] ? $_REQUEST['esb_page'] : 1;
$posts = loadPosts($current_page, 10, false);
$posts_count = $GLOBALS['options']['number_of_posts'];
$pages = ceil($posts_count / 10); // one based, not zero based pages count

?>
<?php if ($posts): ?>
<!-- List of posts -->
<div id="recent-posts" class="wide clearfix">
	<?php foreach ($posts as $post): ?>
		<div class="tile loader-wrap <?php echo $post->type; ?>">
			<div class="foreground">
				<h3><?php echo $post->title; ?></h3>
				<p class="date">
					<?php echo date('Y-m-d', strtotime($post->postdate)); ?>
				</p>
			</div>
			<span class="manage-buttons">
				<a class="switch change_publish <?php echo isset($post->published) && $post->published ? 'checked' : ''; ?>" href="<?php echo url(array('action' => 'setPublished', 'esb_postid' => $post->id, 'esb_published' => intval(!(isset($post->published) && $post->published)))); ?>"><?php _e('Published'); ?></a>
				<a class="btn default edit" href="<?php echo url(array('action' => 'loadContent', 'esb_file' => 'views/forms/post_'.$post->type.'.php', 'esb_postid' => $post->id)); ?>"><?php _e('button_edit'); ?></a>
				<a class="btn default delete" href="<?php echo url(array('action' => 'delete', 'esb_postid' => $post->id)); ?>"><?php _e('button_delete'); ?></a>
			</span>
		</div>
	<?php endforeach; ?>
	
	<?php if ($current_page != $pages) : ?>
		<div class="tile loader-wrap center">
			<a href="<?php echo url(array('action' => 'loadContent', 'esb_file' => 'views/settings/recent_posts.php', 'esb_page' => (isset($_REQUEST['esb_page']) ? intval($_REQUEST['esb_page'])+1 : 2))); ?>" class="btn default js-more-posts"><?php _e('More posts')?></a>
		</div>
	<?php endif; ?>
</div>
<?php else: ?>
<div>
	<p class="center"><?php _e('You don\'t have any posts yet. First create a post in the %s section.', '<a class="write-a-post" href="#">'.__('Write a post').'</a>'); ?></p>
</div>
<?php endif; ?>