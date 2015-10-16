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
$page->pageTitle = "Site Options";
$page->pageSlug = "site-options";
$page->pageIcon = "fa-puzzle-piece";
$page->pageParent = "System";
$page->pageParentSlug = "system";
$page->pageParentIcon = "fa-cogs";
$page->pageDescription = "";
$page->pageExcerpt = "";
$page->pageReferal = urlencode(curPageURL());
$page->pageIncludes = <<< EOI
<!-- NESTED LIST SORTABLE -->
<script src="js/jquery-nestedlist-sortable.js"></script>

<!-- VALIDATE FORM -->
<script src="js/jquery.validate.min.js"></script>
<script src="js/jquery.validate.additional-method.js"></script>

<!-- FILE UPLOAD -->
<link rel="stylesheet" href="uploader/css/blueimp-gallery.min.css">
<link rel="stylesheet" href="uploader/css/jquery.fileupload.css">
<link rel="stylesheet" href="uploader/css/jquery.fileupload-ui.css">

<!-- CSS adjustments for browsers with JavaScript disabled -->
<noscript><link rel="stylesheet" href="uploader/css/jquery.fileupload-noscript.css"></noscript>
<noscript><link rel="stylesheet" href="uploader/css/jquery.fileupload-ui-noscript.css"></noscript>

