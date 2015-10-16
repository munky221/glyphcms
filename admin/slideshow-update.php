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

$id = $_REQUEST['id'];
$action = $_REQUEST['a'];
$ref = $_REQUEST['ref'];
$hash = $_REQUEST['hash'];

// Start Database connection
global $db;

// set db query
$q = "SELECT * FROM `slideshows` WHERE `id` = '$id'";
$r = $db->query($q);
$a = $db->fetch_array_assoc($r)
?>

<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title" id="myModalLabel">Updating Slideshow Status</h4>
		</div>

		<form class="form" name="form-create-category" id="slideshowUpdateStatusForm" role="form" action="slideshow-status-update.php" method="post" enctype="multipart/form-data" target="_self">
			<div class="modal-body">
					<input type="hidden" name="t" value="slideshows">
					<input type="hidden" name="a" value="<?php _e($action); ?>">
					<input type="hidden" name="id" value="<?php _e($id); ?>">
					<input type="hidden" name="ref" value="<?php _e($ref); ?>">
					<input type="hidden" name="hash" value="<?php _e($hash); ?>">

					<p>Are you sure you want to set the status of slideshow "<span class="text-warning"><strong><?php _e($a['title']); ?></strong></span>" to <?php _status($action); ?>?</p>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="submit" id="slideshowUpdateStatusFormButton" class="btn btn-primary"><strong>Set as <?php _status($action); ?></strong></button>
			</div>
		</form> <!-- /#slideshowUpdateStatusForm -->

	</div>
</div>

<script>
	$('#slideshowUpdateStatusFormButton').on('click',function(e){
		$('#slideshowUpdateStatusForm').submit();
	});
</script>