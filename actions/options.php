<?php
require_once "functions.php";
require_once "database.php";
require_once "wix.php";
require_once "save.php";
require_once 'locale/localization.php';

$GLOBALS['DEFAULT_OPTONS'] = array (
	'unlimitedFeeds'	=> false,
	'unlimitedRows'		=> false,
	'postLayout'		=> 'basic',
	'allowRating'		=> false,
	'ratingType'		=> '1'
);

$signInstance = getSignedInstance();
if( !isset( $GLOBALS['options'] ) || !isset( $GLOBALS['options']['user'] ) || ($GLOBALS['options']['user'] != $signInstance->instanceId)) {
	// fill options variable by data about application
	$GLOBALS['options']['version'] = '1.6.6';
	$GLOBALS['options']['user'] = $signInstance->instanceId;
	$GLOBALS['options']['signDate'] = $signInstance->signDate;
	$GLOBALS['options']['uid'] = isset($signInstance->uid) ? $signInstance->uid : null;
	$GLOBALS['options']['permissions'] = isset($signInstance->permissions) ? $signInstance->permissions : null;
	$GLOBALS['options']['ipAndPort'] = $signInstance->ipAndPort;
	$GLOBALS['options']['vendorProductId'] = isset($signInstance->vendorProductId) ? $signInstance->vendorProductId : null;
	$GLOBALS['options']['is_premium'] = ($GLOBALS['options']['vendorProductId'] == 'PREMIUM_USER');
	$GLOBALS['options']['demoMode'] = $signInstance->demoMode;
	
	$mysqli = connect();
	$result = $mysqli->query("SELECT * FROM `options` WHERE `user`= '".$signInstance->instanceId."'");
	if ( $result && $result->num_rows > 0 ) {
		$GLOBALS['options'] = array_merge($GLOBALS['options'], _getOptions($result->fetch_object()));
		$result->close();
	} else {
		$stmt = $mysqli->prepare("INSERT INTO `options` (`user`) VALUES (?)");
	  	$stmt->bind_param('s', $signInstance->instanceId); 
	  	if ( $stmt->execute() ) {
			$stmt->close();
	  		// get default values from DB
	  		if ( $result = $mysqli->query("SELECT * FROM `options` WHERE `user`= '".$signInstance->instanceId."'") ) {
	  			$GLOBALS['options'] = array_merge($GLOBALS['options'], _getOptions($result->fetch_object()));
	  			$result->close();
	  		}
	  	} 
	  	
	}
	close($mysqli);
}

function saveOptions() {
	// chceckboxes "unchecked" value is set first
	$options = array_merge(array(
		'unlimitedFeeds' => 0,
		'unlimitedRows' => 0,
		'bgOpacity' => 0,
		'postBgOpacity' => 0,
		'allowRating' => 0,
		'paginationShowNextPrev' => 0,
		'paginationShowNumbers' => 0,
		'paginationShowFirstLast' => 0,
		'paginationShowNextPrevPost' => 0,
		'showDisplayByTag' => 0,
		'showBackToFeed' => 0,
    'showReadMore' => 0,
    'showDatesFeed' => 0,
    'showDatesDetail' => 0,
    'showDatesFeedMobile' => 0,
    'showDatesDetailMobile' => 0,    
		'share_show_list_top' => 0,
		'share_show_list_bottom' => 0,
		'share_show_detail_top' => 0,
		'share_show_detail_bottom' => 0,
		'share_fb' => 0,
		'share_gplus' => 0,
		'share_linkedin' => 0,
		'share_twitter' => 0,
		'share_pinterest' => 0,
		'showSidebar' => 0,
		'showRecentPosts' => 0,
		'showTagCloud' => 0,
		'enableSidebarDivider' => 0,
		'sidebarDividerColorOpacity' => 0,
	), $_REQUEST['options']);
	
	// Preprocess special variables in Options
	if (isset($options['share_order'])) {
		if (!is_array($options['share_order'])) {
			parse_str($options['share_order'], $shareArray);
			$options['share_order'] = $shareArray['share'];
		}
		$options['share_order'] = serialize($options['share_order']);
	}
	
	// get columns for update
	$columns = implode('=?, ', array_keys($options)) . '=?';
	
	// add user to end of array
	$options['user'] = $GLOBALS['options']['user'];
	$types = implode('', array_fill(0, count($options), 's'));
	
	$error = 0;
	if ($mysqli = connect()) {
		// create a options UPDATE statement
		
		if ($stmt = $mysqli->prepare('UPDATE `options` SET '.$columns.' WHERE user = ?')) {
				
			$refs = array();
			$refs[] = &$types;
			foreach($options as $key => $value) {
				if ($key != 'share_order') {
					$options[$key] = htmlspecialchars($options[$key]);
				}
				$refs[] = &$options[$key];
			}
				
			if (call_user_func_array (array($stmt,'bind_param'), $refs)) {
				// execute update query
				if (!$stmt->execute()) {
					$error = 204;
				}
			}
			else {
				$error = 203;
			}
			$stmt->close();
		}
		else {
			$error = 202;
		}
		close($mysqli);
	}
	else {
		$error = 101;
	}
	return $error;
}

