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
$a = $db->fetch_array_assoc($r)
?>

<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title" id="myModalLabel">Updating Menu Item</h4>
		</div>

		<form class="form" name="form-update-menu" id="menuItemUpdateForm" role="form" action="menu-update-action.php" method="post" enctype="multipart/form-data" target="_self">
			<div class="modal-body">
					<input type="hidden" name="t" value="menu">
					<input type="hidden" name="id" value="<?php _e($id); ?>">
					<input type="hidden" name="ref" value="<?php _e($ref); ?>">

					

							<div class="form-group">
								<label>Title</label>
								<input name="title" id="title" class="form-control" placeholder="Your menu title..." required min-length="5" value="<?php _e($a['title']); ?>">
							</div>

							<div class="form-group">
								<label>Menu Type</label>
								<div>
									<div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
										<h4>Grid Type</h4>

										<label class="radio">
											<input type="radio" name="type" id="pageType4"-edit value="4" class="pageType-edit" <?php if ($a['type'] == '4') echo "checked"; ?>> Slideshow Grid
											<sup data-toggle="tooltip" data-placement="top" title="Slideshow Grid - generate a link to a specific slideshow grid."><i class="fa fa-question"></i></sup>
										</label>

										<label class="radio">
											<input type="radio" name="type" id="pageType6-edit" value="6" class="pageType-edit" <?php if ($a['type'] == '6') echo "checked"; ?>> Gallery Grid
											<sup data-toggle="tooltip" data-placement="top" title="Gallery Grid - generate a link to a specific gallery grid."><i class="fa fa-question"></i></sup>
										</label>
									</div>
									<div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
										<h4>Presentation</h4>

										<label class="radio">
											<input type="radio" name="type" id="pageType3-edit" value="3" class="pageType-edit" <?php if ($a['type'] == '3') echo "checked"; ?>> Slideshow 
											<sup data-toggle="tooltip" data-placement="top" title="Slideshow - generate a link to a specific slideshow presentation."><i class="fa fa-question"></i></sup>
										</label>

										<label class="radio">
											<input type="radio" name="type" id="pageType5-edit" value="5" class="pageType-edit" <?php if ($a['type'] == '5') echo "checked"; ?>> Gallery
											<sup data-toggle="tooltip" data-placement="top" title="Gallery - generate a link to a specific gallery presentation."><i class="fa fa-question"></i></sup>
										</label>
									</div>
									<div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
										<h4>Other Types</h4>

										<label class="radio">
											<input type="radio" name="type" id="pageType0-edit" value="0" class="pageType-edit" <?php if ($a['type'] == '0') echo "checked"; ?>> Placeholder
											<sup data-toggle="tooltip" data-placement="top" title="Placeholder - Use this if you just want to generate a placeholder for submenus."><i class="fa fa-question"></i></sup>
										</label>

										<label class="radio">
											<input type="radio" name="type" id="pageType1-edit" value="1" class="pageType-edit" <?php if ($a['type'] == '1') echo "checked"; ?>> Page
											<sup data-toggle="tooltip" data-placement="top" title="Page - for static pages (i.e: About us page, etc.)"><i class="fa fa-question"></i></sup>
										</label>
										<label class="radio">
											<input type="radio" name="type" id="pageType2-edit" value="2" class="pageType-edit" <?php if ($a['type'] == '2') echo "checked"; ?>> Hyperlink
											<sup data-toggle="tooltip" data-placement="top" title="Hyperlink - use this if you want to place a link on the menu that's outside your website."><i class="fa fa-question"></i></sup>
										</label>
									</div>
									<div class="clearfix"></div>
								</div>
							</div>

							<div id="menuTypePanels-edit">
								<div id="panel-placeholder-edit" class="form-group menu-type-panel<?php if ($a['type'] == '0') echo ' show'; ?>">
									<label>No options...</label>
								</div> <!-- /#panel-placeholder -->

								<div id="panel-page-edit" class="form-group menu-type-panel<?php if ($a['type'] == '1') echo ' show'; ?>">
									<label>Select Page</label>
									<div>
										<select name="pageSelect">
											<?php
											// set db query
											$q2 = "SELECT * FROM `pages` WHERE `status`='2' ORDER BY `title` ASC";
											$r2 = $db->query($q2);
											?>
											<?php while ($a2 = $db->fetch_array_assoc($r2)) { ?>
											<option value="<?php _e($a2['id']); ?>"<?php if ($a['data'] == $a2['id']) echo ' selected'; ?>><?php _e($a2['title']); ?></option>
											<?php } ?>
										</select>
									</div>
								</div> <!-- /#panel-page -->

								<div id="panel-hyperlink-edit" class="form-group menu-type-panel<?php if ($a['type'] == '2') echo ' show'; ?>">
									<label>Target URL</label>
									<input name="hyperlink" id="hyperlink" class="form-control" placeholder="http://www..." value="<?php _e($a['data']); ?>">
								</div> <!-- /#panel-hyperlink -->

								<div id="panel-slideshow-edit" class="form-group menu-type-panel<?php if ($a['type'] == '3' || $a['type'] == '4') echo ' show'; ?>">
									<label>Select Slideshow</label>
									<div>
										<select name="slideshowSelect">
											<?php
											// set db query
											$q4 = "SELECT * FROM `slideshows` WHERE `status`='2' ORDER BY `title` ASC";
											$r4 = $db->query($q4);
											?>
											<?php while ($a4 = $db->fetch_array_assoc($r4)) { ?>
											<option value="<?php _e($a4['id']); ?>" <?php if ($a['data'] == $a4['id']) echo 'selected'; ?>><?php _e($a4['title']); ?> --- (<?php _e(getSlideshowPhotoCount($a4['id'])); ?>)</option>
											<?php } ?>
										</select>
									</div>
								</div> <!-- /#panel-slideshow -->

								<div id="panel-gallery-edit" class="form-group menu-type-panel<?php if ($a['type'] == '5' || $a['type'] == '6') echo ' show'; ?>">
									<label>Select Gallery</label>
									<div>
										<select name="gallerySelect">
											<?php
											// set db query
											$q3 = "SELECT * FROM `galleries` WHERE `status`='2' ORDER BY `title` ASC";
											$r3 = $db->query($q3);
											?>
											<?php while ($a3 = $db->fetch_array_assoc($r3)) { ?>
											<option value="<?php _e($a3['id']); ?>"<?php if ($a['data'] == $a3['id']) echo ' selected'; ?>><?php _e($a3['title']); ?></option>
											<?php } ?>
										</select>
									</div>
								</div> <!-- /#panel-gallery -->

							</div> <!-- /#typePanels -->


			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="submit" id="submitButton" class="btn btn-primary"><i class="fa fa-check"></i> <strong>Update Menu Item</strong></button>
			</div>
		</form> <!-- /#slideshowUpdateStatusForm -->

	</div>
</div>

<script>

	/* Menu Item Type Selection */

	if ($('#menuTypePanels-edit').length > 0) {
		$('.pageType-edit').change(function(){
			$this = $(this);
			$('#menuTypePanels-edit .menu-type-panel').removeClass('show').hide();

			if ($this.val() == '0')
				$('#panel-placeholder-edit').addClass('show');
			else if ($this.val() == '1')
				$('#panel-page-edit').addClass('show');
			else if ($this.val() == '2')
				$('#panel-hyperlink-edit').addClass('show');
			else if ($this.val() == '3' || $this.val() == '4')
				$('#panel-slideshow-edit').addClass('show');
			else if ($this.val() == '5' || $this.val() == '6')
				$('#panel-gallery-edit').addClass('show');
		});
	}


	$('#submitButton').on('click',function(e){
		$('#menuItemUpdateForm').submit();
	});

	$('[data-toggle="tooltip"]').tooltip();

</script>