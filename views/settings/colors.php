<h3><?php _e('Colors and Fonts'); ?></h3>
<div class="feature">
	<h4 class="lower-header"><?php _e('Customize your blog'); ?></h4>
	<p>	
		<?php _e('Customize the colors of your blog to get your personal look and feel'); ?>
	</p>
	<ul class="list color_setting">
		<li>
			<div>
				<label for="Settings_feedBgColor" class=" required"><?php _e('Background Color'); ?></label>
				<input type="text" id="Settings_feedBgColor" name="options[bgColor]" required="required" value="<?php echo $options['bgColor']; ?>"/>
				<input type="checkbox" id="Settings_feedBgOpacity" name="options[bgOpacity]" value="1" <?php echo $options['bgOpacity'] ? 'checked="checked"' : ''; ?>/>
				<label for="Settings_feedBgOpacity"><?php _e('Opacity'); ?></label>
				<div class="slider">
					<div id="feedBgOpacityValue_slider"></div>
					<div class="count">
						<input type="text" id="Settings_feedBgOpacityValue" name="options[bgOpacityValue]" value="<?php echo $options['bgOpacityValue']; ?>" />
					</div>
				</div>
			</div>
		</li>
		<li>
			<div>
				<label for="Settings_postsBgColor" class=" required"><?php _e('Posts Background Color'); ?></label>
				<input type="text" id="Settings_postsBgColor" name="options[postBgColor]" required="required" value="<?php echo $options['postBgColor']; ?>" />


				<input type="checkbox" id="Settings_postsBgOpacity" name="options[postBgOpacity]" value="1"  <?php echo $options['postBgOpacity'] ? 'checked="checked"' : ''; ?>/>
				<label for="Settings_postsBgOpacity"><?php _e('Opacity'); ?></label>


				<div class="slider">
					<div id="postsBgOpacityValue_slider"></div>
					<div class="count">
						<input type="text" id="Settings_postsBgOpacityValue" name="options[postBgOpacityValue]" value="<?php echo $options['postBgOpacityValue']; ?>" />
					</div>
				</div>
			</div>
		</li>
		<li>
			<div>
				<label for="Settings_borderColor" class=" required"><?php _e('Posts border color'); ?></label>
				<input type="text" id="Settings_borderColor" name="options[borderColor]" required="required" value="<?php echo $options['borderColor']; ?>" />
				
				<label for="Settings_borderThickness" class=" required"><?php _e('Border thickness')?></label>
				<div class="slider">
					<div id="borderThickness_slider"></div>
					<div class="count">
						<input type="text" id="Settings_borderThickness" name="options[borderThickness]" value="<?php echo $options['borderThickness']; ?>" />
					</div>
				</div>
			</div>                    
		</li>
	</ul>
	<h4 class="lower-header"><?php _e('Posts font settings'); ?></h4>
	<p>
		<?php _e('Adjust font colors and sizes of all text elements of your blog'); ?>
	</p>
	<ul class="list color_setting fonts">
		<li class="titles">
			<div class="first"></div>
			<div class="second"><?php _e('Color'); ?></div>
			<div class="third"><?php _e('Layout font size'); ?></div>
			<div class="fourth"><?php _e('Detail font size'); ?></div>
			<div class="fifth"><?php _e('Font family'); ?></div>
		</li>
		<li class="fontSettings">
			<div>
				<label for="Settings_titleColor" class=" required"><?php _e('Post Title'); ?></label>
				<input type="text" id="Settings_titleColor" name="options[titleColor]" required="required" value="<?php echo $options['titleColor']; ?>" />
				
				<div class="slider">
					<div id="titleFontSize_slider"></div>
					<div class="count">
						<input type="text" id="Settings_titleFontSize" name="options[titleFontSize]" value="<?php echo $options['titleFontSize']; ?>" />
						<div class="unit"><?php _e('px'); ?></div>
					</div>
				</div>		
				<div class="slider">
					<div id="titleSingleFontSize_slider"></div>
					<div class="count">
						<input type="text" id="Settings_titleSingleFontSize" name="options[titleSingleFontSize]" value="<?php echo $options['titleSingleFontSize']; ?>" />
						<div class="unit"><?php _e('px'); ?></div>
					</div>
				</div>		
				<?php echo loadFontsSelect($options['fonts'], $options['titleFontFamily'], "titleFontFamily"); ?>
			</div>
		</li>
		<li class="fontSettings">
			<div>
				<label for="Settings_textColor" class=" required"><?php _e('Post Body Text'); ?></label>
				<input type="text" id="Settings_textColor" name="options[textColor]" required="required" value="<?php echo $options['textColor']; ?>" />
				<div class="slider">
					<div id="textFontSize_slider"></div>
					<div class="count">
						<input type="text" id="Settings_textFontSize" name="options[textFontSize]" value="<?php echo $options['textFontSize']; ?>" />
						<div class="unit"><?php _e('px'); ?></div>
					</div>
				</div>
				<div class="slider">
					<div id="textSingleFontSize_slider"></div>
					<div class="count">
						<input type="text" id="Settings_textSingleFontSize" name="options[textSingleFontSize]" value="<?php echo $options['textSingleFontSize']; ?>" />
						<div class="unit"><?php _e('px'); ?></div>
					</div>
				</div>
				<?php echo loadFontsSelect($options['fonts'], $options['textFontFamily'], "textFontFamily"); ?>
			</div>
		</li>
		<li class="fontSettings">
			<div>
				<label for="Settings_linksColor" class=" required"><?php _e('Link post'); ?></label>
				<input type="text" id="Settings_linksColor" name="options[linksColor]" required="required" value="<?php echo $options['linksColor']; ?>" />
				<div class="slider">
					<div id="linksFontSize_slider"></div>
					<div class="count">
						<input type="text" id="Settings_linksFontSize" name="options[linksFontSize]" value="<?php echo $options['linksFontSize']; ?>" />
						<div class="unit"><?php _e('px'); ?></div>
					</div>
				</div>
				<div class="slider">
					<div id="linksSingleFontSize_slider"></div>
					<div class="count">
						<input type="text" id="Settings_linksSingleFontSize" name="options[linksSingleFontSize]" value="<?php echo $options['linksSingleFontSize']; ?>" />
						<div class="unit"><?php _e('px'); ?></div>
					</div>
				</div>	
				<?php echo loadFontsSelect($options['fonts'], $options['linksFontFamily'], "linksFontFamily"); ?>
			</div>
		</li>
		<li class="fontSettings">
			<div>
				<label for="Settings_tagsFontColor" class=" required"><?php _e('Tags'); ?></label>
				<input type="text" id="Settings_tagsFontColor" name="options[tagsFontColor]" required="required" value="<?php echo $options['tagsFontColor']; ?>" />
				<div class="slider">
					<div id="tagsFontSize_slider"></div>
					<div class="count">
						<input type="text" id="Settings_tagsFontSize" name="options[tagsFontSize]" value="<?php echo $options['tagsFontSize']; ?>" />
						<div class="unit"><?php _e('px'); ?></div>
					</div>
				</div>	
				<div class="slider">
					<div id="tagsSingleFontSize_slider"></div>
					<div class="count">
						<input type="text" id="Settings_tagsSingleFontSize" name="options[tagsSingleFontSize]" value="<?php echo $options['tagsSingleFontSize']; ?>" />
						<div class="unit"><?php _e('px'); ?></div>
					</div>
				</div>
				<?php echo loadFontsSelect($options['fonts'], $options['tagsFontFamily'], "tagsFontFamily"); ?>
			</div>
		</li>
		<li class="fontSettings">
			<div>
				<label for="Settings_dateFontColor" class=" required"><?php _e('Date'); ?></label>
				<input type="text" id="Settings_tagsFontColor" name="options[dateFontColor]" required="required" value="<?php echo $options['dateFontColor']; ?>" />
				<div class="slider">
					<div id="dateFontSize_slider"></div>
					<div class="count">
						<input type="text" id="Settings_dateFontSize" name="options[dateFontSize]" value="<?php echo $options['dateFontSize']; ?>" />
						<div class="unit"><?php _e('px'); ?></div>
					</div>
				</div>	
				<div class="slider">
					<div id="dateSingleFontSize_slider"></div>
					<div class="count">
						<input type="text" id="Settings_dateSingleFontSize" name="options[dateSingleFontSize]" value="<?php echo $options['dateSingleFontSize']; ?>" />
						<div class="unit"><?php _e('px'); ?></div>
					</div>
				</div>	
				<?php echo loadFontsSelect($options['fonts'], $options['dateFontFamily'], "dateFontFamily"); ?>
			</div>
		</li>
		<li class="fontSettings">
			<div>
				<label for="Settings_paginationFontColor" class=" required"><?php _e('Pagination'); ?></label>
				<input type="text" id="Settings_tagsFontColor" name="options[paginationFontColor]" required="required" value="<?php echo $options['paginationFontColor']; ?>" />
				<div class="slider">
					<div id="paginationFontSize_slider"></div>
					<div class="count">
						<input type="text" id="Settings_paginationFontSize" name="options[paginationFontSize]" value="<?php echo $options['paginationFontSize']; ?>" />
						<div class="unit"><?php _e('px'); ?></div>
					</div>
				</div>	
				<div class="slider">
					<div id="paginationSingleFontSize_slider"></div>
					<div class="count">
						<input type="text" id="Settings_paginationSingleFontSize" name="options[paginationSingleFontSize]" value="<?php echo $options['paginationSingleFontSize']; ?>" />
						<div class="unit"><?php _e('px'); ?></div>
					</div>
				</div>	
				<?php echo loadFontsSelect($options['fonts'], $options['paginationFontFamily'], "paginationFontFamily"); ?>
			</div>
		</li>
		<li class="fontSettings">
			<div>
				<label for="Settings_btfFontColor" class=" required"><?php _e('Back to feed'); ?></label>
				<input type="text" id="Settings_tagsFontColor" name="options[btfFontColor]" required="required" value="<?php echo $options['btfFontColor']; ?>" />
				<div class="slider">
					<div id="btfFontSize_slider"></div>
					<div class="count">
						<input type="text" id="Settings_btfFontSize" name="options[btfFontSize]" value="<?php echo $options['btfFontSize']; ?>" />
						<div class="unit"><?php _e('px'); ?></div>
					</div>
				</div>
				<div class="slider">
					<div id="btfSingleFontSize_slider"></div>
					<div class="count">
						<input type="text" id="Settings_btfSingleFontSize" name="options[btfSingleFontSize]" value="<?php echo $options['btfSingleFontSize']; ?>" />
						<div class="unit"><?php _e('px'); ?></div>
					</div>
				</div>	
				<?php echo loadFontsSelect($options['fonts'], $options['btfFontFamily'], "btfFontFamily"); ?>
			</div>
		</li>
		<li class="fontSettings">
			<div>
				<label for="Settings_dbtFontColor" class=" required"><?php _e('Display by tag'); ?></label>
				<input type="text" id="Settings_dbtFontColor" name="options[dbtFontColor]" required="required" value="<?php echo $options['dbtFontColor']; ?>" />
				<div class="slider">
					<div id="dbtFontSize_slider"></div>
					<div class="count">
						<input type="text" id="Settings_dbtFontSize" name="options[dbtFontSize]" value="<?php echo $options['dbtFontSize']; ?>" />
						<div class="unit"><?php _e('px'); ?></div>
					</div>
				</div>	
				<?php echo loadFontsSelect($options['fonts'], $options['dbtFontFamily'], "dbtFontFamily"); ?>
			</div>
		</li>
		<li class="fontSettings">
			<div>
				<label for="Settings_readMoreFontColor" class="required"><?php _e('Read more'); ?></label>
				<input type="text" id="Settings_readMoreFontColor" name="options[readMoreFontColor]" required="required" value="<?php echo $options['readMoreFontColor']; ?>" />
				<div class="slider">
					<div id="readMoreFontSize_slider"></div>
					<div class="count">
						<input type="text" id="Settings_readMoreFontSize" name="options[readMoreFontSize]" value="<?php echo $options['readMoreFontSize']; ?>" />
						<div class="unit"><?php _e('px'); ?></div>
					</div>
				</div>	
				<?php echo loadFontsSelect($options['fonts'], $options['readMoreFontFamily'], "readMoreFontFamily"); ?>
			</div>
		</li>						
	</ul>
	<h4 class="lower-header"><?php _e('Sidebar font settings'); ?></h4>
	<p>
		<?php _e('Adjust font colors and sizes of sidebar widgets'); ?>
	</p>
	<ul class="list sidebar_colors color_setting fonts">
		<li class="titles">
			<div class="first"></div>
			<div class="second"><?php _e('Color'); ?></div>
			<div class="third"><?php _e('Font size'); ?></div>
			<div class="fourth"><?php _e('Font family'); ?></div>
		</li>
		<li class="fontSettings">
			<div>
				<label for="Settings_sidebarTitleFontColor" class=" required"><?php _e('Widgets title'); ?></label>
				<input type="text" id="Settings_sidebarTitleFontColor" name="options[sidebarTitleFontColor]" required="required" value="<?php echo $options['sidebarTitleFontColor']; ?>" />
				<div class="slider">
					<div id="sidebarTitleFontSize_slider"></div>
					<div class="count">
						<input type="text" id="Settings_sidebarTitleFontSize" name="options[sidebarTitleFontSize]" value="<?php echo $options['sidebarTitleFontSize']; ?>" />
						<div class="unit"><?php _e('px'); ?></div>
					</div>
				</div>
				<?php echo loadFontsSelect($options['fonts'], $options['sidebarTitleFontFamily'], "sidebarTitleFontFamily"); ?>
			</div>
		</li>
		<li class="fontSettings">
			<div>
				<label for="Settings_sidebarRecentPostsFontColor" class=" required"><?php _e('Recent posts'); ?></label>
				<input type="text" id="Settings_sidebarRecentPostsFontColor" name="options[sidebarRecentPostsFontColor]" required="required" value="<?php echo $options['sidebarRecentPostsFontColor']; ?>" />
				<div class="slider">
					<div id="sidebarRecentPostsFontSize_slider"></div>
					<div class="count">
						<input type="text" id="Settings_sidebarRecentPostsFontSize" name="options[sidebarRecentPostsFontSize]" value="<?php echo $options['sidebarRecentPostsFontSize']; ?>" />
						<div class="unit"><?php _e('px'); ?></div>
					</div>
				</div>
				<?php echo loadFontsSelect($options['fonts'], $options['sidebarRecentPostsFontFamily'], "sidebarRecentPostsFontFamily"); ?>
			</div>
		</li>
		<li class="fontSettings">
			<div>
				<label for="Settings_sidebarTagsFontColor" class=" required"><?php _e('Tag cloud'); ?></label>
				<input type="text" id="Settings_sidebarTagsFontColor" name="options[sidebarTagsFontColor]" required="required" value="<?php echo $options['sidebarTagsFontColor']; ?>" />
				<div class="slider">
					<div id="sidebarTagsFontSize_slider"></div>
					<div class="count">
						<input type="text" id="Settings_sidebarTagsFontSize" name="options[sidebarTagsFontSize]" value="<?php echo $options['sidebarTagsFontSize']; ?>" />
						<div class="unit"><?php _e('px'); ?></div>
					</div>
				</div>
				<?php echo loadFontsSelect($options['fonts'], $options['sidebarTagsFontFamily'], "sidebarTagsFontFamily"); ?>
			</div>
		</li>		
	</ul>
</div>