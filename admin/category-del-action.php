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
	if ($_REQUEST['t'] == 'categories') {
		// perform category creation
		category_delete($_REQUEST);
	} else {
		header("location: index.php");
	}
}

function category_delete($data) {

	$id = $data['id'];

	// prevent FEATURED category from being deleted
	if ($id != '0') {

		// Start Database connection
		global $db;

		// set db query
		$q = "DELETE FROM `categories` WHERE id='$id'";
		$r = $db->query($q);


		// clean the slideshows from the deleted category

		$q2 = "SELECT * FROM `slideshows`";
		$r2 = $db->query($q2);
		while ($a2 = $db->fetch_array_assoc($r2)) {
			// checking photo array
			$sid = $a2['id'];
			$cat_array2 = explode(";", $a2['category']);
			$key2 = array_search($id,$cat_array2);
			if ($key2 !== false) {
				unset($cat_array2[$key2]);
				$updated_cat2 = implode(";",$cat_array2);
				$q2b = "UPDATE `slideshows` SET `category`='$updated_cat2' WHERE id='$sid'";
				$r2b = $db->query($q2b);
			}
		}


		// clean the galleries from the deleted category

		$q3 = "SELECT * FROM `galleries`";
		$r3 = $db->query($q3);
		while ($a3 = $db->fetch_array_assoc($r3)) {
			// checking photo array
			$gid = $a3['id'];
			$cat_array3 = explode(";", $a3['categories']);
			$key3 = array_search($id,$cat_array3);
			if ($key3 !== false) {
				unset($cat_array3[$key3]);
				$updated_cat3 = implode(";",$cat_array3);
				$q3b = "UPDATE `galleries` SET `categories`='$updated_cat3' WHERE id='$gid'";
				$r3b = $db->query($q3b);
			}
		}


		// clean the photos from the deleted category

		$q4 = "SELECT * FROM `photos`";
		$r4 = $db->query($q4);
		while ($a4 = $db->fetch_array_assoc($r4)) {
			// checking photo array
			$pid = $a4['id'];
			$cat_array4 = explode(";", $a4['categories']);
			$key4 = array_search($id,$cat_array4);
			if ($key4 !== false) {
				unset($cat_array4[$key4]);
				$updated_cat4 = implode(";",$cat_array4);
				$q4b = "UPDATE `photos` SET `categories`='$updated_cat4' WHERE id='$pid'";
				$r4b = $db->query($q4b);
			}
		}


		$_SESSION['site']['return_msg'] = "Category deleted.";
		$_SESSION['site']['return_type'] = "danger";
	} else {
		$_SESSION['site']['return_msg'] = "You attempted to the FEATURED category. Shame on you!";
		$_SESSION['site']['return_type'] = "danger";

	}

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
		$ref = "categories.php";
	}

	header("location: $ref");
}
?>