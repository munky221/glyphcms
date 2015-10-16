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

$items = explode(";",$_REQUEST['items']);


// Start Database connection
global $db;
global $site;

// loop items array
for ($i=0;$i!=count($items);$i++) {
	// set db query
	$id = $items[$i];
	$q = "SELECT * FROM `photos` WHERE `id` = '$id'";
	$r = $db->query($q);
	$a = $db->fetch_array_assoc($r);

	$filename = $a['name'];
	// now delete the db entry
	$q2 = "DELETE FROM `photos` WHERE id='$id'";
	$r2 = $db->query($q2);

	// now delete physical files
	// delete the thumbnail
	$thumbpath = ABSPATH . $site->uploads_dir . "/thumbnail/";
	$thumbfile = realpath($thumbpath . $filename);
	// echo $thumbfile;
	if (is_resource($thumbfile)) {
		fclose($thumbfile);
		if (!unlink($thumbfile)) {
			if (is_readable($thumbfile)) {
				unlink($thumbfile);
			}
		}
	} else {
		if (is_readable($thumbfile)) {
			unlink($thumbfile);
		}
	}
	// delete the preview
	$prevpath = ABSPATH . $site->uploads_dir . "/preview/";
	$prevfile = realpath($prevpath . $filename);
	// echo $prevfile;
	if (is_resource($prevfile)) {
		fclose($prevfile);
		if (!unlink($prevfile)) {
			if (is_readable($prevfile)) {
				unlink($prevfile);
			}
		}
	} else {
		if (is_readable($prevfile)) {
			unlink($prevfile);
		}
	}
	// delete the main file
	$mainpath = ABSPATH . $site->uploads_dir . "/";
	$mainfile = realpath($mainpath . $filename);
	// echo $mainfile;
	if (is_resource($mainfile)) {
		fclose($mainfile);
		if (!unlink($mainfile)) {
			if (is_readable($mainfile)) {
				unlink($mainfile);
			}
		}
	} else {
		if (is_readable($mainfile)) {
			unlink($mainfile);
		}
	}


	// clean the slideshows from the deleted photo

	$q2 = "SELECT * FROM `slideshows`";
	$r2 = $db->query($q2);
	while ($a2 = $db->fetch_array_assoc($r2)) {
		// checking photo array
		$sid = $a2['id'];
		$photo_array = explode(";", $a2['photos']);
		$key = array_search($id,$photo_array);
		if ($key !== false) {
			unset($photo_array[$key]);
			$updated_photos = implode(";",$photo_array);
			$q3 = "UPDATE `slideshows` SET `photos`='$updated_photos' WHERE id='$sid'";
			$r3 = $db->query($q3);
		}
		// also check the cover photo
		if ($id == $a2['cover']) {
			$q4 = "UPDATE `slideshows` SET `cover`='NULL' WHERE id='$sid'";
			$r4 = $db->query($q4);
		}
	}

}


// referal page
if (isset($_REQUEST['ref'])) {
	$ref = $_REQUEST['ref'];
	if (isset($_REQUEST['hash'])) {
		if (strpos($_REQUEST['hash'],"#")!=true) {
			$ref = $ref . "#" . $_REQUEST['hash'];
		} else {
			$ref = $ref . $_REQUEST['hash'];
		}
	}
} else {
	$ref = "photos.php";
}

$ref = urldecode($ref);

header("location: $ref");
?>