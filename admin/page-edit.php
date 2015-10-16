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
	// $login_ref = 'pages.php';
	$login_ref = urlencode(curPageURL());
	header("location: login.php?ref=$login_ref");
}

// Page Object
global $page;
$page = new Page;
$page->pageTitle = "Edit Page";
$page->pageSlug = "page-edit";
$page->pageIcon = "fa-pencil-square-o";
$page->pageParent = "Pages";
$page->pageParentSlug = "manage";
$page->pageParentIcon = "fa-pencil";
$page->pageDescription = "";
$page->pageExcerpt = "";
$page->pageReferal = urlencode(curPageURL());
$page->pageIncludes = <<< EOI

<!-- VALIDATE FORM -->
<script src="js/jquery.validate.min.js"></script>
<script src="js/jquery.validate.additional-method.js"></script>

<!-- CODEMIRROR -->
<link rel="stylesheet" type="text/css" href="lib/codemirror/codemirror.min.css" />
<link rel="stylesheet" type="text/css" href="lib/codemirror/theme/monokai.min.css" />
<script type="text/javascript" src="lib/codemirror/codemirror.min.js"></script>
<script type="text/javascript" src="lib/codemirror/mode/xml/xml.min.js"></script>
<script type="text/javascript" src="lib/codemirror/formatting.min.js"></script>

<!-- SUMMERNOTE -->
<link rel="stylesheet" type="text/css" href="css/summernote.css" />
<script type="text/javascript" src="js/summernote.js"></script>
EOI;
// END OF INCLUDES

function pageContent($page)
{
?>

				<?php 

				// get target id
				if (isset($_REQUEST['id'])) {
					$id = $_REQUEST['id'];
				} else {
					header("location: index.php");
				}

				// Start Database connection
				global $db;
				
				// set db query
				$q = "SELECT * FROM `pages` WHERE `id`='$id' LIMIT 1";
				$r = $db->query($q);
				$a = $db->fetch_array_assoc($r)
				?>

				<div class="row">
					<div class="col-lg-12">
						<h1><?php _e($page->pageTitle); ?> <small><?php _e($page->pageDescription); ?></small></h1>
						<ol class="breadcrumb">
							<li><a href="index.php"><i class="fa fa-dashboard"></i>Dashboard</a></li>
							<li><a href="pages.php?status=<?php _e($a['status']); ?>"><i class="fa <?php _e($page->pageParentIcon); ?>"></i><?php _e($page->pageParent); ?></a></li>
							<li class="active"><i class="fa <?php _e($page->pageIcon); ?>"></i><?php _e($page->pageTitle); ?></li>
						</ol>
					</div>

					
					<div class="col-lg-8">

						<form class="form pageForm" name="form-create-page" id="form-create-page" role="form" action="page-edit-action.php" method="post" enctype="application/x-www-form-urlencoded" target="_self">
							<input type="hidden" name="t" value="pages">
							<input type="hidden" name="id" value="<?php _e($id); ?>">
							<input type="hidden" name="ref" value="<?php _e($page->pageReferal); ?>">

							<div class="form-group">
								<label>Title</label>
								<input name="title" id="pageTitle" class="form-control" placeholder="Your page title..." required min-length="5" value="<?php _e($a['title']); ?>">
							</div>

							<div class="form-group">
								<label>Content</label>
								<textarea class="form-control" id="summernote" name="content" rows="18"><?php _e(htmlspecialchars($a['content'])); ?></textarea>
							</div>

							<div class="form-group">
								<p class="help-block"><span class="label label-primary">INFO</span> You can also use HTML tags on your page content. <a href="//www.w3schools.com/html/" target="_blank">Click here</a> to learn more about HTML.</p>
								<div class="content-helper">
									<h4>Shortcodes <span class="label label-primary" data-toggle="tooltip" data-placement="top" title="You can use shortcodes to generate pre-made site elements to help you design your content."><i class="fa fa-question"></i></span></h4>
									<hr>
									<p><span class="label label-info">[% contactForm %]</span> add a contact form.</p>
								</div>
							</div>

							<div class="form-buttons">
								<button id="formSubmit" type="submit" class="btn btn-primary btn-lg">
									<i class="fa fa-check"></i>
									<span>Update Page</span>
								</button>
							</div>

						</div> <!-- /#col-lg-6 -->

						<div class="col-lg-4">

							<div class="form-group">
								<label>Excerpt</label>
								<textarea name="excerpt" id="pageExcerpt" class="textarea form-control" placeholder="An excerpt for your page... (optional)" cols="50" rows="3" style="width:100%" max-length="250"><?php _e(htmlspecialchars($a['excerpt'])); ?></textarea>
							</div>

							<div class="form-group">
								<label>Page Status</label>
								<div>
									<label class="radio-inline">
										<input type="radio" name="status" id="pageStatus1" value="0"<?php if ($a['status'] == "0") _e(" checked='checked'"); ?>> Draft
									</label>
									<label class="radio-inline">
										<input type="radio" name="status" id="pageStatus2" value="2"<?php if ($a['status'] == "2") _e(" checked='checked'"); ?>> Publish
									</label>
									<label class="radio-inline">
										<input type="radio" name="status" id="pageStatus3" value="3"<?php if ($a['status'] == "3") _e(" checked='checked'"); ?>> Archive
									</label>
								</div>
							</div>

						</form>

					</div>

				</div><!-- /.row -->


<?php
}; // end of pageContent()

include('template.php');
?>