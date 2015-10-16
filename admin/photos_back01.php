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

// Page Object
$page = new Page;
$page->pageTitle = "Photo Manager";
$page->pageSlug = "photos";
$page->pageIcon = "fa-th";
$page->pageParent = "Slideshows";
$page->pageParentSlug = "slideshows";
$page->pageParentIcon = "fa-picture-o";
$page->pageDescription = "";
$page->pageExcerpt = "";
$page->pageReferal = urlencode(curPageURL());
// $id = $_REQUEST['id'];
$page->pageIncludes = <<< EOI
<!-- FILE UPLOAD -->
<link rel="stylesheet" href="uploader/css/blueimp-gallery.min.css">
<link rel="stylesheet" href="uploader/css/jquery.fileupload.css">
<link rel="stylesheet" href="uploader/css/jquery.fileupload-ui.css">

<!-- CSS adjustments for browsers with JavaScript disabled -->
<noscript><link rel="stylesheet" href="uploader/css/jquery.fileupload-noscript.css"></noscript>
<noscript><link rel="stylesheet" href="uploader/css/jquery.fileupload-ui-noscript.css"></noscript>

<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="uploader/js/vendor/jquery.ui.widget.js"></script>
<!-- The Templates plugin is included to render the upload/download listings -->
<script src="uploader/js/tmpl.min.js"></script>
<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
<script src="uploader/js/load-image.min.js"></script>
<!-- The Canvas to Blob plugin is included for image resizing functionality -->
<script src="uploader/js/canvas-to-blob.min.js"></script>
<!-- blueimp Gallery script -->
<script src="uploader/js/jquery.blueimp-gallery.min.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="uploader/js/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="uploader/js/jquery.fileupload.js"></script>
<!-- The File Upload processing plugin -->
<script src="uploader/js/jquery.fileupload-process.js"></script>
<!-- The File Upload image preview & resize plugin -->
<script src="uploader/js/jquery.fileupload-image.js"></script>
<!-- The File Upload audio preview plugin -->
<script src="uploader/js/jquery.fileupload-audio.js"></script>
<!-- The File Upload video preview plugin -->
<script src="uploader/js/jquery.fileupload-video.js"></script>
<!-- The File Upload validation plugin -->
<script src="uploader/js/jquery.fileupload-validate.js"></script>
<!-- The File Upload user interface plugin -->
<script src="uploader/js/jquery.fileupload-ui.js"></script>
<!-- The main application script -->
<script src="uploader/js/main.js"></script>
<!-- The XDomainRequest Transport is included for cross-domain file deletion for IE 8 and IE 9 -->
<!--[if (gte IE 8)&(lt IE 10)]>
<script src="uploader/js/cors/jquery.xdr-transport.js"></script>
<![endif]-->

