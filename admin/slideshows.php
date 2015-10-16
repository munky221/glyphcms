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
$page->pageTitle = "Slideshows";
$page->pageSlug = "slideshows";
$page->pageIcon = "fa-ellipsis-h";
$page->pageParent = "Manage";
$page->pageParentSlug = "manage";
$page->pageParentIcon = "fa-flask";
$page->pageDescription = "Manage all your slides.";
$page->pageExcerpt = "Manage all your slides.";
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
                $table = 'slideshows';

                if (isset($_GET['status']) || !empty($_GET['status']))
                    $status = $_GET['status'];
                else
                    $status = "2";

                if (isset($_GET['search']) || !empty($_GET['search']))
                    $search = $_GET['search'];
                else
                    $search = "";

                if (isset($_GET['filter']) || !empty($_GET['filter']))
                    $filter = $_GET['filter'];
                else
                    $filter = "";
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

                            <div class="col-xs-12 col-sm-6 col-lg-3 search-bar">
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

                            <div class="col-xs-12 col-sm-6 col-lg-3 filter-bar">
                                <div class="input-group">
                                    <div class="dropdown">
                                        <button class="btn btn-info dropdown-toggle" type="button" id="filter-menu" data-toggle="dropdown">
                                            <i class="fa fa-filter"></i> <?php if (isset($_GET['filter'])) { _e("Filtered by: " . getCategoryName($_GET['filter'])); } else { ?>Filter by category<?php } ?>
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu" aria-labelledby="filter-menu">
                                            <?php
                                                $none_link_string = "";
                                                if (isset($status))
                                                    if (strlen($none_link_string)>0)
                                                        $none_link_string .= "&status=" . $status;
                                                    else
                                                        $none_link_string .= "?status=" . $status;
                                                if (!empty($search))
                                                    if (strlen($none_link_string)>0)
                                                        $none_link_string .= "&search=" . $search;
                                                    else
                                                        $none_link_string .= "?search=" . $search;
                                            ?>
                                            <li role="presentation" data-id='-1' class="<?php if (!isset($_GET['filter'])) _e('active'); ?>"><a role="menuitem" tabindex="-1" href="<?php _e($none_link_string); ?>">All</a></li>
                                            <li role="presentation" class="divider"></li>

                                            <?php
                                            global $db;
                                            $q = "SELECT * FROM `categories`";
                                            $r = $db->query($q);
                                            while ($a = $db->fetch_array_assoc($r)) {
                                                $filter_link = "?filter=" . $a['id'];
                                                if (isset($status))
                                                    $filter_link .= "&status=" . $status;
                                                if (!empty($search))
                                                    $filter_link .= "&search=" . $search;
                                            ?>
                                            <li role="presentation" data-id="<?php _e($a['id']); ?>" class="<?php if (isset($_GET['filter'])) { if ($_GET['filter'] == $a['id']) { _e('active'); } } ?>"><a role="menuitem" tabindex="-1" href="<?php _e($filter_link); ?>"><?php _e($a['title']); ?></a></li>
                                            <?php } ?>
                                            <?php
                                                $uncat_link = "?filter=empty";
                                                if (isset($status))
                                                    $uncat_link .= "&status=" . $status;
                                                if (!empty($search))
                                                    $uncat_link .= "&search=" . $search;
                                            ?>
                                            <li role="presentation" class="divider"></li>
                                            <li role="presentation" data-id='-2' class="<?php if (isset($_GET['filter'])) { if ($_GET['filter'] == "empty") { _e('active'); } } ?>"><a role="menuitem" tabindex="-1" href="<?php _e($uncat_link); ?>">Uncategorized</a></li>

                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="clearfix"></div>

                    </div> <!-- /.pagetop-bar -->
                    

                    <div class="col-lg-12">

                        <ul class="nav nav-tabs" id="slideshow-tabs">
                            <li class="<?php if ($status == '2') _e("active"); ?>"><a href="slideshows.php?status=2">Published</a></li>
                            <li class="<?php if ($status == '0') _e("active"); ?>"><a href="slideshows.php?status=0">Drafts</a></li>
                            <li class="<?php if ($status == '3') _e("active"); ?>"><a href="slideshows.php?status=3">Archived</a></li>
                            <a id="newSlideshowButton" href="slideshow-new.php" class="btn btn-primary pull-right customButton"><i class="fa fa-plus"></i>New Slideshow</a>
                        </ul>

                        <div class="tab-content">
                            <div id="tab-slideshow-published">

                                <?php
                                // set referal page
                                $ref = urlencode(curPageURL());

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


                                if (($filter == "0") || !empty($filter))
                                    if (strpos($q, "WHERE") !== FALSE)
                                        $q .= " AND (`category` LIKE '%;$filter' OR  `category` LIKE '$filter;%' OR `category` LIKE '%;$filter;%' OR `category` LIKE '$filter')";
                                    else
                                        $q .= " WHERE (`category` LIKE '%;$filter' OR  `category` LIKE '$filter;%' OR `category` LIKE '%;$filter;%' OR `category` LIKE '$filter')";

                                if (!empty($search))
                                    if (strpos($q, "WHERE") !== FALSE)
                                        $q .= " AND (`title` LIKE '%$search%' OR `excerpt` LIKE '%$search%')";
                                    else
                                        $q .= " WHERE (`title` LIKE '%$search%' OR `excerpt` LIKE '%$search%')";

                                if (isset($status))
                                    if (strpos($q, "WHERE") !== FALSE)
                                        $q .= " AND (`status`='$status')";
                                    else
                                        $q .= " WHERE (`status`='$status')";

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

                                $hash = "tab-slideshow-published";


                                $pages = new Paginator;
                                $pages->items_total = $xcount;
                                $pages->mid_range = '7';
                                $pages->items_per_page = $_GET['ipp'];
                                $pages->paginate();
                                ?>
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped tablesorter">
                                        <thead>
                                            <tr>
                                                <th width="60px">ID <i class="fa fa-sort-asc"></i></th>
                                                <th>Title <i class="fa fa-sort"></i></th>
                                                <th width="80px">Photos <i class="fa fa-sort"></i></th>
                                                <th width="150px" class="th-date">Date <i class="fa fa-sort"></i></th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php
                                            while ($a = $db->fetch_array_assoc($r2)) {
                                                $hovermenu = '<li class="tableHoverMenuPrimary"><a href="slideshow-edit.php?id=' . $a['id'] . '" title="Edit"><i class="fa fa-pencil"></i><span>Edit</span></a></li>';
                                                if ($status == '2') {
                                                    $hovermenu .= '<li><a data-toggle="modal" data-target="#modal" class="" href="slideshow-update.php?a=0&id=' . $a['id'] . '&ref=' . $page->pageReferal . '&hash=' . $hash . '" title="Set as Draft"><i class="fa fa-cog"></i><span>Set as Draft</span></a></li>';
                                                    $hovermenu .= '<li><a data-toggle="modal" data-target="#modal" class="" href="slideshow-update.php?a=3&id=' . $a['id'] . '&ref=' . $page->pageReferal . '&hash=' . $hash . '" title="Archive"><i class="fa fa-file-archive-o"></i><span>Archive</span></a></li>';
                                                } else if ($status == '0') {
                                                    $hovermenu .= '<li><a data-toggle="modal" data-target="#modal" class="" href="slideshow-update.php?a=2&id=' . $a['id'] . '&ref=' . $page->pageReferal . '&hash=' . $hash . '" title="Set as Published"><i class="fa fa-check"></i><span>Set as Published</span></a></li>';
                                                    $hovermenu .= '<li><a data-toggle="modal" data-target="#modal" class="" href="slideshow-update.php?a=3&id=' . $a['id'] . '&ref=' . $page->pageReferal . '&hash=' . $hash . '" title="Archive"><i class="fa fa-file-zip-o"></i><span>Archive</span></a></li>';
                                                } else if ($status == '3') {
                                                    $hovermenu .= '<li><a data-toggle="modal" data-target="#modal" class="" href="slideshow-update.php?a=0&id=' . $a['id'] . '&ref=' . $page->pageReferal . '&hash=' . $hash . '" title="Set as Draft"><i class="fa fa-cog"></i><span>Send to Draft</span></a></li>';
                                                }
                                                $hovermenu .= '<li class="tableHoverMenuWarning"><a data-toggle="modal" data-target="#modal" class="" href="slideshow-del.php?id=' . $a['id'] . '&ref=' . $page->pageReferal . '&hash=' . $hash . '" title="Delete"><i class="fa fa-times-circle"></i><span>Delete</span></a></li>';

                                                // get number of photos for slideshow
                                                $sid = $a['id'];

                                                // get slideshow cover
                                                $cover = getSlideshowCover($sid);

                                                $photoCount = count(explode(";",$a['photos']));
                                                ?>
                                            <tr>
                                                <td>
                                                    <div class="table-id"><?php _e($a['id']); ?></div>
                                                </td>
                                                <td>
                                                    <div class="table-picture pull-left"><img src="<?php _e($cover); ?>" width="<?php _e(GALLERY_THUMB_WIDTH); ?>px"></div>
                                                    <div class="table-picturedetails">
                                                        <div class="table-title"><?php _e($a['title']); ?></div>
                                                        <div class="table-desc"><?php _e(getExcerpt($a['excerpt'])); ?></div>
                                                        <div class="tableHoverMenu"><?php _e($hovermenu); ?></div>
                                                    </div>
                                                </td>
                                                <td><div class="table-author"><?php _c($photoCount); ?></div></td>
                                                <td class="td-date">
                                                    <div class="table-date"><?php _d("{$a['timestamp']}"); ?></div>
                                                    <div class="table-status"><?php _status($a['status']); ?></div>
                                                </td>
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

                    </div> <!-- /.col-lg-12 -->

                </div><!-- /.row -->


<?php
}; // end of pageContent()

include('template.php');
?>