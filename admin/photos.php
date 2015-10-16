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
$page->pageSlug = "photo";
$page->pageIcon = "fa-th";
$page->pageParent = "Media";
$page->pageParentSlug = "media";
$page->pageParentIcon = "fa-th";
$page->pageDescription = "";
$page->pageExcerpt = "";
$page->pageReferal = urlencode(curPageURL());
// $id = $_REQUEST['id'];
$page->pageIncludes = <<< EOI
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
                global $site;
                $table = 'photos';

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

                            <div class="col-xs-6 col-sm-6 col-lg-3 filter-bar">
                                <div class="input-group">
                                    <div class="dropdown">
                                        <button class="btn btn-info dropdown-toggle" type="button" id="filter-menu" data-toggle="dropdown">
                                            <i class="fa fa-filter"></i> <?php if (isset($_GET['filter'])) { _e("Filtered by: " . getCategoryName($_GET['filter'])); } else { ?>Filter by category<?php } ?>
                                            <span class="caret"></span>
                                        </button>

                                        <ul class="dropdown-menu photo-filter" role="menu" aria-labelledby="filter-menu">
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

                            <div class="col-xs-6 col-sm-6 col-lg-3 pull-right delete-buttonbar">
                                <label><span>Check All</span> <input type="checkbox" class="delete-toggle"></label>
                                <button type="button" class="btn btn-danger deleteButton">
                                    <i class="glyphicon glyphicon-trash"></i>
                                    <span>Delete</span>
                                </button>
                            </div>

                            <div class="clearfix"></div>

                    </div> <!-- /.pagetop-bar -->

                    <div class="col-lg-12" id="photo-manager-box">
                        <div class="photo-manager-box-container">

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
                                $q .= " AND (`categories` LIKE '%;$filter' OR  `categories` LIKE '$filter;%' OR `categories` LIKE '%;$filter;%' OR `categories` LIKE '$filter')";
                            else
                                $q .= " WHERE (`categories` LIKE '%;$filter' OR  `categories` LIKE '$filter;%' OR `categories` LIKE '%;$filter;%' OR `categories` LIKE '$filter')";

                        if (!empty($search))
                            if (strpos($q, "WHERE") !== FALSE)
                                $q .= " AND (`name` LIKE '%$search%' OR `title` LIKE '%$search%' OR `description` LIKE '%$search%')";
                            else
                                $q .= " WHERE (`name` LIKE '%$search%' OR `title` LIKE '%$search%' OR `description` LIKE '%$search%')";

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
                        <div class="photo-grid" role="presentation">

                                    <?php
                                    while ($a = $db->fetch_array_assoc($r2)) {
                                        $hovermenu = '<li class="tableHoverMenuPrimary"><a data-toggle="modal" data-target="#modal" class="" href="photo-edit.php?id=' . $a['id'] . '&ref=' . $page->pageReferal . '" title="Edit"><i class="fa fa-pencil"></i><span>Edit</span></a></li>';
                                        $hovermenu .= '<li class="tableHoverMenuWarning"><a data-toggle="modal" data-target="#modal" class="" href="photo-del.php?id=' . $a['id'] . '&ref=' . $page->pageReferal . '" title="Delete"><i class="fa fa-times-circle"></i><span>Delete</span></a></li>';

                                        // get number of photos for slideshow
                                        $sid = $a['slideshow'];
                                        // get slideshow name
                                        $sid_name = getSlideshowTitle($sid);

                                        // get preview image file
                                        $thumb = $site->url . $site->uploads_dir . "/preview/" . $a['name'];
                                        ?>
                                        <div class="grid" data-id="<?php _e($a['id']); ?>">
                                            <div class="grid-content">
                                                <div class="picture">
                                                    <img src="<?php _e($thumb); ?>" width="<?php _e($site->gallery_prev_width); ?>px">
                                                </div>
                                                <div class="pictureTitle">
                                                    <?php
                                                    if ($a['title']=="") {
                                                        $title = $a['name'];
                                                    } else {
                                                        $title = $a['title'];
                                                    }
                                                    $title = getExcerpt($title,0,20,true);
                                                    ?>
                                                    <div class="title">
                                                        <input type="checkbox" name="delete[]" id="delete-photo<?php _e($a['id']); ?>" value="<?php _e($a['id']); ?>" class="deleteBox">
                                                        <label for="delete-photo<?php _e($a['id']); ?>"><?php _e($title); ?></label>
                                                    </div>
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
                                            </div> <!-- /.grid-content -->
                                        </div> <!-- /.grid -->
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

                        </div> <!-- /.photo-manager-box-container -->
                    </div> <!-- /#photo-manager-box -->

                    <form name="photodelete" id="photo_delete" class="photo-delete form" role="form" action="photos-delgroup.php" method="post" enctype="multipart/form-data" target="_self">
                        <input type="hidden" name="ref" value="<?php _e($page->pageReferal); ?>">
                        <input type="hidden" name="items" id="items" value="">
                    </form> <!-- /#photo_delete -->

                </div><!-- /.row -->

<?php
}; // end of pageContent()

include('template.php');
?>