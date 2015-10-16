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

<?php if ($login->passwordResetLinkIsValid() == true) { ?>
<form method="post" action="password-reset.php" name="new_password_form">
	<input type='hidden' name='user_name' value='<?php echo $_GET['user_name']; ?>' />
	<input type='hidden' name='user_password_reset_hash' value='<?php echo $_GET['verification_code']; ?>' />

	<div class="form-group">
		<label for="user_password_new"><?php echo WORDING_NEW_PASSWORD; ?></label>
		<input id="user_password_new" type="password" name="user_password_new" pattern=".{6,}" required autocomplete="off" />
	</div>

	<div class="form-group">
		<label for="user_password_repeat"><?php echo WORDING_NEW_PASSWORD_REPEAT; ?></label>
		<input id="user_password_repeat" type="password" name="user_password_repeat" pattern=".{6,}" required autocomplete="off" />
	</div>


	<div class="form-buttons login-buttonbar">
		<button name="submit_new_password" id="formSubmit" type="submit" class="btn btn-success">
			<i class="fa fa-check"></i>
			<span><?php echo WORDING_SUBMIT_NEW_PASSWORD; ?></span>
		</button>
	</div>
</form>
<!-- no data from a password-reset-mail has been provided, so we simply show the request-a-password-reset form -->
<?php } else { ?>
	<form method="post" action="password-reset.php" name="password_reset_form">
		<div class="form-group-reset">
			<label for="user_name"><?php echo WORDING_REQUEST_PASSWORD_RESET; ?></label>
			<input id="user_name" type="text" name="user_name" required />
		</div>


		<div class="form-buttons login-buttonbar">
			<button name="request_password_reset" id="formSubmit" type="submit" class="btn btn-info">
				<i class="fa fa-refresh"></i>
				<span><?php echo WORDING_RESET_PASSWORD; ?></span>
			</button>
		</div>
	</form>

	<div class="loginLinks form-group">
		<a href="login.php">Go back to login form.</a>
	</div>
<?php } ?>


</div>

<?php
}; // end of pageContent()

include('template-login.php');
?>