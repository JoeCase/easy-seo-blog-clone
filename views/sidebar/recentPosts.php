<?php global $options, $post; ?>
<h2><?php echo isset($options['trtRecentPosts']) && $options['trtRecentPosts'] ? $options['trtRecentPosts'] : __('Recent Posts'); ?></h2>
<?php 
		$recPosts = loadPosts(1, $options['recentPostsCount'], true, array("published" => 1, "postdate" => date("Y-m-d H:i:s")), $user);
		if(sizeof($recPosts != 0)){?>
		<ul class="rec-posts"><?php 
		for ($i=0; $i<sizeof($recPosts); $i++) {
			?>
				
					<li class="rec-title">
						<a target="_top" href="<?php echo $GLOBALS['section-url'].$recPosts[$i]->slug; ?>" class="state"><?php echo $recPosts[$i]->title; ?></a>
					</li>			
				
			<?php	
		}
		?></ul><?php } ?>