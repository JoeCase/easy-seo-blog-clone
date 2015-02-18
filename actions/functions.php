<?php
require_once "database.php";

function url($parameters = null, $exclude = null) {
	// merge $_GET and $parameters
	if ($parameters !== null) {
		$parameters = array_merge((array) $_GET, (array) $parameters);
	}
	else {
		$parameters = $_GET;
	}

	// exclude required
	if (is_array($exclude)) foreach ($exclude as $key) {
		if (isset($parameters[$key])) {
			unset($parameters[$key]);
		}
	}

	// create new URL search
	$url = '';
	foreach ($parameters as $key => $value) {
		$url .= urlencode($key).'='.urlencode($value).'&';
	}
	return $_SERVER['SCRIPT_NAME'].'?'.substr($url, 0, -1);
}

function root_path() {
	return substr($_SERVER['SCRIPT_NAME'], 0, strrpos( $_SERVER['SCRIPT_NAME'], '/' ) + 1 );
}

function base_url() {
	$pageURL = "//";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].root_path();
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].root_path();
	}
	return $pageURL;
}

function urlescape($url, $allowedProtocols = array('http', 'https', 'ftp', 'mailto', 'news', 'nntp', 'gopher', 'mid', 'cid', 'prospero', 'telnet', 'rlogin', 'tn3270', 'wais')) {
	$urlLower = strtolower($url);
	$protocolAllowed = startsWith($urlLower, '//');
	if (!$protocolAllowed) foreach ($allowedProtocols as $protocol) {
		if (startsWith($urlLower, $protocol.'://')) {
			$protocolAllowed = true;
			break;
		}
	}
	return $protocolAllowed ? $url : 'http://'.$url;
}

function startsWith($str, $nedle) {
	return !strncmp($str, $nedle, strlen($nedle));
}

function curPageURL() {
 $pageURL = 'http';
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"];
 }
 return $pageURL;
}

function removeParameterFromUrl( $url = "" ) {	
	$link = preg_replace('~(\?|&)esb_page=[^&]*~','$1',$url);
	while(substr($link, -1) == '&'){
		$link = substr($link, 0, strlen($link)-1);
	}
	return $link;
}

/**
 * Convert a hexa decimal color code to its RGB equivalent
 *
 * @param string $hexStr (hexadecimal color value)
 * @param boolean $returnAsString (if set true, returns the value separated by the separator character. Otherwise returns associative array)
 * @param string $seperator (to separate RGB values. Applicable only if second parameter is true.)
 * @return array or string (depending on second parameter. Returns False if invalid hex color value)
 */
function hex2RGB($hexStr, $returnAsString = false, $seperator = ',') {
	$hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr); // Gets a proper hex string
	$rgbArray = array();
	if (strlen($hexStr) == 6) { //If a proper hex code, convert using bitwise operation. No overhead... faster
		$colorVal = hexdec($hexStr);
		$rgbArray['red'] = 0xFF & ($colorVal >> 0x10);
		$rgbArray['green'] = 0xFF & ($colorVal >> 0x8);
		$rgbArray['blue'] = 0xFF & $colorVal;
	} elseif (strlen($hexStr) == 3) { //if shorthand notation, need some string manipulations
		$rgbArray['red'] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
		$rgbArray['green'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
		$rgbArray['blue'] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
	} else {
		return false; //Invalid hex color code
	}
	return $returnAsString ? implode($seperator, $rgbArray) : $rgbArray; // returns the rgb string or the associative array
}

function objValues(array $objArray, $property) {
	$result = array();
	foreach ($objArray as $obj) {
		if (isset($obj->{$property}))
		$result[] = $obj->{$property};
	}
	return $result;
}

function replace_post_vars($str, $post) {
	return str_ireplace(array('!TITLE', '!URL'), array($post->title, $post->permalink), $str);
}

function loadFonts($whereArr = array()){
	$fonts = array();
	$where = "";
	if(($whereArr != null) && (sizeof($whereArr))){
		$where = "WHERE fontId IN ('".join("','",$whereArr)."')";
	}
	$mysqli = connect();
	$result = $mysqli->query("SELECT * FROM `font` ".$where." GROUP BY fontId ORDER BY fontId ASC");
	while ($row = $result->fetch_object()) {
		$fonts[] = $row;
	}
	$result->close();
	
	return $fonts;
}
function loadFontsSelect($arr = array(), $select = "", $selectorName){
	$return = '<div class="fonts-select">
					<select class="select-box" name="options['.$selectorName.']">';			
	foreach ($arr as $k => $v){
		if($v->fontId != $select){
			$return .= '<option value="'.$v->fontId.'" style="font-family:'.$v->fontFamily.' !important;">'.$v->fontName.'</option>';
		} else {
			$return .= '<option value="'.$v->fontId.'" selected="selected" style="font-family:'.$v->fontFamily.' !important;">'.$v->fontName.'</option>';
		}
	}	
	$return .= '</select></div>';
	return $return;
}
function loadDataForTagCloud($mysqli, $user){
	$tags = array();
	if($mysqli == null) {
		$mysqli = connect();
	}
	$result = $mysqli->query("SELECT name, slug, COUNT(*) as count FROM `category` AS c, `post_category` AS p WHERE c.id = p.category_id AND `user` = '".$user."' GROUP BY `slug`");
	while ($row = $result->fetch_object()) {
		$tags[] = $row;
	}
	$result->close();
	
	return $tags;
}
?>