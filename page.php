<?php
	require_once 'config.php';
	require_once 'actions/functions.php';
	require_once 'actions/urlParser.php';
	require_once 'locale/localization.php';			
	require_once 'actions/options.php';
	require_once 'actions/read.php';
	require_once 'htmlpurifier/library/HTMLPurifier.auto.php';
	if (isset($_REQUEST['ajax']) && $_REQUEST['ajax'] === 'true') {
		require_once 'actions/ajax.php';
	}
	global $options;
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
	<?php	
		if(isset($GLOBALS['url']['slug'])){
			$user = $options['user'];
			if (numberOfposts(null, array("postdate" => date("Y-m-d  H:i:s"))) == 0) {
				$user = 'demo';
			}
			$meta = loadPosts($GLOBALS['current_page'], null, true, $GLOBALS['url'], $user);
			if(!empty($meta)){
				$newTitle = $meta[0]->title." | ".$options['siteTitle'];
				
				//HTML purifier config
				$config = HTMLPurifier_Config::createDefault();
				$config->set('HTML.AllowedElements', array());
				$purifier = new HTMLPurifier($config);
				$newDescription  = substr($purifier->purify($meta[0]->content), 0, 500);
				
				$keysArr = array();
				foreach ($meta[0]->tags as $k => $v){
					array_push($keysArr, $v->slug);	
				}
				$newKeys = implode(",", $keysArr);
				?>
					<title><?php echo $newTitle; ?></title>
					<link rel="canonical" href="<?php echo $GLOBALS['section-url']; ?>">					
					<meta name="keywords" content="<?php echo $newKeys; ?>">
                    <meta name="description" content="<?php echo $newDescription; ?>">
					<meta property="og:title" content="<?php echo $newTitle; ?>">
					<meta property="og:type" content="article">                                        
					<meta property="og:description" content="<?php echo $newDescription; ?>">
				<?php
				if($meta[0]->type == "image"){
					?>
						<meta property="og:image" content="<?php echo $meta[0]->url; ?>">
					<?php				
				} else if($meta[0]->type == "video"){
					?>
						<meta property="og:video" content="<?php echo $meta[0]->url; ?>">
						<meta property="og:video:width" content="640">
						<meta property="og:video:height" content="360">
						<meta property="og:video:type" content="application/x-shockwave-flash">
					<?php				
				}
			}
		} else {
			?><title><?php echo $options['siteTitle']; ?></title><?php
		}
	?>         
    <link type="text/css" href="<?php echo base_url(); ?>stylesheets/css/main.min.css?v=<?php echo $options['version']; ?>" rel="stylesheet">
    <link type="text/css" href="<?php echo base_url(); ?>stylesheets/css/post.min.css?v=<?php echo $options['version']; ?>" rel="stylesheet">
    <link type="text/css" href="<?php echo base_url(); ?>stylesheets/css/blog.settings.min.css?v=<?php echo $options['version']; ?>" rel="stylesheet">
    <link type="text/css" href="<?php echo base_url(); ?>stylesheets/css/layouts.min.css?v=<?php echo $options['version']; ?>" rel="stylesheet">
    

    <?php if(isset($options['showSidebar']) && $options['showSidebar']) { ?> 
    	<link type="text/css" href="<?php echo base_url(); ?>stylesheets/css/sidebar-layout.min.css?v=<?php echo $options['version']; ?>" rel="stylesheet">
    <?php } ?>
    
	<?php //Load fonts from Google lib 
		if(isset($options['fonts']) && ($options['fonts'] != NULL)) {
			foreach ($options['fonts'] as $k => $v){
				?><link href='<?php echo $v->link; ?>' rel='stylesheet' type='text/css'><?php
			}
		}
	?>
  
     
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>javascripts/components/imagesloaded/jquery.imagesloaded.min.js?v=<?php echo $options['version']; ?>"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>javascripts/components/tags/jquery.tagsinput.min.js?v=<?php echo $options['version']; ?>"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>javascripts/components/video/embed-video.min.js?v=<?php echo $options['version']; ?>"></script>
	<?php if ($options['pageLayout'] == 'pinterest'):?>	
	<script type="text/javascript" src="<?php echo base_url(); ?>javascripts/components/masonry/jquery.masonry.min.js?v=<?php echo $options['version']; ?>"></script>
	<?php endif; ?>

    <!-- Wix JS SDK -->
	<script type="text/javascript" src="//sslstatic.wix.com/services/js-sdk/1.23.0/js/Wix.js"></script>
    	<style type="text/css">     
  		<?php                                                   
  			echo 'body{background-color:'.$options['bg_color_rgba'].';}'; 
  			echo '.content {background-color:'.$options['post_bg_color_rgba'].';
  					border: '.$options['borderThickness'].'px solid '.$options['borderColor'].'}';
  			echo '.caption p, .caption {color:'.$options['text_color_rgba'].' !important; font-size:'.$options['textFontSize'].'px; font-family:'.$options['fonts'][$options['textFontFamily']]->fontFamily.';}';
  			echo '.caption p a, .url a, .url p a{color:'.$options['links_color_rgba'].' !important; border-bottom-color: '.$options['links_color_rgba'].'; font-size:'.$options['linksFontSize'].'px; font-family:'.$options['fonts'][$options['linksFontFamily']]->fontFamily.';}';
  			echo '.title, .title > a {color:'.$options['title_color_rgba'].' !important;  font-size:'.$options['titleFontSize'].'px !important; font-family:'.$options['fonts'][$options['titleFontFamily']]->fontFamily.';}';
  			echo '.tag a{color:'.$options['tags_color_rgba'].' !important;  font-size:'.$options['tagsFontSize'].'px; font-family:'.$options['fonts'][$options['tagsFontFamily']]->fontFamily.';}';
  			echo '.datebox .date {color:'.$options['date_color_rgba'].' !important;  font-size:'.$options['dateFontSize'].'px; font-family:'.$options['fonts'][$options['dateFontFamily']]->fontFamily.';}';
  			echo '.pagelink.current, .pagelink, .pagelink a {color:'.$options['pagination_color_rgba'].' !important;  font-size:'.$options['paginationFontSize'].'px;border-bottom-color: '.$options['pagination_color_rgba'].'; font-family:'.$options['fonts'][$options['paginationFontFamily']]->fontFamily.';}';
  			echo '.back-link a {color:'.$options['btf_color_rgba'].' !important;  font-size:'.$options['btfFontSize'].'px; font-family:'.$options['fonts'][$options['btfFontFamily']]->fontFamily.';}';
  			echo '.tag-title {color:'.$options['dbt_color_rgba'].' !important;  font-size:'.$options['dbtFontSize'].'px; font-family:'.$options['fonts'][$options['dbtFontFamily']]->fontFamily.';}';			
  			echo '.read_more {color:'.$options['read_more_color_rgba'].' !important;  font-size:'.$options['readMoreFontSize'].'px; font-family:'.$options['fonts'][$options['readMoreFontFamily']]->fontFamily.';}';
  			
  			echo '.single_post .caption p, .single_post .caption {font-size:'.$options['textSingleFontSize'].'px;}';
  			echo '.single_post .caption p a, .url a, .single_post .url p a{font-size:'.$options['linksSingleFontSize'].'px;}';
  			echo '.single_post .title, .single_post .title > a {font-size:'.$options['titleSingleFontSize'].'px !important;}';
  			echo '.single_post .tag a{font-size:'.$options['tagsSingleFontSize'].'px;}';
  			echo '.single_post .datebox .date {font-size:'.$options['dateSingleFontSize'].'px;}';
  			echo '.single_post .pagelink.current, .single_post .pagelink, .single_post .pagelink a {font-size:'.$options['paginationSingleFontSize'].'px;}';
  			echo '.single_post .back-link a {font-size:'.$options['btfSingleFontSize'].'px;}';
  			//echo '.single_post .tag-title {font-size:'.$options['dbtSingleFontSize'].'px;}'; 
  			
  			// Styles for sidebar
  			echo '.sidebar .tag-cloud {font-size:'.$options['sidebarTagsFontSize'].'px;}';
  			echo '.sidebar .tag-cloud a {color:'.$options['sidebar_tags_font_color_rgba'].' !important; font-family:'.$options['fonts'][$options['sidebarTagsFontFamily']]->fontFamily.';}';
  			echo '.sidebar h2 {font-size:'.$options['sidebarTitleFontSize'].'px;color:'.$options['sidebar_title_font_color_rgba'].' !important; font-family:'.$options['fonts'][$options['sidebarTitleFontFamily']]->fontFamily.';}';
  			echo '.rec-date, .rec-title, .rec-title a {font-size:'.$options['sidebarRecentPostsFontSize'].'px;color:'.$options['sidebar_recent_posts_font_color_rgba'].' !important; font-family:'.$options['fonts'][$options['sidebarRecentPostsFontFamily']]->fontFamily.';}';
  			if(isset($options['enableSidebarDivider']) && $options['enableSidebarDivider']){			
  				echo '.left-column {border-right: '.$options['sidebarDividerThicknes'].'px solid '.$options['sidebar_divider_color_rgba'].'}';
  				echo '.left-column {width: '.((650-$options['sidebarDividerThicknes'])*100/980).'%;}';
  			}
  		?>              
  	</style>
    
      <!--[if lte IE 8]>
    <style type="text/css">     
      <?php echo 'body{background-color:'.$options['bg_color_rgb'].';}';
      echo '.content {background-color:'.$options['post_bg_color_rgb'].';}';
      	echo '.caption p, .caption {color:'.$options['text_color_rgb'].' !important;}';
  			echo '.caption p a, .url a, .url p a{color:'.$options['links_color_rgb'].' !important; border-bottom-color: '.$options['links_color_rgb'].';}';
  			echo '.title, .title > a {color:'.$options['title_color_rgb'].' !important;}';
  			echo '.tag a{color:'.$options['tags_color_rgb'].' !important;}';
  			echo '.datebox .date {color:'.$options['date_color_rgb'].' !important;}';
  			echo '.pagelink.current, .pagelink, .pagelink a {color:'.$options['pagination_color_rgb'].' !important;border-bottom-color: '.$options['pagination_color_rgb'].';}';
  			echo '.back-link a {color:'.$options['btf_color_rgb'].' !important;}';
  			echo '.tag-title {color:'.$options['dbt_color_rgb'].' !important;}';			
  			echo '.read_more {color:'.$options['read_more_color_rgb'].' !important;}';
  			
  			// Styles for sidebar
  			echo '.sidebar .tag-cloud a {color:'.$options['sidebar_tags_font_color_rgb'].' !important;}';
  			echo '.sidebar h2 {color:'.$options['sidebar_title_font_color_rgb'].' !important;}';
  			echo '.rec-date, .rec-title, .rec-title a {color:'.$options['sidebar_recent_posts_font_color_rgb'].' !important;}';
  			if(isset($options['enableSidebarDivider']) && $options['enableSidebarDivider']){			
  				echo '.left-column {border-right: '.$options['sidebarDividerThicknes'].'px solid '.$options['sidebar_divider_color_rgb'].'}';
  			}
      ?>       
  	</style>
    <![endif]-->
   
