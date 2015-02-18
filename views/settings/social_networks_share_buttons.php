<?php
	require_once 'actions/options.php';

	global $options;
	
	if (!isset($options['share_order']) || !is_array($options['share_order']) ) {
		$options['share_order'] = array('fb', 'twitter', 'gplus', 'pinterest');
	}
?>
<p>
	<?php _e('Select which share buttons appear in your blog. Drag arrow to rearrange the order.'); ?>
</p>
<ul class="list share sortable <?php echo !isset($options['share_orientation']) || !$options['share_orientation'] ? 'without-counter' : ($options['share_orientation'] == 1 ? 'horizontal' : 'vertical'); ?>">
<?php foreach ($options['share_order'] as $shareBtn): ?>
	<?php if ($shareBtn === 'fb'): ?>
	<li id="share_fb">
		<div class="row-fluid clearfix">
			<div class="span1 drag-hanlde center">
				<i class="icon-move"></i>
			</div>
			<div class="span2">
				<input type="checkbox" id="Settings_shareFb" name="options[share_fb]" value="1" <?php echo isset($options['share_fb']) && $options['share_fb'] ? 'checked="checked"' : ''; ?>/>
				<label for="Settings_shareFb"><?php _e('Facebook'); ?></label>
			</div>
			<div class="span3">
				<div class="social-share-wrapper fb-wrapper  <?php echo isset($options['share_fb_text']) ?  $options['share_fb_text'] : ''; ?>">
					<div class="fb-like" data-href="http://www.teameasyapps.com" data-send="false" data-layout="<?php echo !isset($options['share_orientation']) || !$options['share_orientation'] || $options['share_orientation'] == 1 ? 'button_count' : 'box_count' ?>" data-width="450" data-show-faces="false" data-action="<?php echo isset($options['share_fb_text']) ? $options['share_fb_text'] : 'like'; ?>"></div>
				</div>
			</div>
			<div class="span2">
				<label for="Settings_shareFbText"><?php _e('Button text'); ?></label>
			</div>
			<div class="span4">
				<select id="Settings_shareFbText" name="options[share_fb_text]">
					<option value="like" <?php echo isset($options['share_fb_text']) && $options['share_fb_text'] === 'like' ? 'selected="selected"' : '' ?>>Like</option>
					<option value="recommend" <?php echo isset($options['share_fb_text']) && $options['share_fb_text'] === 'recommend' ? 'selected="selected"' : '' ?>>Recommend</option>
				</select>
			</div>
			
		</div>
	</li>
	<?php endif; ?>
	<?php if ($shareBtn === 'gplus'): ?>
	<li id="share_gplus">
		<div class="row-fluid">
			<div class="span1 drag-hanlde center">
				<i class="icon-move"></i>
			</div>
			<div class="span2">
				<input type="checkbox" id="Settings_shareGplus" name="options[share_gplus]" value="1" <?php echo isset($options['share_gplus']) && $options['share_gplus'] ? 'checked="checked"' : ''; ?>/>
				<label for="Settings_shareGplus"><?php _e('Google+'); ?></label>
			</div>
			<div class="span3">
				<div class="g-plusone" data-size="<?php echo !isset($options['share_orientation']) || !$options['share_orientation'] || $options['share_orientation'] == 1 ? 'medium' : 'tall'; ?>" data-annotation="<?php echo !isset($options['share_orientation']) || !$options['share_orientation'] ? 'none' : 'bubble'; ?>" data-href="http://www.teameasyapps.com"></div>
			</div>
		</div>
	</li>
	<?php endif; ?>
	<?php if ($shareBtn === 'twitter'): ?>
	<li id="share_twitter">
		<div class="row-fluid multiline">
			<div class="span1 drag-hanlde center">
				<i class="icon-move"></i>
			</div>
			<div class="span2">
				<input type="checkbox" id="Settings_shareTwitter" name="options[share_twitter]" value="1" <?php echo isset($options['share_twitter']) && $options['share_twitter'] ? 'checked="checked"' : ''; ?>/>
				<label for="Settings_shareTwitter"><?php _e('Twitter'); ?></label>
			</div>
			<div class="span3">
				<a href="https://twitter.com/share" class="twitter-share-button" data-count="<?php echo !isset($options['share_orientation']) || !$options['share_orientation'] ? 'none' : ($options['share_orientation'] == 1 ? 'horizontal' : 'vertical'); ?>" data-text="<?php _e('Easy SEO Blog can now share posts directly on social networks'); ?>" data-url="http://www.teameasyapps.com" data-hashtags="wix,easyseoblog">Tweet</a>
			</div>
		</div>
	</li>
	<?php endif; ?>
	<?php if ($shareBtn === 'pinterest'): ?>
	<li id="share_pinterest">
		<div class="row-fluid">
			<div class="span1 drag-hanlde center">
				<i class="icon-move"></i>
			</div>
			<div class="span2">
				<input type="checkbox" id="Settings_sharePinterest" name="options[share_pinterest]" value="1" <?php echo isset($options['share_pinterest']) && $options['share_pinterest'] ? 'checked="checked"' : ''; ?>/>
				<label for="Settings_sharePinterest"><?php _e('Pinterest'); ?></label>
			</div>
			<div class="span3">
				<a href="//pinterest.com/pin/create/button/?url=<?php echo urlencode('http://www.teameasyapps.com/'); ?>&media=<?php echo urlencode('http://static.wix.com/media/760379426b0a3f5e93e2107663ee5da339f4a0e1.png'); ?>&description=<?php echo urlencode(__('Easy SEO Blog can now share posts directly on social networks')); ?>" data-pin-do="buttonPin" data-pin-config="<?php echo !isset($options['share_orientation']) || !$options['share_orientation'] ? 'none' : ($options['share_orientation'] == 1 ? 'beside' : 'above') ?>">
					<img src="//assets.pinterest.com/images/pidgets/pin_it_button.png" />
				</a>
			</div>
			<div class="span6">
				<?php _e('Counter will be visible after first share.'); ?>
			</div>
		</div>
		<div class="row-fluid thin">
			<div class="offset1 span11">				
				<label for="Settings_sharePinterestDefaultImage"><?php _e('Pinterest default image (will be used if there is no image in a post)'); ?></label>
			</div>
		</div>
		<div class="row-fluid url-inputbar">
			<div class="offset1 span11">
				<span class="inputbar-wrap" style="padding-right: 240px;">
					<input id="Settings_sharePinterestDefaultImage" class="inputbar" type="text" style="padding-right: 240px;" placeholder="<?php _e('Write image URL'); ?>" name="options[share_pinterest_default_image_url]" value="<?php echo isset($options['share_pinterest_default_image_url']) ? $options['share_pinterest_default_image_url'] : ''; ?>"/>
				</span>
				<span class="url-barbuttons">
					<a class="btn default js-pinterest-image-gallery"><?php _e('wix_gallery'); ?></a>
					<a class="btn default js-image-filepicker <?php echo $options['is_premium'] ? '' : 'premium-features'; ?>"><?php _e('social_network'); ?></a>
				</span>
			</div>
		</div>
	</li>
	<?php endif; ?>
