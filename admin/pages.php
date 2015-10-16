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
$page = new Page();
$page->pageTitle = "Static Pages";
$page->pageSlug = "pages";
$page->pageIcon = "fa-file-o";
$page->pageParent = "Manage";
$page->pageParentSlug = "manage";
$page->pageParentIcon = "fa-flask";
$page->pageDescription = "Manage your website's static pages.";
$page->pageExcerpt = "Manage your website's static pages.";
$page->pageReferal = urlencode(curPageURL());
$page->pageIncludes = <<< EOI
<!-- TABLE SORT -->
<script src="js/tablesorter/jquery.tablesorter.js"></script>
<script src="js/tablesorter/tables.js"></script>
EOI;
// END OF INCLUDES

function pageContent($page)
{
?>

				<?php

				// Start Database connection
				global $db;
				$table = 'pages';
				if (isset($_GET['status']))
					$status = $_GET['status'];
				else
					$status = '2';

				?>

				<div class="row">
					<div class="col-lg-12">
						<h1><?php _e($page->pageTitle); ?> <small><?php _e($page->pageDescription); ?></small></h1>
						<ol class="breadcrumb">
							<li><a href="index.php"><i class="fa fa-dashboard"></i>Dashboard</a></li>
							<li class="active"><i class="fa <?php _e($page->pageIcon); ?>"></i><?php _e($page->pageTitle); ?></li>
						</ol>
					</div>


					<div class="col-lg-12">

						<ul class="nav nav-tabs" id="page-tabs">
							<li class="<?php if ($status == '2') _e("active"); ?>"><a href="pages.php?status=2">Published</a></li>
							<li class="<?php if ($status == '0') _e("active"); ?>"><a href="pages.php?status=0">Drafts</a></li>
							<li class="<?php if ($status == '3') _e("active"); ?>"><a href="pages.php?status=3">Archived</a></li>
							<a id="newPageButton" href="page-new.php" class="btn btn-primary pull-right customButton"><i class="fa fa-plus"></i>New Page</a>
						</ul>

						<div class="tab-content">
							<div class="tab-pane active" id="tab-page-published">

		                        <?php
		                        // set referal page
		                        $ref = urlencode(curPageURL());
		                        $q_status = '2';

		                        // set db query for published slideshows
		                        $q = "SELECT * FROM $table";

		                        // get query string for current page
		                        if (empty($_GET['page'])) {
		                            $p = "1";
		                        } else {
		                            if ($_GET['page'] == "") {
		                                $p = "1";
		                            } else {
		                                $p = $_GET['page'];
		                            }
		                        }

		                        if (isset($_GET['search'])) {
		                            $search_term = $_GET['search'];
		                            $q .= " WHERE (`title` LIKE '%$search_term%' OR `excerpt` LIKE '%$search_term%') AND (`status`='$status')";
		                        } else if (isset($_GET['filter'])) {
		                            $filter_id = $_GET['filter'];
		                            $q .= " WHERE (`category` LIKE '%;$filter_id' OR  `category` LIKE '$filter_id;%' OR `category` LIKE '%;$filter_id;%' OR `category` LIKE '$filter_id') AND (`status`='$status')";
		                        } else {
		                            $q .= " WHERE (`status`='$status')";
		                        }

		                        $q .= " ORDER BY `id` DESC";


		                        $r = $db->query($q);
		                        $xcount = $db->num_rows($r);

		                        $per_page = '12';

		                        if (empty($_GET['ipp'])) {
		                            $_GET['ipp'] = $per_page;
		                            $_GET['page'] = '1';
		                        } else {
		                            if ($_GET['ipp'] == 'all')
		                                $per_page = $xcount;
		                            else
		                                $per_page = $_GET['ipp'];
		                        }

		                        $xcount = $db->num_rows($r);
		                        $start = ($p -1) * $per_page;
		                        $xpages = ceil($xcount/$per_page);


		                        if ($_GET['ipp'] != 'all')
		                            $q .= " LIMIT $start, $per_page";
		                        
		                        $r2 = $db->query($q);

								$pages = new Paginator;
								$pages->items_total = $xcount;
								$pages->mid_range = '7';
								$pages->items_per_page = $_GET['ipp'];
								$pages->paginate();
								?>

								<?php
								$hash = "tab-page-published";
								?>

								<div class="table-responsive">
									<table class="table table-hover table-striped tablesorter">
										<thead>
											<tr>
												<th>Title <i class="fa fa-sort"></i></th>
												<!-- <th width="10%">Author <i class="fa fa-sort"></i></th> -->
												<th width="10%">Date <i class="fa fa-sort"></i></th>
											</tr>
										</thead>
										<tbody>

										<?php
										while ($a = $db->fetch_array_assoc($r2)) {

                                            $hovermenu = '<li class="tableHoverMenuPrimary"><a href="page-edit.php?id=' . $a['id'] . '" title="Edit"><i class="fa fa-pencil"></i><span>Edit</span></a></li>';
                                            if ($status == '2') {
                                                $hovermenu .= '<li><a data-toggle="modal" data-target="#modal" class="" href="page-status.php?a=0&id=' . $a['id'] . '&ref=' . $page->pageReferal . '&hash=' . $hash . '" title="Set as Draft"><i class="fa fa-cog"></i><span>Set as Draft</span></a></li>';
                                                $hovermenu .= '<li><a data-toggle="modal" data-target="#modal" class="" href="page-status.php?a=3&id=' . $a['id'] . '&ref=' . $page->pageReferal . '&hash=' . $hash . '" title="Archive"><i class="fa fa-file-archive-o"></i><span>Archive</span></a></li>';
                                            } else if ($status == '0') {
                                                $hovermenu .= '<li><a data-toggle="modal" data-target="#modal" class="" href="page-status.php?a=2&id=' . $a['id'] . '&ref=' . $page->pageReferal . '&hash=' . $hash . '" title="Set as Published"><i class="fa fa-check"></i><span>Set as Published</span></a></li>';
                                                $hovermenu .= '<li><a data-toggle="modal" data-target="#modal" class="" href="page-status.php?a=3&id=' . $a['id'] . '&ref=' . $page->pageReferal . '&hash=' . $hash . '" title="Archive"><i class="fa fa-file-zip-o"></i><span>Archive</span></a></li>';
                                            } else if ($status == '3') {
                                                $hovermenu .= '<li><a data-toggle="modal" data-target="#modal" class="" href="page-status.php?a=0&id=' . $a['id'] . '&ref=' . $page->pageReferal . '&hash=' . $hash . '" title="Set as Draft"><i class="fa fa-cog"></i><span>Send to Draft</span></a></li>';
                                            }
                                            $hovermenu .= '<li class="tableHoverMenuWarning"><a data-toggle="modal" data-target="#modal" class="" href="page-del.php?id=' . $a['id'] . '&ref=' . $page->pageReferal . '&hash=' . $hash . '" title="Delete"><i class="fa fa-times-circle"></i><span>Delete</span></a></li>';

											// get number of photos for slideshow
											$sid = $a['id'];

											// get slideshow cover
											$cover = getSlideshowCover($sid);

											$q3 = "SELECT * FROM `photos` WHERE `slideshow`='$sid'";
											$r3 = $db->query($q3);
											$xcount3 = $db->num_rows($r3);
											?>

											<tr>
												<td><div class="table-title"><?php _e($a['title']); ?></div><div class="tableHoverMenu"><?php _e($hovermenu); ?></div></td>
												<!-- <td><div class="table-author">admin</div></td> -->
												<td><div class="table-date"><?php _d($a['timestamp']); ?></div><div class="table-status"><?php _status($a['status']); ?></div></td>
											</tr>

											<?php } ?>

										</tbody>
									</table>
								</div> <!-- /.table-responsive -->

								<div id="slideshows1-pagination">

									<ul class="pagination pull-right">
									<?php
									echo $pages->display_pages();
									?>
									</ul>

								</div>

							</div> <!-- /.tab-published -->

						</div> <!-- /.tab-content -->

					</div> <!-- /.class="col-lg-12" -->




				</div><!-- /.row -->


<?php
}; // end of pageContent()

include('template.php');
?>