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
$_REQUEST = sanitize($_REQUEST);
if (isset($_REQUEST['t'])) {
	if ($_REQUEST['t'] == 'options') {
		// perform slideshow creation
		options_update($_REQUEST);
	} else {
		header("location: index.php");
	}
}

function options_update($data)
{

	// General
	if (empty($data['site-name']))
		$general_data['site_name'] = "White Rab.it";
	else
		$general_data['site_name'] = $data['site-name'];

	if (empty($data['site-title']))
		$general_data['site_title'] = "White Rab.it is awesome!";
	else
		$general_data['site_title'] = $data['site-title'];

	if (empty($data['site-domain']))
		$general_data['site_domain'] = $_SERVER['SERVER_NAME'];
	else
		$general_data['site_domain'] = $data['site-domain'];

	if (empty($data['site-url']))
		$general_data['site_url'] = $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
	else {
		if (substr($data['site-url'], -1) !== "/") {
			$general_data['site_url'] = $data['site-url'] . "/";
		} else {
			$general_data['site_url'] = $data['site-url'];
		}
	}


	// Appearance
	if (empty($data['theme']))
		$appearance_data['theme_name'] = "hybris";
	else
		$appearance_data['theme_name'] = $data['theme'];

	if (empty($data['site-homepage-default'])) {
		$appearance_data['site_homepage_default'] = "false";
		
		if (empty($data['site-homepage-type-select-collect'])) {
			$appearance_data['site_homepage_default'] = "true";
			$appearance_data['site_homepage_target_type'] = "";
			$appearance_data['site_homepage_target'] = "";
		} else {
			$appearance_data['site_homepage_target_type'] = $data['site-homepage-type-select-collect'];
			if ($data['site-homepage-type-select-collect'] == "1")
				$appearance_data['site_homepage_target'] = $data['site-homepage-static-page-select'];
			else if ($data['site-homepage-type-select-collect'] == "3")
				$appearance_data['site_homepage_target'] = $data['site-homepage-slideshow-presentation-select'];
			else if ($data['site-homepage-type-select-collect'] == "4")
				$appearance_data['site_homepage_target'] = $data['site-homepage-slideshow-grid-select'];
			else if ($data['site-homepage-type-select-collect'] == "5")
				$appearance_data['site_homepage_target'] = $data['site-homepage-gallery-presentation-select'];
			else if ($data['site-homepage-type-select-collect'] == "6")
				$appearance_data['site_homepage_target'] = $data['site-homepage-gallery-grid-select'];
			else {
				$appearance_data['homepage_default'] = "true";
				$appearance_data['site_homepage_target_type'] = "";
				$appearance_data['site_homepage_target'] = "";
			}
		}
	} else {
		$appearance_data['site_homepage_default'] = "true";
	}

	if (empty($data['display-slider-filmstrip']))
		$appearance_data['display_slider_filmstrip'] = "false";
	else
		$sharing_data['display_slider_filmstrip'] = ($data['display-slider-filmstrip'] == "1") ? "true" : "false";

	if (empty($data['display-slider-infobar']))
		$appearance_data['display_slider_infobar'] = "false";
	else
		$sharing_data['display_slider_infobar'] = ($data['display-slider-infobar'] == "1") ? "true" : "false";


	// Personal
	if (empty($data['owner-name']))
		$personal_data['owner_name'] = "Your Name";
	else
		$personal_data['owner_name'] = $data['owner-name'];

	if (empty($data['owner-email']))
		$personal_data['owner_email'] = "mail@domain.com";
	else
		$personal_data['owner_email'] = $data['owner-email'];


	// Social Media
	if (empty($data['social-facebook']))
		$social_data['social_facebook'] = "";
	else
		$social_data['social_facebook'] = $data['social-facebook'];

	if (empty($data['social-facebook-like']))
		$social_data['social_facebook_like'] = "";
	else
		$social_data['social_facebook_like'] = $data['social-facebook-like'];

	if (empty($data['social-twitter']))
		$social_data['social_twitter'] = "";
	else
		$social_data['social_twitter'] = $data['social-twitter'];

	if (empty($data['social-googleplus']))
		$social_data['social_googleplus'] = "";
	else
		$social_data['social_googleplus'] = $data['social-googleplus'];

	if (empty($data['social-behance']))
		$social_data['social_behance'] = "";
	else
		$social_data['social_behance'] = $data['social-behance'];

	if (empty($data['social-linkedin']))
		$social_data['social_linkedin'] = "";
	else
		$social_data['social_linkedin'] = $data['social-linkedin'];

	if (empty($data['social-instagram']))
		$social_data['social_instagram'] = "";
	else
		$social_data['social_instagram'] = $data['social-instagram'];

	if (empty($data['social-tumblr']))
		$social_data['social_tumblr'] = "";
	else
		$social_data['social_tumblr'] = $data['social-tumblr'];


	// Sharing
	if (empty($data['share-facebook']))
		$sharing_data['share_facebook'] = "false";
	else
		$sharing_data['share_facebook'] = ($data['share-facebook'] == "1") ? "true" : "false";

	if (empty($data['share-twitter']))
		$sharing_data['share_twitter'] = "false";
	else
		$sharing_data['share_twitter'] = ($data['share-twitter'] == "1") ? "true" : "false";

	if (empty($data['share-googleplus']))
		$sharing_data['share_googleplus'] = "false";
	else
		$sharing_data['share_googleplus'] = ($data['share-googleplus'] == "1") ? "true" : "false";

	if (empty($data['share-pinterest']))
		$sharing_data['share_pinterest'] = "false";
	else
		$sharing_data['share_pinterest'] = ($data['share-pinterest'] == "1") ? "true" : "false";

	if (empty($data['share-linkedin']))
		$sharing_data['share_linkedin'] = "false";
	else
		$sharing_data['share_linkedin'] = ($data['share-linkedin'] == "1") ? "true" : "false";

	if (empty($data['share-reddit']))
		$sharing_data['share_reddit'] = "false";
	else
		$sharing_data['share_reddit'] = ($data['share-reddit'] == "1") ? "true" : "false";


	// Google
	if (empty($data['google-analytics']))
		$google_data['google_analytics'] = "UA-XXXXX-X";
	else
		$google_data['google_analytics'] = $data['google-analytics'];

	if (empty($data['google-site-verification']))
		$google_data['google_site_verification'] = "1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	else
		$google_data['google_site_verification'] = $data['google-site-verification'];


	// Start Database connection
	global $site;

	// set general
	$site->setTaxonomy($general_data);

	$site->setTaxonomy($appearance_data);

	$site->setTaxonomy($personal_data);

	$site->setTaxonomy($social_data);

	$site->setTaxonomy($sharing_data);

	$site->setTaxonomy($google_data);

	

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
		$ref = "options.php";
	}

	header("location: $ref");
}
?>