<?php 
	global $options, $post, $prf;
?>

<div class="wrapper">
	<div class="post text">
	
	
		<?php include 'views/posts/_header.php'; ?>

		<div class="content clearfix">
			<div class="caption">
				<?php 
					$content = $post->content;
					$readMore = false;
					if(!isset($GLOBALS['url']['slug']) && (strpos($content, "<!--more-->")!= false)){
						$contentArr = explode("<!--more-->",$post->content);
						$content = $prf->purify($contentArr[0]);
						$readMore = true;
					} 
					echo $content;
					if($readMore && $options['showReadMore']) {
						?><a target="_top" href="<?php echo $post->permalink; ?>" class="state read_more"><?php echo isset($options['trtReadMore']) && $options['trtReadMore'] ? $options['trtReadMore'] : __('Read More >>'); ?></a><?php
					}
				?>
			</div>
		</div>
		
		<?php include 'views/posts/_footer.php'; ?>
		
	</div>
</div>