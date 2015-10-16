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
	$login_ref = 'categories.php';
	// $login_ref = urlencode(curPageURL());
	header("location: login.php?ref=$login_ref");
}

// Pre-Screening
$_REQUEST = sanitize($_REQUEST);
if (isset($_REQUEST['t'])) {
	if ($_REQUEST['t'] == 'profile') {
		// perform category creation
		profile_update($_REQUEST);
	} else {
		header("location: index.php");
	}
}

function profile_update($data)
{

	global $db;

	$first_name = $data['first_name'];
	$last_name = $data['last_name'];
	if (isset($_POST['nice_name']) || !empty($_POST['nice_name']))
		$nice_name = $data['nice_name'];
	else
		$nice_name = "";
	$user_email = $data['user_email'];
	$error = 0;
	$error_msg = "";
	$updatePassword = false;

	$uid = $_SESSION['user_id'];
	$q = "SELECT * FROM `users` WHERE `user_id` = '$uid'";
	$r = $db->query($q);
	$a = $db->fetch_array_assoc($r);

	if (isset($_POST['old_password']))
	{
		if (password_verify($_POST['old_password'], $a['user_password_hash']))
		{
			if ((isset($_POST['password']) && !empty($_POST['password'])) || (isset($_POST['password2']) && !empty($_POST['password2'])))
			{
				if (strlen($_POST['password']) < 6)
				{
					$error++;
					$error_msg .= "- Password too short<br>";
				}
				if (strlen($_POST['password']) > 120)
				{
					$error++;
					$error_msg .= "- Password too long<br>";
				}
				if ($_POST['password'] == $_POST['password2'])
				{
					$user_password = $_POST['password'];

				    // crypt the user's password with PHP 5.5's password_hash() function, results in a 60 character
				    // hash string. the PASSWORD_DEFAULT constant is defined by the PHP 5.5, or if you are using
				    // PHP 5.3/5.4, by the password hashing compatibility library
				    $user_password_hash = password_hash($user_password, PASSWORD_DEFAULT);
				    $updatePassword = true;
				}
				else
				{
					$error++;
					$error_msg .= "- Passwords did not match<br>";
				}
			}
		}
		else
		{
			$error++;
			$error_msg .= "- Wrong password<br>";
		}
	}

	if ($error == 0)
	{


		// Start Database connection

		// set db query
		if ($updatePassword)
			$q2 = "UPDATE `users` SET `first_name` = '$first_name', `last_name` = '$last_name', `nice_name` = '$nice_name', `user_email` = '$user_email', `user_password_hash` = '$user_password_hash' WHERE user_id='$uid'";
		else
			$q2 = "UPDATE `users` SET `first_name` = '$first_name', `last_name` = '$last_name', `nice_name` = '$nice_name', `user_email` = '$user_email' WHERE user_id='$uid'";
		$r2 = $db->query($q2);
		
		// referal page
		if (isset($data['ref']))
		{
			$ref = $data['ref'];
		}
		else
		{
			$ref = "categories.php";
		}

		$_SESSION['site']['return_msg'] = "Your <strong>Profile</strong> has been updated.";
		if ($updatePassword)
			$_SESSION['site']['return_msg'] .= "<br> Also, your <strong>Password</strong> has been changed.";
		$_SESSION['site']['return_type'] = "success";

	}
	else
	{
		$_SESSION['site']['return_msg'] = "Your <strong>Profile</strong> was not updated. <br>" . $error_msg;
		$_SESSION['site']['return_type'] = "warning";
	}

	header("location: profile.php");
}
?>