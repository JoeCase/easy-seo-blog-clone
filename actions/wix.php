<?php
require_once 'config.php';

function getInstanceID() {
	return getSignedInstance()->instanceId;
} 

function getSignedInstance()
{
	if (isset($_SERVER['HTTP_REFERER'])) {
		preg_match( '/\instance=(.*?)\&/', $_SERVER["HTTP_REFERER"], $instanceRE );
	}
	else {
		$instanceRE = false;
	}
	
	$instance = false;
	if ($instanceRE && $instanceRE[1] != '') {
		$instance = $instanceRE[1];
    }
    else {
    	$instance = $_GET['instance'];
    }
    
    if ($instance !== false) {
    	list( $code, $data ) = explode( '.', $instance );
	    if ( base64_decode( strtr( $code, "-_", "+/" ) ) != hash_hmac( "sha256", $data, WIX_APP_SECRET_KEY, TRUE ) )
	    {
	    	echo "UNBLE DECODE INSTANCE";
	        die();  // TODO: Report error 
	    }
	    if ( ( $json = json_decode( base64_decode( $data ) ) ) === null )
	    {
	    	echo "UNBLE DECODE JSON";
	        die();  // TODO: Report error
	    }
    	return $json;
	}
    
}
?>