<?php
chdir('../..');
require_once("actions/options.php");
require_once "/social/twitter/tmhOAuth.php";
require_once "/social/twitter/tmhUtilities.php";

session_start();
twitter_login($GLOBALS['options']['user'], $_SESSION['oauth']['oauth_token'], $_SESSION['oauth']['oauth_token_secret']);
echo "<script>window.close();</script>";

function twitter_login($user = NULL, $twid = NULL, $twsec = NULL) {
		if($user != NULL){ $user = trim($GLOBALS['options']['user']); }
		
		$tmhOAuth = new tmhOAuth(array(
		  'consumer_key'    => 'qGMrXNLGfVcQQbt5fMfQ',
	  	  'consumer_secret' => 'FdUYnLxUYi8fqqphgr3SrXYWFAod6bDCingKtlD4',
		));
		
		$tmhOAuth->config['user_token']  = $_SESSION['oauth']['oauth_token'];
  		$tmhOAuth->config['user_secret'] = $_SESSION['oauth']['oauth_token_secret'];

  		if(isset($_REQUEST['oauth_verifier'])) {
			$code = $tmhOAuth->request('POST', $tmhOAuth->url('oauth/access_token', ''), array(
			  'oauth_verifier' => $_REQUEST['oauth_verifier']
			));
	
			if ($code == 200) {
			  $_SESSION['access_token'] = $tmhOAuth->extract_params($tmhOAuth->response['response']);
			  //unset($_SESSION['oauth']);
			  if($twid != NULL){ $twid = trim($_SESSION['access_token']['oauth_token']); }
			  if($twsec != NULL){ $twsec = trim($_SESSION['access_token']['oauth_token_secret']); }
			
	 	      $mysqli = connect();
	   		  //$mysqli->query("UPDATE `options` SET `facebookId` = '".trim($_REQUEST['uid'])."' WHERE `user`= '".trim($user)."'");
			  $result = $mysqli->prepare("UPDATE `options` SET `twitterId` = ?, `twitterSecret` = ? WHERE `user` = '".$user."'");
			
			  $result->bind_param('ss', $twid, $twsec); 
			  if ( $result->execute() ) {
	  			  $result->close();
			  } 
			
			  close($mysqli);
			} else {
				echo "<script>window.close();</script>";	
			  	//outputError($tmhOAuth);
			}
  		} else {
  			echo "<script>window.close();</script>";
  		}
}
//print_r($_SESSION['oauth']);
/*-- Save data fo DB --*/