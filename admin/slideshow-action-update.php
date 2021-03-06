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
		slideshow_update($_REQUEST);
	} elseif ($_REQUEST['t'] == 'categories') {
		// perform category creation
		category_update($_REQUEST);
	} else {
		header("location: index.php");
	}
}


function slideshow_update($data) {

	$id = $data['id'];
	$title = $data['title'];
	$excerpt = $data['excerpt'];
	$status = $data['status'];
	$category = $data['category'];
	$photos = $data['photos'];
	$show_infobar = $data['show-infobar'];
	$show_filmstrip = $data['show-filmstrip'];

	// parse the category checkboxes
	$n = count($category);
    for($i=0; $i < $n; $i++)
    {
    	if (!isset($c)) $c = $category[$i];
    	else $c = $c . ";" . $category[$i];
    }

    /*
	// parse the photos checkboxes
	$n2 = count($photos);
    for($i2=0; $i2 < $n2; $i2++)
    {
    	if (!isset($p)) $p = $photos[$i2];
    	else $p = $p . ";" . $photos[$i2];
    }
    */

	// Start Database connection
	global $db;

	// set db query
	$q = "UPDATE `slideshows` SET `title` = '$title', `excerpt` = '$excerpt', `category` = '$c', `show_infobar` = '$show_infobar', `show_filmstrip` = '$show_filmstrip', `status` = '$status' WHERE id='$id'";
	$r = $db->query($q);
	
	// referal page
	if (isset($data['ref'])) {
		$ref = urldecode($data['ref']);
	} else {
		$ref = "slideshow-edit.php?id=$id";
	}

	header("location: $ref");
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

	header("location: $ref");
}
?>