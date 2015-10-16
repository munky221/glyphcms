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
$page->pageTitle = "New Slideshow";
$page->pageSlug = "slideshow-new";
$page->pageIcon = "fa-plus";
$page->pageParent = "Slideshows";
$page->pageParentSlug = "manage";
$page->pageParentIcon = "fa-ellipsis-h";
$page->pageDescription = "Create a new slideshow for your website.";
$page->pageExcerpt = "Create a new slideshow for your website.";
$page->pageReferal = urlencode(curPageURL());
$page->pageIncludes = <<< EOI

<!-- VALIDATE FORM -->
<script src="js/jquery.validate.min.js"></script>
<script src="js/jquery.validate.additional-method.js"></script>
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
							<li><a href="slideshows.php"><i class="fa <?php _e($page->pageParentIcon); ?>"></i><?php _e($page->pageParent); ?></a></li>
							<li class="active"><i class="fa <?php _e($page->pageIcon); ?>"></i><?php _e($page->pageTitle); ?></li>
						</ol>
					</div>

					
					<div class="col-lg-6">

						<form class="form slideshowForm" name="form-create-slideshow" id="slideshowCreateForm" role="form" action="slideshow-action-create.php" method="post" enctype="multipart/form-data" target="_self">
							<input type="hidden" name="t" value="slideshows">
							<input type="hidden" name="ref" value="<?php _e($page->pageReferal); ?>">

							<div class="form-group">
								<label>Title</label>
								<input name="title" id="slideshowTitle" class="form-control" placeholder="Your slideshow title..." required min-length="5">
							</div>

							<div class="form-group">
								<label>Excerpt</label>
								<textarea name="excerpt" id="slideshowExcerpt" class="textarea form-control" placeholder="A breif description for your slideshow... (optional)" style="width: 100%; height: 100px"></textarea>
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
									<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4"><label><input type="checkbox" name="category[]" value="<?php _e($a2['id']); ?>"> <?php _e($a2['title']); ?></label></div>
									<?php } ?>
								</div>
							</div>

							<div class="form-group">
								<label>Appearance</label>
								<div class="slideshowAppearanceGroup">
									<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
									<label class="checkbox-inline">
										<input type="checkbox" name="show-infobar" id="show-infobar" value="1" checked="checked"> Show Info-bar
									</label>
									<a class="label label-info" data-toggle="tooltip" data-placement="top" title="Infobar - It contains the slideshow title and social sharing links and is located at the top section of the slideshow presentation."><i class="fa fa-question"></i></a>
									</div>
									<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4">
									<label class="checkbox-inline">
										<input type="checkbox" name="show-filmstrip" id="show-filmstrip" value="1" checked="checked"> Show Filmstrip
									</label>
									<a class="label label-info" data-toggle="tooltip" data-placement="top" title="Filmstrip - This will show a thumbnail filmstrip at the bottom of the slideshow presentation."><i class="fa fa-question"></i></a>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label>Status</label>
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

							<input type="hidden" name="files" id="formFiles" value="">

							<div class="form-buttons fileupload-buttonbar">
								<button id="formSubmit" type="submit" class="btn btn-primary btn-lg">
									<i class="fa fa-check"></i>
									<span>Create Slideshow</span>
								</button>
								<button type="reset" class="btn btn-info btn-lg">
									<i class="fa fa-refresh"></i>
									<span>Reset</span>
								</button>
							</div>

						</form> <!-- /#form-create-slideshow -->

					</div> <!-- /.col-lg-6 -->

					<div class="col-lg-6">

						<div class="alert alert-success">
							<p>Click the <span class="label label-primary"><i class="fa fa-check"></i> Create Slideshow</span> button to continue selecting a cover photo.</p>
						</div>
						
					</div> <!-- /.col-lg-6 -->

				</div><!-- /.row -->

<?php
}; // end of pageContent()

include('template.php');
?>