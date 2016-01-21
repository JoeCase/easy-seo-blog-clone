<?php
require_once 'actions/options.php';
require_once 'actions/read.php';
require_once 'actions/save.php';
require_once 'actions/edit.php';
require_once 'actions/delete.php';
require_once 'actions/functions.php';
/*-- require_once 'actions/social.php'; --*/
require_once 'locale/localization.php';
if (isset($_REQUEST['ajax']) && $_REQUEST['ajax'] === 'true') {
	require_once 'actions/ajax.php';
}

global $options;

$options['number_of_posts'] = numberOfposts(null, array("postdate" => date("Y-m-d  H:i:s")));
if (!$options['is_premium']) {
	$options['posts_left'] = 10 - $options['number_of_posts'];
}

if (isset($_REQUEST['action'])) {
	$message = call_user_func($_REQUEST['action']);

	if ($message === false) {
		$message = 100; // Common (unknown) error
	}

	if (isset($_REQUEST['ajax']) && $_REQUEST['ajax'] === 'true') {
		if (is_array($message)) {
			// convert array to JSON
			echo json_encode($message);
		}
			else if ($message != 0) {
			// return error message only
			echo json_encode(array('msg' => $message));
		}
	}
	else {
		// Redirect browser to URL without action
		// header('HTTP/1.1 302 Found');
		header('Location: '.url(array('msg' => $message), array('action', 'ajax')));
	}
	// Make sure that code below does not get executed when we redirect or sending JSON data.
	exit;
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="Content-type" content="text/html;charset=utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=9" />
	<title><?php _e('Easy SEO Blog - settings | EasyApps'); ?></title>
	<link type="text/css" href="stylesheets/css/main.css?v=<?php echo $options['version']; ?>" rel="stylesheet">
	<link type="text/css" href="stylesheets/css/settings.min.css?v=<?php echo $options['version']; ?>" rel="stylesheet">
	<link type="text/css" href="stylesheets/css/jquery.mCustomScrollbar.min.css?v=<?php echo $options['version']; ?>" rel="stylesheet" />
	<link type="text/css" href="stylesheets/css/jquery.loader.min.css?v=<?php echo $options['version']; ?>" rel="stylesheet"/>
	<link type="text/css" href="stylesheets/css/icons.min.css?v=<?php echo $options['version']; ?>" rel="stylesheet"/>
	<link type="text/css" href="stylesheets/css/blog.settings.min.css?v=<?php echo $options['version']; ?>" rel="stylesheet">
	<link type="text/css" href="stylesheets/css/social.min.css?v=<?php echo $options['version']; ?>" rel="stylesheet"/>
	<link type="text/css" href="stylesheets/css/jquery.selectbox.min.css?v=<?php echo $options['version']; ?>" rel="stylesheet"/>
    <link type="text/css" href="stylesheets/css/jquery.simple-dtpicker.min.css?v=<?php echo $options['version']; ?>" rel="stylesheet"/>
 	
	<?php //Load fonts from Google lib 
		$fontFamilies = "";
		if(isset($options['fonts']) && ($options['fonts'] != NULL)) {
			foreach ($options['fonts'] as $k => $v){
				?><link href='<?php echo $v->link; ?>' rel='stylesheet' type='text/css'><?php
				$fontFamilies .= str_replace("'", "", $v->fontFamily).";";
			}
		}
	?>
	
	<!--[if gt IE 8]>
	<link type="text/css" href="stylesheets/css/ie9/blog.settings.min.css?v=<?php echo $options['version']; ?>" rel="stylesheet">
	<link type="text/css" href="stylesheets/css/ie9/settings.min.css?v=<?php echo $options['version']; ?>" rel="stylesheet">
	<![endif]-->
	
	<!-- Wix JS SDK -->
	<script type="text/javascript" src="//static.parastorage.com/services/js-sdk/1.61.0/js/wix.min.js"></script>
	
	<!-- File picker SDK -->
	<script type="text/javascript" src="//api.filepicker.io/v1/filepicker.js"></script>
	
	<!-- jQuery; needed for Twitter Bootstrap -->
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	
	<!-- Twitter Bootstrap components; include this to utilize the Color Pickers, based on Tooltip and Popover -->
	<script type="text/javascript" src="javascripts/bootstrap/bootstrap.min.js?v=<?php echo $options['version']; ?>"></script>
	<script type="text/javascript" src="javascripts/bootstrap/bootstrap-tooltip.min.js?v=<?php echo $options['version']; ?>"></script>
	<script type="text/javascript" src="javascripts/bootstrap/bootstrap-popover.min.js?v=<?php echo $options['version']; ?>"></script>

	<!-- Wix UI Components -->
	<script type="text/javascript" src="javascripts/components/jquery-ui.min.js?v=<?php echo $options['version']; ?>"></script>
	<script type="text/javascript" src="javascripts/components/color-picker/color-pickers/simple.min.js?v=<?php echo $options['version']; ?>"></script>
	<script type="text/javascript" src="javascripts/components/color-picker/color-pickers/advanced.min.js?v=<?php echo $options['version']; ?>"></script>
	<script type="text/javascript" src="javascripts/components/color-picker/color-picker.min.js?v=<?php echo $options['version']; ?>"></script>
	<script type="text/javascript" src="javascripts/components/video/embed-video.min.js?v=<?php echo $options['version']; ?>"></script>
	<script type="text/javascript" src="javascripts/components/editor/ckeditor.js?v=<?php echo $options['version']; ?>"></script>
	<script type="text/javascript" src="javascripts/components/tags/jquery.tagsinput.min.js?v=<?php echo $options['version']; ?>"></script>

	<script type="text/javascript" src="javascripts/bootstrap/jquery.mousewheel.min.js?v=<?php echo $options['version']; ?>"></script>
	<script type="text/javascript" src="javascripts/components/custom-scrollbars/jquery.mCustomScrollbar.concat.min.js?v=<?php echo $options['version']; ?>"></script>
	<script type="text/javascript" src="javascripts/components/imagesloaded/jquery.imagesloaded.min.js?v=<?php echo $options['version']; ?>"></script>
	<!-- Loader -->
	<script type="text/javascript" src="javascripts/components/loader/jquery.loader.min.js?v=<?php echo $options['version']; ?>"></script>
	<!-- Forms validations -->
	<script type="text/javascript" src="javascripts/components/form-validation/jquery.validate.min.js?v=<?php echo $options['version']; ?>"></script>
	<!-- Inline confirmation -->
	<script type="text/javascript" src="javascripts/components/inline-confirmation/jquery.inline-confirmation.min.js?v=<?php echo $options['version']; ?>"></script>
	<!-- Placeholder IE support -->
	<script type="text/javascript" src="javascripts/components/placeholder/jquery.placeholder.min.js?v=<?php echo $options['version']; ?>"></script>
	<!-- Customize selectbox -->
	<script type="text/javascript" src="javascripts/components/selectbox/jquery.selectbox-0.2.min.js?v=<?php echo $options['version']; ?>"></script>
	<!-- Datetime picker -->
	<script type="text/javascript" src="javascripts/components/datetimepicker/jquery.simple-dtpicker.min.js?v=<?php echo $options['version']; ?>"></script>
	

 	<script type="text/javascript">
		var fonts = new Array();
		<?php 
			if(isset($options['fonts']) && ($options['fonts'] != NULL)) {
				$i = 0;
				foreach ($options['fonts'] as $k => $v){
					if($v->link != NULL) {
						?>fonts[<?php echo $i; ?>] = "<?php echo $v->link; ?>";
						<?php
						++$i;
					} 
				}
			}
		?>
	</script>
	
	<!-- Settings View Logic -->
	<script type="text/javascript" src="javascripts/views/settings.min.js?v=<?php echo $options['version']; ?>"></script>
	<script type="text/javascript" src="javascripts/views/blog.settings.min.js?v=<?php echo $options['version']; ?>"></script>
	
<!-- 	<script type="text/javascript">
	// <![CDATA[
		var facebook_key = '<?php //echo FACEBOOK_APP_ID; ?>';
		var facebook_uid = '<?php //echo $GLOBALS['options']['facebookId']; ?>';
	
		var twitter_link = '<?php //echo getTwitterAuthUrl(); ?>';			
	
		tSReload = '<?php //echo url(array('action' => 'loadContent', 'esb_file' => 'views/forms/social/twitter.php', 'ajax' => 'true')); ?>';
		fSReload = '<?php //echo url(array('action' => 'loadContent', 'esb_file' => 'views/forms/social/facebook.php', 'ajax' => 'true')); ?>';
		gSReload = '<?php //echo url(array('action' => 'loadContent', 'esb_file' => 'views/settings/social_authorization.php', 'ajax' => 'true')); ?>';
		
		Wix.getSiteInfo( function(siteInfo) {
			var searchTitle = siteInfo.pageTitle;
			Wix.Settings.getSitePages( function(sitePages) {
				for(var i = 1; i < sitePages.length; i++){
					if(sitePages[i].title == searchTitle){
						$("#input-main-link").val(siteInfo.baseUrl+"#/"+sitePages[i].id);
						break;
					}
				}
			});	
		});
		
	// ]]>
	</script> -->
	<!-- Social logins 
		<script type="text/javascript" src="http://connect.facebook.net/en_US/all.js"></script>
		<script type="text/javascript" src="javascripts/components/social/jquery.oauthpopup.js?v=<?php echo $options['version']; ?>"></script>
		<script type="text/javascript" src="javascripts/components/social/script.js?v=<?php echo $options['version']; ?>"></script>
		<script type="text/javascript" src="javascripts/components/social/twitter-text.js?v=<?php echo $options['version']; ?>"></script>
	-->
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-45186431-1', 'tea-server.com');
  ga('send', 'pageview');

</script>
</head>
<body>
	<div class="page">
		<?php 
		// 	if (isset($_REQUEST['msg']) && strlen($_REQUEST['msg']) > 0) {
		// 		echo '<p>Message: '.$_REQUEST['msg'].'</p>';
		// 	}
		?>
		<header class="intro box">
			<div class="title">
				<!-- App Logo with native CSS3 gloss -->
				<div class="icon">
					<div class="logo">
						<span class="gloss"></span>
					</div>
				</div>
	
				<!-- This divider is a must according to the Wix design requirements -->
				<div class="divider"></div>
	
				</div>
	
	
			<!-- Connect account area -->
			<div class="login">
				<div class="guest clearfix">
					<div class="description">
						<div class="settingsdescription">
							<?php _e('settings_description'); ?>
						</div>
						<div class="underAppDescription">
							<?php _e('Easy SEO Blog (ver. %s)', $options['version']); ?>
						</div>
					</div>
					<div class="upgrade-wrap clearfix">
						<?php if (!$options['is_premium']): ?>
						<button id="js-upgrade-btn" type="submit" class="btn upgrade">
							<?php _e('Upgrade to premium'); ?>
						</button>
						<span><span class="premium-features"></span>&nbsp;<?php _e('Premium features'); ?>
						</span><br/>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</header>
	
		<!-- Settings box -->
		<div class="accordion">
			<div class="box">
				<h3><?php _e('Write a post'); ?></h3>
				<div class="feature post-area">
	
					<!-- Post icons -->
					<p class="post_buttons">
						<a href="#"><span
							class="blog_but-post-buttons post_but_text js-post-text-action active"></span>
						</a> <a href="#"><span
							class="blog_but-post-buttons post_but_photo js-post-image-action"></span>
						</a> <a href="#"><span
							class="blog_but-post-buttons post_but_video js-post-video-action"></span>
						</a> <a href="#"><span
							class="blog_but-post-buttons post_but_link js-post-link-action"></span>
						</a>
					</p>
	
					<!-- Post text -->
					<div class="post-text">
						<?php include 'views/forms/post_text.php';?>
					</div>
	
					<!-- Post image -->
					<div class="post-image hidden">
						<?php include 'views/forms/post_image.php';?>
					</div>
	
					<!-- Post video -->
					<div class="post-video hidden">
						<?php include 'views/forms/post_video.php'; ?>
					</div>
	
					<!-- Post link -->
					<div class="post-link hidden">
						<?php include 'views/forms/post_link.php';?>
					</div>
				</div>
			</div>
	
			<div class="box">
				<h3><?php _e('Manage posts'); ?></h3>
				<div class="feature recent-posts">
					<?php include 'views/settings/recent_posts.php';?>
				</div>
			</div>
			
			<form id="options_form" method="post"
				action="<?php echo url(array('action' => 'saveOptions')); ?>">
	
				<!-- Layouts and Pagination -->
				<div class="box">
					<?php include 'views/settings/layout_pagination.php'; ?>
				</div>
	
				<!-- Colors -->
				<div class="box">
					<?php include 'views/settings/colors.php'; ?>
				</div>
	
				<!-- Social networs - share buttons and integration -->
				<div class="box">
					<?php include 'views/settings/social_networks.php'; ?>
				</div>
	
				<!-- Sidebar settings -->
				<div class="box">
					<?php include 'views/settings/sidebar_settings.php'; ?>
				</div>
	
				<!-- General settings - Format and translations -->
				<div class="box">
					<?php include 'views/settings/general_settings.php'; ?>
				</div>
				
				<!-- Contact Easy Apps -->
				<div class="box">
					<?php include 'views/settings/contact_us.php'; ?>
				</div>
			</form>
	
		</div>
		<!-- End of .accordion -->
	</div>

	<?php if ($options['is_premium']): ?>
	<script type="text/javascript">
	// <![CDATA[
	filepicker.setKey('ACpt3mbOS4Cn4vYMkVfK9z');

	function initliazeFilepicker($btn, $element, data) {
		$btn.unbind('click').on('click', function() {
			filepicker.pick(data, function(FPFile) {
				// $('#filepicker').addClass('hidden');
				$element.val(FPFile.url).change();
			});
		});
	}

	function initliazePostImageFormPremium($form) {
		initliazeFilepicker($form.find('.js-image-filepicker'), $form.find('input[name=url]'), { mimetypes: ['image/*'] } );
	}

	function initliazePostVideoFormPremium($form) {
		initliazeFilepicker($form.find('.js-video-filepicker'), $form.find('input[name=url]'), { mimetypes: ['video/*'] } );
	}
	// ]]>
	</script>
	<?php else: ?>
	<script type="text/javascript">
	// <![CDATA[
		// disable premium inputs
		$(document).ready(function() {
			$('.premium-features .feature').find('input, button, label, .color-selector-wrap').addClass('disabled');
			$('.premium-features .feature').find('input').attr('disabled', 'disabled');
			$('.premium-features .feature').find('button').attr('disabled', 'disabled');
			$('.premium-features .feature').find('#feedBgOpacityValue_slider, #postsBgOpacityValue_slider, #borderThickness_slider').slider('disable');

			$('.premium-features.property').find('input, label, .color-selector-wrap').addClass('disabled');
			$('.premium-features.property').find('input').attr('disabled', 'disabled');
			$('.premium-features.property').find('#feedBgOpacityValue_slider, #postsBgOpacityValue_slider, #borderThickness_slider, #gridRows_slider').slider('disable');

			
			$('.upgrade').click(function() {
				Wix.Settings.openBillingPage();
			});
			
			//Placeholders run
			$('input, textarea').placeholder();
		});
	// ]]>
	</script>
	<?php endif; ?>
		
	<script type="text/javascript">
	// <![CDATA[
		tagsInputOptions = {
			'defaultText': '<?php _e('Add tags (optional)'); ?>',
			'maxChars' : 255
		}

		/* Set enable fonts in blog for CKEditor */
		CKEDITOR.config.font_names = '<?php echo $fontFamilies; ?>'; 
		<?php //Load fonts from Google lib 
			/*if(isset($options['fonts']) && ($options['fonts'] != NULL)) {
				foreach ($options['fonts'] as $k => $v){
					if($v->link != NULL) {
						?>CKEDITOR.document.appendStyleSheet("<?php echo $v->link; ?>");<?php
					}
				}
			}*/
		?>
		
		__delete_question = '<?php _e('Delete and risk SEO decrease?'); ?>&nbsp;&nbsp;&nbsp;';
		__delete_confirmation = '<span class="btn default"><?php _e('Delete'); ?></span>';
		__delete_cancelation = '<span class="btn default"><?php _e('Cancel'); ?></span>';
		
		$(document).ready(function() {
			// form validation 
			$.extend($.validator.messages, {
				required: '<?php _e('This is a required field'); ?>',
				url: '<?php _e('Please insert the valid URL'); ?>',
				maxlength: $.validator.format('<?php _e('Please insert at most {0} characters')?>'),
			});

			Wix.getSiteInfo( function(siteInfo) {
				var siteUrl = '<?php echo $options['baseUrl']; ?>';				
				// updte siteUrl when it is changed
				if (siteUrl != siteInfo.siteUrl) {
					$.post('<?php echo url(array('action' => 'updateSiteInfo', 'ajax' => 'true')); ?>', siteInfo, function() {
						// do nothing
					});
				}
			});
			$('.comming- + .feature').remove();

			$(".select-box").selectbox({speed: 400});
            
		});
	// ]]>
	</script>
</body>
</html>
