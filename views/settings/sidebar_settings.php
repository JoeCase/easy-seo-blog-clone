<?php 
	global $options;
?>

<h3><?php _e('Sidebar settings'); ?></h3>
<div class="feature">
	<h4 class="lower-header">
		<?php _e('Default sidebar settings'); ?>
	</h4>
	<ul class="list sidebar_setting">
		<li>
			<div class="width_full">
				<input class="list checkbox-sidebar" type="checkbox"
					id="Settings_showSidebar"
					name="options[showSidebar]" value="1"
					<?php echo $options['showSidebar'] ? 'checked="checked"' : ''; ?> />
				<label class="checkbox-label" for="Settings_showSidebar"><?php _e('Enable sidebar'); ?>
				</label>
			</div>
		</li>
		<li>
			<div class="width_full">
				<?php _e('Sidebar divider'); ?>
			</div>
			<div class="width_full">
				<input class="list checkbox-sidebar" type="checkbox"
					id="Settings_enableSidebarDivider"
					name="options[enableSidebarDivider]" value="1"
					<?php echo $options['enableSidebarDivider'] ? 'checked="checked"' : ''; ?> />
				<label class="checkbox-label width_full" for="Settings_enableSidebarDivider"><?php _e('Enable sidebar divider'); ?>
				</label>
			</div>
			<div class="sidebarDividerThicknes width_full">
				<label for="Settings_sidebarDividerColor" class=" required"><?php _e('Color'); ?></label>
				<input type="text" id="Settings_sidebarDividerColor" name="options[sidebarDividerColor]" required="required" value="<?php echo $options['sidebarDividerColor']; ?>"/>
				<input type="checkbox" id="Settings_sidebarDividerColorOpacity" name="options[sidebarDividerColorOpacity]" value="1"  <?php echo $options['sidebarDividerColorOpacity'] ? 'checked="checked"' : ''; ?>/>
				<label for="Settings_sidebarDividerColorOpacity"><?php _e('Opacity'); ?></label>
				<div class="slider dividerOpacity">
					<div id="sidebarDividerColorOpacityValue_slider"></div>
					<div class="count">
						<input type="text" id="Settings_sidebarDividerColorOpacityValue" name="options[sidebarDividerColorOpacityValue]" value="<?php echo $options['sidebarDividerColorOpacityValue']; ?>" />
					</div>
				</div>
				<label class="sidebarDividerThicknesLabel" for="Settings_sidebarDividerThicknes"><?php _e('Thicknes'); ?></label>
				<div class="slider">
					<div id="sidebarDividerThicknes_slider"></div>
					<div class="count">
						<input type="text" id="Settings_sidebarDividerThicknes" name="options[sidebarDividerThicknes]" value="<?php echo $options['sidebarDividerThicknes']; ?>" />
					</div>
				</div>
			</div>
		</li>
	</ul>
	
	<h4 class="lower-header">
		<?php _e('Widgets'); ?>
	</h4>
	<p>	
		<?php _e('Recent posts'); ?>
	</p>
	<ul class="list widgets_setting ">
		<li class="recent_posts">
			<div class="width_full">
				<input class="list checkbox-pagination" type="checkbox"
					id="Settings_showRecentPosts"
					name="options[showRecentPosts]" value="1"
					<?php echo $options['showRecentPosts'] ? 'checked="checked"' : ''; ?> />
				<label class="checkbox-label" for="Settings_showRecentPosts"><?php _e('Show "Recent posts" widget'); ?>
				</label>
			</div>
			<div class="width_full">
				<label class="recentPostsCountLabel" for="Settings_recentPostsCount"><?php _e('Number of posts to show'); ?></label>
				<div class="slider">
						<div id="recentPostsCount_slider"></div>
						<div class="count numberOfPosts">
							<input type="text" id="Settings_recentPostsCount" name="options[recentPostsCount]" value="<?php echo $options['recentPostsCount']; ?>" />							
						</div>
				</div>
			</div>
			<div class="width_full trt">
				<label class="" for="trtRecentPosts"><?php _e('Title translation'); ?></label>
				<span class="inputbar-wrap">
					<input id="trtRecentPosts" class="inputbar placeholder" type="text" placeholder="<?php _e('Recent posts'); ?>" name="options[trtRecentPosts]" value="<?php echo (isset($options['trtRecentPosts']) ? $options['trtRecentPosts']: _e('Recent posts')); ?>"/>
				</span>
			</div>
		</li>
	 	<li class="tag_cloud">
	 		<div class="width_full">
				<?php _e('Tag cloud'); ?>
			</div>
			<div>
				<input class="list checkbox-pagination" type="checkbox"
					id="Settings_showTagCloud"
					name="options[showTagCloud]" value="1"
					<?php echo $options['showTagCloud'] ? 'checked="checked"' : ''; ?> />
				<label class="checkbox-label" for="Settings_showTagCloud"><?php _e('Show "Tag cloud" widget'); ?>
				</label>
			</div>
			<div class="width_full trt">
				<label class="" for="trtTagCloud"><?php _e('Title translation'); ?></label>
				<span class="inputbar-wrap">
					<input id="trtTagCloud" class="inputbar placeholder" type="text" placeholder="<?php _e('Tag cloud'); ?>" name="options[trtTagCloud]" value="<?php echo (isset($options['trtTagCloud']) ? $options['trtTagCloud']: _e('Tag cloud')); ?>"/>
				</span>
			</div>
		</li>
	</ul> 
</div>

