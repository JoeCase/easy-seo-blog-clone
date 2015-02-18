<?php global $options, $post; ?>
<h2><?php echo isset($options['trtTagCloud']) && $options['trtTagCloud'] ? $options['trtTagCloud'] : __('Tag cloud'); ?></h2>
<?php 
		?><span class="tag-cloud"><?php
		$tags = loadDataForTagCloud(null,$user);
		$maximum = 0;
		for ($i=0; $i<sizeof($tags); $i++){
		    $counter = $tags[$i]->count;
		    		 
		    // update $maximum if this term is more popular than the previous terms
		    if ($counter > $maximum) $maximum = $counter;
		}
		shuffle($tags);
		foreach ($tags as $tag) {
			$percent = floor(($tag->count / $maximum) * 100);
			if ($percent < 20): 
				$class = 'smallest'; 
			elseif ($percent >= 20 and $percent < 40):
			    $class = 'small'; 
			elseif ($percent >= 40 and $percent < 60):
			    $class = 'medium';
			elseif ($percent >= 60 and $percent < 80):
			    $class = 'large';
			else:
				$class = 'largest';
			endif;
			?>
				<span class="<?php echo $class; ?>">
					<a target="_top" href="<?php echo $GLOBALS['section-url']."tag/".$tag->slug; ?>" class="state"><?php echo $tag->name; ?></a>
				</span>			
			<?php	
		}
		?></span><?php
	/*}*/
?>