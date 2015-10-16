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
	$login_ref = 'slideshows.php';
	// $login_ref = urlencode(curPageURL());
	header("location: login.php?ref=$login_ref");
}

$pid = $_REQUEST['pid'];
$sid = $_REQUEST['sid'];

// Start Database connection
global $db;

// first, clear the previous photo cover of the target slideshow
$q = "UPDATE `slideshows` SET `cover`=$pid WHERE id='$sid' LIMIT 1";
$r = $db->query($q);
	
// referal page
if (isset($_REQUEST['ref'])) {
	$ref = $_REQUEST['ref'];
} else {
	$ref = "slideshow-edit.php?id=$sid";
}

// header("location: $ref");
?>