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
$page->pageTitle = "Media Settings";
$page->pageSlug = "media-settings";
$page->pageIcon = "fa-gear";
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

                ?>

                <div class="row">
                        <div class="col-lg-12">
                                <h1><?php _e($page->pageTitle); ?> <small><?php _e($page->pageDescription); ?></small></h1>
                                <ol class="breadcrumb">
                                        <li><a href="index.php"><i class="fa fa-dashboard"></i>Dashboard</a></li>
                                        <li class="active"><i class="fa <?php _e($page->pageIcon); ?>"></i><?php _e($page->pageTitle); ?></li>
                                </ol>
                                <div class="alert alert-warning alert-dismissable">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <p>Setting the image sizes only affects future uploaded images. Your old images will remain unaffected by the changes you are going to make here. Currently there is no way of changing the sizes/quality of old images. If you really want to change the sizes/quality of your old images, we suggest re-uploading all the images again with the appropriate settings.</p>
                                </div>
                        </div>

                        <div class="col-lg-12">

                            <form class="form form-horizontal" action="media-settings-action.php" role="form" method="post" enctype="multipart/form-data" target="_self">
                            
                                    <input type="hidden" name="t" value="media-settings">
                                    <input type="hidden" name="ref" value="<?php _e($page->pageReferal); ?>">

                                <legend><h2>Thumbnails</h2> <small class="help-block">Thumbnail sizes are used for the filmstrip on both gallery and slideshow presentations.</small></legend>
                                <fieldset>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="thumb-width">Width</label>  
                                        <div class="col-md-3 input-group">
                                        <input id="thumb-width" name="thumb-width" type="number" placeholder="80" class="form-control input-md" value="<?php _e($site->gallery_thumb_width); ?>" required max="80">
                                        <span class="input-group-addon">*</span>
                                        </div>
                                        <p class="help-block">Maximum of 80px</p>
                                    </div>

                                </fieldset>

                                <fieldset>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="thumb-height">Height</label>  
                                        <div class="col-md-3 input-group">
                                        <input id="thumb-height" name="thumb-height" type="number" placeholder="80" class="form-control input-md" value="<?php _e($site->gallery_thumb_height); ?>" required max="80">
                                        <span class="input-group-addon">*</span>
                                        </div>
                                        <p class="help-block">Maximum of 80px</p>
                                    </div>

                                </fieldset>

                                <fieldset>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="thumb-quality">Quality</label>  
                                        <div class="col-md-3 input-group">
                                        <input id="thumb-quality" name="thumb-quality" type="number" placeholder="70" class="form-control input-md" value="<?php _e($site->gallery_thumb_quality); ?>" required max="100">
                                        <span class="input-group-addon">*</span>
                                        </div>
                                    </div>

                                </fieldset>

                                <fieldset>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="thumb-crop">Crop To Size</label>
                                        <div class="col-md-3">
                                            <div class="checkbox">
                                                <label for="thumb-crop">
                                                    <input type="checkbox" name="thumb-crop" id="thumb-crop" value="1" <?php if ($site->gallery_thumb_crop) echo "checked"; ?>>
                                                    Yes
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                </fieldset>

                                <legend><h2>Preview</h2> <small class="help-block">The preview images are the ones displayed on the slideshow / gallery page grid.</small></legend>
                                <fieldset>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="prev-width">Width</label>  
                                        <div class="col-md-3 input-group">
                                        <input id="prev-width" name="prev-width" type="number" placeholder="230" class="form-control input-md" value="<?php _e($site->gallery_prev_width); ?>" required>
                                        <span class="input-group-addon">*</span>
                                        </div>
                                    </div>

                                </fieldset>

                                <fieldset>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="prev-height">Height</label>  
                                        <div class="col-md-3 input-group">
                                        <input id="prev-height" name="prev-height" type="number" placeholder="345" class="form-control input-md" value="<?php _e($site->gallery_prev_height); ?>" required>
                                        <span class="input-group-addon">*</span>
                                        </div>
                                    </div>

                                </fieldset>

                                <fieldset>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="prev-quality">Quality</label>  
                                        <div class="col-md-3 input-group">
                                        <input id="prev-quality" name="prev-quality" type="number" placeholder="70" class="form-control input-md" value="<?php _e($site->gallery_prev_quality); ?>" required max="100">
                                        <span class="input-group-addon">*</span>
                                        </div>
                                    </div>

                                </fieldset>

                                <fieldset>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="prev-crop">Crop To Size</label>
                                        <div class="col-md-3">
                                            <div class="checkbox">
                                                <label for="prev-crop">
                                                    <input type="checkbox" name="prev-crop" id="prev-crop" value="1" <?php if ($site->gallery_prev_crop) echo "checked"; ?>>
                                                    Yes
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                </fieldset>

                                <legend><h2>Full Size</h2> <small class="help-block"></small></legend>
                                <fieldset>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="photo-width">Width</label>  
                                        <div class="col-md-3 input-group">
                                        <input id="photo-width" name="photo-width" type="number" placeholder="1200" class="form-control input-md" value="<?php _e($site->gallery_photo_width); ?>" required>
                                        <span class="input-group-addon">*</span>
                                        </div>
                                    </div>

                                </fieldset>

                                <fieldset>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="photo-height">Height</label>  
                                        <div class="col-md-3 input-group">
                                        <input id="photo-height" name="photo-height" type="number" placeholder="800" class="form-control input-md" value="<?php _e($site->gallery_photo_height); ?>" required>
                                        <span class="input-group-addon">*</span>
                                        </div>
                                    </div>

                                </fieldset>

                                <fieldset>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="photo-quality">Quality</label>  
                                        <div class="col-md-3 input-group">
                                        <input id="photo-quality" name="photo-quality" type="number" placeholder="70" class="form-control input-md" value="<?php _e($site->gallery_photo_quality); ?>" required max="100">
                                        <span class="input-group-addon">*</span>
                                        </div>
                                    </div>

                                </fieldset>

                                <fieldset>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="photo-crop">Crop To Size</label>
                                        <div class="col-md-3">
                                            <div class="checkbox">
                                                <label for="photo-crop">
                                                    <input type="checkbox" name="photo-crop" id="photo-crop" value="1" <?php if ($site->gallery_photo_crop) echo "checked"; ?>>
                                                    Yes
                                                </label>
                                            </div>
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
                                            <a href="photos.php" id="button-cancel" name="button-cancel" class="btn btn-md btn-danger">Nope.</a>
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