<?php 

 if(isset($_GET['deviceType']) && $_GET['deviceType'] == 'mobile'){
 	require_once 'mobile.php';
 } else {
 	require_once 'page.php';
 }

?>