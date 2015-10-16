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

$id = $_REQUEST['id'];
$ref = $_REQUEST['ref'];


// Start Database connection
global $db;
// set db query
$q = "SELECT * FROM `categories` WHERE `id` = '$id'";
$r = $db->query($q);
$a = $db->fetch_array_assoc($r)
?>

<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title" id="myModalLabel">Edit Category</h4>
		</div>

		<form class="form categoryUpdateForm" name="form-update-category" id="categoryUpdateForm<?php _e($id); ?>" role="form" action="category-update-action.php" method="post" enctype="multipart/form-data" target="_self">
			<div class="modal-body">
					<input type="hidden" name="t" value="categories">
					<input type="hidden" name="id" value="<?php _e($id); ?>">
					<input type="hidden" name="ref" value="<?php _e($ref); ?>">

					<div class="form-group">
						<label>Editing: <?php _e($a['title']); ?></label>
						<input name="title" id="title" class="form-control" placeholder="Enter a new title..." required min-length="5" value="<?php _e($a['title']); ?>">
					</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-primary categoryUpdateButton">Update Category</button>
			</div>
		</form> <!-- /#categoryUpdateForm -->
		
	</div>
</div>

<script>
		// CATEGORY UPDATE FORM
		$('.categoryUpdateButton').on('click',function(e){
			$('#categoryUpdateForm<?php _e($id); ?>').valid();
		});
</script>