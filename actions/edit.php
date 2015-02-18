<?php
include_once "read.php";

function loadPostForEdit() {
	return array( loadPost($_REQUEST["esb_postid"], null, null, true) );
}

?>