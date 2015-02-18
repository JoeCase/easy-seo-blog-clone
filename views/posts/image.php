<?php 
	global $options, $post;
?>

<div class="wrapper">
	<div class="post photo">
		
		<?php include 'views/posts/_header.php'; ?>
		
		<!-- Post layout's -->
		<div class="clearfix content">
			<?php 
				$content = $post->content;
				$readMore = false;
				if(!isset($GLOBALS['url']['slug']) && (strpos($content, "<!--more-->")!= false)){
					$contentArr = explode("<!--more-->",$post->content);
					$content = $prf->purify($contentArr[0]);
					$readMore = true;
				} 
			?>
			<?php if(strncmp($post->layout, "reverse", 7) != 0): ?>
				<img class="photo" src="<?php echo $post->url; ?>" />
				<div class="caption"><?php echo $content; ?>
				<?php if($readMore && $options['showReadMore']) {
					?><a target="_top" href="<?php echo $post->permalink; ?>" class="state read_more"><?php echo isset($options['trtReadMore']) && $options['trtReadMore'] ? $options['trtReadMore'] : __('Read More >>'); ?></a><?php
				}?></div>
			<?php else: ?>
				<div class="caption"><?php echo $content; ?>
				<?php if($readMore && $options['showReadMore']) {
					?><a target="_top" href="<?php echo $post->permalink; ?>" class="state read_more"><?php echo isset($options['trtReadMore']) && $options['trtReadMore'] ? $options['trtReadMore'] : __('Read More >>'); ?></a><?php
				}?></div>
				<img class="photo" src="<?php echo $post->url; ?>" />
			<?php endif; ?>
		</div>
		
		<?php include 'views/posts/_footer.php'; ?>
		
	</div>
</div>
