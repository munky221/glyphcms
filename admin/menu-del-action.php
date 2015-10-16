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
	$login_ref = 'menu.php';
	// $login_ref = urlencode(curPageURL());
	header("location: login.php?ref=$login_ref");
}

// Pre-Screening
if (isset($_REQUEST['t'])) {
	if ($_REQUEST['t'] == 'menu') {
		// perform menu deletion
		menu_delete($_REQUEST);
	} else {
		header("location: index.php");
	}
}


function menu_delete($data) {

	$id = $data['id'];

	// Start Database connection
	global $db;

	// set db query
	$q = "DELETE FROM `menu` WHERE id='$id'";
	$r = $db->query($q);

	// remove the target id from the menu list data
	$q = "SELECT * FROM `taxonomy` WHERE `name`='menu_data' LIMIT 1";
	$r = $db->query($q);
	$a = $db->fetch_array_assoc($r);

	$menu_array = menuArrayConvert($a['value']);

	/*echo "<pre>";
	print_r($menu_array);
	echo "</pre>";*/

	// search the target id inside $menu_array
	foreach ($menu_array as $index => $data) {
		if ($data['id'] == $id) {
			unset($menu_array[$index]);
		}
		if (isset($data['children'])) {
			$child_array = $data['children'];
			foreach ($child_array as $child_index => $child_data) {
				if ($child_data == $id) {
					unset($menu_array[$child_index]);
				}
			}
		}
	}
	// encode the revised menu array
	$new_value = menuArrayRevert($menu_array);

	// update menu data taxonomy
	$q2 = "UPDATE `taxonomy` SET `value` = '$new_value' WHERE name='menu_data'";
	$r2 = $db->query($q2);

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
		$ref = "menu.php";
	}

	header("location: $ref");
}
?>