<!-- WYSI HTML5 -->
<link rel="stylesheet" type="text/css" href="src/bootstrap-wysihtml5.css" />
<script src="lib/js/wysihtml5-0.3.0.js"></script>
<script src="src/bootstrap3-wysihtml5.js"></script>

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
				$table = 'photos';

				?>

				<div class="row">
					<div class="col-lg-12">
						<h1><?php _e($page->pageTitle); ?> <small><?php _e($page->pageDescription); ?></small></h1>
						<ol class="breadcrumb">
							<li><a href="index.php"><i class="fa fa-dashboard"></i>Dashboard</a></li>
							<li class="active"><i class="fa <?php _e($page->pageIcon); ?>"></i><?php _e($page->pageTitle); ?></li>
						</ol>
					</div>

					<div class="alert alert-warning"><?php //global $site; var_dump($site); ?></div>

					<form name="fileupload" id="photo_fileupload" class="fileupload form" role="form" action="<?php _e(SITE_URL . "/" . SITE_ADMIN . "/"); ?>uploader/php/" method="post" enctype="multipart/form-data" target="_self">

						<div class="col-lg-12">

								<!-- Redirect browsers with JavaScript disabled to the origin page -->
								<noscript><input type="hidden" name="redirect" value="<?php _e(SITE_URL); ?>"></noscript>
								<!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
								<div class="row fileupload-buttonbar">
									<div class="col-lg-6">
										<!-- The fileinput-button span is used to style the file input field as button -->
										<span class="btn btn-success fileinput-button">
												<i class="glyphicon glyphicon-plus"></i>
												<span>Add photos...</span>
												<input type="file" name="files[]" multiple>
										</span>
										<button type="submit" class="btn btn-primary start">
											<i class="glyphicon glyphicon-upload"></i>
											<span>Upload photos</span>
										</button>
										<button type="reset" class="btn btn-warning cancel">
											<i class="glyphicon glyphicon-ban-circle"></i>
											<span>Cancel</span>
										</button>
										<!-- The global file processing state -->
										<span class="fileupload-process"></span>
									</div>

									<div class="col-lg-6 delete-buttonbar">
										<label><input type="checkbox" class="delete-toggle"> Check All</label>
										<button type="button" class="btn btn-danger deleteButton">
												<i class="glyphicon glyphicon-trash"></i>
												<span>Delete</span>
										</button>
									</div>

									<div class="clearfix"></div>

									<!-- The global progress state -->
									<div class="col-lg-12 fileupload-progress fade">
										<!-- The global progress bar -->
										<div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
												<div class="progress-bar progress-bar-success" style="width:0%;"></div>
										</div>
										<!-- The extended global progress state -->
										<div class="progress-extended">&nbsp;</div>
									</div> <!-- /.fileupload-buttonbar -->

								</div>

						</div> <!-- /.col-lg-12 -->

						<div class="col-lg-12">

							<?php
							// set referal page
							$ref = urlencode(curPageURL());

							// set db query for published slideshows
							$q = "SELECT * FROM $table";
							$r = $db->query($q);
							$xcount = $db->num_rows($r);

							$per_page = '12';
							if (!isset($_GET['ipp'])) {
								$_GET['ipp'] = $per_page;
								$_GET['page'] = '1';
							} else {
								if ($_GET['ipp'] == 'all')
									$per_page = $xcount;
								else
									$per_page = $_GET['ipp'];
							}

							// get query string for current page
							if (!isset($_GET['page'])) {
								$p = "1";
							} else {
								if ($_GET['page'] == "") {
									$p = "1";
								} else {
									$p = $_GET['page'];
								}
							}
							$xpages = ceil($xcount/$per_page);

							$start = ($p -1) * $per_page;

							$q .= " ORDER BY `id` DESC";
							if ($_GET['ipp'] != 'all')
								$q .= " LIMIT $start, $per_page";
							$r2 = $db->query($q);


							$pages = new Paginator;
							$pages->items_total = $xcount;
							$pages->mid_range = '7';
							$pages->items_per_page = $_GET['ipp'];
							$pages->paginate();
							?>
							<div class="photo-grid uploadePreview" role="presentation">

								<div class="files"></div>

										<?php
										while ($a = $db->fetch_array_assoc($r2)) {
											$hovermenu = '<li class="tableHoverMenuPrimary"><a class="modalButton" href="photo-edit.php?id=' . $a['id'] . '&ref=' . $page->pageReferal . '">Edit</a></li>';
											$hovermenu .= '<li class="tableHoverMenuWarning"><a class="modalButton" href="photo-del.php?id=' . $a['id'] . '&ref=' . $page->pageReferal . '">Delete</a></li>';

											// get number of photos for slideshow
											$sid = $a['slideshow'];
											// get slideshow name
											$sid_name = getSlideshowTitle($sid);

											// get preview image file
											$thumb = SITE_URL . "/" . SITE_UPLOADS . "/preview/" . $a['name'];
											?>
											<div class="grid">
												<div class="picture">
													<img src="<?php _e($thumb); ?>" width="<?php _e(GALLERY_PREV_WIDTH); ?>px">
												</div>
												<div class="pictureTitle">
													<div class="title">
													<label><input type="checkbox" name="delete[]" value="<?php _e($a['id']); ?>" class="deleteBox">
													<?php if ($a['title']!="") { ?>
													<?php _e($a['title']); ?></label></div>
													<div class="filename"><i class="fa fa-file-o"></i> <?php _e($a['name']); ?></div>
													<?php } else { ?>
													<?php _e($a['name']); ?></label></div>
													<?php } ?>
												</div>
												<div class="pictureDetails">
													<?php if ($a['description']!="") { ?>
													<div class="description"><i class="fa fa-quote-left"></i> <?php _e(getExcerpt($a['description'])); ?></div>
													<?php } ?>
													<div class="categories">
														<?php
														// parse category options
														if ($a['categories'] != "") {
															$categories = explode(";", $a['categories']);
															?>
															<?php foreach ($categories as $cat_id) { ?>
															<div class="label label-primary"><i class="fa fa-tag"></i> <?php _e(getCategoryName($cat_id)); ?></div>
															<?php } } ?>
													</div>
													<div class="date"><?php _d("{$a['timestamp']}"); ?></div>
													<ul class="tableHoverMenu"><?php _e($hovermenu); ?></ul>
												</div>
											</div>
										<?php } ?>
							</div> <!-- /.photo-grid -->

							<div class="clearfix"></div>

							<div id="photos-pagination">
								<?php //makePagination($_GET,$xpages,$page); ?>

									<ul class="pagination pull-right">
									<?php
									echo $pages->display_pages();
									?>
									</ul>
							</div>

						</div> <!-- /.col-lg-12 -->

					</form><!-- /#fileupload -->

					<form name="photodelete" id="photo_delete" class="photo-delete form" role="form" action="photos-delgroup.php" method="post" enctype="multipart/form-data" target="_self">
						<input type="hidden" name="ref" value="<?php _e($page->pageReferal); ?>">
						<input type="hidden" name="items" id="items" value="">
					</form> <!-- /#photo_delete -->

				</div><!-- /.row -->



