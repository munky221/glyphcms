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
	// $login_ref = 'categories.php';
	$login_ref = urlencode(curPageURL());
	header("location: login.php?ref=$login_ref");
}

// Page Object
$page = new Page;
$page->pageTitle = "Slideshow Categories";
$page->pageSlug = "categories";
$page->pageIcon = "fa-list";
$page->pageParent = "Slideshows";
$page->pageParentSlug = "manage";
$page->pageParentIcon = "fa-ellipsis-h";
$page->pageDescription = "Manage different categories for your slideshow.";
$page->pageExcerpt = "Manage different categories for your slideshow.";
$page->pageReferal = urlencode(curPageURL());
$page->pageIncludes = <<< EOI
<!-- TABLE SORT -->
<script src="js/tablesorter/jquery.tablesorter.js"></script>
<script src="js/tablesorter/tables.js"></script>

<!-- VALIDATE FORM -->
<script src="js/jquery.validate.min.js"></script>
<script src="js/jquery.validate.additional-method.js"></script>
EOI;
// END OF INCLUDES

function pageContent($page)
{
?>


				<div class="row">
					<div class="col-lg-12" id="breadcrumbs">
						<h1><?php _e($page->pageTitle); ?> <small><?php _e($page->pageDescription); ?></small></h1>
						<ol class="breadcrumb">
							<li><a href="index.php"><i class="fa fa-dashboard"></i>Dashboard</a></li>
							<li><a href="slideshows.php"><i class="fa <?php _e($page->pageParentIcon); ?>"></i><?php _e($page->pageParent); ?></a></li>
							<li class="active"><i class="fa <?php _e($page->pageIcon); ?>"></i><?php _e($page->pageTitle); ?></li>
						</ol>
					</div>

					
					<div class="col-lg-4">

						<form class="form categoryForm" name="form-create-category" id="categoryCreate" role="form" action="categories-new-action.php" method="post" enctype="multipart/form-data" target="_self">
							<input type="hidden" name="t" value="categories">
							<input type="hidden" name="re" value="<?php _e($page->pageReferal); ?>">

							<div class="form-group">
								<label>New Category Title</label>
								<input name="title" id="title" class="form-control" placeholder="Your cateogry title..." required min-length="5">
							</div>

							<div class="form-buttons fileupload-buttonbar">
								<button id="formSubmit" type="submit" class="btn btn-primary btn-lg">
									<i class="fa fa-check"></i>
									<span>Create Category</span>
								</button>
							</div>

						</form> <!-- /#form-create-slideshow -->

					</div> <!-- /#col-lg-4 -->

					<div class="col-lg-8">


						<?php

						// Start Database connection
						global $db;
						// set db query
						$q = "SELECT * FROM `categories` WHERE `id`!='0'";
						$r = $db->query($q);
						$per_page = 10;
						$xcount = $db->num_rows($r);
						$xpages = ceil($xcount/$per_page);

						// get query string for current page
						if (!isset($_GET['p'])) {
							$p = "1";
						} else {
							if ($_GET['p'] == "") {
								$p = "1";
							} else {
								$p = $_GET['p'];
							}
						}

						$start = ($p -1) * $per_page;

						$q .= "  ORDER BY `title` ASC LIMIT $start, $per_page";
						$r2 = $db->query($q);
						?>

						<div class="table-responsive">
							<table class="table table-hover table-striped tablesorter">
								<thead>
									<tr>
										<th>Category <i class="fa fa-sort"></i></th>
										<th width="20%">Date <i class="fa fa-sort"></i></th>
									</tr>
								</thead>
								<tbody>
									<?php if ($p == '1') { ?>
									<tr>
										<td><div class="table-title">Featured</div><ul class="tableHoverMenu"><li>Do not edit/delete</li></ul></td>
										<td><div class="table-date">---</div></td>
									</tr>
									<?php } ?>
									<?php
									while ($a = $db->fetch_array_assoc($r2)) {
										$hovermenu = '<li class="tableHoverMenuPrimary"><a data-toggle="modal" data-target="#modal" class="" href="category-edit.php?id=' . "{$a['id']}" . '&ref=' . $page->pageReferal . '" title="Edit"><i class="fa fa-pencil"></i><span>Edit</span></a></li>';
										$hovermenu .= '<li class="tableHoverMenuWarning"><a data-toggle="modal" data-target="#modal" class="" href="category-del.php?id=' . $a['id'] . '&ref=' . $page->pageReferal . '" title="Delete"><i class="fa fa-times-circle"></i><span>Delete</span></a></li>';
									?>
									<tr>
										<td><div class="table-title"><?php _e("{$a['title']}"); ?></div><ul class="tableHoverMenu"><?php _e($hovermenu); ?></ul></td>
										<td><div class="table-date"><?php _d("{$a['timestamp']}"); ?></div></td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div> <!-- /.table-responsive -->

						<div id="categories-pagination">
							<?php makePagination($_GET,$xpages,$page); ?>
						</div>

					</div> <!-- /#col-lg-8 -->

				</div><!-- /.row -->

<?php
}; // end of pageContent()

include('template.php');
?>