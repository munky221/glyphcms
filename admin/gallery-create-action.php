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
		gallery_create($_REQUEST);
	} else {
		header("location: index.php");
	}
}


function gallery_create($data) {

	$title = $data['title'];
	$slug = makeSlug($title);
	$excerpt = $data['excerpt'];
	$status = $data['status'];
	if (empty($data['category']) || !isset($data['category']))
		$category = "";
	else
		$category = $data['category'];
	if (empty($data['slideshow']) || !isset($data['slideshow']))
		$slideshow = "";
	else
		$slideshow = $data['slideshow'];

	// parse the category checkboxes
	$n = count($category);
	$c = "";
	if (!empty($category)) {
	    for($i=0; $i != $n; $i++)
	    {
	    	if (empty($c)) $c = $category[$i];
	    	else $c = $c . ";" . $category[$i];
	    }
	}

	// parse the slideshow checkboxes
	$n2 = count($slideshow);
	$s = "";
	if (!empty($slideshow)) {
	    for($i2=0; $i2 != $n2; $i2++)
	    {
	    	if (empty($s)) $s = $slideshow[$i2];
	    	else $s = $s . ";" . $slideshow[$i2];
	    }
	}

	// Start Database connection
	global $db;

	// set db query
	$q = "INSERT INTO `galleries` (`title`, `excerpt`, `slug`, `categories`, `slideshows`, `status`) VALUES ('$title', '$excerpt', '$slug', '$c', '$s', '$status')";
	$r = $db->query($q);
	$gid = mysql_insert_id();

	header("location: gallery-edit.php?id=$gid");
}
?>