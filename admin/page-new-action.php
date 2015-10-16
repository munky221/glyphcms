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
		page_create($_REQUEST);
	} else {
		header("location: index.php");
	}
}


function page_create($data) {

	$title = $data['title'];
	$slug = makeSlug($data['title']);
	$content = addslashes($data['content']);
	$excerpt = strip_tags(addslashes($data['excerpt']));
	$status = $data['status'];

	// Start Database connection
	global $db;

	// set db query
	$q = "INSERT INTO `pages` (`title`, `slug`, `content`, `excerpt`, `status`) VALUES ('$title', '$slug', '$content', '$excerpt', '$status')";
	$r = $db->query($q);
	$sid = mysql_insert_id();

	header("location: page-edit.php?id=$sid");
}
?>