<?php
if(strpos(getcwd(), "actions")){
	chdir('..');
}
require_once "/locale/localization.php"; 
require_once "/actions/functions.php";
require_once "/actions/database.php";
require_once "/actions/options.php";
require_once "/social/facebook/src/facebook.php";
require_once "/social/twitter/tmhOAuth.php";
require_once "/social/twitter/tmhUtilities.php";

if(isset($_REQUEST['scaction'])){
	if(is_callable($_REQUEST['scaction'])){
		call_user_func($_REQUEST['scaction']);
	}
}

function facebook_login() {
	if(isset($_REQUEST['uid']) && $_REQUEST['uid'] != null){
		$user = trim($GLOBALS['options']['user']);
		$uid = trim($_REQUEST['uid']);
		
		$facebook = new Facebook(array(
		  'appId'  => FACEBOOK_APP_ID,
		  'secret' => FACEBOOK_APP_SECRET,
		  'fileUpload' => true,
		  'cookie' => true
		));
		$facebook->setExtendedAccessToken(); //long-live access_token 60 days
    	$access_token = $facebook->getAccessToken();
    
		$user_info = $facebook->api('/'.$uid);
		
		$mysqli = connect();
		//$mysqli->query("UPDATE `options` SET `facebookId` = '".trim($_REQUEST['uid'])."' WHERE `user`= '".trim($user)."'");
		$result = $mysqli->prepare("UPDATE `options` SET `facebookId` = ?, `facebookAT` = ? WHERE `user` = '".$user."'");
		
		$result->bind_param('ss', $uid, $access_token); 
		if ( $result->execute() ) {
			$result->close();
		} 
		
		close($mysqli);
		echo $uid;
	}
}

function facebook_logout() {
		$user = trim($GLOBALS['options']['user']);
		$mysqli = connect();
		
		//$mysqli->query("UPDATE `options` SET `facebookId` = '".trim($_REQUEST['uid'])."' WHERE `user`= '".trim($user)."'");
		$result = $mysqli->prepare("UPDATE `options` SET `facebookId` = NULL, `facebookAT` = NULL WHERE `user` = ?");
		$result->bind_param('s', $user); 
		if ( $result->execute() ) {
			$result->close();
		} 
		if (isset($_SESSION['fb_' . FACEBOOK_APP_ID . '_code'])) { unset ($_SESSION['fb_' . FACEBOOK_APP_ID . '_code']); }
		if (isset($_SESSION['fb_' . FACEBOOK_APP_ID . '_access_token'])) { unset ($_SESSION['fb_' . FACEBOOK_APP_ID . '_access_token']); }
		if (isset($_SESSION['fb_' . FACEBOOK_APP_ID . '_user_id'])) { unset ($_SESSION['fb_' . FACEBOOK_APP_ID . '_user_id']); }
		close($mysqli);
}

function twitter_logout() {
		$user = trim($GLOBALS['options']['user']);
		$mysqli = connect();
		
		//$mysqli->query("UPDATE `options` SET `facebookId` = '".trim($_REQUEST['uid'])."' WHERE `user`= '".trim($user)."'");
		$result = $mysqli->prepare("UPDATE `options` SET `twitterId` = NULL, `twitterSecret` = NULL WHERE `user` = ?");
		$result->bind_param('s', $user); 
		if ( $result->execute() ) {
			$result->close();
		} 
		close($mysqli);
		echo getTwitterAuthUrl($_REQUEST['href']);
}
function twitter_login($user = NULL, $twid = NULL, $twsec = NULL) {
		if($user != NULL){ $user = trim($GLOBALS['options']['user']); }
		if($twid != NULL){ $twid = trim($GLOBALS['options']['user']); }
		if($twsec != NULL){ $twsec = trim($GLOBALS['options']['user']); }
		
		$mysqli = connect();
		//$mysqli->query("UPDATE `options` SET `facebookId` = '".trim($_REQUEST['uid'])."' WHERE `user`= '".trim($user)."'");
		$result = $mysqli->prepare("UPDATE `options` SET `twitterId` = ?, `twitterSecret` = ? WHERE `user` = '".$user."'");
		
		$result->bind_param('ss', $twid, $twsec); 
		if ( $result->execute() ) {
			$result->close();
		} 
		
		close($mysqli);
}
function getTwitterAuthUrl($href = ""){
	$tmhOAuth = new tmhOAuth(array(
  		'consumer_key'    => TWITTER_APP_KEY,
	  	'consumer_secret' => TWITTER_APP_SECRET,
	));
	
	$here = tmhUtilities::php_self();
	session_start();
	
	$callback = getTwitterUrl("social/twitter/save_twitter_acc.php",$href);
	$params = array(
		'oauth_callback' => $callback
	);
	
	$params['x_auth_access_type'] = 'write';
	
	$code = $tmhOAuth->request('POST', $tmhOAuth->url('oauth/request_token', ''), $params);
	
	if ($code == 200) {
	  	$_SESSION['oauth'] = $tmhOAuth->extract_params($tmhOAuth->response['response']);
	    $method = isset($_REQUEST['authenticate']) ? 'authenticate' : 'authorize';
	    $force  = isset($_REQUEST['force']) ? '&force_login=1' : '';
	    $authurl = $tmhOAuth->url("oauth/{$method}", '') .  "?oauth_token={$_SESSION['oauth']['oauth_token']}{$force}";
	  	return $authurl;
	} else {
	  	return null;
	}
}

function getTwitterUrl($replacement = "", $href = ""){
	$query = $_SERVER['QUERY_STRING'];
	if($href != "") {
		$arr = explode("?", $href);
		$query = $arr[1];	 	
	}
	if(substr($_SERVER['HTTP_HOST'], strlen($_SERVER['HTTP_HOST'])*-1) == "/"){
		return (strpos($_SERVER['HTTP_HOST'], "http") ? $_SERVER['HTTP_HOST'] : "http://".$_SERVER['HTTP_HOST']).$replacement."?".$query;
	} else {
		return (strpos($_SERVER['HTTP_HOST'], "http") ? $_SERVER['HTTP_HOST'] : "http://".$_SERVER['HTTP_HOST'])."/".$replacement."?".$query;
	}
}