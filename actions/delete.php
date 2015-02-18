<?php
require_once "database.php";

function delete() {
	$post_id = $_REQUEST["esb_postid"];
	if ($post_id) {
		$error = 0;
		$mysqli = connect();
		if ($mysqli) {
			if (($error = delete_post($mysqli, $post_id)) == 0) {
				$error = delete_unused_categories($mysqli, $GLOBALS['options']['user']);
			}
			close($mysqli);
		}
		else {
			$error = 101;
		}
		return $error;
	}
}

function delete_post($mysqli, $post_id, $user = null) {
	if ($user === null) {
		$user = $GLOBALS['options']['user'];
	}
	
	$error = 0;
	if ($stmt = $mysqli->prepare("DELETE FROM `post` WHERE `id` = ? AND `user` = ?")) {
		if ($stmt->bind_param('ss', $post_id, $user)) {
			if (!$stmt->execute()) {
				$error = 304;
			}
		}
		else {
			$error = 303;
		}
		$stmt->close();
	}
	else {
		$error = 302;
	}
	return $error;
}

function delete_post_category_refs($mysqli, $post_id) {
	$error = 0;
	if ($stmt = $mysqli->prepare("DELETE FROM `post_category` WHERE `post_id` = ?")) {
		if ($stmt->bind_param('i', $post_id)) {
			if (!$stmt->execute()) {
				$error = 314;
			}
		}
		else {
			$error = 313;
		}
		$stmt->close();
	}
	else {
		$error = 312;
	}
	return $error;
}

function delete_unused_categories($mysqli, $user = null) {
	if ($user === null) {
		$user = $GLOBALS['options']['user'];
	}
	
	$error = 0;
	if ($stmt = $mysqli->prepare("DELETE FROM `category` WHERE `user` = ? AND id NOT IN (SELECT pc.category_id FROM `post_category` pc INNER JOIN `post` p ON p.id = pc.post_id WHERE p.user = ?)")) {
		if ($stmt->bind_param('ss', $user, $user)) {
			if (!$stmt->execute()) {
				$error = 324;
			}
		}
		else {
			$error = 323;
		}
		$stmt->close();
	}
	else {
		$error = 322;
	}
	return $error;
}

?>