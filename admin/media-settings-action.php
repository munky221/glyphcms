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
	$login_ref = 'media-settings.php';
	// $login_ref = urlencode(curPageURL());
	header("location: login.php?ref=$login_ref");
}

// Pre-Screening
$_REQUEST = sanitize($_REQUEST);
if (isset($_REQUEST['t'])) {
	if ($_REQUEST['t'] == 'media-settings') {
		// perform slideshow creation
		mediasettings_update($_REQUEST);
	} else {
		header("location: index.php");
	}
}

function mediasettings_update($data)
{

	global $site;

	if (empty($data['thumb-width']))
		$galleryData['gallery_thumb_width'] = 80;
	else
		$galleryData['gallery_thumb_width'] = $data['thumb-width'];
		if ($data['thumb-width'] > 80)
				$galleryData['gallery_thumb_width'] = 80;
	if (empty($data['thumb-height']))
		$galleryData['gallery_thumb_height'] = 80;
	else
		$galleryData['gallery_thumb_height'] = $data['thumb-height'];
		if ($data['thumb-height'] > 80)
				$galleryData['gallery_thumb_height'] = 80;
	if (empty($data['thumb-quality']))
		$galleryData['gallery_thumb_quality'] = 70;
	else
		$galleryData['gallery_thumb_quality'] = $data['thumb-quality'];
		if ($data['thumb-quality'] > 100)
				$galleryData['gallery_thumb_quality'] = 100;
	if (empty($data['thumb-crop']) || !isset($data['thumb-crop']))
		$galleryData['gallery_thumb_crop'] = "false";
	else
		$galleryData['gallery_thumb_crop'] = $data['thumb-crop'];
		$galleryData['gallery_thumb_crop'] = "true";


	if (empty($data['prev-width']))
		$galleryData['gallery_prev_width'] = 230;
	else
		$galleryData['gallery_prev_width'] = $data['prev-width'];
		if ($data['prev-width'] > 520)
				$galleryData['gallery_prev_width'] = 520;
	if (empty($data['prev-height']))
		$galleryData['gallery_prev_height'] = 345;
	else
		$galleryData['gallery_prev_height'] = $data['prev-height'];
		if ($data['prev-height'] > 520)
				$galleryData['gallery_prev_height'] = 520;
	if (empty($data['prev-quality']))
		$galleryData['gallery_prev_quality'] = 70;
	else
		$galleryData['gallery_prev_quality'] = $data['prev-quality'];
		if ($data['prev-quality'] > 100)
				$galleryData['gallery_prev_quality'] = 100;
	if (empty($data['prev-crop']) || !isset($data['prev-crop']))
		$galleryData['gallery_prev_crop'] = "false";
	else
		$galleryData['gallery_prev_crop'] = "true";


	if (empty($data['photo-width']))
		$galleryData['gallery_photo_width'] = 1200;
	else
		$galleryData['gallery_photo_width'] = $data['photo-width'];
		if ($data['photo-width'] > 1800)
				$galleryData['gallery_photo_width'] = 1800;
	if (empty($data['photo-height']))
		$galleryData['gallery_photo_height'] = 800;
	else
		$galleryData['gallery_photo_height'] = $data['photo-height'];
		if ($data['photo-height'] > 1200)
				$galleryData['gallery_photo_height'] = 1200;
	if (empty($data['photo-quality']))
		$galleryData['gallery_photo_quality'] = 70;
	else
		$galleryData['gallery_photo_quality'] = $data['photo-quality'];
		if ($data['photo-quality'] > 100)
				$galleryData['gallery_photo_quality'] = 100;
	if (empty($data['photo-crop']) || !isset($data['photo-crop']))
		$galleryData['gallery_photo_crop'] = "false";
	else
		$galleryData['gallery_photo_crop'] = "true";

	$site->setTaxonomy($galleryData);

	

	// referal page
	if (isset($data['ref'])) {
		$ref = urldecode($data['ref']);
		if (isset($data['hash'])) {
			if (strpos($data['hash'],"#")!=true) {
				$ref = $ref . "#" . $data['hash'];
			} else {
				$ref = $ref . $data['hash'];
			}
		}
	} else {
		$ref = "media-settings.php";
	}

	header("location: $ref");
}
?>