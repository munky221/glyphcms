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
	// $login_ref = 'galleries.php';
	$login_ref = urlencode(curPageURL());
	header("location: login.php?ref=$login_ref");
}

// Page Object
$page = new Page;
$page->pageTitle = "Galleries";
$page->pageSlug = "galleries";
$page->pageIcon = "fa-th-large";
$page->pageParent = "Slideshows";
$page->pageParentSlug = "manage";
$page->pageParentIcon = "fa-flask";
$page->pageDescription = "Manage all your galleries.";
$page->pageExcerpt = "Manage all your galleries.";
$page->pageReferal = urlencode(curPageURL());
$page->pageIncludes = <<< EOI
<!-- NESTED LIST SORTABLE -->
<script src="js/jquery-nestedlist-sortable.js"></script>

<!-- TABLE SORT -->
<script src="js/tablesorter/jquery.tablesorter.js"></script>
<script src="js/tablesorter/tables.js"></script>

<!-- JQUERY PAGINATION -->
<script src="js/jquery-simple-pagination-plugin.js"></script>
EOI;
// END OF INCLUDES

function pageContent($page)
{
?>

				<?php

				// Start Database connection
				global $db;
				$table = 'galleries';
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

                    <div class="col-lg-12 pagetop-bar">

                            <div class="col-lg-3 search-bar">
                                <form class="search-form" role="search" action="" method="get">
                                    <div class="input-group">
                                        <input type="text" class="form-control search-term" placeholder="Search keyword..." name="search" id="search-term" value="<?php if (isset($_GET['search'])) _e($_GET['search']); ?>" required>
                                        <?php if (isset($_GET['search'])) { ?><a href="?" class="search-clear"><span class="glyphicon glyphicon-remove-circle"></span></a><?php } ?>
                                        <div class="input-group-btn">
                                            <button class="btn btn-primary" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="clearfix"></div>

                    </div> <!-- /.pagetop-bar -->

					<div class="col-lg-12">

						<ul class="nav nav-tabs" id="slideshow-tabs">
							<li class="<?php if ($status == '2') _e("active"); ?>"><a href="galleries.php?status=2">Published</a></li>
							<li class="<?php if ($status == '0') _e("active"); ?>"><a href="galleries.php?status=0">Drafts</a></li>
							<li class="<?php if ($status == '3') _e("active"); ?>"><a href="galleries.php?status=3">Archived</a></li>
							<a id="newGalleryButton" href="gallery-new.php" class="btn btn-primary pull-right customButton"><i class="fa fa-plus"></i>New Gallery</a>
						</ul>

						<div class="tab-content">
							<div id="tab-galleries-published">

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
								$hash = "tab-galleries-published";
								?>
								<div class="table-responsive">
									<table class="table table-hover table-striped tablesorter">
										<thead>
											<tr>
												<th width="60px">ID <i class="fa fa-sort-asc"></i></th>
												<th>Title <i class="fa fa-sort"></i></th>
												<th>Slideshows <i class="fa fa-sort"></i></th>
												<th width="160px" class="th-date">Date <i class="fa fa-sort"></i></th>
											</tr>
										</thead>
										<tbody>

											<?php
											while ($a = $db->fetch_array_assoc($r2)) {
												$hovermenu = '<li class="tableHoverMenuPrimary"><a href="gallery-edit.php?id=' . $a['id'] . '" title="Edit"><i class="fa fa-pencil"></i><span>Edit</span></a></li>';
												if ($status == '2') {
													$hovermenu .= '<li><a data-toggle="modal" data-target="#modal" class="" href="gallery-update.php?a=0&id=' . $a['id'] . '&ref=' . $page->pageReferal . '&hash=' . $hash . '" title="Set as Draft"><i class="fa fa-cog"></i><span>Set as Draft</span></a></li>';
													$hovermenu .= '<li><a data-toggle="modal" data-target="#modal" class="" href="gallery-update.php?a=3&id=' . $a['id'] . '&ref=' . $page->pageReferal . '&hash=' . $hash . '" title="Archive"><i class="fa fa-file-archive-o"></i><span>Archive</span></a></li>';
												} else if ($status == '0') {
													$hovermenu .= '<li><a data-toggle="modal" data-target="#modal" class="" href="gallery-update.php?a=2&id=' . $a['id'] . '&ref=' . $page->pageReferal . '&hash=' . $hash . '" title="Set as Published"><i class="fa fa-check"></i><span>Set as Published</span></a></li>';
													$hovermenu .= '<li><a data-toggle="modal" data-target="#modal" class="" href="gallery-update.php?a=3&id=' . $a['id'] . '&ref=' . $page->pageReferal . '&hash=' . $hash . '" title="Archive"><i class="fa fa-file-zip-o"></i><span>Archive</span></a></li>';
												} else if ($status == '3') {
													$hovermenu .= '<li><a data-toggle="modal" data-target="#modal" class="" href="gallery-update.php?a=0&id=' . $a['id'] . '&ref=' . $page->pageReferal . '&hash=' . $hash . '" title="Set as Draft"><i class="fa fa-cog"></i><span>Send to Draft</span></a></li>';
												}
												$hovermenu .= '<li class="tableHoverMenuWarning"><a data-toggle="modal" data-target="#modal" class="" href="gallery-del.php?id=' . $a['id'] . '&ref=' . $page->pageReferal . '&hash=' . $hash . '" title="Delete"><i class="fa fa-times-circle"></i><span>Delete</span></a></li>';

												// get number of slideshow for gallery
												$s = explode(";", $a['slideshows']);
												$xcount3 = count($s);
												?>
											<tr data-id="<?php _e($a['slug']); ?>">
												<td>
													<div class="table-id"><?php _e($a['id']); ?></div>
												</td>
												<td>
													<div class="table-title"><?php _e($a['title']); ?></div>
													<div class="table-description"><?php _e($a['excerpt']); ?></div>
													<ul class="tableHoverMenu"><?php _e($hovermenu); ?></ul>
												</td>
												<td>
													<div class="table-slideshows" id="table-slideshows-<?php _e($a['id']); ?>">
														<div class="table-slideshows-content">
															<div class="table-slideshows-expand">
																<a href="#expand" class="btn btn-default"><i class="fa fa-caret-down"></i></a>
															</div>
															<div class="table-slideshows-add">
																<a href="gallery-add-slideshow.php?id=<?php _e($a['id']); ?>&ref=<?php _e($page->pageReferal); ?>&hash=<?php _e($hash); ?>" data-toggle="modal" data-target="#modal" class="btn btn-md btn-success"  data-toggle="tooltip" data-placement="top" title="Add another slideshow" rel="tooltip">
																	<i class="fa fa-plus"></i>
																</a>
															</div>
															<ul class="slideshow-thumbnails" data-id="<?php _e($a['id']); ?>">
																<?php
																if (!empty($a['slideshows'])) {
																foreach($s as $target_sid) {
																	$cover = getSlideshowCover($target_sid);
																	$slideshow_name = getSlideshowTitle($target_sid);
																?>
																<li data-id="<?php _e($target_sid); ?>">
																	<a href="#drag" class="slideshowDragger"><i class="fa fa-arrows"></i></a>
																	<a href="#remove" class="slideshowRemover"><i class="fa fa-minus-circle"></i></a>
																	<a href="slideshow-edit.php?id=<?php _e($target_sid); ?>" data-toggle="tooltip" data-placement="top" title="<?php _e($slideshow_name); ?>" rel="tooltip">
																		<img src="<?php _e($cover); ?>" width="<?php _e(GALLERY_THUMB_WIDTH); ?>px">
																	</a>
																</li>
																<?php } } else { ?>
																	<li><img src="css/defaul-thumb.jpg" width="<?php _e(GALLERY_THUMB_WIDTH); ?>px"></li>
																<?php } ?>

															</ul> <!-- /.slideshow-thumbnails -->
														</div> <!-- /.table-slideshows-content -->
													</div>
													<span class="hidden"><?php _e($xcount3); ?></span>
												</td>
												<td class="td-date">
													<div class="table-date"><?php _d("{$a['timestamp']}"); ?></div>
													<div class="table-status"><?php _status($a['status']); ?></div>
												</td>
											</tr>
											<?php } ?>
										</tbody>
									</table>
								</div> <!-- /.table-responsive -->

								<div id="galleries-pagination">

									<ul class="pagination pull-right">
									<?php
									echo $pages->display_pages();
									?>
									</ul>

								</div>
							</div> <!-- /.tab-published -->
						</div> <!-- /.tab-content -->

					</div> <!-- /.col-lg-12 -->

				</div><!-- /.row -->


<?php
}; // end of pageContent()

include('template.php');
?>