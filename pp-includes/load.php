<?php
/*
Photopress Site Loader Functions
*/

$isHome = true;
$isPage = false;
$isSlideshow = false;
$isSlideshowRoll = false;
$isSlideshowPop = false;
$isGallery = false;
$isFrame = false;

if (isset($_REQUEST['pid'])) {
	$pid = $_REQUEST['pid'];
} else {
	$pid = 0;
}

if (isset($_REQUEST['gid'])) {
	$gid = $_REQUEST['gid'];
} else {
	$gid = 0;
}

if (isset($_REQUEST['t'])) {

	// if page type is default
	if ($_REQUEST['t'] == '1') {
		$isPage = true;
		$isHome = false;

	// if page type is gallery
	} else if ($_REQUEST['t'] == '2') {
		$isGallery = true;
		$isHome = false;

	// if page type is contact
	} else if ($_REQUEST['t'] == '3') {
		$isFrame = true;
		$isHome = false;

	// if page type is slideshow
	} else if ($_REQUEST['t'] == '4') {
		$isSlideshow = true;
		$isHome = false;

	// if page type is rolling slideshow
	} else if ($_REQUEST['t'] == '5'){
		$isSlideshowRoll = true;
		$isHome = false;

	// if page type is rolling slideshow
	} else if ($_REQUEST['t'] == '6'){
		$isSlideshowPop = true;
		$isHome = false;
	}
} else {
	$isHome = true;
}
?>