<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="uploader/js/vendor/jquery.ui.widget.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="uploader/js/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="uploader/js/jquery.fileupload.js"></script>
<!-- The XDomainRequest Transport is included for cross-domain file deletion for IE 8 and IE 9 -->
<!--[if (gte IE 8)&(lt IE 10)]>
<script src="uploader/js/cors/jquery.xdr-transport.js"></script>
<![endif]-->
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

                $subscription_now = time(); // or your date as well
                $subscription_your_date = strtotime($site->subscription_due);
                $subscription_datediff = $subscription_your_date - $subscription_now;
                $subscription_days_left = number_format(floor($subscription_datediff/(60*60*24)));

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

                            <form class="form form-horizontal" action="options-action.php" role="form" method="post" enctype="multipart/form-data" target="_self">
                            
                                <input type="hidden" name="t" value="options">
                                <input type="hidden" name="ref" value="<?php _e($page->pageReferal); ?>">

                                <legend><h2>General</h2> <small class="help-block">Your website's general information.</small></legend>
                                <fieldset>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="subscription-due">Subscription Due</label>
                                        <div class="col-md-4">
                                        <div id="subscription-due" class="alert alert-success" role="alert"><?php _e(gmdate("M j, Y - h:i:s a",strtotime($site->subscription_due))); ?> <span class="badge pull-right"><?php _e($subscription_days_left); ?> days left</span></div>
                                        <p class="help-block"><a href="#renew" class="btn btn-info">Renew Subscription</a></p>
                                        </div>
                                    </div>

                                </fieldset>

                                <fieldset>

                                    <hr>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="site-name">Site Name</label>  
                                        <div class="col-md-4 input-group">
                                        <input id="site-name" name="site-name" type="text" placeholder="White Rab.it" class="form-control input-md" value="<?php _e($site->name); ?>" required>
                                        <span class="input-group-addon">*</span>
                                        </div>
                                    </div>

                                </fieldset>

                                <fieldset>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="site-title">Site Title</label>  
                                        <div class="col-md-4 input-group">
                                        <input id="site-title" name="site-title" type="text" placeholder="White Rab.it is awesome!" class="form-control input-md" value="<?php _e($site->title); ?>" required>
                                        <span class="input-group-addon">*</span>
                                        </div>
                                    </div>

                                </fieldset>

                                <fieldset>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="site-domain">Domain Name</label>  
                                        <div class="col-md-4 input-group">
                                        <input id="site-domain" name="site-domain" type="text" placeholder="example: domain.com" class="form-control input-md" value="<?php _e($site->domain); ?>" required>
                                        <span class="input-group-addon">*</span>
                                        </div>
                                    </div>

                                </fieldset>

                                <fieldset>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="site-url">Site URL</label>  
                                        <div class="col-md-4 input-group">
                                        <input id="site-url" name="site-url" type="text" placeholder="example: http://www.domain.com" class="form-control input-md" value="<?php _e($site->url); ?>" required>
                                        <span class="input-group-addon">*</span>
                                        </div>
                                    </div>

                                </fieldset>

                                <legend><h2>Appearance</h2> <small class="help-block">Your website's appearance.</small></legend>
                                <fieldset>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="theme">Select A Theme</label>
                                        <div class="col-md-4 input-group">
                                        <select id="theme" name="theme" class="form-control">
                                        	<?php
												$dir = ABSPATH . "system/themes";
												$themes_array = dirToArray($dir);

												$i = 0;
												foreach($themes_array as $key => $val) {
                                        	?>
											<option value="<?php _e($key); ?>"<?php if ($site->theme_name == $key) echo 'selected'; ?>><?php _e($key); ?></option>
											<?php } ?>
										</select>
                                        <span class="input-group-addon">*</span>
                                        </div>
                                    </div>

                                </fieldset>

                                <fieldset>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="site-homepage-default">Use Homepage Default</label>
                                        <div class="col-md-3">
                                            <div class="checkbox">
                                                <label for="site-homepage-default">
                                                    <input type="checkbox" name="site-homepage-default" id="site-homepage-default" value="1" <?php if ($site->site_homepage_default) echo "checked"; ?>>
                                                    Yes
                                                </label>
                                                <p class="help-block">Default homepage will show a random image tagged with a <span class="label label-primary">Featured</span> category.</p>
                                            </div>
                                        </div>
                                    </div>

                                </fieldset>

                                <fieldset id="site-homepage-target-set" <?php if ($site->site_homepage_default) _e("disabled"); ?>>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="site-homepage-target">Home Page Target</label>  

                                        <div class="col-md-4 input-group">
                                            <div class="input-group-btn">
                                                <?php
                                                $site_homepage_target_array = array("NA","Static Page","External Page","Slideshow Presentation","Slideshow Grid","Gallery Presentation","Gallery Grid");
                                                ?>
                                                <button type="button" id="site-homepage-type-select-button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><?php if ($site->site_homepage_default) _e("Action"); else _e($site_homepage_target_array[$site->site_homepage_target_type]); ?> <span class="caret"></span></button>
                                                <ul id="site-homepage-type-select" class="dropdown-menu" role="menu">
                                                    <li class="<?php if ($site->site_homepage_target_type == "1") _e("active"); ?>"><a href="#" data-id="1">Static Page</a></li>
                                                    <li class="<?php if ($site->site_homepage_target_type == "3") _e("active"); ?>"><a href="#" data-id="3">Slideshow Presentation</a></li>
                                                    <li class="<?php if ($site->site_homepage_target_type == "4") _e("active"); ?>"><a href="#" data-id="4">Slideshow Grid</a></li>
                                                    <li class="<?php if ($site->site_homepage_target_type == "5") _e("active"); ?>"><a href="#" data-id="5">Gallery Presentation</a></li>
                                                    <li class="<?php if ($site->site_homepage_target_type == "6") _e("active"); ?>"><a href="#" data-id="6">Gallery Grid</a></li>
                                                </ul>
                                            </div><!-- /btn-group -->
                                            <input type="hidden" id="site-homepage-type-select-collect" name="site-homepage-type-select-collect" value="<?php if ($site->site_homepage_default) _e("0"); else _e($site->site_homepage_target_type); ?>">
                                            <div id="site-homepage-target-select-type1" class="site-homepage-target-select <?php if (!$site->site_homepage_default AND $site->site_homepage_target_type == "1") _e("show"); ?>">
                                                <select class="form-control" id="site-homepage-target-type1" name="site-homepage-static-page-select">
                                                    <?php
                                                        // STATIC PAGE
                                                        $q = "SELECT * FROM `pages` WHERE `status`='2'";
                                                        $r = $db->query($q);
                                                        $c = $db->num_rows($r);
                                                        if ($c > 0) {
                                                        while ($a = $db->fetch_array_assoc($r)) {
                                                    ?>
                                                    <option value="<?php _e($a['id']); ?>"<?php if ($site->site_homepage_target == $a['id']) echo 'selected'; ?>><?php _e($a['title']); ?></option>
                                                    <?php } } else { ?>
                                                    <option>None...</option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div id="site-homepage-target-select-type3" class="site-homepage-target-select <?php if (!$site->site_homepage_default AND $site->site_homepage_target_type == "3") _e("show"); ?>">
                                                <select class="form-control" id="site-homepage-target-type3" name="site-homepage-slideshow-presentation-select">
                                                    <?php
                                                        // SLIDESHOW PRESENTATION
                                                        $q = "SELECT * FROM `slideshows` WHERE `status`='2'";
                                                        $r = $db->query($q);
                                                        $c = $db->num_rows($r);
                                                        if ($c > 0) {
                                                        while ($a = $db->fetch_array_assoc($r)) {
                                                    ?>
                                                    <option value="<?php _e($a['id']); ?>"<?php if ($site->site_homepage_target == $a['id']) echo 'selected'; ?>><?php _e($a['title']); ?></option>
                                                    <?php } } else { ?>
                                                    <option>None...</option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div id="site-homepage-target-select-type4" class="site-homepage-target-select <?php if (!$site->site_homepage_default AND $site->site_homepage_target_type == "4") _e("show"); ?>">
                                                <select class="form-control" id="site-homepage-target-type4" name="site-homepage-slideshow-grid-select">
                                                    <?php
                                                        // SLIDESHOW GRID
                                                        $q = "SELECT * FROM `slideshows` WHERE `status`='2'";
                                                        $r = $db->query($q);
                                                        $c = $db->num_rows($r);
                                                        if ($c > 0) {
                                                        while ($a = $db->fetch_array_assoc($r)) {
                                                    ?>
                                                    <option value="<?php _e($a['id']); ?>"<?php if ($site->site_homepage_target == $a['id']) echo 'selected'; ?>><?php _e($a['title']); ?></option>
                                                    <?php } } else { ?>
                                                    <option>None...</option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div id="site-homepage-target-select-type5" class="site-homepage-target-select <?php if (!$site->site_homepage_default AND $site->site_homepage_target_type == "5") _e("show"); ?>">
                                                <select class="form-control" id="site-homepage-target-type5" name="site-homepage-gallery-presentation-select">
                                                    <?php
                                                        // GALLERY PRESENTATION
                                                        $q = "SELECT * FROM `galleries` WHERE `status`='2'";
                                                        $r = $db->query($q);
                                                        $c = $db->num_rows($r);
                                                        if ($c > 0) {
                                                        while ($a = $db->fetch_array_assoc($r)) {
                                                    ?>
                                                    <option value="<?php _e($a['id']); ?>"<?php if ($site->site_homepage_target == $a['id']) echo 'selected'; ?>><?php _e($a['title']); ?></option>
                                                    <?php } } else { ?>
                                                    <option>None...</option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div id="site-homepage-target-select-type6" class="site-homepage-target-select <?php if (!$site->site_homepage_default AND $site->site_homepage_target_type == "6") _e("show"); ?>">
                                                <select class="form-control" id="site-homepage-target-type6" name="site-homepage-gallery-grid-select">
                                                    <?php
                                                        // GALLERY GRID
                                                        $q = "SELECT * FROM `galleries` WHERE `status`='2'";
                                                        $r = $db->query($q);
                                                        $c = $db->num_rows($r);
                                                        if ($c > 0) {
                                                        while ($a = $db->fetch_array_assoc($r)) {
                                                    ?>
                                                    <option value="<?php _e($a['id']); ?>"<?php if ($site->site_homepage_target == $a['id']) echo 'selected'; ?>><?php _e($a['title']); ?></option>
                                                    <?php } } else { ?>
                                                    <option>None...</option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div><!-- /input-group -->
                                    </div>

                                </fieldset>

                                <hr>

                                <fieldset>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="display-slider-filmstrip">Global Show Filmstrip</label>
                                        <div class="col-md-3">
                                            <div class="checkbox">
                                                <label for="display-slider-filmstrip">
                                                    <input type="checkbox" name="display-slider-filmstrip" id="display-slider-filmstrip" value="1" <?php if ($site->display_slider_filmstrip) echo "checked='checked'"; ?>>
                                                    Yes
                                                </label>
                                                <p class="help-block">This will overried all individual slideshow settings.</p>
                                            </div>
                                        </div>
                                    </div>

                                </fieldset>

                                <fieldset>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="display-slider-infobar">Global Show Info Bar</label>
                                        <div class="col-md-3">
                                            <div class="checkbox">
                                                <label for="display-slider-infobar">
                                                    <input type="checkbox" name="display-slider-infobar" id="display-slider-infobar" value="1" <?php if ($site->display_slider_infobar) echo "checked='checked'"; ?>>
                                                    Yes
                                                </label>
                                                <p class="help-block">This will overried all individual slideshow settings.</p>
                                            </div>
                                        </div>
                                    </div>

                                </fieldset>

                                <hr>

                                <fieldset id="site-options-logo-main">
                                    <div class="form-group" id="site-options-logo">
                                        <label class="col-md-3 control-label" for="owner-email">Custom Logo</label>  
                                        <div class="col-md-4 input-group" id="site-logo-box-container">
                                            <?php
                                            if (empty($site->site_logo)) {
                                                $logo_prev_src = $site->url . "system/images/defaul-thumb.jpg";
                                                $logo_full_src = "javascript:void(0);";
                                            } else {
                                                $logo_prev_src = $site->url . $site->uploads_dir . "/static/preview/" . $site->site_logo;
                                                $logo_full_src = $site->url . $site->uploads_dir . "/static/" . $site->site_logo;
                                            }
                                            ?>
                                            <div id="site-option-logo-box">
                                                <a href="<?php _e($logo_full_src); ?>" target="_blank"><img src="<?php _e($logo_prev_src); ?>"></a>
                                            </div> <!-- /#site-option-logo-box -->
                                            <?php if (!empty($site->site_logo)) { ?>
                                            <a data-toggle="modal" data-target="#modal" class="btn btn-danger btn-del-logo" href="options-del-logo.php">Delete Logo</a>
                                            <?php } else { ?>
                                            <!-- The fileinput-button span is used to style the file input field as button -->
                                            <span class="btn btn-success fileinput-button">
                                                <i class="glyphicon glyphicon-plus"></i>
                                                <span>Select file...</span>
                                                <!-- The file input field used as target for the file upload widget -->
                                                <input id="fileupload" type="file" name="files[]">
                                            </span>
                                            <br>
                                            <br>
                                            <!-- The global progress bar -->
                                            <div id="progress" class="progress">
                                                <div class="progress-bar progress-bar-success"></div>
                                            </div>
                                            <!-- The container for the uploaded files -->
                                            <div id="files" class="files"></div>
                                            <?php } ?>
                                        </div>
                                    </div> <!-- /#site-options-logo -->
                                </fieldset>

                                <legend><h2>Owner Info</h2> <small class="help-block">The owner's' basic personal information.</small></legend>
                                <fieldset>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="owner-name">Owner Name</label>  
                                        <div class="col-md-4 input-group">
                                        <input id="owner-name" name="owner-name" type="text" placeholder="Your Name..." class="form-control input-md" value="<?php _e($site->owner_name); ?>" required>
                                        <span class="input-group-addon">*</span>
                                        </div>
                                    </div>

                                </fieldset>

                                <fieldset>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="owner-email">E-mail Address</label>  
                                        <div class="col-md-4 input-group">
                                        <input id="owner-email" name="owner-email" type="text" placeholder="mail@domain.com" class="form-control input-md" value="<?php _e($site->owner_email); ?>" required>
                                        <span class="input-group-addon">*</span>
                                        </div>
                                    </div>

                                </fieldset>

                                <fieldset>

                                    <legend><h2>Social Media</h2> <small class="help-block">Link to your social media presence.</small></legend>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="social-facebook">Facebook</label>
                                        <div class="col-md-4 input-group">
                                        <input id="social-facebook" name="social-facebook" type="text" placeholder="Facebook User ID" class="form-control input-md" value="<?php _e($site->social_facebook); ?>">
                                        </div>
                                    </div>

                                </fieldset>

                                <fieldset>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="social-twitter">Twitter</label>
                                        <div class="col-md-4 input-group">
                                        <input id="social-twitter" name="social-twitter" type="text" placeholder="Twitter User ID" class="form-control input-md" value="<?php _e($site->social_twitter); ?>">
                                        </div>
                                    </div>

                                </fieldset>

                                <fieldset>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="social-googleplus">Google+</label>
                                        <div class="col-md-4 input-group">
                                        <input id="social-googleplus" name="social-googleplus" type="text" placeholder="Google+ User ID" class="form-control input-md" value="<?php _e($site->social_googleplus); ?>">
                                        </div>
                                    </div>

                                </fieldset>

                                <fieldset>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="social-behance">Behance</label>
                                        <div class="col-md-4 input-group">
                                        <input id="social-behance" name="social-behance" type="text" placeholder="Behance User ID" class="form-control input-md" value="<?php _e($site->social_behance); ?>">
                                        </div>
                                    </div>

                                </fieldset>

                                <fieldset>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="social-linkedin">LinkedIn</label>
                                        <div class="col-md-4 input-group">
                                        <input id="social-linkedin" name="social-linkedin" type="text" placeholder="LinkedIn User ID" class="form-control input-md" value="<?php _e($site->social_linkedin); ?>">
                                        </div>
                                    </div>

                                </fieldset>

                                <fieldset>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="social-tumblr">Tumblr</label>
                                        <div class="col-md-4 input-group">
                                        <input id="social-tumblr" name="social-tumblr" type="text" placeholder="Tumblr User ID" class="form-control input-md" value="<?php _e($site->social_tumblr); ?>">
                                        </div>
                                    </div>

                                </fieldset>

                                <fieldset>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="social-instagram">Instagram</label>
                                        <div class="col-md-4 input-group">
                                        <input id="social-instagram" name="social-instagram" type="text" placeholder="Instagram User ID" class="form-control input-md" value="<?php _e($site->social_instagram); ?>">
                                        </div>
                                    </div>

                                </fieldset>

                                <legend><h2>Sharing</h2> <small class="help-block">Let your visitors share your content.</small></legend>
                                <fieldset>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="share-facebook">Facebook</label>
                                        <div class="col-md-3 input-group">
                                            <div class="checkbox">
                                                <label for="share-facebook">
                                                    <input type="checkbox" name="share-facebook" id="share-facebook" value="1" <?php if ($site->share_facebook) echo "checked"; ?>>
                                                    Yes
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                </fieldset>

                                <fieldset>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="share-twitter">Twitter</label>
                                        <div class="col-md-3 input-group">
                                            <div class="checkbox">
                                                <label for="share-twitter">
                                                    <input type="checkbox" name="share-twitter" id="share-twitter" value="1" <?php if ($site->share_twitter) echo "checked"; ?>>
                                                    Yes
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                </fieldset>

                                <fieldset>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="share-googleplus">Google+</label>
                                        <div class="col-md-3 input-group">
                                            <div class="checkbox">
                                                <label for="share-googleplus">
                                                    <input type="checkbox" name="share-googleplus" id="share-googleplus" value="1" <?php if ($site->share_googleplus) echo "checked"; ?>>
                                                    Yes
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                </fieldset>

                                <fieldset>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="share-pinterest">Pinterest</label>
                                        <div class="col-md-3 input-group">
                                            <div class="checkbox">
                                                <label for="share-pinterest">
                                                    <input type="checkbox" name="share-pinterest" id="share-pinterest" value="1" <?php if ($site->share_pinterest) echo "checked"; ?>>
                                                    Yes
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                </fieldset>

                                <fieldset>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="share-linkedin">LinkedIn</label>
                                        <div class="col-md-3 input-group">
                                            <div class="checkbox">
                                                <label for="share-linkedin">
                                                    <input type="checkbox" name="share-linkedin" id="share-linkedin" value="1" <?php if ($site->share_linkedin) echo "checked"; ?>>
                                                    Yes
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                </fieldset>

                                <fieldset>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="share-reddit">Reddit</label>
                                        <div class="col-md-3 input-group">
                                            <div class="checkbox">
                                                <label for="share-reddit">
                                                    <input type="checkbox" name="share-reddit" id="share-reddit" value="1" <?php if ($site->share_reddit) echo "checked"; ?>>
                                                    Yes
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                </fieldset>

                                <legend><h2>Google</h2> <small class="help-block">Stuff that will help you monitor your website's traffic.</small></legend>
                                <fieldset>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="google-analytics">Analytics ID</label>  
                                        <div class="col-md-4 input-group">
                                        <input id="google-analytics" name="google-analytics" type="text" placeholder="UA-XXXXX-X" class="form-control input-md" value="<?php _e($site->google_analytics); ?>">
                                        <p class="help-block">Visit <a href="https://www.google.com/analytics" target="_blank">Google Analytics</a> for more details.</p>
                                        </div>
                                    </div>

                                </fieldset>

                                <fieldset>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="google-site-verification">Site Verification Code</label>  
                                        <div class="col-md-4 input-group">
                                        <input id="google-site-verification" name="google-site-verification" type="text" placeholder="Check Google's webmaster tools..." class="form-control input-md" value="<?php _e($site->google_site_verification); ?>">
                                        <p class="help-block">Visit <a href="https://www.google.com/webmasters/tools" target="_blank">Google's Webmaster Tool</a> for more details.</p>
                                        </div>
                                    </div>

                                </fieldset>

                                <fieldset>

                                    <hr>
                                    <!-- Button (Double) -->
                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="button-submit">Save Settings?</label>
                                        <div class="col-md-4">
                                            <button type="sumbit" id="button-submit" name="button-submit" class="btn btn-md btn-success"><strong>Yes, save it!</strong></button>
                                            <a href="index.php" id="button-cancel" name="button-cancel" class="btn btn-md btn-danger">Nope.</a>
                                        </div>
                                    </div>

                                </fieldset>
                            </form>



                        </div>

                </div><!-- /.row -->

<?php
}; // end of pageContent()

include('template.php');
?>