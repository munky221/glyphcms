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
$_REQUEST = sanitize($_REQUEST);
if (isset($_REQUEST['t'])) {
	if ($_REQUEST['t'] == 'pages') {
		// perform slideshow creation
		page_delete($_REQUEST);
	} else {
		header("location: index.php");
	}
}


function page_delete($data) {

	$id = $data['id'];

	// Start Database connection
	global $db;

	// set db query
	$q = "DELETE FROM `pages` WHERE id='$id'";
	$r = $db->query($q);

	// referal page
	if (isset($data['ref'])) {
		$ref = $data['ref'];
		if (isset($data['hash'])) {
			if (strpos($data['hash'],"#")!=true) {
				// $ref = $ref . "#" . $data['hash'];
			} else {
				// $ref = $ref . $data['hash'];
			}
		}
	} else {
		$ref = "pages.php";
	}

	header("location: $ref");
}
?>