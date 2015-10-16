<?php
// Admin Panel Required Files
require('admin-core.php');

// Page Object
$page = new Page;
$page->pageTitle = "Login";
$page->pageSlug = "login";
$page->pageIcon = "fa-lock";
$page->pageParent = "";
$page->pageParentSlug = "";
$page->pageDescription = "Herp derp you need to login, derp...";
$page->pageExcerpt = "Herpy derpy derp...";
$page->pageIncludes = <<< EOI
<!-- VALIDATE FORM -->
<script src="js/jquery.validate.min.js"></script>
<script src="js/jquery.validate.additional-method.js"></script>
EOI;
// END OF INCLUDES

function pageContent()
{
?>

<div class="loginBox">

			
		
		<?php

		if (isset($_REQUEST['ref']))
			$ref = $_REQUEST['ref'];
		else
			$ref = "index.php";

		global $login;
		// show potential errors / feedback (from login object)
		if (isset($login)) {
			if ($login->errors) {
				echo '<div id="loginErrorMsg" class="alert alert-danger alert-dismissable">';
				echo '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
				foreach ($login->errors as $error) {
					echo $error;
				}
				echo '</div>';
			}
			if ($login->messages) {
				echo '<div id="loginMsg" class="alert alert-success alert-dismissable">';
				echo '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
				foreach ($login->messages as $message) {
					echo $message;
				}
				echo '</div>';
			}
		}
		?>

		<?php
		// show potential errors / feedback (from registration object)
		if (isset($registration)) {
			if ($registration->errors) {
				echo '<div id="loginErrorMsg" class="alert alert-danger alert-dismissable">';
				echo '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
				foreach ($registration->errors as $error) {
					echo $error;
				}
				echo '</div>';
			}
			if ($registration->messages) {
				echo '<div id="loginMsg" class="alert alert-success alert-dismissable">';
				echo '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
				foreach ($registration->messages as $message) {
					echo $message;
				}
				echo '</div>';
			}
		}
		?>

<?php if ($login->isUserLoggedIn() == true) { ?>
	<div class="loginCountDown alert alert-success">You have successfully logged in. You will be re-directed to your <a href="index.php">dashboard</a> in <span class="countNumber">3</span> second/s.</div>
	<script>
	var count = 3;

	var counter=setInterval(timer, 1000); //1000 will  run it every 1 second

	function timer()
	{
		count=count-1;
		if (count <= 0)
		{
			clearInterval(counter);
			$('.countNumber').html(count);
			//counter ended, do something here
			document.location.assign('index.php');
			return;
		}
		$('.countNumber').html(count);

		//Do code for showing the number of seconds here
	}
	</script>
<?php } else { ?>

	<form class="form form-login" name="form-login" id="loginForm" role="form" action="login.php" method="post" enctype="multipart/form-data" target="_self">
		<input type="hidden" name="ref" value="<?php _e($ref); ?>">
		<input type="hidden" name="login">
		<div class="form-group">
			<label for="user_name">Username</label>
			<input type="text" name="user_name" id="user_name" required min-length="5">
		</div>

		<div class="form-group">
			<label for="user_password">Password</label>
			<input id="login_input_password" class="login_input" type="password" name="user_password" autocomplete="off" required min-length="6"/>
		</div>

		<div class="form-buttons login-buttonbar">
			<button id="formSubmit" type="submit" class="btn btn-success">
				<i class="fa fa-lock"></i>
				<span>Log in</span>
			</button>
		</div>
	</form>

	<div class="loginLinks form-group">
		<a href="password-reset.php">I forgot my password.</a>
	</div>
<?php } ?>
</div>

<?php
}; // end of pageContent()

include('template-login.php');
?>