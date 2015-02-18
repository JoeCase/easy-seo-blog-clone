<?php
require_once "database.php";
require_once "wix.php";

function loadPosts($page = null, $posts_per_page = null, $tags = false, $where_params = array(), $from_user = null) {
	if(!is_null($from_user)){
		$user = $from_user;
	} else {
		$user = $GLOBALS['options']['user'];
	}
	if ($page === null) {
		$page = 1;
	}
	if ($posts_per_page === null) {
		$posts_per_page = isset($GLOBALS['options']['posts_per_page']) ? $GLOBALS['options']['posts_per_page'] : 6;
	}

	$from = max(0, $page-1)*$posts_per_page;
	$to = $posts_per_page;

	$mysqli = connect();
	$where = "";
	$join_category = false;
	foreach ($where_params as $k => $v){
		if ($k == "tag") {
			$where .= " AND `c`.`slug`='".$v."'";
			$join_category = true;
		} else if($k == "postdate"){
			//$where .= " AND `p`.`".$k."`<='".$v."'";
		} else {
			$where .= " AND `p`.`".$k."`='".$v."'";
		} 
	}
	$result = $mysqli->query("SELECT `p`.* FROM `post` AS p ".($join_category ? 'INNER JOIN post_category AS cp ON cp.post_id = p.id INNER JOIN category AS c ON c.id = cp.category_id ' : '')." WHERE p.`user`= '".$user."'".$where." ORDER BY postdate DESC LIMIT ".$from.",".$to);
	$posts = array();
	$post_ids = array();
	while ($row = $result->fetch_object()) {
		$posts[] = $row;
		$post_ids[] = $row->id;
	}
	$result->close();
	
	// load categories
	if ($tags) {
		$categories = loadCategoriesByPosts($post_ids, $mysqli, $user);
		if (is_array($categories)) foreach ($posts as &$post) {
			if (isset($categories[$post->id])) {
				$post->tags = $categories[$post->id];
			}
			else {
				$post->tags = array();
			}
		}
	}
	
	close($mysqli);
	return $posts;
}

function loadPost($post_id, $mysqli = null, $user = null, $tags = true) {
	if ($user === null) {
		$user = $GLOBALS['options']['user'];
	}
	
	$error = 0;
	$post = null;
	$mysqli_passed = $mysqli !== null;
	
	if (!$mysqli_passed) {
		$mysqli = connect();
	}
	if ($mysqli) {
		// create a prepared statement
		if ($stmt = $mysqli->prepare('SELECT id, user, title, slug, content, url, type, postdate, published FROM `post` WHERE id = ? AND user = ?')) {
			
			// bind parameters
			if ($stmt->bind_param('is', $post_id, $user)) {
				
				// execute query
				if ($stmt->execute()) {
		
					// bind result variables
					if ($stmt->bind_result($id, $user, $title, $slug, $content, $url, $type, $postdate, $published)) {
		
						// fetch values
						if ($stmt->fetch()) {
							$post = (object) array (
								'id' => $id,
								'user' => $user,
								'title' => $title,
								'slug' => $slug,
								'content' => $content,
								'url' => $url,
								'type' => $type, 
								'postdate' => $postdate,
								'published' => $published
							);
						}
						else {
							$error = 116;
						}
					}
					else {
						$error = 115;
					}
				}
				else {
					$error = 114;
				}
			}
			else {
				$error = 113;
			}
			/* close statement */
			$stmt->close();
		}
		else {
			$error = 112;
		} // end of Create a prepared statement
		
		// load categories
		if ($tags) {
			$categories = loadCategoriesByPosts(array($post_id), $mysqli, $user);
			if (is_array($categories) && isset($categories[$post->id])) {
				$post->tags = $categories[$post->id];
			}
			else {
				$post->tags = array();
			}
		}
		
		if (!$mysqli_passed) {
			close($mysqli);
		}
	}
	else {
		$error = 101;
	}
	
	return $error !== 0 ? $error : $post;
}

function numberOfposts($user = null, $where_params = array()) {
	if ($user === null) {
		$user = $GLOBALS['options']['user'];
	}
	$where = "";
	$join_category = false;
	foreach ($where_params as $k => $v){
		if ($k == "tag") {
			$where .= " AND `c`.`slug`='".$v."'";
			$join_category = true;
		} else if($k == "postdate"){
			//$where .= " AND `p`.`".$k."`<='".$v."'";
		} else {
			$where .= " AND `p`.`".$k."`='".$v."'";
		} 
	}
	$mysqli = connect();
	$result = $mysqli->query("SELECT COUNT(*) as count FROM `post` AS p ".($join_category ? 'INNER JOIN post_category AS cp ON cp.post_id = p.id INNER JOIN category AS c ON c.id = cp.category_id ' : '')." WHERE p.`user`= '".$user."'".$where." ORDER BY postdate DESC");	
	$arr = array();
	while ($row = $result->fetch_object()) {
		$arr[] = $row;
	}
	return $arr[0]->count;
}

