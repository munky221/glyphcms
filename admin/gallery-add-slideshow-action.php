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
		gallery_edit_action($_REQUEST);
	} else {
		header("location: index.php");
	}
}


function gallery_edit_action($data) {

	// Start Database connection
	global $db;

	$id = $data['id'];
	$slideshow = $data['slideshow'];

	// get current slideshows
	$q = "SELECT * FROM `galleries` WHERE `id` = '$id'";
	$r = $db->query($q);
	$a = $db->fetch_array_assoc($r);
	$current_slideshows = $a['slideshows'];

	// parse the category checkboxes
    for($i2=0; $i2 < count($slideshow); $i2++)
    {
    	if (!isset($s)) $s = $slideshow[$i2];
    	else $s = $s . ";" . $slideshow[$i2];
    }

    if ($current_slideshows != "")
    	$new_slideshows = $current_slideshows . ";" . $s;
    else
    	$new_slideshows = $s;

	// set db query
	$q2 = "UPDATE `galleries` SET `slideshows` = '$new_slideshows' WHERE id='$id'";
	$r2 = $db->query($q2);

	$_SESSION['site']['return_msg'] = "Slideshow has been added to <strong>" . $a['title'] . "</strong>.";
	$_SESSION['site']['return_type'] = "success";

	// referal page
	if (isset($data['ref'])) {
		$ref = $data['ref'];
		if (isset($data['hash'])) {
			/*if (strpos($data['hash'],"#")!=true) {
				$ref = $ref . "#" . $data['hash'];
			} else {
				$ref = $ref . $data['hash'];
			}*/
		}
	} else {
		$ref = "galleries.php";
	}
	$ref .= "#" . $a['slug'];

	// header("location: $ref");
}
?>