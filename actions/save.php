<?php
require_once "wix.php";
require_once "database.php";
require_once "read.php";
require_once 'htmlpurifier/library/HTMLPurifier.auto.php';
/*-- require_once "/social/facebook/src/facebook.php";
require_once "/social/twitter/tmhOAuth.php";
require_once "/social/twitter/tmhUtilities.php"; --*/

function save($ts = array()) {	
	$mysqli = connect();
	if ($mysqli) {
		$error = 0;
		if(!isset($ts['id']) || $ts['id'] < 0) {
			$slug = find_unused_slug_for_post($ts['slug'], null, $mysqli, $ts['user']);
			if (!is_array($slug)) {
				$error = $slug;
			}
			elseif ($stmt = $mysqli->prepare("INSERT INTO `post` (`user`, `title`, `slug`, `content`, `url`, `type`, `published`, `postdate`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)")) {
				$purifier = new HTMLPurifier();
				$ts['title'] = htmlspecialchars($ts['title']);
				$ts['content'] = $ts['content'];//$purifier->purify($ts['content']);
				$ts['url'] = urlescape($ts['url']);
				$ts['published'] = intval($ts['published']);
				if ($stmt->bind_param('ssssssis', $ts['user'], $ts['title'], $slug[0], $ts['content'], $ts['url'], $ts['type'], $ts['published'], $ts['postdate'])) {
					if ($stmt->execute()) {
						$error = 1;
						// insert selected categories
						save_post_category_refs($mysqli, $stmt->insert_id, save_categories($mysqli, $ts['tags']));
					}
					else {
						$error = 104;
					}
				}
				else {
					$error = 103;
				}
				// close opened statement (do not report any error)
				$stmt->close();
				/*if($ts['published'] == 1){
					if(($ts['fbEnable'] != null) && ($ts['fb'] != null)){
						postOnFacebook($ts, $slug[0]);
					}
					if($ts['twEnable'] != null){
						postOnTwitter($ts, $slug[0]);
					}
				}*/
			}
			else {
				$error = 102;
			}
		} else {
			$slug = find_unused_slug_for_post($ts['slug'], $ts['id'], $mysqli, $ts['user'], $ts['published']);
			if (!is_array($slug)) {
				$error = $slug;
			}
			else {
				$ts['slug'] = $slug[0];
				$error = updatePost($ts, $mysqli);
				
			}
		}
		close($mysqli);
	}
	else {
		$mysqli = 101;
	}

	return $error;
}

