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
	$login_ref = 'menu.php';
	// $login_ref = urlencode(curPageURL());
	header("location: login.php?ref=$login_ref");
}

$id = $_REQUEST['id'];
$ref = $_REQUEST['ref'];


// Start Database connection
global $db;
// set db query
$q = "SELECT * FROM `menu` WHERE `id` = '$id'";
$r = $db->query($q);
$a = $db->fetch_array_assoc($r);
?>

<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title" id="myModalLabel">Deleting Menu Item</h4>
		</div>
		<div class="modal-body">

			<form class="form" name="form-delete-menu" id="menuDeleteForm<?php _e($id); ?>" role="form" action="menu-del-action.php" method="post" enctype="multipart/form-data" target="_self">
				<input type="hidden" name="t" value="menu">
				<input type="hidden" name="id" value="<?php _e($id); ?>">
				<input type="hidden" name="ref" value="<?php _e($ref); ?>">

			</form> <!-- /#form-create-slideshow -->

			<p>Are you sure you want to delete the menu item "<span class="text-warning"><strong><?php _e("{$a['title']}"); ?></strong></span>"  <a href="javascript:void(0);" class="label label-info" data-toggle="tooltip" data-placement="top" title="Poof! Gone, just like magic!"><i class="fa fa-question"></i></a></p>

		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
			<button id="submitButton<?php _e($id); ?>" type="button" class="btn btn-warning"><strong>Delete</strong></button>
		</div>
	</div>
</div>

<script>
	$('#submitButton<?php _e($id); ?>').on('click',function(e){
		$('#menuDeleteForm<?php _e($id); ?>').submit();
	});

	if ($('[data-toggle="tooltip"]').length>0) {
		$('[data-toggle="tooltip"]').tooltip();
	}
</script>