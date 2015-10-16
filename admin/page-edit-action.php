<?php
// Admin Panel Required Files
require('admin-core.php');

global $login;
// ... ask if we are logged in here:
if ($login->isUserLoggedIn() == true) {
	// the user is logged in. you can do whatever you want here.
	// for demonstration purposes, we simply show the "you are logged in" view.
	// include("views/logged_in.php");

} else {
	// the user is not logged in. you can do whatever you want here.
	// for demonstration purposes, we simply show the "you are not logged in" view.
	// include("views/not_logged_in.php");
	$login_ref = 'pages.php';
	// $login_ref = urlencode(curPageURL());
	header("location: login.php?ref=$login_ref");
}

// Pre-Screening
$_REQUEST = html_decode($_REQUEST,false);
$_REQUEST['title'] = sanitize($_REQUEST['title']);
if (isset($_REQUEST['t'])) {
	if ($_REQUEST['t'] == 'pages') {
		// perform page creation
		page_update($_REQUEST);
	} else {
		header("location: index.php");
	}
}


function page_update($data) {

	$id = $data['id'];
	$title = $data['title'];
	$slug = makeSlug($data['title']);
	$content = addslashes(htmlspecialchars_decode($data['content']));
	$excerpt = strip_tags(addslashes($data['excerpt']));
	$status = $data['status'];

	// Start Database connection
	global $db;

	// set db query
	$q = "UPDATE `pages` SET `title` = '$title', `slug` = '$slug', `content` = '$content', `excerpt` = '$excerpt', `status` = '$status' WHERE id='$id'";
	$r = $db->query($q);
	
	// referal page
	if (isset($data['ref'])) {
		$ref = urldecode($data['ref']);
	} else {
		$ref = "page-edit.php?id=$id";
	}

	header("location: $ref");
}
?>