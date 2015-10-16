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
	$login_ref = 'menu.php';
	// $login_ref = urlencode(curPageURL());
	header("location: login.php?ref=$login_ref");
}

// Pre-Screening
if (isset($_REQUEST['t'])) {
	if ($_REQUEST['t'] == 'menu') {
		// perform page creation
		menu_create($_REQUEST);
	} else {
		header("location: index.php");
	}
}


function menu_create($data) {

	$title = $data['title'];
	$slug = makeSlug($data['title']);
	$allowChildren = false;
	if (isset($data['type'])) {
		$type = $data['type'];
		if ($type == '0') {
			// PLACEHOLDER TYPE
			$allowChildren = true;
			$data_value = '';

		} else if ($type == '1') {
			// PAGE TYPE
			$data_value = $data['pageSelect'];

		} else  if ($type == '2') {
			// GALLERY TYPE
			$data_value = $data['hyperlink'];

		} else if ($type == '3') {
			// HYPERLINK TYPE
			$data_value = $data['slideshowSelect'];

		} else if ($type == '4') {
			// HYPERLINK TYPE
			$data_value = $data['slideshowSelect'];

		} else if ($type == '5') {
			// HYPERLINK TYPE
			$data_value = $data['gallerySelect'];

		} else if ($type == '6') {
			// HYPERLINK TYPE
			$data_value = $data['gallerySelect'];

		}
	} else {
		$type = '0';
	}
	$status = '2';

	// Start Database connection
	global $db;

	// set db query
	$q = "INSERT INTO `menu` (`title`, `slug`, `type`, `data`, `allowChildren`, `status`) VALUES ('$title', '$slug', '$type', '$data_value', '$allowChildren', '$status')";
	$r = $db->query($q);
	$sid = mysql_insert_id();

	header("location: menu.php");
}
?>