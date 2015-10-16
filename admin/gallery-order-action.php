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


// Start Database connection
global $db;

$id = $_POST['id'];
$data = $_POST['data'];
$value = menuArrayConvert($data);

echo "<pre>";
print_r($value);
echo "</pre>";

// set db query
$q = "UPDATE `galleries` SET `slideshows` = '$data' WHERE `id`='$id'";
$r = $db->query($q);


$ref = "menu.php";

// header("location: $ref");

echo "OK";

?>