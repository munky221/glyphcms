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
		category_update($_REQUEST);
	} else {
		header("location: index.php");
	}
}

function category_update($data) {

	$id = $data['id'];
	$title = $data['title'];
	$slug = makeSlug($data['title']);

	// Start Database connection
	global $db;

	// set db query
	$q = "UPDATE `categories` SET `title` = '$title', `slug` = '$slug' WHERE id='$id'";
	$r = $db->query($q);
	
	// referal page
	if (isset($data['ref'])) {
		$ref = $data['ref'];
	} else {
		$ref = "categories.php";
	}

	$_SESSION['site']['return_msg'] = "Category updated.";
	$_SESSION['site']['return_type'] = "success";

	header("location: $ref");
}
?>