function loadCategoriesByPosts($post_ids, $mysqli = null, $user = null) {
	// wrap single value to array
	if (!is_array($post_ids)) {
		$post_ids = array($post_ids);
	}
	// check if array is not empty
	if (count($post_ids) <= 0) {
		return array();
	}
	
	if ($user === null) {
		$user = $GLOBALS['options']['user'];
	}
	
	$inQuery = implode(',', array_fill(0, count($post_ids), '?'));
	$types = implode('', array_fill(0, count($post_ids), 'i'));
	
	
	$select = 'SELECT c.id, c.user, c.name, c.slug, pc.post_id FROM `category` c 
				INNER JOIN `post_category` pc ON c.id = pc.category_id 
				WHERE c.user = ? AND pc.post_id IN ('.$inQuery.') 
				ORDER BY pc.post_id, c.id';
	
	$error = 0;
	$cat_for_post = array();
	$mysqli_passed = $mysqli !== null;
	
	if (!$mysqli_passed) {
		$mysqli = connect();
	}
	if ($mysqli) {
		// create a prepared statement
		if ($stmt = $mysqli->prepare($select)) {
			
			$arr = array_merge( array('s'.$types), array_merge(array($user), $post_ids));
			$refs = array();
			foreach($arr as $key => $value) {
				$refs[$key] = &$arr[$key];
			}
			
			call_user_func_array (array($stmt,'bind_param'), $refs);
			
			// execute query
			if ($stmt->execute()) {
				
				// bind result variables
				if ($stmt->bind_result($id, $user, $name, $slug, $post_id)) {

					// fetch values
					while ($stmt->fetch()) {
						if (!isset($cat_for_post[$post_id])) {
							$cat_for_post[$post_id] = array();
						}
						
						$cat_for_post[$post_id][] = (object) array (
								'id' => $id,
								'user' => $user,
								'name' => $name,
								'slug' => $slug,
								'post_id' => $post_id
						);
					}
						
				}
				else {
					$error = 115;
				}
			}
			else {
				$error = 114;
			}
	
			/* close statement */
			$stmt->close();
		}
		else {
			$error = 112;
		} // end of Create a prepared statement
		if (!$mysqli_passed) {
			close($mysqli);
		}
	}
	else {
		$error = 101;
	}
	return $error !== 0 ? $error : $cat_for_post;
}

function loadCategories($mysqli = null, $user = null) {
	if ($user === null) {
		$user = $GLOBALS['options']['user'];
	}
	
	$error = 0;
	$categories = array();
	$mysqli_passed = $mysqli !== null;
	
	if (!$mysqli_passed) {
		$mysqli = connect();
	}
	if ($mysqli) {
		/* create a prepared statement */
		if ($stmt = $mysqli->prepare('SELECT DISTINCT c.id, c.user, c.name, c.slug FROM `category` c WHERE c.user = ? ORDER BY c.id')) {
		
		    /* bind parameters for markers */
	    	if ($stmt->bind_param("s", $user)) {
		
			    /* execute query */
			    if ($stmt->execute()) {
			
				    /* bind result variables */
				    if ($stmt->bind_result($id, $user, $name, $slug)) {
				
					    /* fetch value */
					    while ($stmt->fetch()) {
					    	$categories[$slug] = (object) array (
					    		'id' => $id,
				    			'user' => $user,
				    			'name' => $name,
				    			'slug' => $slug
					    	);
					    }
					    
				    }
				    else {
				    	$error = 115;
				    }
			    }
			    else {
			    	$error = 114;
			    }
	    	}
	    	else {
	    		$error = 113;
	    	}
		
		    /* close statement */
		    $stmt->close();
		}
		else {
			$error = 112;
		}
		if (!$mysqli_passed) {
			close($mysqli);
		}
	}
	else {
		$error = 101;
	}
	return $error !== 0 ? $error : $categories;
}

