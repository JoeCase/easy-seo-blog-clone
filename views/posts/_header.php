<?php 
	global $post;
?>
<div class="header">
	<?php if(!isset($GLOBALS['url']['slug'])): ?>
		<span class="title">
			<a target="_top" href="<?php echo $post->permalink; ?>" class="state"><?php echo $post->title ?></a>
		</span>
		
		<!-- Share buttons on list preview -->
		<?php if ($GLOBALS['options']['share_show_list_top']): ?>
			<?php include 'views/share_buttons.php'; ?>
		<?php endif; ?>
		
	<?php else: ?>
		<span class="title"><?php echo $post->title; ?></span>
		
		<!-- Share buttons on detail preview -->
		<?php if ($GLOBALS['options']['share_show_detail_top']): ?>
			<?php include 'views/share_buttons.php'; ?>
		<?php endif; ?>
		
	<?php endif; ?>
</div>