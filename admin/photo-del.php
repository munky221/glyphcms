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
	$login_ref = 'photos.php';
	// $login_ref = urlencode(curPageURL());
	header("location: login.php?ref=$login_ref");
}

$id = $_REQUEST['id'];
$ref = $_REQUEST['ref'];

// Start Database connection
global $db;

// set db query
$q = "SELECT * FROM `photos` WHERE `id` = '$id'";
$r = $db->query($q);
$a = $db->fetch_array_assoc($r);

if ($a['title'] != "")
	$title = $a['title'];
else
	$title = $a['name'];
?>

<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title" id="myModalLabel">Deleting Photo</h4>
		</div>
		<div class="modal-body">

			<form class="form" name="form-delete-photo" id="photoDeleteForm<?php _e($id); ?>" role="form" action="photo-del-action.php" method="post" enctype="multipart/form-data" target="_self">
				<input type="hidden" name="t" value="photos">
				<input type="hidden" name="id" value="<?php _e($id); ?>">
				<input type="hidden" name="ref" value="<?php _e($ref); ?>">

			</form> <!-- /#form-create-slideshow -->

			<p>Are you sure you want to delete the photo "<span class="text-warning"><strong><?php _e($title); ?></strong></span>"?</p>

		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
			<button id="photoDeleteButton<?php _e($id); ?>" type="button" class="btn btn-warning">Delete Permanently</button>
		</div>
	</div>
</div>

<script>
	$('#photoDeleteButton<?php _e($id); ?>').on('click',function(e){
		$('#photoDeleteForm<?php _e($id); ?>').submit();
	});
	$('.photoUpdateButton').on('click',function(e){
		e.preventDefault();
		$('#photoDeleteForm<?php _e($id); ?>').submit();
	});


	$("#photoDeleteForm<?php _e($id); ?>").submit(function() {

	    var url = "photo-del-action.php";

	    $('.grid[data-id=<?php _e($id); ?>]').addClass('preloadPhoto')

	    $.ajax({
	           type: "POST",
	           url: url,
	           data: $("#photoDeleteForm<?php _e($id); ?>").serialize(),
	           success: function(data)
	           {

					// close the modal box
					$('.modal').modal('hide');

					// remove the DOM element
	           		$('.grid[data-id=<?php _e($id); ?>]').fadeOut('300',function(){
	           			$('.grid[data-id=<?php _e($id); ?>]').remove();
	           		});
	           }
	         });

	    return false;
	});
</script>