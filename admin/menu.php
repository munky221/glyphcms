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

// Page Object
$page = new Page;
$page->pageTitle = "Site Menu";
$page->pageSlug = "site-menu";
$page->pageIcon = "fa-sitemap";
$page->pageParent = "System";
$page->pageParentSlug = "system";
$page->pageParentIcon = "fa-cogs";
$page->pageDescription = "Compose your website's navigation menu.";
$page->pageExcerpt = "Compose your website's navigation menu.";
$page->pageReferal = urlencode(curPageURL());
$page->pageIncludes = <<< EOI
<!-- NESTED LIST SORTABLE -->
<script src="js/jquery-nestedlist-sortable.js"></script>

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
							<li class="active"><i class="fa <?php _e($page->pageIcon); ?>"></i><?php _e($page->pageTitle); ?></li>
						</ol>
					</div>
					
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-5">

						<div class="refreshMenuTitle">
							<h3>1. Create Menu Item</h3>
							<h5>Customize your menu by creating different types of menu items</h5>
						</div>

						<form class="form categoryForm" name="form-create-menu" id="menuItemCreate" role="form" action="menu-create-action.php" method="post" enctype="multipart/form-data" target="_self">
							<input type="hidden" name="t" value="menu">
							<input type="hidden" name="re" value="<?php _e($page->pageReferal); ?>">

							<div class="form-group">
								<label>Title</label>
								<input name="title" id="title" class="form-control" placeholder="Your menu title..." required min-length="5">
							</div>

							<div class="form-group">
								<label>Menu Type</label>
								<div>
									<div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
										<h4>Grid Type</h4>

										<label class="radio">
											<input type="radio" name="type" id="pageType4" value="4" class="pageType"> Slideshow Grid
											<sup data-toggle="tooltip" data-placement="top" title="Slideshow Grid - generate a link to a specific slideshow grid."><i class="fa fa-question"></i></sup>
										</label>

										<label class="radio">
											<input type="radio" name="type" id="pageType6" value="6" class="pageType"> Gallery Grid
											<sup data-toggle="tooltip" data-placement="top" title="Gallery Grid - generate a link to a specific gallery grid."><i class="fa fa-question"></i></sup>
										</label>
									</div>
									<div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
										<h4>Presentation</h4>

										<label class="radio">
											<input type="radio" name="type" id="pageType3" value="3" class="pageType"> Slideshow 
											<sup data-toggle="tooltip" data-placement="top" title="Slideshow - generate a link to a specific slideshow presentation."><i class="fa fa-question"></i></sup>
										</label>

										<label class="radio">
											<input type="radio" name="type" id="pageType5" value="5" class="pageType"> Gallery
											<sup data-toggle="tooltip" data-placement="top" title="Gallery - generate a link to a specific gallery presentation."><i class="fa fa-question"></i></sup>
										</label>
									</div>
									<div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
										<h4>Other Types</h4>

										<label class="radio">
											<input type="radio" name="type" id="pageType0" value="0" class="pageType" checked> Placeholder
											<sup data-toggle="tooltip" data-placement="top" title="Placeholder - Use this if you just want to generate a placeholder for submenus."><i class="fa fa-question"></i></sup>
										</label>

										<label class="radio">
											<input type="radio" name="type" id="pageType1" value="1" class="pageType"> Page
											<sup data-toggle="tooltip" data-placement="top" title="Page - for static pages (i.e: About us page, etc.)"><i class="fa fa-question"></i></sup>
										</label>
										<label class="radio">
											<input type="radio" name="type" id="pageType2" value="2" class="pageType"> Hyperlink
											<sup data-toggle="tooltip" data-placement="top" title="Hyperlink - use this if you want to place a link on the menu that's outside your website."><i class="fa fa-question"></i></sup>
										</label>
									</div>
									<div class="clearfix"></div>
								</div>
							</div>

							<div id="menuTypePanels">
								<div id="panel-placeholder" class="form-group menu-type-panel show">
									<label>No options...</label>
								</div> <!-- /#panel-placeholder -->

								<div id="panel-page" class="form-group menu-type-panel">
									<label>Select Page</label>
									<div>
										<select name="pageSelect">
											<?php
											// set db query
											$q2 = "SELECT * FROM `pages` WHERE `status`='2' ORDER BY `title` ASC";
											$r2 = $db->query($q2);
											?>
											<?php while ($a2 = $db->fetch_array_assoc($r2)) { ?>
											<option value="<?php _e($a2['id']); ?>"><?php _e($a2['title']); ?></option>
											<?php } ?>
										</select>
									</div>
								</div> <!-- /#panel-page -->

								<div id="panel-hyperlink" class="form-group menu-type-panel">
									<label>Target URL</label>
									<input name="hyperlink" id="hyperlink" class="form-control" placeholder="http://www...">
								</div> <!-- /#panel-hyperlink -->

								<div id="panel-slideshow" class="form-group menu-type-panel">
									<label>Select Slideshow</label>
									<div>
										<select name="slideshowSelect">
											<?php
											// set db query
											$q4 = "SELECT * FROM `slideshows` WHERE `status`='2' ORDER BY `title` ASC";
											$r4 = $db->query($q4);
											?>
											<?php while ($a4 = $db->fetch_array_assoc($r4)) { ?>
											<option value="<?php _e($a4['id']); ?>"><?php _e($a4['title']); ?> --- (<?php _e(getSlideshowPhotoCount($a4['id'])); ?>)</option>
											<?php } ?>
										</select>
									</div>
								</div> <!-- /#panel-slideshow -->

								<div id="panel-gallery" class="form-group menu-type-panel">
									<label>Select Gallery</label>
									<div>
										<select name="gallerySelect">
											<?php
											// set db query
											$q3 = "SELECT * FROM `galleries` WHERE `status`='2' ORDER BY `title` ASC";
											$r3 = $db->query($q3);
											?>
											<?php while ($a3 = $db->fetch_array_assoc($r3)) { ?>
											<option value="<?php _e($a3['id']); ?>"><?php _e($a3['title']); ?></option>
											<?php } ?>
										</select>
									</div>
								</div> <!-- /#panel-gallery -->

							</div> <!-- /#typePanels -->

							<div class="form-buttons fileupload-buttonbar">
								<button id="formSubmit" type="submit" class="btn btn-primary btn-lg">
									<i class="fa fa-check"></i>
									<span>Create Menu Item</span>
								</button>
							</div>

						</form> <!-- /#form-create-slideshow -->

					</div> <!-- /#col-lg-4 -->


					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-7">
						<div class="col-xs-6 col-sm-6">

							<div class="refreshMenuTitle">
								<h3>2. Menu Items</h3>
								<h5>Drag and drop these menu items to the next column on the right.</h5>
								<!-- <div class="refreshMenuItems"><a href="<?php _e($page->pageSlug); ?>.php#theMenuItemsWrapper" class="ajaxLink btn btn-primary"><i class="fa fa-refresh"></i></a></div> -->
							</div>

							<div id="theMenuItemsWrapper">

								<?php
								// set db query for published slideshows
								$q = "SELECT * FROM `menu` WHERE `status`='2'";
								$r = $db->query($q);
								?>

								<ol id="theMenuItems" class="listsortable">
									<?php
									$count_result = $db->num_rows($r);
									if ($count_result > 0) {
									while ($a = $db->fetch_array_assoc($r))
									{


											// set the hovermenu for parent
											$hovermenu = '<div class="hoverMenuPrimary show"><a class="menuItemDrag" href="#drag"><i class="fa fa-arrows"></i></a></div>';
											$hovermenu .= '<div class="hoverMenuPrimary show"><a data-toggle="modal" data-target="#modal" class="" href="menu-edit.php?id=' . $a['id'] . '&ref=' . $page->pageReferal . '"><i class="fa fa-pencil"></i></a></div>';
											$hovermenu .= '<div class="hoverMenuWarning show"><a data-toggle="modal" data-target="#modal" class="" href="menu-del.php?id=' . $a['id'] . '&ref=' . $page->pageReferal . '"><i class="fa fa-trash-o"></i></a></div>';
											$hovermenu .= '<div class="hoverMenuPrimary hide"><a class="menuItemDrag" href="#drag"><i class="fa fa-arrows"></i></a></div>';
											$hovermenu .= '<div class="hoverMenuWarning hide"><a class="menuItemRemove" href="#remove"><i class="fa fa-times"></i></a></div>';

											$menuClass = "menuItem";
											if ($a['allowChildren'])
												$menuClass .= " menuItemParent";

											// add the parent to result
											if (!isset($result))
												$result = '<li class="' . $menuClass . '" data-id="' . $a['id'] . '"><span>' . $a['title'] . "</span>\n";
											else
												$result .= '<li class="' . $menuClass . '" data-id="' . $a['id'] . '"><span>' . $a['title'] . "</span>\n";
											$result .= '<div class="hoverMenu">' . $hovermenu . '</div>' . "\n";

											// open sub container for children
											if ($a['allowChildren'])
											{
												$result .= '<ol></ol>' . "\n";
											}
											$result .= '</li>' . "\n";
									}

									echo $result;
									}
									?>

								</ol>

								<div class="serialize_output"></div>
								<input type="hidden" name="menuItemOutput" id="menuItemOutput" value="">
							</div> <!-- /#theMenuItemsWrapper -->
						</div> <!-- /.col-xs-6 col-sm-4 -->



						<div class="col-xs-6 col-sm-6">

							<div class="refreshMenuTitle">
								<h3>3. Menu List</h3>
								<h5>Arrange the menu items according to your specification.</h5>
							</div>

							<div id="theMenuListWrapper">

								<?php
								// set db query for published slideshows
								$q = "SELECT * FROM `taxonomy` WHERE `name`='menu_data' LIMIT 1";
								$r = $db->query($q);
								$a = $db->fetch_array_assoc($r);

								// prep result data
								unset($result);
								?>

									<ol id="theMenuList" class="listsortable">
									
									<?php
									if (!empty($a['value'])) {
										$parent_array = explode(",",$a['value']);
										foreach ($parent_array as $key => $val)
										{
											// if parent has children
											if (strpos($val,";") > 0)
											{
												list($parent, $children) = explode(";",$val);

												// get menu data for parent
												$x = getMenuData($parent);

												// set the hovermenu for parent
												$hovermenu = '<div class="hoverMenuPrimary"><a class="menuItemDrag" href="#drag"><i class="fa fa-arrows"></i></a></div>';
												$hovermenu .= '<div class="hoverMenuWarning"><a class="menuItemRemove" href="#remove"><i class="fa fa-times"></i></a></div>';

												// add the parent to result
												if (!isset($result))
													$result = '<li data-id="' . $parent . '"><span>' . $x['title'] . "</span>\n";
												else
													$result .= '<li data-id="' . $parent . '"><span>' . $x['title'] . "</span>\n";
												$result .= '<div class="hoverMenu">' . $hovermenu . '</div>' . "\n";

												// open sub container for children
												if ($x['allowChildren'])
												{
													$result .= '<ol>' . "\n";
												}

												// iterate all children
												$children_array = explode("-",$children);

												foreach ($children_array as $key2 => $child)
												{
													// get menu data for children
													$y = getMenuData($child);

													// set the hovermenu for parent
													$hovermenu_child = '<div class="hoverMenuPrimary"><a class="menuItemDrag" href="#drag"><i class="fa fa-arrows"></i></a></div>';
													$hovermenu_child .= '<div class="hoverMenuWarning menuListLink"><a class="menuItemRemove" href="#remove"><i class="fa fa-times"></i></a></div>';
													$result .= '<li data-id="' . $child . '"><span>' . $y['title'] . "</span>\n";
													$result .= '<div class="hoverMenu">' . $hovermenu_child . '</div>' . "\n";
													$result .= '</li>' . "\n";
												}

												// close the sub container for children
												if ($x['allowChildren']) {
													$result .= '</ol>' . "\n";
												}
												$result .= '</li>' . "\n";

											// if parent has no children
											}
											else
											{
												// get menu data for parent
												$x = getMenuData($val);

												// set the hovermenu for parent
												$hovermenu = '<div class="hoverMenuPrimary"><a class="menuItemDrag" href="#drag"><i class="fa fa-arrows"></i></a></div>';
												$hovermenu .= '<div class="hoverMenuWarning menuListLink"><a class="menuItemRemove" href="#remove"><i class="fa fa-times"></i></a></div>';

												if (!isset($result))
													$result = '<li data-id="' . $val . '"><span>' . $x['title'] . "</span>\n";
												else
													$result .= '<li data-id="' . $val . '"><span>' . $x['title'] . "</span>\n";
												$result .= '<div class="hoverMenu">' . $hovermenu . '</div>' . "\n";
												if ($x['allowChildren']) {
													$result .= '<ol></ol>';
												}
												$result .= '</li>';
											}
										}

										echo $result;
										?>

									<?php } ?>

									</ol>

								<div class="serialize_output"></div>
								<input type="hidden" name="menuItemOutput" id="menuItemOutput" value="">
							</div> <!-- /#theMenuListWrapper -->

						</div> <!-- /.col-xs-6 col-sm-4 -->
					</div> <!-- /.col-lg-8 -->

				</div><!-- /.row -->

<?php
}; // end of pageContent()

include('template.php');
?>