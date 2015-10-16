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
    $login_ref = 'index.php';
    // $login_ref = urlencode(curPageURL());
    header("location: login.php?ref=$login_ref");
}

// Page Object
$page = new Page;
$page->pageTitle = "Dashboard";
$page->pageSlug = "dashboard";
$page->pageIcon = "fa-dashboard";
$page->pageParent = "";
$page->pageParentSlug = "";
$page->pageDescription = "Dude, it's a dashboard.";
$page->pageExcerpt = "Dude, it's a dashboard.";
$page->pageReferal = urlencode(curPageURL());
$page->pageIncludes = <<< EOI
<script src="js/raphael/raphael-min.js"></script>
<script src="js/morris/morris-0.4.3.min.js"></script>
<script src="js/morris/chart-data-morris.js"></script>
<!--[if lte IE 8]><script src="js/excanvas.min.js"></script><![endif]-->
<script src="js/flot/jquery.flot.js"></script>
<script src="js/flot/jquery.flot.tooltip.min.js"></script>
<script src="js/flot/jquery.flot.resize.js"></script>
<script src="js/flot/jquery.flot.pie.js"></script>
EOI;
// END OF INCLUDES

function pageContent($page)
{
?>

                <div class="row">
                    <div class="col-lg-12">
                        <h1>Dashboard <small>Statistics Overview</small></h1>
                        <ol class="breadcrumb">
                            <li class="active"><i class="fa fa-dashboard"></i>Dashboard</li>
                        </ol>
                    </div>
                </div><!-- /.row -->

                <?php
                // set db connection
                global $db;
                global $site;

                // get slideshow count
                // set db query for published slideshows
                $q = "SELECT * FROM `slideshows`";
                $r = $db->query($q);
                $slideshowCount = $db->num_rows($r);

                // get photos count
                // set db query for published slideshows
                $q = "SELECT * FROM `photos`";
                $r = $db->query($q);
                $photoCount = $db->num_rows($r);


                $now = time(); // or your date as well
                $your_date = strtotime($site->subscription_due);
                $datediff = $your_date - $now;
                $days_left = number_format(floor($datediff/(60*60*24)));
                ?>


                <div class="row">
                    <div class="col-lg-4">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-6">
                                        <i class="fa fa-picture-o fa-5x"></i>
                                    </div>
                                    <div class="col-xs-6 text-right">
                                        <p class="announcement-heading"><?php _c($slideshowCount); ?></p>
                                        <p class="announcement-text">Slideshows</p>
                                    </div>
                                </div>
                            </div>
                            <a href="slideshows.php">
                                <div class="panel-footer announcement-bottom">
                                    <div class="row">
                                        <div class="col-xs-6">
                                            View Slideshows
                                        </div>
                                        <div class="col-xs-6 text-right">
                                            <i class="fa fa-arrow-circle-right"></i>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="panel panel-warning">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-6">
                                        <i class="fa fa-th fa-5x"></i>
                                    </div>
                                    <div class="col-xs-6 text-right">
                                        <p class="announcement-heading"><?php _c($photoCount); ?></p>
                                        <p class="announcement-text">Photos Uploaded</p>
                                    </div>
                                </div>
                            </div>
                            <a href="upload.php">
                                <div class="panel-footer announcement-bottom">
                                    <div class="row">
                                        <div class="col-xs-6">
                                            Upload Photos
                                        </div>
                                        <div class="col-xs-6 text-right">
                                            <i class="fa fa-arrow-circle-right"></i>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-6">
                                        <i class="fa fa-clock-o fa-5x"></i>
                                    </div>
                                    <div class="col-xs-6 text-right">
                                        <p class="announcement-heading"><?php _e($days_left); ?></p>
                                        <p class="announcement-text">Days Left</p>
                                    </div>
                                </div>
                            </div>
                            <a href="#">
                                <div class="panel-footer announcement-bottom">
                                    <div class="row">
                                        <div class="col-xs-6">
                                            Renew Now
                                        </div>
                                        <div class="col-xs-6 text-right">
                                            <i class="fa fa-arrow-circle-right"></i>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div><!-- /.row -->

                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-upload"></i> Latest Photo Upload</h3>
                            </div>
                            <!-- <div class="panel-body"> -->
                                <?php
                                $date = date("Y-m-d");
                                // set db query for published slideshows
                                $q = "SELECT * FROM `photos`";
                                // $q .= " WHERE `timestamp` LIKE '$date%'";
                                $q .= " ORDER BY `id` DESC";
                                $q .= " LIMIT 6";
                                $r = $db->query($q);
                                ?>
                                <div class="photo-grid" role="presentation">

                                    <?php
                                    while ($a = $db->fetch_array_assoc($r)) {
                                        $hovermenu = '<li class="tableHoverMenuPrimary"><a data-toggle="modal" data-target="#modal" class="" href="photo-edit.php?id=' . $a['id'] . '&ref=' . $page->pageReferal . '" title="Edit"><i class="fa fa-pencil"></i><span>Edit</span></a></li>';
                                        $hovermenu .= '<li class="tableHoverMenuWarning"><a data-toggle="modal" data-target="#modal" class="" href="photo-del.php?id=' . $a['id'] . '&ref=' . $page->pageReferal . '" title="Delete"><i class="fa fa-times-circle"></i><span>Delete</span></a></li>';

                                        // get number of photos for slideshow
                                        $sid = $a['slideshow'];
                                        // get slideshow name
                                        $sid_name = getSlideshowTitle($sid);

                                        // get preview image file
                                        $thumb = SITE_URL . "/" . SITE_UPLOADS . "/preview/" . $a['name'];
                                        ?>
                                        <div class="grid" data-id="<?php _e($a['id']); ?>">
                                        <div class="grid-content">
                                            <div class="picture">
                                                <img src="<?php _e($thumb); ?>" width="<?php _e(GALLERY_PREV_WIDTH); ?>px">
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
                                                <div class="title"><label><?php _e($title); ?></label></div>
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
                            <!-- </div> -->
                            <a href="photos.php">
                                <div class="panel-footer announcement-bottom">
                                    <div class="row">
                                        <div class="col-xs-6">
                                            Manage Photos
                                        </div>
                                        <div class="col-xs-6 text-right">
                                            <i class="fa fa-arrow-circle-right"></i>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div><!-- /.row -->

<?php
}; // end of pageContent()

include('template.php');
?>