function find_unused_slug_for_post($post_slug, $post_id = null, $mysqli = null, $user = null, $published = 0) {
	if ($user === null) {
		$user = $GLOBALS['options']['user'];
	}
	
	$error = 0;
	$unused_slug = null;
	$mysqli_passed = $mysqli !== null;
	
	if (!$mysqli_passed) {
		$mysqli = connect();
	}	
	if ($mysqli) {
		$is_published = is_post_published($post_id, $mysqli, $user);
		if(($post_id == null) || ($published == 0) || ($is_published == 0)) {
			if ($stmt = $mysqli->prepare('SELECT id, slug FROM `post` WHERE `user` = ? AND `slug` LIKE ?')) {
				$slug_search = $post_slug.'%';
				if ($stmt->bind_param('ss', $user, $slug_search)) {
					if ($stmt->execute()) {
						// get similar slugs from DB
						$stmt->bind_result($id, $slug);
						$slugs = array();
						$slug_already_exists = false;
						$unused_slug = $post_slug;
						while ($stmt->fetch()) {
							// exclude currently updated post
							if ($id != $post_id) { //TODO: Change !== to !=
								$slugs[] = $slug;
								
								if ($slug === $unused_slug) {
									$slug_already_exists = true;
								}
							}
						}
						
						// find unused slugs by adding number to end
						$n = 0;
						
						while ($slug_already_exists) {
							$slug_already_exists = false;
							$unused_slug = $post_slug . '-' . ++$n;
							foreach ($slugs as $slug) {
								if ($slug === $unused_slug) {
									$slug_already_exists = true;
								}
							}
							
						}
						
					}
					else {
						$error = 104;
					}
				}
				else {
					$error = 103;
				}
			}
			else {
				$error = 102;
			}
		} else {
			if ($stmt = $mysqli->prepare('SELECT slug FROM `post` WHERE `user` = ? AND `id` = ?')) {
				if ($stmt->bind_param('si', $user, $post_id)) {
					if ($stmt->execute()) {
						// get similar slugs from DB
						$stmt->bind_result($slug);
						$unused_slug = $post_slug;
						while ($stmt->fetch()) {
							$unused_slug = $slug;
						}	
					}
					else {
						$error = 104;
					}
				}
				else {
					$error = 103;
				}
			}
			else {
				$error = 102;
			}	
		}
		// close connection if was opened in this function
		if (!$mysqli_passed) {
			close($mysqli);
		}
	}
	else {
		$error = 101;
	}
	
	return $error !== 0 ? $error : array($unused_slug);
}

function find_similar_categories_by_slug($slug, $mysqli = null, $user = null) {
	if ($user === null) {
		$user = $GLOBALS['options']['user'];
	}
	$error = 0;
	$categories = array();
	$mysqli_passed = $mysqli !== null;
	
	if (!$mysqli_passed) {
		$mysqli = connect();
	}
	
	if ($mysqli) {
		if ($stmt = $mysqli->prepare('SELECT id, name, slug FROM `category` WHERE `user` = ? AND `slug` LIKE ?')) {
			$slug_search = $slug.'%';
			if ($stmt->bind_param('ss', $user, $slug_search)) {
				if ($stmt->execute()) {
					// get similar slugs from DB
					$stmt->bind_result($id, $name, $slug);
					
					while ($stmt->fetch()) {
						// exclude currently updated post
						$categories[] = (object) array(
							'id' => $id,
							'user' => $user,
							'name' => $name,
							'slug' => $slug
						);
					}
				}
				else {
					$error = 104;
				}
			}
			else {
				$error = 103;
			}
		}
		else {
			$error = 102;
		}
		// close connection if was opened in this function
		if (!$mysqli_passed) {
			close($mysqli);
		}
	}
	else {
		$error = 101;
	}
	
	return $error !== 0 ? $error : $categories;
}

function is_post_published($post_id = null, $mysqli = null, $user = null) {
	if ($user === null) {
		$user = $GLOBALS['options']['user'];
	}
	$is_publish = 0;
	$error = 0;
	$mysqli_passed = $mysqli !== null;
	
	if (!$mysqli_passed) {
		$mysqli = connect();
	}
	
	if ($mysqli) {
		if ($stmt = $mysqli->prepare('SELECT published FROM `post` WHERE `user` = ? AND `id` = ?')) {
			if ($stmt->bind_param('si', $user, $post_id)) {
				if ($stmt->execute()) {
					// get similar slugs from DB
					$stmt->bind_result($published);
					while ($stmt->fetch()) {
						$is_publish = $published;
					}						
				}
				else {
					$error = 104;
				}
			}
			else {
				$error = 103;
			}
		}
		else {
			$error = 102;
		}
		// close connection if was opened in this function
		if (!$mysqli_passed) {
			close($mysqli);
		}
	}
	else {
		$error = 101;
	}
	
	return $error !== 0 ? $error : array($published);	
}

