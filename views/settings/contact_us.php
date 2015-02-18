<?php 
	global $options;
?>
<h3><?php _e('Contact Easy Apps'); ?></h3>
<div class="feature">
	<div>
		<iframe src="http://easyblog.support.cuptech.eu/support/?user=<?php echo urlencode($options['user']);?>&baseUrl=<?php echo urlencode($options['baseUrl']); ?>&product_id=<?php echo urlencode($options['vendorProductId']);?>&locale=<?php echo $_REQUEST['locale']; ?>" width="100%" height="500"></iframe>
	</div>
</div>