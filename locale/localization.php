<?php
if((isset($_REQUEST['locale'])) && (file_exists('locale/locale_'.$_REQUEST['locale'].'.php'))){
	require_once 'locale_'.$_REQUEST['locale'].'.php';
} else {
	require_once 'locale.php';
}

require_once 'messages.php';

function __($s){	
	global $LOCALE;
	$args = array_slice(func_get_args(), 1);
	return isset($LOCALE[$s]) ? call_user_func_array('sprintf', array_merge((array) $LOCALE[$s], $args)) : call_user_func_array('sprintf', array_merge((array) $s, $args));
}

function _e($s) {
	echo call_user_func_array('__', func_get_args());
}