<?php 
	require_once 'locale/localization.php';
	require_once 'actions/options.php';
	
	global $options;
?>

<h3><?php _e('Social Networks'); ?></h3>
<div class="feature social_networks">
	<!-- Social sharing -->
	<h4 class="lower-header">
		<?php _e('Social buttons'); ?>
	</h4>
	<ul class="list">
		<li>
			<div class="row-fluid multiline">
				<div class="span3">
					<label><?php _e('Show buttons:'); ?></label>
				</div>
				<div class="span4">
					<strong><?php _e('Feed View'); ?></strong><br/>
					<input type="checkbox" id="Settings_shareShowListTop" name="options[share_show_list_top]" value="1" <?php echo isset($options['share_show_list_top']) && $options['share_show_list_top'] ? 'checked="checked"' : ''; ?>/>
					<label for="Settings_shareShowListTop"><?php _e('Above Post'); ?></label>
					<br/>
					<input type="checkbox" id="Settings_shareShowListBottom" name="options[share_show_list_bottom]" value="1" <?php echo isset($options['share_show_list_bottom']) && $options['share_show_list_bottom'] ? 'checked="checked"' : ''; ?>/>
					<label for="Settings_shareShowListBottom"><?php _e('Below Post'); ?></label>
				</div>
				<div class="span5">
					<strong><?php _e('Post View'); ?></strong><br/>
					<input type="checkbox" id="Settings_shareShowDetailTop" name="options[share_show_detail_top]" value="1" <?php echo isset($options['share_show_detail_top']) && $options['share_show_detail_top'] ? 'checked="checked"' : ''; ?>/>
					<label for="Settings_shareShowDetailTop"><?php _e('Above Post'); ?></label>
					<br/>
					<input type="checkbox" id="Settings_shareShowDetailBottom" name="options[share_show_detail_bottom]" value="1" <?php echo isset($options['share_show_detail_bottom']) && $options['share_show_detail_bottom'] ? 'checked="checked"' : ''; ?>/>
					<label for="Settings_shareShowDetailBottom"><?php _e('Below Post'); ?></label>
				</div>
			</div>
		</li>
		<li>
			<div class="row-fluid multiline">
				<div class="span3">
					<label><?php _e('Shares counter:'); ?></label>
				</div>
				<div class="span9">
					<input type="radio" id="Settings_shareOrientation_None" name="options[share_orientation]" value="0" <?php echo !isset($options['share_orientation']) || !$options['share_orientation'] ? 'checked="checked"' : ''; ?>/>
					<label for="Settings_shareOrientation_None"><?php _e('Not shown'); ?></label>
					<br/>
					<input type="radio" id="Settings_shareOrientation_Horizontal" name="options[share_orientation]" value="1" <?php echo isset($options['share_orientation']) && $options['share_orientation'] == '1' ? 'checked="checked"' : ''; ?>/>
					<label for="Settings_shareOrientation_Horizontal"><?php _e('Show share counter'); ?></label>
				</div>
			</div>
		</li>
	</ul>
	<div class="social_networks_share_buttons">
		<?php include 'views/settings/social_networks_share_buttons.php';?>
	</div>
	<div class="" id="socialAuthorization">
		<?php //include 'social_authorization.php'; ?>
	</div>
</div>
<script>
	var refresh_share_buttons = 0;
	$('.social_networks').delegate('[name="options[share_orientation]"],[name="options[share_fb_text]"]', 'change', function() {
		refresh_share_buttons = 1;
	});
	$('[name="options[share_orientation]"]').closest('form').on('saved', function() {
		if (refresh_share_buttons == 1) {
			refresh_share_buttons = 2;
			$('.social_networks_share_buttons').load('<?php echo url(array('action' => 'loadContent', 'ajax' => 'true', 'esb_file' => 'views/settings/social_networks_share_buttons.php')); ?>', function() {
				refresh_share_buttons = 0;
			});
		}
	});
</script>