</head>
<body id="page-iframe" class="clearfix">
	<div class="clearfix allPosts <?php 
		if(!isset($GLOBALS['url']['slug'])){
			echo 'columns_'.intval($options['page_columns']);
		} else {
			echo 'single_post';
		}
		echo ' '.$options['pageLayout'];
	?>">
		<?php if(isset($options['showSidebar']) && $options['showSidebar'] && !isset($GLOBALS['url']['slug'])) { ?> <div class="left-column"> <?php } ?>
		
		<?php if(isset($GLOBALS['url']['tag']) && isset($GLOBALS['options']['showDisplayByTag']) && $GLOBALS['options']['showDisplayByTag']) : ?>
			<div class="tag-title"><?php 
				$categoryName = getCategoryNameBySlug($GLOBALS['url']['tag']);
				echo ((isset($options['displayByTag']) && $options['displayByTag']) ? $options['displayByTag'] : _e("Display by tag:"))." ".($categoryName == "" ? $GLOBALS['url']['tag'] : $categoryName); 
			?></div>
		<?php endif; ?>
		
		<?php if(((isset($GLOBALS['url']['slug'])) || (isset($GLOBALS['url']['tag'])) || (isset($GLOBALS['url']['type']))) && isset($GLOBALS['options']['showBackToFeed']) && $GLOBALS['options']['showBackToFeed']) : ?>
			<div class="back-link"><a target="_top" href="<?php echo $GLOBALS['section-url']; ?>" class="state"><?php echo ((isset($options['backToFeed']) && $options['backToFeed']) ? $options['backToFeed'] : _e("Back to feed")) ?></a></div>
		<?php endif; ?>
		
		<?php require_once 'views/posts/writeAll.php'; ?>
		
		<?php if (!isset($GLOBALS['url']['slug'])) { ?>
			<div id="pagination">
				<?php include 'views/pagination.php';?>
			</div>
		<?php } else { ?>
			<div id="pagination">
				<?php include 'views/postPagination.php';?>
			</div>
		<?php } ?>
		
		<?php if(isset($options['showSidebar']) && $options['showSidebar'] && !isset($GLOBALS['url']['slug'])) { ?> </div><div class="right-column sidebar">
		<?php require_once 'views/sidebar/sidebar.php'; ?>
		</div> <?php } ?>
	</div>
	<script type="text/javascript">
        var lastHeight = 0;

        heightInterval = setInterval(function() {
            console.log(1);
            reportHeight();
        }, 1000);

		Wix.getSiteInfo( function(siteInfo) {
			if((siteInfo != null) && (typeof siteInfo != 'undefined')) {
				if (siteInfo.url.indexOf("editor.wix.com/html/editor/web/") !== -1) {
					// change deep linking URLs to works in preview mode
			  		$("a[target=_top]").each(function() {
						var href = $(this).attr('href');
						href = href.replace("//?","/").replace("/?","/");
						$(this).removeAttr('target').attr('href', href+window.location.search);
				  	});

					// Disable click for social share buttons in preview mode
			  		$('.social-share-wrapper').each(function() {
				  		var $cover = $('<div style="position:absolute;top:0;left:0;right:0;bottom:0;"></div>');
						$cover.click(function() {
							Wix.openModal('<?php echo base_url(); ?>message.php?msg=<?php echo urlencode(__('In order to interact with this social widget you must first publish your site.')); ?>', 400, 150);
						});
				  		
				  		$(this).css('position', 'relative').append($cover);
			  		});
				}
				if (siteInfo.pageTitle) {
					$('head title').text(siteInfo.pageTitle);
				}
			}
		});
		Wix.getSiteInfo( function(siteInfo) {
			var siteTitle = "<?php echo $options['siteTitle']; ?>";
			var siteDescription = "<?php echo $options['siteDescription']; ?>";
			var siteKeywords = "<?php echo $options['siteKeywords']; ?>";
			// updte siteUrl when it is changed
			if ((siteTitle != siteInfo.siteTitle) || 
					(siteDescription != siteInfo.siteDescription) || (siteKeywords != siteInfo.siteKeywords)) {
				$.post('<?php echo url(array('action' => 'updateSEOInfo', 'ajax' => 'true')); ?>', siteInfo, function() {
					// do nothing
				});
			}
		});
		$(document).ready(function() {
			// Add target="_blank" to all links in text
			$(".caption a:not([target])").attr("target","_blank")
			
      
			// replace video links
			$(".content .video").each(function() {
				var w = $(this).parent().width()-20;
				if($(".post.video .line").length != 0) {
					w = w*0.48;
 				}
				var h = ((w-20)/2)+40;
				if((w >= 200) && (h < 190)){
					h = 200;
				}
        
				var ifr = ((w >= 200) && (h >= 190));
				if(ifr) {                                  
				  	ifr = getEmbeddedPlayer($(this).text(), "93.5%", h);                                    
				  	if(ifr != false) {
					  	$(this).empty();
				  		$(this).prepend(ifr);
			  		}
            var iframe = $(this).find("iframe");
            $(iframe).attr("height", $(iframe).width() / 100 * 57);            
				} 
				if(!ifr){
					var l = $(this).text();
					$(this).empty();
					var r = '<a target="_blank" href="'+l+'" class="video-button-link">.</a>';
			  		$(this).prepend(r);
				}
                reportHeight();
		  	});
		
			if($(".line").length != 0) {
				var w = 0;
				if($(".post.video").length > 0){
					w = $(".post.video")[0].clientWidth;
				} else if($(".post.photo").length > 0){
					w = $(".post.photo")[0].clientWidth;
				}
				if($(".content .video iframe").length == false){
					$(".content .video").css("width",Math.floor(w*0.48));
				}
				$(".content .video").css("margin",Math.floor(w*0.01));
				$(".content .url").css("width",Math.floor(w*0.48));
				$(".content .url").css("margin",Math.floor(w*0.009));
				
				$(".post.video .caption").css("width",Math.floor(w*0.48));
				$(".post.video .caption").css("margin",Math.floor(w*0.01));
				$(".post.photo .caption").css("width",Math.floor(w*0.48));
				$(".post.photo .caption").css("margin",Math.floor(w*0.01));
				$(".post.link .caption").css("width",Math.floor(w*0.48));
				$(".post.link .caption").css("margin",Math.floor(w*0.009));

                reportHeight();
			}

			// Load Share buttons APIs asynchronously 
			var addAsyncScript = function(d, s, id, url) {
				  var js, fjs = d.getElementsByTagName(s)[0];
				  if (d.getElementById(id)) return;
				  js = d.createElement(s); js.id = id; js.async=true;
				  js.src = url;
				  fjs.parentNode.insertBefore(js, fjs);
			};

			<?php if (isset($options['share_fb']) && $options['share_fb']): ?>
			// Facebook 
			addAsyncScript(document, 'script', 'facebook-jssdk', '//connect.facebook.net/en_US/all.js#xfbml=1&status=0');
			<?php endif; ?>
			
			<?php if (isset($options['share_gplus']) && $options['share_gplus']): ?>
			// Google+ 
			addAsyncScript(document, 'script', 'gplus-jssdk', '//apis.google.com/js/plusone.js');
			<?php endif; ?>

			<?php if (isset($options['share_twitter']) && $options['share_twitter']): ?>
			// Twitter 
			addAsyncScript(document, 'script', 'twitter-jssdk', '//platform.twitter.com/widgets.js');
			<?php endif; ?>

			<?php if (isset($options['share_pinterest']) && $options['share_pinterest']): ?>
			// Pinterest 
			addAsyncScript(document, 'script', 'pinterest-jssdk', '//assets.pinterest.com/js/pinit.js');
			<?php endif; ?>

			checkSocialInitialized = function() {
                reportHeight();
				if (
					<?php if (isset($options['share_fb']) && $options['share_fb']): ?>
						typeof (FB) == 'undefined' ||
					<?php endif; ?>
					<?php if (isset($options['share_gplus']) && $options['share_gplus']): ?>
						typeof (gapi) == 'undefined' ||
					<?php endif; ?>
					<?php if (isset($options['share_twitter']) && $options['share_twitter']): ?>
						typeof (twttr) == 'undefined' ||
					<?php endif; ?>
					<?php if (isset($options['share_pinterest']) && $options['share_pinterest']): ?>
						!$('.pinterest-wrapper a').is('[target]') ||
					<?php endif; ?>
						false
				) {
					window.setTimeout(checkSocialInitialized, 1000);
				}
			};

			window.setTimeout(checkSocialInitialized, 2000);
		});
		$(document).imagesLoaded(function() {
		  	$(".content.line .photo").each(function() {
	  			var space = $(this).parent().height()-$(this).height();
	  			$(this).css("margin-top",Math.floor(space/2));
	  			$(this).css("margin-bottom",Math.floor(space/2));	
		  	});
		  	$(".content.line .video").each(function() {
	  			var space = $(this).parent().height()-$(this).height();
	  			$(this).css("margin-top",Math.floor(space/2));
	  			$(this).css("margin-bottom",Math.floor(space/2));	
		  	});
		  	$(".content.line .url").each(function() {
	  			var space = $(this).parent().height()-$(this).height();
	  			$(this).css("margin-top",Math.floor(space/2));
	  			$(this).css("margin-bottom",Math.floor(space/2));	
		  	});

        
        $(window).resize(function()
        {                                                                                        
          $(".video").each(function()
          {                  
            var iframe = $(this).find("iframe");
            $(iframe).attr("height", $(iframe).width() / 100 * 57);
          });
        });        
		  	<?php if ($options['pageLayout'] == 'pinterest'):?> 
        $(window).resize(function()
        {                                                                                                                                                       
		  	  $('.pinterest:not(.columns_1,.single_post) .displayedPosts').masonry({
		  	    // options
		  	    itemSelector : '.wrapper'
		  	  });
	  	  });
        <?php endif; ?>

            reportHeight();



		});

        function reportHeight(){
            var newHeight = $(".allPosts").innerHeight();
            if(lastHeight != newHeight) {
                lastHeight = newHeight;
                Wix.setHeight($(".allPosts").innerHeight());
            } else {
                clearInterval(heightInterval);
            }
        }
	</script> 
</body>
</html>
