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
	// $login_ref = 'photos.php';
	$login_ref = urlencode(curPageURL());
	header("location: login.php?ref=$login_ref");
}

$id = $_REQUEST['id'];
	
// referal page
if (isset($_REQUEST['ref'])) {
	$ref = $_REQUEST['ref'];
} else {
	$ref = "photo-edit.php?id=$id";
}


// Start Database connection
global $db;
// set db query
$q = "SELECT * FROM `photos` WHERE `id` = '$id'";
$r = $db->query($q);
$a = $db->fetch_array_assoc($r);

if ($a['title']=="") {
	$title = $a['name'];
} else {
	$title = $a['title'];
}
// $title = getExcerpt($title,0,35,true);
?>

<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title" id="myModalLabel">Photo Edit</h4>
		</div>

		<form class="form" name="form-update-category" id="photoUpdateForm<?php _e($id); ?>" role="form" action="photo-update.php" method="post" enctype="multipart/form-data" target="_self">
			<div class="modal-body">
					<input type="hidden" name="t" value="photos">
					<input type="hidden" name="id" value="<?php _e($id); ?>">
					<input type="hidden" name="ref" value="<?php _e($ref); ?>">

					<div class="media photo-edit-header">
						<a class="pull-left" href="<?php _e(getImage($id,'full')); ?>">
							<img class="media-object" src="<?php _e(getImage($id,'thumbnail')); ?>" alt="...">
						</a>
						<div class="media-body">
							<h3><?php _e($title); ?></h3>
							<p class="help-block">
								ID Number: <?php _e($a['id']); ?><br>
								Filename: <a href="<?php _e(getImage($id,'full')); ?>" target="_blank"><?php _e($a['name']); ?> <i class="fa fa-external-link"></i></a>
							</p>
						</div>
					</div>

					<div class="form-group">
						<label for="title">Title</label>
						<input name="title" id="title" class="form-control" placeholder="Enter a new title..." value="<?php _e($a['title']); ?>">
					</div>

					<div class="form-group">
						<label for="description">Description</label>
						<textarea name="description" id="description" class="form-control" placeholder="Add description (optional)"><?php _e($a['description']); ?></textarea>
					</div>

								<div class="categoryGroup">
									<?php
									// set db query
									$q2 = "SELECT * FROM `categories` ORDER BY `title` ASC";
									$r2 = $db->query($q2);

									// parse category options
									$categories = explode(";", $a['categories']);
									?>
									<?php while ($a2 = $db->fetch_array_assoc($r2)) { ?>
									<div class="col-xs-6 col-sm-4"><label><input type="checkbox" name="category[]" value="<?php _e($a2['id']); ?>" <?php if (isCategory($categories,$a2['id'])) _e("checked"); ?>> <?php _e($a2['title']); ?></label></div>
									<?php } ?>
								</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-primary photoUpdateButton">Update Category</button>
			</div>
		</form> <!-- /#categoryUpdateForm -->
		
	</div>
</div>

<script>
	// CATEGORY UPDATE FORM
	$('.photoUpdateButton').on('click',function(e){
		e.preventDefault();
		$('#photoUpdateForm<?php _e($id); ?>').submit();
	});


	$("#photoUpdateForm<?php _e($id); ?>").submit(function() {

	    var url = "photo-update.php";

	    $('.grid[data-id=<?php _e($id); ?>]').addClass('preloadPhoto');

	    $.ajax({
	           type: "POST",
	           url: url,
	           data: $("#photoUpdateForm<?php _e($id); ?>").serialize(),
	           success: function(data)
	           {

					// console.log(data);

					// update the table list
					<?php if (empty($_GET['ref'])) { ?>
					if(typeof selected_filter_id != 'undefined')
						var loadUrl = "photos.php?filter=" + selected_filter_id;
					else
						var loadUrl = "photos.php";
					<?php } else { ?>
						var loadUrl = "<?php _e(urldecode($_GET['ref'])); ?>";
					<?php } ?>

					$('.grid[data-id=<?php _e($id); ?>]').load(loadUrl + ' .grid[data-id=<?php _e($id); ?>] .grid-content',
						function (responseText, textStatus, XMLHttpRequest) {
							if (textStatus == "success") {
								// check if grid is present on ajax load
								if (responseText.indexOf('<div class="grid" data-id="<?php _e($id); ?>">') >= 0) {
									ajaxCallbackFunc_Photos('.grid[data-id=<?php _e($id); ?>]');

									$.bootstrapGrowl("<strong>Photo</strong> has been updated.",{
										ele: '.grid[data-id=<?php _e($id); ?>]',
										offset: {from: 'top', amount: 0},
										type: 'success',
										align: 'center',
										delay: 15000,
										stackup_spacing: 0
									});
								} else {
									<?php
										if (strpos(urldecode($ref), 'photos.php') !== false) {
											$photo_alert_text = " and is no longer part of the search/filter result. Please update your filter or search parameters.</p>";
										} else {
											$photo_alert_text = " and is no longer part of the current list provided. Click the button below to proceed</p><p><a href='photos.php' class='btn btn-primary'><i class='fa fa-th'></i> Photo Manager</a></p>";
										}
									?>
									$('.grid[data-id=<?php _e($id); ?>]').html("<div class='alert alert-info photo-alert'><p><strong>This photo has been updated</strong><?php _e($photo_alert_text); ?></div>");
								}

								$('.grid[data-id=<?php _e($id); ?>]').removeClass('preloadPhoto');
							}
						});


	           }
	         });

		

		// close the modal box
		$('.modal').modal('hide');

	    return false;
	});
</script>