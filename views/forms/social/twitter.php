<?php 
	$twClass = ""; 
	if(!isset($GLOBALS['options']['twitterId']) || ($GLOBALS['options']['twitterId'] == null)){
		$twClass = "disabled";				
	}
?>
<span class="lower-header clearfix"><input id="tw_enable_share" <?php echo $twClass; ?> name="twEnable" type="checkbox" value="yes"><label for="tw_enable_share" class="social-share-enable <?php echo $twClass; ?>">Twitter</label></span>
<?php if(isset($GLOBALS['options']['twitterId']) && ($GLOBALS['options']['twitterId'] != null)) {?>		
<span class="share-settings tw-settings clearfix" style="display: none;">
	<textarea class="tw-share" name="twShare"><?php _e('I have new post !TITLE, so check it out !URL'); ?></textarea>		
	<span class="tw-counter"></span>
</span>
<?php } else { ?>
<span class="tw-message tw-authorize"><?php _e('You must be authorized to share post on Twitter.'); ?></span>	
<?php } ?>
