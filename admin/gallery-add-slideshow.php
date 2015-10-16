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

$id = $_REQUEST['id'];
$ref = $_REQUEST['ref'];
$hash = $_REQUEST['hash'];

// Start Database connection
global $db;

// set db query
$q = "SELECT * FROM `galleries` WHERE `id` = '$id'";
$r = $db->query($q);
$a = $db->fetch_array_assoc($r);
$title = $a['title'];
$status = $a['status'];

$hovermenu = '<li class="tableHoverMenuPrimary"><a href="gallery-edit.php?id=' . $a['id'] . '"><i class="fa fa-pencil"></i> Edit Gallery</a></li>';
?>

<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title" id="myModalLabel">Add Slideshows To Gallery</h4>
		</div>

		<form class="form" name="form-gallery-add-slideshow" id="galleryAddSlideshowForm" role="form" action="gallery-add-slideshow-action.php" method="post" enctype="multipart/form-data" target="_self">
			<div class="modal-body">
					<input type="hidden" name="t" value="galleries">
					<input type="hidden" name="id" value="<?php _e($id); ?>">
					<input type="hidden" name="ref" value="<?php _e($ref); ?>">
					<input type="hidden" name="hash" value="<?php _e($hash); ?>">

					<div class="modal-edit-header">
						<div class="text-center"><h2><?php _e($title); ?></h2></div>
						<a href="gallery-edit.php?id=<?php _e($a['id']); ?>" class="label label-primary modal-edit-header-btn"><i class="fa fa-pencil"></i></a>
					</div>

					<div class="form-group">
								<div id="gallery-select-slideshows">
									<ul class="gallery-slideshow-list">
										<?php
										// set db query for published slideshows
										$q3 = "SELECT * FROM `slideshows` WHERE `status`='2' ORDER BY `id` DESC";
										$r3 = $db->query($q3);

										// parse slideshow options
										$slideshows = explode(";", $a['slideshows']);
										while ($a3 = $db->fetch_array_assoc($r3)) {

											// get slideshow cover
											$sid = $a3['id'];
											$slideshow_title = getExcerpt($a3['title'],0,15,true);
											$cover = getSlideshowCover($sid);
										?>
										<?php if (!in_array($a3['id'],$slideshows)) { ?>
										<li class="col-xs-6 col-sm-4 selected">
											<label for="slideshow<?php _e($sid); ?>">
												<a href="slideshow-edit.php?id=<?php _e($sid); ?>" class="label label-default"><?php _e($slideshow_title); ?> <i class="fa fa-pencil"></i></a>
												<img src="<?php _e($cover); ?>" width="<?php _e(GALLERY_THUMB_WIDTH); ?>px" data-toggle="tooltip" data-placement="top" title="<?php _e($a3['title']); ?>" rel="tooltip">
												<input type="checkbox" name="slideshow[]" id="slideshow<?php _e($sid); ?>" value="<?php _e($sid); ?>" <?php if (in_array($a3['id'],$slideshows)) _e("checked"); ?>>
											</label>
										</li>
										<?php } ?>
										<?php } ?>
									</ul>
									<div class="clearfix"></div>
									<div class="text-center paginationWrapper">
									</div>
								</div> <!-- /#gallery-select-slideshows -->
					</div>

			</div> <!-- /.modal-body -->
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-primary categoryUpdateButton">Update Category</button>
			</div>
		</form> <!-- /#galleryAddSlideshowForm -->
		
	</div>
</div>

<script>

	// SUBMIT FORM
	$('#galleryAddSlideshowFormButton').on('click',function(e){
		e.preventDefault();
		$('#galleryAddSlideshowForm').submit();
	});

	if ($('[data-toggle="tooltip"]').length>0) {
		$('[data-toggle="tooltip"]').tooltip();
	}

	$("#galleryAddSlideshowForm").submit(function() {

	    var url = "gallery-add-slideshow-action.php";

	    $('#table-slideshows-<?php _e($id); ?>').addClass('preloadSlideshowThumbnails');

	    $.ajax({
	           type: "POST",
	           url: url,
	           data: $("#galleryAddSlideshowForm").serialize(),
	           success: function(data)
	           {

					// console.log(data);

					// update the table list
					$('#table-slideshows-<?php _e($id); ?>').load('<?php _e($ref); ?> #table-slideshows-<?php _e($id); ?> .table-slideshows-content',
						function (responseText, textStatus, XMLHttpRequest) {
							if (textStatus == "success") {
								ajaxCallbackFunc_Gallery('#table-slideshows-<?php _e($id); ?>');

								$.bootstrapGrowl("<strong>Gallery</strong> has been updated.",{
									ele: '#table-slideshows-<?php _e($id); ?>',
									offset: {from: 'top', amount: 0},
									type: 'success',
									align: 'center',
									delay: 500,
									stackup_spacing: 0
								});					

								$('#table-slideshows-<?php _e($id); ?>').removeClass('preloadSlideshowThumbnails');


								$(window).resize();
								var $icon = $('#table-slideshows-<?php _e($id); ?> .table-slideshows-expand i');
								var $UL = $('#table-slideshows-<?php _e($id); ?> .slideshow-thumbnails');
								var $expander = $('#table-slideshows-<?php _e($id); ?> .table-slideshows-expand');
								
				    			$UL.removeClass('collapse').addClass('expand');
	    						$expander.find('a').attr('disabled', false);
				    			$icon.removeClass('fa-caret-down').addClass('fa-caret-up');
							}
						});


	           }
	         });

		// close the modal box
		$('.modal').modal('hide');

	    return false;
	});

	// PAGINATION
	if ($.fn.simplePagination) {
		$(function(){
			var perPage = 6;
			var opened = 1;
			var onClass = 'active';
			var paginationSelector = '.paginationWrapper';
			$('.gallery-slideshow-list').simplePagination(perPage, opened, onClass, paginationSelector);
		});
	}
	$('.modal-body .tableHoverMenu .modalButton').on('click', function(e){
		
		e.preventDefault();

		var url = $(this).attr('href');
		if (url.indexOf('#') == 0) {
			$(url).modal('open');
		} else {
			$.get(url, function(data) {
				$('<div class="modal" role="dialog">' + data + '</div>').modal();
			}).success(function(){
				// do something...
			});
		}
		console.log('modal init'); 
	});

</script>