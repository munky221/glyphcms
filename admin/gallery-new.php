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

// Page Object
$page = new Page;
$page->pageTitle = "New Gallery";
$page->pageSlug = "gallery-new";
$page->pageIcon = "fa-plus";
$page->pageParent = "Galleries";
$page->pageParentSlug = "manage";
$page->pageParentIcon = "fa-th-large";
$page->pageDescription = "";
$page->pageExcerpt = "";
$page->pageReferal = urlencode(curPageURL());
$page->pageIncludes = <<< EOI
<!-- VALIDATE FORM -->
<script src="js/jquery.validate.min.js"></script>
<script src="js/jquery.validate.additional-method.js"></script>

<!-- JQUERY PAGINATION -->
<script src="js/jquery-simple-pagination-plugin.js"></script>
<script>
	// PAGINATION
	if ($.fn.simplePagination) {
		$(function(){
			var perPage = 12;
			var opened = 1;
			var onClass = 'active';
			var paginationSelector = '.paginationWrapper';
			$('.gallery-slideshow-list').simplePagination(perPage, opened, onClass, paginationSelector);
		});
	}

</script>
EOI;
// END OF INCLUDES

function pageContent($page)
{
?>
				<?php

				// Start Database connection
				global $db;

				?>
				<div class="row">
					<div class="col-lg-12">
						<h1><?php _e($page->pageTitle); ?> <small><?php _e($page->pageDescription); ?></small></h1>
						<ol class="breadcrumb">
							<li><a href="index.php"><i class="fa fa-dashboard"></i>Dashboard</a></li>
							<li><a href="galleries.php"><i class="fa <?php _e($page->pageParentIcon); ?>"></i><?php _e($page->pageParent); ?></a></li>
							<li class="active"><i class="fa <?php _e($page->pageIcon); ?>"></i><?php _e($page->pageTitle); ?></li>
						</ol>
					</div>

					
					<form class="form galleryForm" name="form-create-gallery" id="galleryCreateForm" role="form" action="gallery-create-action.php" method="post" enctype="multipart/form-data" target="_self">
						<div class="col-lg-6">
							<input type="hidden" name="t" value="galleries">
							<input type="hidden" name="ref" value="<?php _e($page->pageReferal); ?>">

							<div class="form-group">
								<label>Title</label>
								<input name="title" id="galleryTitle" class="form-control" placeholder="Your gallery title..." required min-length="5">
							</div>

							<div class="form-group">
								<label>Excerpt</label>
								<textarea name="excerpt" id="galleryExcerpt" class="textarea form-control" placeholder="A breif description for your gallery... (optional)" style="width: 100%; height: 100px"></textarea>
							</div>

							<div class="form-group">
								<label>Category</label>

								<div class="categoryGroup">
									<?php
									// set db query
									$q2 = "SELECT * FROM `categories` ORDER BY `title` ASC";
									$r2 = $db->query($q2);
									?>
									<?php while ($a2 = $db->fetch_array_assoc($r2)) { ?>
									<div class="col-xs-6 col-sm-4"><label><input type="checkbox" name="category[]" value="<?php _e($a2['id']); ?>"> <?php _e($a2['title']); ?></label></div>
									<?php } ?>
								</div>
							</div>

							<div class="form-group">
								<label>Slideshow Status</label>
								<div class="slideshowStatusGroup">
									<label class="radio-inline">
										<input type="radio" name="status" id="slideshowStatus1" value="0" checked> Draft
									</label>
									<label class="radio-inline">
										<input type="radio" name="status" id="slideshowStatus2" value="2"> Publish
									</label>
									<label class="radio-inline">
										<input type="radio" name="status" id="slideshowStatus3" value="3"> Archive
									</label>
								</div>
							</div>

							<div class="form-buttons fileupload-buttonbar">
								<button id="formSubmit" type="submit" class="btn btn-primary btn-lg">
									<i class="fa fa-check"></i>
									<span>Create Gallery</span>
								</button>
								<button type="reset" class="btn btn-info btn-lg">
									<i class="fa fa-refresh"></i>
									<span>Reset</span>
								</button>
							</div>

						</div> <!-- /.col-lg-6 -->

						<div class="col-lg-6">

							<div class="form-group">
								<label>Select Slideshows</label>
								<div id="gallery-select-slideshows">
									<ul class="gallery-slideshow-list">
										<?php
										// set db query for published slideshows
										$q = "SELECT * FROM `slideshows` WHERE `status`='2'";
										$r = $db->query($q);
										while ($a = $db->fetch_array_assoc($r)) {

											// get slideshow cover
											$sid = $a['id'];
											$slideshow_title = $a['title'];
											$cover = getSlideshowCover($sid);
										?>
										<li class="col-xs-6 col-sm-4 selected">
											<label for="slideshow<?php _e($sid); ?>">
												<a href="slideshow-edit.php?id=<?php _e($sid); ?>" class="label label-default"><?php _e($slideshow_title); ?></a>
												<img src="<?php _e($cover); ?>" width="<?php _e(GALLERY_THUMB_WIDTH); ?>px">
												<input type="checkbox" name="slideshow[]" id="slideshow<?php _e($sid); ?>" value="<?php _e($sid); ?>">
											</label>
										</li>
										<?php } ?>
									</ul>
									<div class="clearfix"></div>
									<div class="text-center paginationWrapper">
								</div>
							</div> <!-- /#gallery-select-slideshows -->
							
						</div> <!-- /.col-lg-6 -->

					</form> <!-- /#form-create-gallery -->

				</div><!-- /.row -->

<?php
}; // end of pageContent()

include('template.php');
?>