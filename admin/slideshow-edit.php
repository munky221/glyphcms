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
	// $login_ref = 'slideshows.php';
	$login_ref = urlencode(curPageURL());
	header("location: login.php?ref=$login_ref");
}

// Page Object
$page = new Page;
$page->pageTitle = "Edit Slideshow";
$page->pageSlug = "slideshow-edit";
$page->pageIcon = "fa-pencil";
$page->pageParent = "Slideshows";
$page->pageParentSlug = "manage";
$page->pageParentIcon = "fa-ellipsis-h";
$page->pageDescription = "";
$page->pageExcerpt = "";
$page->pageReferal = urlencode(curPageURL());
$id = $_REQUEST['id'];
$page->pageIncludes = <<< EOI
<!-- NESTED LIST SORTABLE -->
<script src="js/jquery-nestedlist-sortable.js"></script>

<!-- JQUERY PAGINATION -->
<script src="js/jquery-simple-pagination-plugin.js"></script>
<script>
	// PAGINATION
	if ($.fn.simplePagination) {
		$(function(){
			var perPage = 16;
			var opened = 1;
			var onClass = 'active';
			var paginationSelector = '.paginationWrapper';
			$('#photoItems').simplePagination(perPage, opened, onClass, paginationSelector);
		});
	}

</script>
EOI;
// END OF INCLUDES

