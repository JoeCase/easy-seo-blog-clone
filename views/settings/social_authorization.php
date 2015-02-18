<?php
	require_once 'actions/options.php'; 
	require_once 'locale/localization.php';
?>
	<h4 class="lower-header">
		<?php _e('Social Network Integration'); ?>
	</h4>
	<p class="subtitle">
		<?php _e('Share your posts to social networks to reach a bigger audience and further improve your search ranking (SEO). As an example, a shared post would show the title and have a link back to your site.'); ?>
	</p>
	<ul class="list social-rating">
		<li class="">
			<div id="fb-root"></div>
			<input type="hidden" id="fb-login-status" value="">
			<?php if($GLOBALS['options']['facebookId'] == null){ ?>
			<button id="facebook-login" type="button" class="btn default">
				<?php _e('Connect to Facebook'); ?>
			</button>
			<?php } else { ?>
			<button id="facebook-logout" type="button" class="btn default" data-fuid="<?php echo $GLOBALS['options']['facebookId'] ?>">
				<?php _e('Disconnect from Facebook'); ?>
			</button>
			<?php } ?>
			<p class="list description">
				<?php _e('Share your posts on Facebook'); ?>
			</p>
		</li>
		
		<li class="">
			<?php if($GLOBALS['options']['twitterId'] == null){ ?>
			<button id="twitter-login" type="button" class="btn default">
				<?php _e('Connect to Twitter'); ?>
			</button>
			<?php } else { ?>
			<button id="twitter-logout" type="button" class="btn default" data-fuid="<?php echo $GLOBALS['options']['twitterId'] ?>">
				<?php _e('Disconnect from Twitter'); ?>
			</button>
			<?php } ?>
			<p class="list description">
				<?php _e('Share your posts on Twitter'); ?>
			</p>
		</li>
	</ul>