<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}


	<div class="grid grid-upload template-upload">
		<div class="picture">
			<span class="preview"></span>
		</div>
		<div class="pictureDetails">
			<p>
				<input required class="photoTitle" name="title[]" size="50" style="width:100%" placeholder="Title for your photo...">
			</p>
			<p>
				<textarea name="description[]" cols="50" rows="2" style="width:100%" placeholder="Add description (optional)"></textarea>
			</p>
		</div>
		<div class="grid-upload-processing">
			<div class="grid-upload-progress">
				<div class="size">Processing...</div>
				<div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
				<div class="clearfix"></div>
			</div>
			<div class="grid-upload-buttons">
				{% if (!i && !o.options.autoUpload) { %}
						<button class="btn btn-primary start" disabled>
								<i class="glyphicon glyphicon-upload"></i>
								<span></span>
						</button>
				{% } %}
				{% if (!i) { %}
						<button class="btn btn-warning cancel">
								<i class="glyphicon glyphicon-ban-circle"></i>
								<span></span>
						</button>
				{% } %}
			</div>
		</div>
	</div>
{% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}

	<div class="grid">
		<div class="picture">
			<img src="<?php _e(SITE_URL . "/" . SITE_UPLOADS); ?>/preview/{%=file.name%}" width="<?php _e(GALLERY_PREV_WIDTH); ?>">
		</div>
		<div class="pictureTitle">
			<div class="title">
				<label><input type="checkbox" name="delete[]" value="{%=file.id%}" class="deleteBox">
			{% if (file.title) { %}
			{%=file.title%}</label></div>
			<div class="filename"><i class="fa fa-file-o"></i> {%=file.name%}</div>
			{% } else { %}
			{%=file.title%} - {%=file.name%}</label></div>
			{% } %}
		</div>
		<div class="pictureDetails">
			<div class="description"><i class="fa fa-quote-left"></i> {%=file.description%}</div>
			<div class="date">{%=file.timestamp%}</div>
			<ul class="tableHoverMenu">
				<li class="tableHoverMenuPrimary"><a class="modalButton"  href="javascript:void(0);" onclick="openModal('photo-edit.php?id={%=file.id%}&ref=photos.php');">Edit</a></li>
				<li class="tableHoverMenuWarning"><a class="modalButton" href="photo-del.php?id={%=file.id%}&ref={%=file.id%}">Delete</a></li>
			</ul>
		</div>
	</div>
{% } %}
</script>

<?php
}; // end of pageContent()

include('template.php');
?>