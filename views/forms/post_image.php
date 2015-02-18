<?php global $options, $post; ?>

<form method="post" action="<?php echo url(array('action' => 'saveImage')); ?>" class="image">
	<p class="wide">
		<input type="hidden" name="id" value="<?php echo (isset($post->id) ? $post->id: '-1'); ?>"/><!-- used for update -->
		<span class="inputbar-wrap">
			<input class="inputbar" type="text" placeholder="<?php _e('Title (required)'); ?>" name="title" value="<?php echo (isset($post->title) ? $post->title: ''); ?>"/>
		</span>
		<span class="url-inputbar">
			<span class="inputbar-wrap" style="padding-right: 240px;">
				<input class="inputbar" type="text" placeholder="<?php _e('image_paste'); ?>" name="url" value="<?php echo (isset($post->url) ? $post->url: ''); ?>"/>
			</span>
			<span class="url-barbuttons">
				<a class="btn default js-image-gallery"><?php _e('wix_gallery'); ?></a>
				<a class="btn default js-image-filepicker <?php echo $options['is_premium'] ? '' : 'premium-features'; ?>"><?php _e('social_network'); ?></a>
			</span>
		</span>
		<span class="center image-preview hidden">
		    <img class="image-source" src="data:image/gif;base64,R0lGODlhAQABAPABAP///wAAACH5BAEKAAAALAAAAAABAAEAAAICRAEAOw%3D%3D" />
		    <button type="button" class="btn default image-remove"><?php _e('button_remove'); ?></button>
		</span>

		<textarea id="image-caption" name="content"><?php echo (isset($post->content) ? str_replace('&', '&amp;', $post->content) : ''); ?></textarea>
		<input class="inputbar" type="text" name="tags" value="<?php echo (isset($post->tags) ? implode(',', objValues($post->tags, 'name')) : ''); ?>"/>
		<span class="social-sharing clearfix"><?php //include 'post_social.php'; ?></span>
	</p>
	<?php if (isset($options['posts_left'])) : ?>
	<p>
		<?php
			switch (max(0, $options['posts_left'])) {
				case 0:
					_e('%s number of posts for a basic version. %s for unlimited posting and all premium features - or delete old posts.', '<b>'.__('You reached the maximum').'</b>', '<span class="upgrade">'.__('upgrade to premium').'</span>');
					break; 
				case 1:
					 _e('%s in the basic version. %s for unlimited posting and all premium features.', '<b>'.__('Only one post left').'</b>', '<span class="upgrade">'.__('upgrade to premium').'</span>');
					break;
				case 2:
				case 3:
				case 4:
				case 5:
					$postsLeft = __('Only %d posts left', $options['posts_left']);
					_e('%s in the basic version. %s for unlimited posting and all premium features.', '<b>'.$postsLeft.'</b>', '<span class="upgrade">'.__('upgrade to premium').'</span>');
					break;
				default:
					// do nothing
					break;
				}
		?>
	</p>
	<?php endif; ?>
	<p class="clearfix dtp">
		<span class="input-append datetimepicker">
			<label class="lower-header"><?php _e("Publish date");?></label>
			<span class="inputbar-wrap">
	    		<input class="inputbar" data-format="yyyy-MM-dd hh:mm:ss" type="text" name="postdate" placeholder="Date of publication"  value="<?php echo (isset($post->postdate) ? date("Y-m-d H:i",strtotime($post->postdate)): ''); ?>"></input>
	    	</span>
	  	</span>
	</p>
	<p class="clearfix pcb">
		<span class="post-cancel-buttons">
			<?php if (!isset($post->published)): ?>
				<button type="submit" name="published" value="1" class="btn post-image" <?php echo isset($options['posts_left']) && $options['posts_left'] <= 0 ? 'disabled="disabled"' : ''?>><?php echo isset($post->published) && $post->published ? __('button_update') : __('button_publish'); ?></button>
				<button type="submit" name="published" value="0" class="btn default save-text" <?php echo isset($options['posts_left']) && $options['posts_left'] <= 0 ? 'disabled="disabled"' : ''?>><?php _e('button_save'); ?></button>
			<?php else: ?>
				<?php if ($post->published): ?>
				<button type="submit" name="published" value="1" class="btn post-image"><?php echo isset($post->published) && $post->published ? __('button_update') : __('button_publish'); ?></button>
				<?php else: ?>
				<button type="submit" name="published" value="0" class="btn default save-image"><?php _e('button_save'); ?></button>
				<?php endif; ?>
			<?php endif; ?>
			<button type="button" class="btn default js-post-image-cancel"><?php _e('button_cancel'); ?></button>
		</span>
	</p>
</form>
