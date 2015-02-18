<?php 
	$fbClass = ""; 
	if(!isset($GLOBALS['options']['facebookId']) || ($GLOBALS['options']['facebookId'] == null)){
		$fbClass = "disabled";				
	}
?>	
<span class="lower-header clearfix"><input id="fb_enable_share" <?php echo $fbClass; ?> name="fbEnable" type="checkbox" value="yes"><label for="fb_enable_share" class="social-share-enable <?php echo $fbClass; ?>">Facebook</label></span>
<?php if(isset($GLOBALS['options']['facebookId']) && ($GLOBALS['options']['facebookId'] != null)) {?>
	<span class="share-settings fb-settings clearfix" style="display: none;">
		<textarea class="fb-share" name="fbShare"><?php _e('I have new post !TITLE, so check it out !URL'); ?></textarea>
		<span class="fb-checkboxs"></span>
	</span>	
<?php } else { ?>
		<span class="fb-message fb-authorize" style="display:none;"><?php echo 'You must be authorized to share post on Facebook.'; ?></span>
<?php } ?>
	<span class="fb-message fb-login" style="display:none;"><?php echo 'You must be <span id="log-to-fb">LOG IN</span> to share post on Facebook.'; ?></span>