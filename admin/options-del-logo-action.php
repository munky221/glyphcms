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
	$login_ref = 'options.php';
	// $login_ref = urlencode(curPageURL());
	header("location: login.php?ref=$login_ref");
}

// Pre-Screening
logo_delete();


function logo_delete() {

	// Start Database connection
	global $db;
	global $site;

	// set db query
	$q = "SELECT * FROM `taxonomy` WHERE `name` = 'site_logo'";
	$r = $db->query($q);
	$a = $db->fetch_array_assoc($r);

	$filename = $a['value'];
	// now delete the custom logo from db
	$q2 = "UPDATE `taxonomy` SET `value` = '' WHERE name='site_logo'";
	$r2 = $db->query($q2);

	// now delete physical files
	// delete the thumbnail
	$thumbpath = ABSPATH . $site->uploads_dir . "/static/thumbnail/";
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
	$prevpath = ABSPATH . $site->uploads_dir . "/static/preview/";
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
	$mainpath = ABSPATH . $site->uploads_dir . "/static/";
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
		$ref = "options.php";
	}

	header("location: $ref");
}
?>