<?php
	require_once 'actions/options.php'; 
	require_once 'locale/localization.php';
?>
<h3><?php _e('Other settings'); ?></h3>
<div class="feature">
	<h4 class="lower-header">
		<?php _e('Date Formatting'); ?>
	</h4>
	<p>
		<select name="options[dateFormat]" class="select date-select"
			id="date_format">
			<?php 
				$first = strtotime('first day of January'); $last = strtotime('last day of December'); $date_formats = array_filter(explode('|', __('date_formats'))); ?>
			<?php foreach ($date_formats as $format): ?>
			<option id="date_format_option" value="<?php echo $format; ?>"
			<?php echo ($format == $GLOBALS['options']['dateFormat'] ? 'selected="selected"' : ''); ?>>
				<?php echo date($format, $first) .' - '.date($format, $last); ?>
			</option>
			<?php endforeach; ?>
		</select>
	</p>
	<br clear="all" />
	<p class="subtitle">
		<?php _e('Select how you want the date of each post to be represented, from the dropdown menu above.'); ?>
	</p>

	<!-- Dates -->
	<h4 class="lower-header">
		<?php _e('Dates visibility'); ?>
	</h4>
	<div>
		<ul class="list dates-settings">
			<li style="width:48%; float:left;">
				<input class="list checkbox-translation" type="checkbox"
					id="Settings_showDatesFeed"
					name="options[showDatesFeed]" value="1"
					<?php echo $options['showDatesFeed'] ? 'checked="checked"' : ''; ?> />
				<label class="checkbox-label" for="Settings_showDatesFeed"><?php _e('Show dates in feed:'); ?></label>
			</li>
    	<li style="width:48%; float:right;">
				<input class="list checkbox-translation" type="checkbox"
					id="Settings_showDatesFeedMobile"
					name="options[showDatesFeedMobile]" value="1"
					<?php echo $options['showDatesFeedMobile'] ? 'checked="checked"' : ''; ?> />
				<label class="checkbox-label" for="Settings_showDatesFeedMobile"><?php _e('Show dates in feed(Mobile):'); ?></label>
			</li>
    </ul>
    <ul class="list dates-settings no-border">
			<li style="width:48%; float:left;">
				<input class="list checkbox-translation" type="checkbox"
					id="Settings_showDatesDetail"
					name="options[showDatesDetail]" value="1"
					<?php echo $options['showDatesDetail'] ? 'checked="checked"' : ''; ?> />
				<label class="checkbox-label" for="Settings_showDatesDetail"><?php _e('Show date in detail:'); ?></label>
			</li>
			<li style="width:48%; float:right;">
				<input class="list checkbox-translation" type="checkbox"
					id="Settings_showDatesDetailMobile"
					name="options[showDatesDetailMobile]" value="1"
					<?php echo $options['showDatesDetailMobile'] ? 'checked="checked"' : ''; ?> />
				<label class="checkbox-label" for="Settings_showDatesDetailMobile"><?php _e('Show date in detail(Mobile):'); ?></label>
			</li>      
		</ul>
	</div>  
	
	<!-- Translations -->
	<h4 class="lower-header">
		<?php _e('Translations'); ?>
	</h4>
	<div>
		<ul class="list translation_setting">
			<li>
				<input class="list checkbox-translation" type="checkbox"
					id="Settings_showDisplayByTag"
					name="options[showDisplayByTag]" value="1"
					<?php echo $options['showDisplayByTag'] ? 'checked="checked"' : ''; ?> />
				<label class="checkbox-label" for="Settings_showDisplayByTag"><?php _e('Display by tag:'); ?></label>
				<span class="inputbar-wrap">
					<input id="displayByTag" class="inputbar" type="text" placeholder="<?php _e('Display by tag:'); ?>" name="options[displayByTag]" value="<?php echo (isset($GLOBALS['options']['displayByTag']) ? $GLOBALS['options']['displayByTag']: ''); ?>"/>
				</span>
			</li>
			<li>
				<input class="list checkbox-translation" type="checkbox"
					id="Settings_showBackToFeed"
					name="options[showBackToFeed]" value="1"
					<?php echo $options['showBackToFeed'] ? 'checked="checked"' : ''; ?> />
				<label class="checkbox-label" for="Settings_showBackToFeed"><?php _e('Back to feed:'); ?></label>
				<span class="inputbar-wrap">					
					<input id="backToFeed" class="inputbar" type="text" placeholder="<?php _e('Back to feed:'); ?>" name="options[backToFeed]" value="<?php echo (isset($GLOBALS['options']['backToFeed']) ? $GLOBALS['options']['backToFeed']: ''); ?>"/>
				</span>
			</li>
			<li>
        <input class="list checkbox-translation" type="checkbox"
					id="Settings_showReadMore"
					name="options[showReadMore]" value="1"
					<?php echo $options['showReadMore'] ? 'checked="checked"' : ''; ?> />
				<label class="checkbox-label" for="Settings_showReadMore"><?php _e('Read more:'); ?></label>
				<span class="inputbar-wrap">					
					<input id="readMore" class="inputbar" type="text" placeholder="<?php _e('Read more >>'); ?>" name="options[trtReadMore]" value="<?php echo (isset($GLOBALS['options']['trtReadMore']) ? $GLOBALS['options']['trtReadMore']: ''); ?>"/>
				</span>
			</li>
		</ul>
	</div>  
</div>
