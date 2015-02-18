<?php 
	global $options, $post;
?>
<div class="footer">
	<?php if(!isset($GLOBALS['url']['slug'])): ?>
		<!-- Share buttons on list preview -->
		<?php if ($GLOBALS['options']['share_show_list_bottom']): ?>
			<?php include 'views/share_buttons.php'; ?>
		<?php endif; ?>
	<?php else: ?>
		<!-- Share buttons on detail preview -->
		<?php if ($GLOBALS['options']['share_show_detail_bottom']): ?>
			<?php include 'views/share_buttons.php'; ?>
		<?php endif; ?>
	<?php endif; ?>                                                                     
	
  <?php 
    if(isset($_GET['deviceType']) && ((count($posts) > 1 && (($_GET['deviceType'] == 'mobile' && $options['showDatesFeedMobile']) || ($_GET['deviceType'] != 'mobile' && $options['showDatesFeed'])))
    || (count($posts) == 1 && (($_GET['deviceType'] == 'mobile' && $options['showDatesDetailMobile']) || ($_GET['deviceType'] != 'mobile' && $options['showDatesDetail'])))))
    { 
  ?>
	<div class="datebox">
		<span class="date"><?php echo date($options['dateFormat'], strtotime($post->postdate)); ?></span>
	</div>
  <?php
    }
  ?>
	<?php if(sizeof($post->tags) != 0) { ?>
		<div class="tags">
			<svg xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:cc="http://creativecommons.org/ns#" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd" xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape" version="1.0" width="16.000000px" height="16.000000px" viewBox="0 0 100.000000 100.000000" preserveAspectRatio="xMidYMid meet" id="svg23034" inkscape:version="0.48.4 r9939" sodipodi:docname="tag_icon_100.svg">
				<g transform="translate(0.000000,100.000000) scale(0.100000,-0.100000)" fill="<?php echo $options['tags_color_rgba']; ?>" stroke="none" id="g23038">
				  <path d="M563 962 c-70 -25 -87 -39 -336 -283 -283 -278 -280 -234 -35 -471 187 -181 214 -200 264 -181 12 4 123 105 246 224 259 251 279 279 286 400 4 67 1 83 -23 138 -32 71 -94 133 -163 164 -66 29 -172 33 -239 9z m232 -60 c126 -65 181 -220 122 -347 -31 -66 -455 -480 -492 -480 -34 0 -365 317 -365 350 0 17 57 79 218 235 231 226 242 235 300 256 52 20 166 13 217 -14z" id="path23040" />
				  <path d="m 103.53309,34.88372 a 7.1556349,6.9320216 0 1 1 -14.311268,0 7.1556349,6.9320216 0 1 1 14.311268,0 z" transform="matrix(11.493072,0,0,-11.863815,-350.96463,1143.7288)" />
				</g>
			</svg>
			<?php foreach($post->tags as $val): ?>
				<span class="tag">
					<a target="_top" href="<?php echo $GLOBALS['section-url'].'tag/'.$val->slug; ?>" class="state"><?php echo $val->name; ?></a>
				</span>
			<?php endforeach; ?>
		</div>
	<?php } ?>
</div>