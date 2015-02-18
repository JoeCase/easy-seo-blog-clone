<?php 
	global $options;
?>

<h3><?php _e('Layouts and Pagination'); ?></h3>
<div class="feature">
	<h4 class="lower-header">
		<?php _e('Page Layout'); ?>
	</h4>
	<!-- 
	<p>
		<?php _e('Select the number of columns on your blog page (where your recent posts are shown). Choose from a regular single column blog feed or select up to four columns to layout your posts as a grid.'); ?>
	</p>
	 -->
	<div class="center">
		<div class="horizontal-options clearfix">

			<div class="feed option divider_right">
				<div class="choose_layout">
					<input type="radio" id="Settings_pageLayout_1" name="options[pageLayout]" required="required" value="feed" <?php echo $options['pageLayout'] === 'feed' ? 'checked="checked"' : ''; ?> />
					<label for="Settings_pageLayout_1" class=" required"><?php _e('Show feed'); ?></label>

				</div>
				<div class="layout_img">
					<img src="images/settings/layout/feed.png" alt="<?php _e('Feed layout'); ?>">
				</div>
				<p><?php _e('Last feeds'); ?></p>
				<div class="slider">
					<div class="layout_slider" id="lastPostsCount_slider"></div>
					<div class="count">
						<input type="text" id="Settings_lastPostsCount" name="options[lastPostsCount]" required="required" value="<?php echo $options['lastPostsCount']; ?>" />
						<label for="Settings_lastPostsCount" class=" required"><?php _e('Last feeds'); ?></label>
					</div>
				</div>
			</div> 

			<div class="onepage option ">
				<div class="choose_layout">
					<input type="radio" id="Settings_pageLayout_2" name="options[pageLayout]" required="required" value="post" <?php echo $options['pageLayout'] === 'post' ? 'checked="checked"' : ''; ?> />
					<label for="Settings_pageLayout_2" class=" required"><?php _e('Show one post'); ?></label>

				</div>
				<div class="layout_img">
					<img src="images/settings/layout/single.png" alt="<?php _e('Post layout'); ?>">
				</div>
			</div>
		
			<div class="grid option divider_left">
				<div class="choose_layout">
					<input type="radio" id="Settings_pageLayout_0" name="options[pageLayout]" required="required" value="grid" <?php echo $options['pageLayout'] === 'grid' ? 'checked="checked"' : ''; ?> />
					<label for="Settings_pageLayout_0" class=" required"><?php _e('Show grid'); ?></label>
				</div>
				<div class="layout_img">
					<img src="images/settings/layout/grid.png" alt="<?php _e('Grid layout'); ?>">
				</div>
				<p><?php _e('Columns'); ?></p>
				<div class="slider">
					<div class="layout_slider" id="gridColumns_slider"></div>
					<div class="count">
						<input type="text" id="Settings_gridColumns" name="options[pageColumns]" required="required" value="<?php echo $options['pageColumns']; ?>" />
						<label for="Settings_gridColumns" class=" required"><?php _e('Columns'); ?></label>

					</div>
				</div>
				<p><?php _e('Rows'); ?></p>
				<div class="slider">
					<div class="layout_slider" id="gridRows_slider"></div>
					<div class="count">
						<input type="text" id="Settings_gridRows" name="options[pageRows]" required="required" value="<?php echo $options['pageRows']; ?>" />
						<label for="Settings_gridRows" class=" required"><?php _e('Rows'); ?></label>

					</div>
				</div>
			</div>
			
			<!-- Pinterest layout -->
			<div class="pinterest option divider_left">
				<div class="choose_layout">
					<input type="radio" id="Settings_pageLayout_3" name="options[pageLayout]" required="required" value="pinterest" <?php echo $options['pageLayout'] === 'pinterest' ? 'checked="checked"' : ''; ?> />
					<label for="Settings_pageLayout_3" class=" required"><?php _e('Pinterest like'); ?></label>
				</div>
				<div class="layout_img">
					<img src="images/settings/layout/pinterest.png" alt="<?php _e('Pinterest like layout'); ?>">
				</div>
				<p><?php _e('Columns'); ?></p>
				<div class="slider">
					<div class="layout_slider" id="pinterestColumns_slider"></div>
					<div class="count">
						<input type="text" id="Settings_pinterestColumns" name="options[pinterestColumns]" required="required" value="<?php echo $options['pinterestColumns']; ?>" />
						<label for="Settings_pinterestColumns" class=" required"><?php _e('Columns'); ?></label>
					</div>
				</div>
				<p><?php _e('Rows'); ?></p>
				<div class="slider">
					<div class="layout_slider" id="pinterestRows_slider"></div>
					<div class="count">
						<input type="text" id="Settings_pinterestRows" name="options[pinterestRows]" required="required" value="<?php echo $options['pinterestRows']; ?>" />
						<label for="Settings_pinterestRows" class=" required"><?php _e('Rows'); ?></label>
					</div>
				</div>
			</div>
		</div>
	</div>
	<h4 class="lower-header">
		<?php _e('Mobile Page Layout'); ?>
	</h4>	 
	<p>
		<?php _e('Mobile page layout is set to feed from default.'); ?>
	</p>
	<div class="left">
		<div class="horizontal-options clearfix">
			<div class="feed option">
				<p><?php _e('Last feeds'); ?></p>
				<div class="slider">
					<div class="layout_slider" id="mobilePostsCount_slider"></div>
					<div class="count">
						<input type="text" id="Settings_mobilePostsCount" name="options[mobilePostsCount]" required="required" value="<?php echo $options['mobilePostsCount']; ?>" />
						<label for="Settings_mobilePostsCount" class=" required"><?php _e('Last feeds'); ?></label>
					</div>
				</div>
			</div>
		</div>
	</div>  
	
	
	
	<h4 class="lower-header">
		<?php _e('Pagination Customization'); ?>
	</h4>
	<p>	
	<?php _e('Pagination Description'); ?>
	</p>
	<ul class="list pagination_setting">
		<li>
			<div>
				<input class="list checkbox-pagination" type="checkbox"
					id="Settings_showNextPrevious"
					name="options[paginationShowNextPrev]" value="1"
					<?php echo $options['paginationShowNextPrev'] ? 'checked="checked"' : ''; ?> />
				<label class="checkbox-label" for="Settings_showNextPrevious"><?php _e('Show labels for "Next" and "Previous" page'); ?>
				</label>
			</div>
			<span class="inputbar-wrap">
				<input class="inputbar" type="text" placeholder="<?php _e('prevPage'); ?>" name="options[prevPage]" value="<?php echo (isset($options['prevPage']) ? $options['prevPage']: ''); ?>"/>
			</span>
			<span class="inputbar-wrap">
				<input class="inputbar" type="text" placeholder="<?php _e('nextPage'); ?>" name="options[nextPage]" value="<?php echo (isset($options['nextPage']) ? $options['nextPage']: ''); ?>"/>
			</span>
		</li>
		<li>
			<div>
				<input class="list checkbox-pagination" type="checkbox"
					id="Settings_showNextPreviousPost"
					name="options[paginationShowNextPrevPost]" value="1"
					<?php echo $options['paginationShowNextPrevPost'] ? 'checked="checked"' : ''; ?> />
				<label class="checkbox-label" for="Settings_showNextPreviousPost"><?php _e('Show labels for "Next" and "Previous" post'); ?>
				</label>
			</div>
			<span class="inputbar-wrap">
				<input class="inputbar" type="text" placeholder="<?php _e('nextPost'); ?>" name="options[nextPost]" value="<?php echo (isset($options['nextPost']) ? $options['nextPost']: ''); ?>"/>
			</span>
			<span class="inputbar-wrap">
				<input class="inputbar" type="text" placeholder="<?php _e('prevPost'); ?>" name="options[prevPost]" value="<?php echo (isset($options['prevPost']) ? $options['prevPost']: ''); ?>"/>
			</span>
		</li>
		<li class="property">
			<div>
				<input class="list checkbox-pagination" type="checkbox"
					id="Settings_paginationShowNumbers" name="options[paginationShowNumbers]"
					value="1"
					<?php echo $options['paginationShowNumbers'] ? 'checked="checked"' : ''; ?> />
				<label class="checkbox-label" for="Settings_paginationShowNumbers"><?php _e('Show numbers to jump pages'); ?>
				</label>
				<div class="slider">
					<div id="paginationNumbersCount_slider"></div>
					<div class="count">
						<input type="text" id="Settings_paginationNumbersCount"
							name="options[paginationNumbersCount]"
							value="<?php echo $options['paginationNumbersCount']; ?>" />
					</div>
				</div>
			</div>
		</li>
		<li class="property">
			<div>
				<input class="list checkbox-pagination" type="checkbox"
					id="Settings_paginationShowFirstLast"
					name="options[paginationShowFirstLast]" value="1"
					<?php echo $options['paginationShowFirstLast'] ? 'checked="checked"' : ''; ?> />
				<label class="checkbox-label" for="Settings_paginationShowFirstLast"><?php _e('Show labels for "First" and "Last" page'); ?>
				</label>
			</div>
			<span class="inputbar-wrap">
				<input class="inputbar" type="text" placeholder="<?php _e('firstPage'); ?>" name="options[firstPage]" value="<?php echo (isset($options['firstPage']) ? $options['firstPage']: ''); ?>"/>
			</span>
			<span class="inputbar-wrap">
				<input class="inputbar" type="text" placeholder="<?php _e('lastPage'); ?>" name="options[lastPage]" value="<?php echo (isset($options['lastPage']) ? $options['lastPage']: ''); ?>"/>
			</span>
		</li>
	</ul>
</div>