function pageContent($page)
{
?>

				<?php 

				// get target id
				if (!isset($_REQUEST['id'])) {
					$id = 0;
				} else {
					$id = $_REQUEST['id'];
				}
				$_SESSION['slideshow_new_id'] = $id;

				// Start Database connection
				global $db;
				
				// set db query
				$q = "SELECT * FROM `slideshows` WHERE `id`='$id' LIMIT 1";
				$r = $db->query($q);
				$a = $db->fetch_array_assoc($r)
				?>
				<div class="row">
					<div class="col-lg-12">
						<h1><?php _e($page->pageTitle); ?> <small><?php _e($page->pageDescription); ?></small></h1>
						<ol class="breadcrumb">
							<li><a href="index.php"><i class="fa fa-dashboard"></i>Dashboard</a></li>
							<li><a href="slideshows.php?status=<?php _e($a['status']); ?>"><i class="fa <?php _e($page->pageParentIcon); ?>"></i><?php _e($page->pageParent); ?></a></li>
							<li class="active"><i class="fa <?php _e($page->pageIcon); ?>"></i><?php _e($page->pageTitle); ?></li>
						</ol>
					</div>

					<form class="form slideshowForm" name="form-update-slideshow" id="slideshowUpdateForm" role="form" action="slideshow-action-update.php" method="post" enctype="multipart/form-data" target="_self">
					<div class="col-lg-6">

						
							<input type="hidden" name="t" value="slideshows">
							<input type="hidden" name="id" value="<?php _e($id); ?>">
							<input type="hidden" name="ref" value="<?php _e($page->pageReferal); ?>">

							<div class="form-group">
								<label>Title</label>
								<input name="title" id="slideshowTitle" class="form-control" placeholder="Your slideshow title..." value="<?php _e($a['title']); ?>">
							</div>

							<div class="form-group">
								<label>Excerpt</label>
								<textarea name="excerpt" id="slideshowExcerpt" class="textarea form-control" placeholder="A breif description for your slideshow... (optional)" style="width: 100%; height: 100px"><?php _e($a['excerpt']); ?></textarea>
							</div>

							<div class="form-group">
								<label>Category</label>

								<div class="categoryGroup">
									<?php
									// set db query
									$q2 = "SELECT * FROM `categories` ORDER BY `title` ASC";
									$r2 = $db->query($q2);

									// parse category options
									$categories = explode(";", $a['category']);
									?>
									<?php while ($a2 = $db->fetch_array_assoc($r2)) { ?>
									<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4"><label><input type="checkbox" name="category[]" value="<?php _e($a2['id']); ?>" <?php if (isCategory($categories,$a2['id'])) _e("checked"); ?>> <?php _e($a2['title']); ?></label></div>
									<?php } ?>
								</div>
							</div>

							<div class="form-group">
								<label>Appearance</label>
								<div class="slideshowAppearanceGroup">
									<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
									<label class="checkbox-inline">
										<input type="checkbox" name="show-infobar" id="show-infobar" value="1" <?php if ($a['show_infobar'] == '1') { ?>checked="checked"<?php }?>> Show Info-bar
									</label>
									<a class="label label-info" data-toggle="tooltip" data-placement="top" title="Infobar - It contains the slideshow title and social sharing links and is located at the top section of the slideshow presentation."><i class="fa fa-question"></i></a>
									</div>
									<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
									<label class="checkbox-inline">
										<input type="checkbox" name="show-filmstrip" id="show-filmstrip" value="1" <?php if ($a['show_filmstrip'] == '1') { ?>checked="checked"<?php }?>> Show Filmstrip
									</label>
									<a class="label label-info" data-toggle="tooltip" data-placement="top" title="Filmstrip - This will show a thumbnail filmstrip at the bottom of the slideshow presentation."><i class="fa fa-question"></i></a>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label>Status</label>
								<div class="slideshowStatusGroup">
									<label class="radio-inline">
										<input type="radio" name="status" id="slideshowStatus1" value="0"<?php if ($a['status'] == "0") _e(" checked"); ?>> Draft
									</label>
									<label class="radio-inline">
										<input type="radio" name="status" id="slideshowStatus2" value="2"<?php if ($a['status'] == "2") _e(" checked"); ?>> Publish
									</label>
									<label class="radio-inline">
										<input type="radio" name="status" id="slideshowStatus3" value="3"<?php if ($a['status'] == "3") _e(" checked"); ?>> Archive
									</label>
								</div>
							</div>

							<div class="form-buttons">
								<button id="formSubmit" class="btn btn-primary btn-lg">
									<i class="fa fa-check"></i>
									<span>Update Slideshow</span>
								</button>
							</div>

						

					</div> <!-- /.col-lg-6 -->

					<div class="col-lg-6" id="photoSelectGroup">
						
						<label>Selected Photos</label>

						<ul id="photoSelected" class="photo-grid" data-id="<?php _e($id); ?>">

							<?php
							// set referal page
							$ref = urlencode(curPageURL());

							$photos = explode(";", $a['photos']);

							if (!empty($a['photos'])) {

								foreach($photos as $p) {
									$q = "SELECT * FROM `photos` WHERE `id`='$p'";
									$r = $db->query($q);
									$pic = $db->fetch_array_assoc($r);
									$pic_src = SITE_URL . "/" . SITE_UPLOADS . "/thumbnail/" . $pic['name'];
							?>

							<li id="grid-thumb-<?php _e($pic['id']); ?>" data-id="<?php _e($pic['id']); ?>" class="grid grid-thumbs <?php if ($a['cover'] == $pic['id']) echo "active"; ?>">
								<div class="grid-thumbs-content">
									<a href="#drag" class="photoDragger"><i class="fa fa-arrows"></i></a>
									<a href="#remove" class="photoRemover"><i class="fa fa-minus-circle"></i></a>
									<a href="slideshow-cover-set.php?sid=<?php _e($id); ?>&pid=<?php _e($pic['id']); ?>" class="photoCover">
										<img src="<?php _e($pic_src); ?>" width="<?php _e(GALLERY_THUMB_WIDTH); ?>px">
										<div class="grid-thumb-label"><i class="fa fa-check"></i> COVER</div>
									</a>
								</div> <!-- /.grid-thumbs-content -->
							</li>
							<?php } } ?>

						</ul>
						
						<label>Available Related Photos</label>

						<div id="photoItemsBox">
						<ul id="photoItems" class="photo-grid" data-id="<?php _e($id); ?>">

							<?php

							// set thumb array
							$thumbs = array();

							// set query to select all photos
							$q = "SELECT * FROM `photos`";
							$r = $db->query($q);

							// filter out photos with matching categories
							while ($t = $db->fetch_array_assoc($r)) {

								// collect categories of unfiltered thumbnails
								if (isset($t['categories']) && $t['categories'] != "") {
									$cats = explode(";", $t['categories']);

									// if there are more than 1 category present, make a loop
									if (count($cats) > 0) {
										foreach($cats as $key => $val) {
											// if unfiltered thumbnail belongs to the slideshow categories
											if (search_array($val,$categories)) {

												// check if photo is already in the array
												if (!search_array($t['name'],$thumbs)) {

													// insert the thumbnail data
													array_push($thumbs, $t);
												}
											}
										}
									} else {
										// if there's only 1 category present on unfiltered thumbnail
										if (search_array($cats,$categories)) {

											// check if photo is already in the array
											if (!search_array($t['name'],$thumbs)) {

												// insert the thumbnail data
												array_push($thumbs, $t);
											}
										}
									}
								}
							}

							foreach ($thumbs as $thumb) {
								if (!in_array($thumb['id'],$photos)) {
								$thumb_src = SITE_URL . "/" . SITE_UPLOADS . "/thumbnail/" . $thumb['name'];
							?>

							<li data-id="<?php _e($thumb['id']); ?>" class="grid grid-thumbs <?php if ($a['cover'] == $thumb['id']) echo "active"; ?>">
								<div class="grid-thumbs-content">
									<a href="#drag" class="photoDragger"><i class="fa fa-arrows"></i></a>
									<a href="#remove" class="photoRemover"><i class="fa fa-minus-circle"></i></a>
									<div class="photoHolder">
										<img src="<?php _e($thumb_src); ?>" width="<?php _e(GALLERY_THUMB_WIDTH); ?>px">
									</div>
									<a href="slideshow-cover-set.php?sid=<?php _e($id); ?>&pid=<?php _e($thumb['id']); ?>" class="photoCover">
										<img src="<?php _e($thumb_src); ?>" width="<?php _e(GALLERY_THUMB_WIDTH); ?>px">
										<div class="grid-thumb-label"><i class="fa fa-check"></i> COVER</div>
									</a>
								</div> <!-- /.grid-thumbs-content -->
							</li>

							<?php } } ?>

						</ul>
						</div> <!-- /#photoItemsBox -->

						<div class="clearfix"></div>
						<div class="text-center paginationWrapper"></div>

					</div> <!-- /.col-lg-6 -->
					</form> <!-- /#form-create-slideshow -->

				</div><!-- /.row -->

<?php
}; // end of pageContent()

include('template.php');
?>