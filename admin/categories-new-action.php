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
	$login_ref = 'categories.php';
	// $login_ref = urlencode(curPageURL());
	header("location: login.php?ref=$login_ref");
}

// Pre-Screening
$_REQUEST = sanitize($_REQUEST);
if (isset($_REQUEST['t'])) {
	if ($_REQUEST['t'] == 'categories') {
		// perform category creation
		category_create($_REQUEST);
	} else {
		header("location: index.php");
	}
}

function category_create($data) {

	$title = $data['title'];
	$slug = makeSlug($data['title']);

	global $db;

	// check if category title is present
	if (!checkCategoryExist($slug)) {

		// Start Database connection

		// set db query
		$q = "INSERT INTO `categories` (`title`, `slug`) VALUES ('$title', '$slug')";
		$r = $db->query($q);

		$_SESSION['site']['return_msg'] = "Category added.";
		$_SESSION['site']['return_type'] = "success";
	} else {
		$_SESSION['site']['return_msg'] = "Category already exist.";
		$_SESSION['site']['return_type'] = "danger";
	}
		
	// referal page
	if (isset($data['ref'])) {
		$ref = urldecode($data['ref']);
	} else {
		$ref = "categories.php";
	}

	header("location: $ref");

	// print_r(checkCategoryExist($slug));
}
?>