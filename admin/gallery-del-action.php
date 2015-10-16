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
	$login_ref = 'galleries.php';
	// $login_ref = urlencode(curPageURL());
	header("location: login.php?ref=$login_ref");
}

// Pre-Screening
$_REQUEST = sanitize($_REQUEST);
if (isset($_REQUEST['t'])) {
	if ($_REQUEST['t'] == 'galleries') {
		// perform slideshow creation
		gallery_delete($_REQUEST);
	} else {
		header("location: index.php");
	}
}


function gallery_delete($data) {

	$id = $data['id'];

	// Start Database connection
	global $db;

	// set db query
	$q = "DELETE FROM `galleries` WHERE id='$id'";
	$r = $db->query($q);

	// if slideshow was successfully removed,
	// remove the photos associated with it
	// if ($r) {
	// 	$q2 = "DELETE FROM `photos` WHERE slideshow='$id'";
	// 	$r2 = $db->query($q2);

	// 	// if photos was successfully removed,
	// 	// then remove the physical files
	// 	if ($r2) {
	// 		$dir = ABSPATH . SITE_PUBLIC . "/" . SITE_UPLOADS . "/" . $id . "/";
	// 		rrmdir($dir); // delete the entire directory
	// 	}
	// }

	// referal page
	if (isset($data['ref'])) {
		$ref = $data['ref'];
		if (isset($data['hash'])) {
			if (strpos($data['hash'],"#")!=true) {
				$ref = $ref . "#" . $data['hash'];
			} else {
				$ref = $ref . $data['hash'];
			}
		}
	} else {
		$ref = "galleries.php";
	}

	header("location: $ref");
}
?>