<?php endforeach; ?>
</ul>
<input type="hidden" name="options[share_order]" value="<?php echo isset($options['share_order']) ? 'share[]='.implode('&share[]=', $options['share_order']) : ''; ?>">
<script>
	// invoke later
	window.setTimeout(function() {
		// Load Share buttons APIs asynchronously 
		var addAsyncScript = function(d, s, id, url) {
			  var js, fjs = d.getElementsByTagName(s)[0];
			  if (d.getElementById(id)) return;
			  js = d.createElement(s); js.id = id; js.async=true;
			  js.src = url;
			  fjs.parentNode.insertBefore(js, fjs);
		};
		
		//Facebook
		if (typeof (FB) != 'undefined') {
			FB.XFBML.parse();
		} else {
			addAsyncScript(document, 'script', 'facebook-jssdk', '//connect.facebook.net/en_US/all.js#xfbml=1');
		}
		
		//Google+
		if (typeof (gapi) != 'undefined') {
	        gapi.plusone.go();
		} else {
			addAsyncScript(document, 'script', 'gplus-jssdk', '//apis.google.com/js/plusone.js');
		}
	
		//Twitter
		if (typeof (twttr) != 'undefined') {
		    twttr.widgets.load();
		} else {
			addAsyncScript(document, 'script', 'twitter-jssdk', '//platform.twitter.com/widgets.js');
		}
		
		// Pinterest 
		addAsyncScript(document, 'script', 'pinterest-jssdk', '//assets.pinterest.com/js/pinit.js');
	
		$('.sortable.share').sortable({
			handle: '.drag-hanlde',
			placeholder: 'drag-placeholder',
			update: function() {
				$('[name="options[share_order]"]').val($('.sortable.share').sortable( "serialize" )).change();
			}
		});
	
		$('.list.share .url-inputbar .js-pinterest-image-gallery').unbind('click').on('click', function() {
			var $btn = $(this);
			Wix.Settings.openMediaDialog(Wix.Settings.MediaType.IMAGE, false, function(data) {
				$btn.closest('.url-inputbar').find('[name="options[share_pinterest_default_image_url]"]').val(Wix.Utils.Media.getImageUrl(data.relativeUri)).change();
			});
		});
	
		if (typeof(initliazeFilepicker) == 'function') {
			initliazeFilepicker($('.list.share .js-image-filepicker'), $('.list.share input[name=url]'), { mimetypes: ['image/*'] } );
		}
	}, 1000);
	
</script>