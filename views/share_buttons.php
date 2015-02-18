<?php
	require_once 'actions/options.php';
	require_once 'actions/functions.php';
	
	global $options, $post;
	$order = $options['share_order'];
	if (!is_array($order)) {
		$order = array('fb', 'twitter', 'gplus', 'pinterest');
	}
?>

<div class="clearfix <?php echo !isset($options['share_orientation']) || !$options['share_orientation'] ? 'without-counter' : ($options['share_orientation'] == 1 ? 'horizontal' : 'vertical'); ?>">
<?php foreach ($order as $shareBtn): ?>
	<?php if ($shareBtn === 'fb' && $options['share_fb']): ?>
			<div class="social-share-wrapper fb-wrapper  <?php echo isset($options['share_fb_text']) ?  $options['share_fb_text'] : ''; ?>">
				<div class="fb-like" data-href="<?php echo $post->permalink; ?>" data-send="false" data-layout="<?php echo !isset($options['share_orientation']) || !$options['share_orientation'] || $options['share_orientation'] == 1 ? 'button_count' : 'box_count' ?>" data-width="450" data-show-faces="false" data-action="<?php echo isset($options['share_fb_text']) ? $options['share_fb_text'] : 'like'; ?>"></div>
			</div>
	<?php endif; ?>
	<?php if ($shareBtn === 'gplus' && $options['share_gplus']): ?>
			<div class="social-share-wrapper gplus-wrapper">
				<div class="g-plusone" data-size="<?php echo !isset($options['share_orientation']) || !$options['share_orientation'] || $options['share_orientation'] == 1 ? 'medium' : 'tall'; ?>" data-annotation="<?php echo !isset($options['share_orientation']) || !$options['share_orientation'] ? 'none' : 'bubble'; ?>" data-href="<?php echo $post->permalink; ?>"></div>
			</div>
	<?php endif; ?>
	<?php if ($shareBtn === 'twitter' && $options['share_twitter']): ?>
			<div class="social-share-wrapper tweet-wrapper">
				<a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php $twitterlink = explode('#!', $post->permalink); echo (isset($twitterlink[0]) ? $twitterlink[0] : $post->permalink) . '?easyseoblog=post-'.$post->id. (isset($twitterlink[1]) ? '#!'.str_replace('-', '%2D',$twitterlink[1]) : '');  ?>" data-counturl="<?php $twitterlink = explode('#!', $post->permalink); echo (isset($twitterlink[0]) ? $twitterlink[0] : $post->permalink) . '?easyseoblog=post-'.$post->id. (isset($twitterlink[1]) ? '#!'.str_replace('-', '%2D',$twitterlink[1]) : '');  ?>" data-count="<?php echo !isset($options['share_orientation']) || !$options['share_orientation'] ? 'none' : ($options['share_orientation'] == 1 ? 'horizontal' : 'vertical'); ?>" data-text="<?php echo $post->title; ?>">Tweet</a>
			</div>
	<?php endif; ?>
	<?php if ($shareBtn === 'pinterest' && $options['share_pinterest']): ?>
			<?php 
				$pinterest_thumb = $post->url;
				if(!$pinterest_thumb) {
					$out = preg_match_all( '/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->content, $match);
					if ( $out > 0 ){
						$pinterest_thumb = $match[1][0];
					} else {
						$pinterest_thumb = $options['share_pinterest_default_image_url'];
					}
				}
			?>
			<div class="social-share-wrapper pinterest-wrapper">
				<a href="//pinterest.com/pin/create/button/?url=<?php echo urlencode($post->permalink); ?>&media=<?php echo urlencode($pinterest_thumb); ?>&description=<?php echo urlencode(replace_post_vars(__('!TITLE on !URL'), $post)); ?>" data-pin-do="buttonPin" data-pin-config="<?php echo !isset($options['share_orientation']) || !$options['share_orientation'] ? 'none' : ($options['share_orientation'] == 1 ? 'beside' : 'above') ?>"><img src="//assets.pinterest.com/images/pidgets/pin_it_button.png" /></a>
			</div>
	<?php endif; ?>
<?php endforeach; ?>
</div>

