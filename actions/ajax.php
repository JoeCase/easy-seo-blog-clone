<?php
require_once 'locale/localization.php';
require_once 'actions/functions.php';

function loadContent() {
	if (isset($_REQUEST['esb_postid']) && $_REQUEST['esb_postid']) {
		$GLOBALS['post'] = loadPost($_REQUEST['esb_postid']);
	}
	
	if (isset($_REQUEST['esb_file']) && $_REQUEST['esb_file']) {
		require $_REQUEST['esb_file'];
		return 0;
	}
	else {
		return 501;
	}
}

function setPublished() {
	$status = 0;
	$published = 0;
	if (isset($_REQUEST['esb_postid']) && $_REQUEST['esb_postid'] && isset($_REQUEST['esb_published'])) {
		$published = intval($_REQUEST['esb_published']);
		$status = updatePost(array('id' => $_REQUEST['esb_postid'], 'published' => $published));
	}
	$published = intval(!$published);
	return array(
		'msg' => $status,
		'published' => $published,
		'url' => url(array( 'esb_published' => $published ))
	);
}

function tagsAutocomplete() {
	$categories = find_similar_categories_by_slug(get_slug($_REQUEST['term']));
	return array_unique( array_merge((array) $_REQUEST['term'], objValues($categories, 'name')) );
}