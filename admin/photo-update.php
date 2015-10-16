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
	$login_ref = 'photos.php';
	// $login_ref = urlencode(curPageURL());
	header("location: login.php?ref=$login_ref");
}

// Pre-Screening
$_REQUEST = sanitize($_REQUEST);
if (isset($_REQUEST['t'])) {
	if ($_REQUEST['t'] == 'photos') {
		// perform category creation
		photo_update($_REQUEST);
	} else {
		header("location: index.php");
	}
}

function photo_update($data) {

	$id = $data['id'];
	$title = $data['title'];
	$description = $data['description'];
	$category = $data['category'];

	// parse the category checkboxes
	$n = count($category);
    for($i=0; $i < $n; $i++)
    {
    	if (!isset($c)) $c = $category[$i];
    	else $c = $c . ";" . $category[$i];
    }

	// Start Database connection
	global $db;

	// set db query
	$q = "UPDATE `photos` SET `title`='$title', `description`='$description', `categories` = '$c' WHERE id='$id'";
	$r = $db->query($q);
		
	// referal page
	if (isset($data['ref'])) {
		$ref = $data['ref'];
	} else {
		$ref = "photos.php";
	}

	header("location: $ref");
}
?>