function getPostHTML($post = array(), $separatePost = 0, $postLayout = "basic", $class = ""){
	if(($post != null) && (sizeof($post) > 0)){
		$post->{'layout'} = $postLayout;
		$GLOBALS['post'] = $post;
		switch ($post->type) {
			case "text":{
				include 'views/posts/text.php';
				break;
			}
			case "image":{
				include 'views/posts/image.php';
				break;
			}
			case "video":{
				include 'views/posts/video.php';
				break;
			}
			case "link":{
				include 'views/posts/link.php';
				break;
			}		
		}
	}	
}

function getDummyPosts(){
	$return = '';
	
	$return .= '<div class="wrapper dummy"><div class="post text">';
	$return .= '<div class="header"><span class="title">First blog post</span></div>';
	$return .= '<div class="content clearfix"><div class="caption"><p>This is your first blog post. Write on anything from photography or wind surfing, business tips and tasty recipes for beer and wine. Your blog will be your voice to the world and your opportunity to inspire. The pen is mightier than the sword.</p></div></div>';
	$return .= '<div class="footer"><div class="datebox"><span class="date">'.date($GLOBALS['options']['dateFormat']).'</span></div>';
	$return .= '<div class="tags"><span class="tag">first</span><span class="tag">post</span>';
	$return .= '</div></div></div></div>';
	
	$return .= '<div class="wrapper dummy"><div class="post photo">';
	$return .= '<div class="header"><span class="title">San Francisco - Home of Easy Apps</span></div>';
	$return .= '<div class="content basic clearfix"><img class="photo" src="http://img208.imageshack.us/img208/8924/4217586809fa09b3b4ao.jpg" />';
	$return .= '<div class="caption"><p>The Team of Easy Apps began in the city of San Francisco in April 2013. The five guys on the team met at Startup Weekend Bay Area and had the prototype for Easy SEO Blog running after a weekend together. A few days later the - a week after Easy Apps met for the first time - the first version of the Easy SEO Blog app was submitted to Wix.com.</p></div></div>';
	$return .= '<div class="footer"><div class="datebox"><span class="date">'.date($GLOBALS['options']['dateFormat']).'</span></div>';
	$return .= '<div class="tags"><span class="tag">image</span><span class="tag">sanfrancisco</span>';
	$return .= '</div></div></div></div>';
	
	
	$return .= '<div class="wrapper dummy"><div class="post video">';
	$return .= '<div class="header"><span class="title">How Easy SEO Blog Works - a Demo</span></div>';
	$return .= '<div class="content basic clearfix"><p class="video">http://www.youtube.com/watch?v=OZy1SIJeF4E</p>';
	$return .= '<div class="caption">In this video you can see how easy it is to create a new blog post on your Wix.com site. Having fresh content on your site will help your SEO ranking. So remember to post!</div></div>';
	$return .= '<div class="footer"><div class="datebox"><span class="date">'.date($GLOBALS['options']['dateFormat']).'</span></div>';
	$return .= '<div class="tags"><span class="tag">video</span><span class="tag">demo</span>';
	$return .= '</div></div></div></div>';
	
	echo $return;
}

function getCategoryNameBySlug($slug, $mysqli = null, $user = null) {
	if ($user === null) {
		$user = $GLOBALS['options']['user'];
	}
	
	$error = 0;
	$categoryName = "";
	$mysqli_passed = $mysqli !== null;
	
	if (!$mysqli_passed) {
		$mysqli = connect();
	}
	
	if ($mysqli) {
		if ($stmt = $mysqli->prepare('SELECT name FROM `category` WHERE `user` = ? AND `slug` = ?')) {
			if ($stmt->bind_param('ss', $user, $slug)) {
				if ($stmt->execute()) {
					// get similar slugs from DB
					$stmt->bind_result($name);
					if ($stmt->fetch()) {
						$categoryName = $name;
					}
				}
				else {
					$error = 104;
				}
			}
			else {
				$error = 103;
			}
		}
		else {
			$error = 102;
		}
		// close connection if was opened in this function
		if (!$mysqli_passed) {
			close($mysqli);
		}
	}
	else {
		$error = 101;
	}
	
	return $error !== 0 ? $error : $categoryName;
}
?>