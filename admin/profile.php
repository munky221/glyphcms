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
$page->pageTitle = "User Profile";
$page->pageSlug = "user-profile";
$page->pageIcon = "fa-user";
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
                $uid = $_SESSION['user_id'];
                $q = "SELECT * FROM `users` WHERE `user_id` = '$uid'";
                $r = $db->query($q);
                $a = $db->fetch_array_assoc($r);
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

                            <?php // var_dump($_SESSION); ?>

                            <form class="form form-horizontal" action="profile-action.php" role="form" method="post" enctype="multipart/form-data" target="_self">
                            
                                <input type="hidden" name="t" value="profile">
                                <input type="hidden" name="ref" value="<?php _e($page->pageReferal); ?>">

                                <legend><h2>Personal Information</h2> <small class="help-block">Your general information.</small></legend>

                                <fieldset>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="profile-firstname">First Name</label>  
                                        <div class="col-md-4 input-group">
                                        <input id="profile-firstname" name="first_name" type="text" placeholder="Your first name..." class="form-control input-md" value="<?php _e($a['first_name']); ?>" required maxlength="60">
                                        <span class="input-group-addon">*</span>
                                        </div>
                                    </div>

                                </fieldset>

                                <fieldset>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="profile-lastname">Last Name</label>  
                                        <div class="col-md-4 input-group">
                                        <input id="profile-lastname" name="last_name" type="text" placeholder="Your last name..." class="form-control input-md" value="<?php _e($a['last_name']); ?>" required maxlength="60">
                                        <span class="input-group-addon">*</span>
                                        </div>
                                    </div>

                                </fieldset>

                                <fieldset>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="profile-nicename">Alias</label>  
                                        <div class="col-md-4 input-group">
                                        <input id="profile-nicename" name="nice_name" type="text" placeholder="Your first name..." class="form-control input-md" value="<?php _e($a['nice_name']); ?>" maxlength="32">
                                        </div>
                                        <div class="col-md-3"></div><div class="col-md-4 input-group"><p class="help-block text-muted">A nice name you want others to call you by.</p></div>
                                    </div>

                                </fieldset>

                                <fieldset>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="profile-email">Email Address</label>  
                                        <div class="col-md-4 input-group">
                                        <input id="profile-email" name="user_email" type="text" placeholder="example: mail@domain.com" class="form-control input-md" value="<?php _e($a['user_email']); ?>" required>
                                        <span class="input-group-addon">*</span>
                                        </div>
                                        <div class="col-md-3"></div><div class="col-md-4 input-group"><p class="help-block text-muted">Used for reseting passwords in case you forget them. Also used for your avatar provided by <a href="http://www.gravatar.com" target="_blank">Gravatar</a>.</p></div>
                                    </div>

                                </fieldset>

                                <legend><h2>Security</h2> <small class="help-block">Leave the fields blank if you don't want to change your password.</small></legend>

                                <fieldset>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Username</label>  
                                        <div class="col-md-4 input-group">
                                        <span class="form-control input-md"><strong><?php _e($a['user_name']); ?></strong></span>
                                        </div>
                                    </div>

                                </fieldset>

                                <fieldset>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="profile-password">New Password</label>  
                                        <div class="col-md-4 input-group">
                                        <input id="profile-password" name="password" type="password" class="form-control input-md" autocomplete="off" maxlength="120">
                                        </div>
                                        <div class="col-md-3"></div><div class="col-md-4 input-group"><p class="help-block text-muted">Minimum of 6 and maximum of 120 characters.</p></div>
                                    </div>

                                </fieldset>

                                <fieldset>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="profile-password2">Re-type New Password</label>  
                                        <div class="col-md-4 input-group">
                                        <input id="profile-password2" name="password2" type="password" class="form-control input-md" autocomplete="off" maxlength="120">
                                        </div>
                                    </div>

                                </fieldset>

                                <fieldset>

                                    <hr>
                                    <!-- Button (Double) -->
                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="profile-oldpassword">Your Current Password</label>
                                        <div class="col-md-4 input-group">
                                        <input id="profile-oldpassword" name="old_password" type="password" placeholder="************" class="form-control input-md" autocomplete="off" maxlength="120" required>
                                        <span class="input-group-addon">*</span>
                                        </div>
                                    </div>
                                        
                                        <label class="col-md-3 control-label" for="button-submit"></label>
                                        <div class="col-md-4">
                                            <label for="button-submit"><strong>Update Your Profile?</strong></label><br>
                                            <button type="sumbit" id="button-submit" name="button-submit" class="btn btn-md btn-success"><strong>Yes, please!</strong></button>
                                            <a href="index.php" id="button-cancel" name="button-cancel" class="btn btn-md btn-danger">Nope.</a>
                                        </div>

                                </fieldset>
                            </form>



                        </div>

                </div><!-- /.row -->

<?php
}; // end of pageContent()

include('template.php');
?>