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

// Pre-Screening
$_REQUEST = sanitize($_REQUEST);
if (isset($_REQUEST['t'])) {
	if ($_REQUEST['t'] == 'slideshows') {
		// perform slideshow creation
		slideshow_create($_REQUEST);
	} elseif ($_REQUEST['t'] == 'categories') {
		// perform category creation
		category_create($_REQUEST);
	} else {
		header("location: index.php");
	}
}


function slideshow_create($data) {

	$title = $data['title'];
	$excerpt = $data['excerpt'];
	$status = $data['status'];
	if (empty($data['category']) || !isset($data['category']))
		$category = "";
	else
		$category = $data['category'];
	$show_infobar = $data['show-infobar'];
	$show_filmstrip = $data['show-filmstrip'];

	// parse the category checkboxes
	$n = count($category);
	$c = "";
	if (!empty($category)) {
	    for($i=0; $i < $n; $i++)
	    {
	    	if (!isset($c)) $c = $category[$i];
	    	else $c = $c . ";" . $category[$i];
	    }
	}

	// Start Database connection
	global $db;

	// set db query
	$q = "INSERT INTO `slideshows` (`title`, `excerpt`, `category`, `show_infobar`, `show_filmstrip`, `status`) VALUES ('$title', '$excerpt', '$c', '$show_infobar', '$show_filmstrip', '$status')";
	$r = $db->query($q);
	$sid = mysql_insert_id();

	header("location: slideshow-edit.php?id=$sid");
}

function category_create($data) {

	$title = $data['title'];
	$slug = makeSlug($data['title']);

	// Start Database connection
	global $db;

	// set db query
	$q = "INSERT INTO `categories` (`title`, `slug`) VALUES ('$title', '$slug')";
	$r = $db->query($q);
	
	// referal page
	if (isset($data['ref'])) {
		$ref = urldecode($data['ref']);
	} else {
		$ref = "categories.php";
	}

	header("location: $ref");
}
?>