function updatePost($post, $mysqli = null) {
	// check if update could be performed
	if (!is_array($post)) {
		$post = (array) $post;
	}
	if (!isset($post['id']) || !$post['id']) {
		// unable update post when ID is not known
		return 205;
	}
	$post_id = $post['id'];
	unset($post['id']);
	$user = null;
	if (isset($post['user'])) { 
		$user = $post['user'];
		unset($post['user']);
	}
	
	// remove tags if present
	$tags = null;
	if (isset($post['tags'])) {
		$tags = $post['tags'];
		unset($post['tags']);
	}
	
	// chceckboxes "unchecked" value is set first
	$post = array_merge(array(
			// 'checkbox' => 0 
	), $post);

	// get columns for update
	$columns = implode('=?, ', array_keys($post)) . '=?';
	$types = implode('', array_fill(0, count($post), 's'));

	// add ID and USER to end of array
	$post['id'] = $post_id;
	$post['user'] = isset($user) && $user ? $user : $GLOBALS['options']['user'];
	$types .= 'is';
	$error = 0;
	$mysqli_passed = $mysqli !== null;
	
	if (!$mysqli_passed) {
		$mysqli = connect();
	}
			
	if ($mysqli) {
		// create a post UPDATE statement
		if ($stmt = $mysqli->prepare('UPDATE `post` SET '.$columns.' WHERE `id` = ? AND `user` = ?')) {

			$purifier = new HTMLPurifier();
			$arr = array_merge( array($types), $post);
			$refs = array();
			foreach($arr as $key => $value) {
				switch ($key) {
					case 0:
						$arr[$key] = $arr[$key];
						break;
					case 'id':
					case 'published':
						$arr[$key] = intval($arr[$key]);
						break;
					case 'url':
						$arr[$key] = urlescape($arr[$key]);
						break;
					case 'content':
						$arr[$key] = $purifier->purify($arr[$key]);
						break;
					default:
						$arr[$key] = htmlspecialchars($arr[$key]);
						break;
				}
				$refs[] = &$arr[$key];
			}

			if (call_user_func_array (array($stmt,'bind_param'), $refs)) {
				// execute update query
				if ($stmt->execute()) {
					$error = 2;
					
					if ($tags !== null) {
						// remove all categories for post
						delete_post_category_refs($mysqli, $post_id);
						
						// insert selected categories
						save_post_category_refs($mysqli, $post_id, save_categories($mysqli, $tags));
						
						// clean-up categories table (romove unused catogrories for user)
						delete_unused_categories($mysqli, $post['user']);
					}
				}
				else {
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
		
		// close connection if was established
		if (!$mysqli_passed) {
			close($mysqli);
		}
	}
	else {
		$error = 101;
	}
	return $error;
}



function save_categories($mysqli, $categories_str) {
	$tags_list = preg_split('/\s*,\s*/', $categories_str, null, PREG_SPLIT_NO_EMPTY);
	$user_tags = loadCategories();
	
	$post_tags = array();
	foreach ($tags_list as $t) {
		$orig_slug = get_slug($t);
		$i=0;
		$slug = $orig_slug;
		while (isset($user_tags[$slug])) {
			$category = $user_tags[$slug];
			// if tag name equals to User tag name we have to create connection between existing tag and post,
			// othrwise we have to find unused tag slug, create new one and connect to post.
			if (trim($t) != $category->name) {
				$slug = $orig_slug . '-' . ++$i;
			}
			else {
				// 
				break;
			}
		}
		// Was found unused category slug? TRUE => create new category
		if (!isset($user_tags[$slug])) {
			if ($stmt = $mysqli->prepare("INSERT INTO `category` (`user`,`name`,`slug`) VALUES (?, ?, ?)")) {
				$t = htmlspecialchars($t);
				if ($stmt->bind_param('sss', $GLOBALS['options']['user'], $t, $slug)) {
					if ($stmt->execute()) {
						$user_tags[$slug] = (object) array (
							'id' => $stmt->insert_id,
							'user' => $GLOBALS['options']['user'],
							'name' => $t,
							'slug' => $slug
						);
						$post_tags[] = $stmt->insert_id;
					}
					else {
						$error = 104;
					}
				}
				else {
					$error = 103;
				}
				// close opened statement (do not report any error)
				$stmt->close();
			}
			else {
				$error = 102;
			}
		}
		else {
			$post_tags[] = $user_tags[$slug]->id;
		}
	}
	return $post_tags;
}

function save_post_category_refs($mysqli, $post_id, $category_ids) {
	$error = 0;
	if ( is_array($category_ids) ) {
		if ($stmt = $mysqli->prepare("INSERT INTO `post_category` (`post_id`,`category_id`) VALUES (?, ?)")) {
			foreach ($category_ids as $c_id) {
				if ($stmt->bind_param('ii', $post_id, $c_id)) {
					if (!$stmt->execute()) {
						$error = 114;
					}
				}
				else {
					$error = 113;
				}
			}
			$stmt->close();
		}
		else {
			$error = 112;
		}
	}
	return $error;
}

function saveImage(){
	$toSave= array(
	  "id" => isset($_REQUEST["id"]) ? $_REQUEST["id"] : -1,
	  "user" => $GLOBALS['options']['user'],
	  "title" => isset($_REQUEST["title"]) ? $_REQUEST["title"] : NULL,
	  "slug" => isset($_REQUEST["title"]) ? get_slug($_REQUEST["title"]) : NULL,
	  "content" => isset($_REQUEST["content"]) ? $_REQUEST["content"] : NULL,
	  "type" => "image",
	  "url" => isset($_REQUEST["url"]) ? $_REQUEST["url"] : NULL,
	  "tags" => isset($_REQUEST["tags"]) ? $_REQUEST["tags"] : NULL,
	  "published" => isset($_REQUEST["published"]) ? $_REQUEST["published"] : 0,
	  "postdate" => isset($_REQUEST["postdate"]) ? $_REQUEST["postdate"] : date("Y-m-d H:i:s")
	  /*"fb" => isset($_REQUEST["fb-checkbox"]) ? $_REQUEST["fb-checkbox"] : NULL,
	  "mainLink" => isset($_REQUEST["mainLink"]) ? $_REQUEST["mainLink"] : NULL,
	  "fbShare" => isset($_REQUEST["fbShare"]) ? $_REQUEST["fbShare"] : NULL,
	  "twShare" => isset($_REQUEST["twShare"]) ? $_REQUEST["twShare"] : NULL,
	  "fbEnable" => isset($_REQUEST["fbEnable"]) ? $_REQUEST["fbEnable"] : NULL,
	  "twEnable" => isset($_REQUEST["twEnable"]) ? $_REQUEST["twEnable"] : NULL,*/
	);
	return save($toSave);
}
function saveText(){
	$toSave = array(
	  "id" => isset($_REQUEST["id"]) ? $_REQUEST["id"] : -1,
	  "user" => $GLOBALS['options']['user'],
	  "title" => isset($_REQUEST["title"]) ? $_REQUEST["title"] : NULL,
	  "slug" => isset($_REQUEST["title"]) ? get_slug($_REQUEST["title"]) : NULL,
	  "content" => isset($_REQUEST["content"]) ? $_REQUEST["content"] : NULL,
	  "type" => "text",
	  "url" => isset($_REQUEST["url"]) ? $_REQUEST["url"] : NULL,
	  "tags" => isset($_REQUEST["tags"]) ? $_REQUEST["tags"] : NULL,
	  "published" => isset($_REQUEST["published"]) ? $_REQUEST["published"] : 0,
	  "postdate" => (isset($_REQUEST["postdate"]) && ($_REQUEST["postdate"] != NULL)) ? $_REQUEST["postdate"] : date("Y-m-d H:i:s")
	  /*"fb" => isset($_REQUEST["fb-checkbox"]) ? $_REQUEST["fb-checkbox"] : NULL,
	  "mainLink" => isset($_REQUEST["mainLink"]) ? $_REQUEST["mainLink"] : NULL,
	  "fbShare" => isset($_REQUEST["fbShare"]) ? $_REQUEST["fbShare"] : NULL,
	  "twShare" => isset($_REQUEST["twShare"]) ? $_REQUEST["twShare"] : NULL,
	  "fbEnable" => isset($_REQUEST["fbEnable"]) ? $_REQUEST["fbEnable"] : NULL,
	  "twEnable" => isset($_REQUEST["twEnable"]) ? $_REQUEST["twEnable"] : NULL,*/
	);
	return save($toSave);
}
function saveVideo(){
	$toSave = array(
	  "id" => isset($_REQUEST["id"]) ? $_REQUEST["id"] : -1,
	  "user" => $GLOBALS['options']['user'],
	  "title" => isset($_REQUEST["title"]) ? $_REQUEST["title"] : NULL,
	  "slug" => isset($_REQUEST["title"]) ? get_slug($_REQUEST["title"]) : NULL,
	  "content" => isset($_REQUEST["content"]) ? $_REQUEST["content"] : NULL,
	  "type" => "video",
	  "url" => isset($_REQUEST["url"]) ? $_REQUEST["url"] : NULL,
	  "tags" => isset($_REQUEST["tags"]) ? $_REQUEST["tags"] : NULL,
	  "published" => isset($_REQUEST["published"]) ? $_REQUEST["published"] : 0,
	  "postdate" => isset($_REQUEST["postdate"]) ? $_REQUEST["postdate"] : date("Y-m-d H:i:s")/*,
	  "fb" => isset($_REQUEST["fb-checkbox"]) ? $_REQUEST["fb-checkbox"] : NULL,
	  "mainLink" => isset($_REQUEST["mainLink"]) ? $_REQUEST["mainLink"] : NULL,
	  "fbShare" => isset($_REQUEST["fbShare"]) ? $_REQUEST["fbShare"] : NULL,
	  "twShare" => isset($_REQUEST["twShare"]) ? $_REQUEST["twShare"] : NULL,
	  "fbEnable" => isset($_REQUEST["fbEnable"]) ? $_REQUEST["fbEnable"] : NULL,
	  "twEnable" => isset($_REQUEST["twEnable"]) ? $_REQUEST["twEnable"] : NULL,*/
	);
	return save($toSave);
}
function saveLink(){
	$toSave = array(
	  "id" => isset($_REQUEST["id"]) ? $_REQUEST["id"] : -1,
	  "user" => $GLOBALS['options']['user'],
	  "title" => isset($_REQUEST["title"]) ? $_REQUEST["title"] : NULL,
	  "slug" => isset($_REQUEST["title"]) ? get_slug($_REQUEST["title"]) : NULL,
	  "content" => isset($_REQUEST["content"]) ? $_REQUEST["content"] : NULL,
	  "type" => "link",
	  "url" => isset($_REQUEST["url"]) ? $_REQUEST["url"] : NULL,
	  "tags" => isset($_REQUEST["tags"]) ? $_REQUEST["tags"] : NULL,
	  "published" => isset($_REQUEST["published"]) ? $_REQUEST["published"] : 0,
	  "postdate" => isset($_REQUEST["postdate"]) ? $_REQUEST["postdate"] : date("Y-m-d H:i:s")/*,
	  "fb" => isset($_REQUEST["fb-checkbox"]) ? $_REQUEST["fb-checkbox"] : NULL,
	  "mainLink" => isset($_REQUEST["mainLink"]) ? $_REQUEST["mainLink"] : NULL,
	  "fbShare" => isset($_REQUEST["fbShare"]) ? $_REQUEST["fbShare"] : NULL,
	  "twShare" => isset($_REQUEST["twShare"]) ? $_REQUEST["twShare"] : NULL,
	  "fbEnable" => isset($_REQUEST["fbEnable"]) ? $_REQUEST["fbEnable"] : NULL,
	  "twEnable" => isset($_REQUEST["twEnable"]) ? $_REQUEST["twEnable"] : NULL,*/
	);
	return save($toSave);
}
function postOnFacebook($data = null, $slug = ""){
	if($data != null) {
		$facebook = new Facebook(array(
		  'appId'  => FACEBOOK_APP_ID,
		  'secret' => FACEBOOK_APP_SECRET,
		  'fileUpload' => true,
		  'cookie' => true
		));
		try {
			if($facebook->getUser()){
				$message = $data['fbShare'];
				$message = str_replace("!TITLE", $data["title"], $message);
				$message = str_replace("!URL", $data['mainLink']."/".$slug, $message);
				foreach($data['fb'] as $val) {
					if($data['type'] == 'text'){
						$publishStream = $facebook->api('/'.$val.'/feed', 'post', array(
							'message' => $message,
						)); 
					} else {
						$publishStream = $facebook->api('/'.$val.'/feed', 'post', array(
					    	'message' => $message,
							'name' => $data['title'],
					    	'link' => $data['url'],
						));
					}
				}
			}
		} catch (FacebookApiException $e) {
		}
	}
}
function postOnTwitter($data = null, $slug = ""){
	if($data != null) {
		$tmhOAuth = new tmhOAuth(array(
		  'consumer_key'    => TWITTER_APP_KEY,
	  	  'consumer_secret' => TWITTER_APP_SECRET,
		  'user_token'      => $GLOBALS['options']['twitterId'],
		  'user_secret'     => $GLOBALS['options']['twitterSecret'],
		));
		try {
			$message = $data['twShare'];
			$message = str_replace("!TITLE", $data["title"], $message);
			$message = str_replace("!URL", $data['mainLink']."/".$slug, $message);
			$code = $tmhOAuth->request('POST', $tmhOAuth->url('1/statuses/update'), array(
			  'status' => $message
			));
			if ($code == 200) {
			  tmhUtilities::pr(json_decode($tmhOAuth->response['response']));
			} else {
			  tmhUtilities::pr($tmhOAuth->response['response']);
			}
		} catch (Exception $e) {
		}
	}
}
?>