function updateSiteInfo() {
	$error = 0;
	if ($mysqli = connect()) {
		// create statement
		if ($stmt = $mysqli->prepare('UPDATE `options` SET baseUrl = ? WHERE user = ?')) {
			// create statement
			$baseUrl = urlescape($_REQUEST['baseUrl']);
			if ($stmt->bind_param('ss', $baseUrl, $GLOBALS['options']['user'])) {
				// execute update query
				if (!$stmt->execute()) {
					$error = 204;
				}
			}
			else {
				$error = 203;
			}
			$stmt->close();
		}
		else {
			$error = 202;
		}
		close($mysqli);
	}
	else {
		$error = 101;
	}
	return $error;
}

function updateSEOInfo() {
	$error = 0;
	if ($mysqli = connect()) {
		// create statement
		if ($stmt = $mysqli->prepare('UPDATE `options` SET siteTitle = ?, 
									  siteDescription = ?, siteKeywords = ? WHERE user = ?')) {
			// create statement
			$siteTitle = htmlspecialchars($_REQUEST['siteTitle']);
			$siteDescription = htmlspecialchars($_REQUEST['siteDescription']);
			$siteKeywords = htmlspecialchars($_REQUEST['siteKeywords']);
			if ($stmt->bind_param('ssss', $siteTitle, $siteDescription, $siteKeywords, $GLOBALS['options']['user'])) {
				// execute update query
				if (!$stmt->execute()) {
					$error = 204;
				}
			}
			else {
				$error = 203;
			}
			$stmt->close();
		}
		else {
			$error = 202;
		}
		close($mysqli);
	}
	else {
		$error = 101;
	}
	return $error;
}

function _getOptions($row) {
	
	// ---------------
	// options from DB
	// ---------------
	
	$options = (array) $row;
	
	// for non-premium users reset options to default.
	if (!$GLOBALS['options']['is_premium']) {
		$options = array_merge($options, $GLOBALS['DEFAULT_OPTONS']);
	};
	
	// Postprocess special variables in Options
	if (isset($options['share_order'])) {
		$options['share_order'] = unserialize($options['share_order']);
	}
	
	// ------------------
	// consolited options
	// ------------------
	
	if(isset($_GET['deviceType']) && $_GET['deviceType'] == 'mobile'){
		$options['pageLayout'] = 'feed';
		$options['page_rows'] = $options['mobilePostsCount'];
		$options['page_columns'] = 1;
		$options['posts_per_page'] = $options['mobilePostsCount'];
		$options['infinite_page'] = (bool) $options['unlimitedFeeds'];
	} else {		
	
		// posts_per_page and infinite_page options
		if ($options['pageLayout'] == 'feed') {
			$options['page_rows'] = $options['lastPostsCount'];
			$options['page_columns'] = 1;
			$options['posts_per_page'] = $options['lastPostsCount'];
			$options['infinite_page'] = (bool) $options['unlimitedFeeds'];
		}
		elseif ($options['pageLayout'] == 'grid') {
			$options['page_rows'] = $options['pageRows'];
			$options['page_columns'] = $options['pageColumns'];
			$options['posts_per_page'] = $options['pageRows']*$options['pageColumns'];
			$options['infinite_page'] = (bool) $options['unlimitedRows'];
		}
		elseif ($options['pageLayout'] == 'pinterest') {
			$options['page_rows'] = $options['pinterestRows'];
			$options['page_columns'] = $options['pinterestColumns'];
			$options['posts_per_page'] = $options['pinterestRows']*$options['pinterestColumns'];
			$options['infinite_page'] = (bool) $options['pinterestUnlimitedRows'];
		}
		else { // pageLayout == 'post'
			$options['page_rows'] = 1;
			$options['page_columns'] = 1;
			$options['posts_per_page'] = 1;
			$options['infinite_page'] = false;
		}
	}
	$fonts = array();	
	foreach (loadFonts() as $k => $v){
		$fonts[$v->fontId] = $v;
	}
	$options['fonts'] = $fonts;
	
  //RGB colors
  $options['bg_color_rgb'] = $options['bgColor'];
	$options['post_bg_color_rgb'] = $options['postBgColor'];
  $options['border_color_rgb'] = $options['borderColor'];
	$options['text_color_rgb'] = $options['textColor'];
	$options['links_color_rgb'] = $options['linksColor'];
	$options['title_color_rgb'] = $options['titleColor'];
	$options['tags_color_rgb'] = $options['tagsFontColor'];
	$options['date_color_rgb'] = $options['dateFontColor'];
	$options['pagination_color_rgb'] = $options['paginationFontColor'];
	$options['btf_color_rgb'] = $options['btfFontColor'];
	$options['dbt_color_rgb'] = $options['dbtFontColor'];
	$options['sidebar_title_font_color_rgb'] = $options['sidebarTitleFontColor'];
	$options['sidebar_tags_font_color_rgb'] = $options['sidebarTagsFontColor'];
	$options['sidebar_recent_posts_font_color_rgb'] = $options['sidebarRecentPostsFontColor'];
	$options['sidebar_divider_color_rgb'] = $options['sidebarDividerColor']; 
	$options['read_more_color_rgb'] = $options['readMoreFontColor'];
  
  // RGBA colors
	$options['bg_color_rgba'] = 'rgba('. hex2RGB($options['bgColor'], true) . ',' . ( $options['bgOpacity'] ? $options['bgOpacityValue'] : '1') . ')';
	$options['post_bg_color_rgba'] = 'rgba('. hex2RGB($options['postBgColor'], true) . ',' . ( $options['postBgOpacity'] ? $options['postBgOpacityValue'] : '1') . ')';
	$options['border_color_rgba'] = 'rgba('. hex2RGB($options['borderColor'], true) . ',1)';
	$options['text_color_rgba'] = 'rgba('. hex2RGB($options['textColor'], true) . ',1)';
	$options['links_color_rgba'] = 'rgba('. hex2RGB($options['linksColor'], true) . ',1)';
	$options['title_color_rgba'] = 'rgba('. hex2RGB($options['titleColor'], true) . ',1)';
	$options['tags_color_rgba'] = 'rgba('. hex2RGB($options['tagsFontColor'], true) . ',1)';
	$options['date_color_rgba'] = 'rgba('. hex2RGB($options['dateFontColor'], true) . ',1)';
	$options['pagination_color_rgba'] = 'rgba('. hex2RGB($options['paginationFontColor'], true) . ',1)';
	$options['btf_color_rgba'] = 'rgba('. hex2RGB($options['btfFontColor'], true) . ',1)';
	$options['dbt_color_rgba'] = 'rgba('. hex2RGB($options['dbtFontColor'], true) . ',1)';
	$options['sidebar_title_font_color_rgba'] = 'rgba('. hex2RGB($options['sidebarTitleFontColor'], true) . ',1)';
	$options['sidebar_tags_font_color_rgba'] = 'rgba('. hex2RGB($options['sidebarTagsFontColor'], true) . ',1)';
	$options['sidebar_recent_posts_font_color_rgba'] = 'rgba('. hex2RGB($options['sidebarRecentPostsFontColor'], true) . ',1)';
	$options['sidebar_divider_color_rgba'] = 'rgba('. hex2RGB($options['sidebarDividerColor'], true) . ',' . ( $options['sidebarDividerColorOpacity'] ? $options['sidebarDividerColorOpacityValue'] : '1') . ')';
	$options['read_more_color_rgba'] = 'rgba('. hex2RGB($options['readMoreFontColor'], true) . ',1)';
	
	return $options;
}

?>