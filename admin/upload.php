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
$page->pageTitle = "Upload";
$page->pageSlug = "upload";
$page->pageIcon = "fa-upload";
$page->pageParent = "Image Manager";
$page->pageParentSlug = "media";
$page->pageParentIcon = "fa-th";
$page->pageDescription = "";
$page->pageExcerpt = "";
$page->pageReferal = urlencode(curPageURL());
$page->pageIncludes = <<< EOI
<!-- FILE UPLOAD -->
<link rel="stylesheet" href="uploader/css/blueimp-gallery.min.css">
<link rel="stylesheet" href="uploader/css/jquery.fileupload.css">
<link rel="stylesheet" href="uploader/css/jquery.fileupload-ui.css">

<!-- CSS adjustments for browsers with JavaScript disabled -->
<noscript><link rel="stylesheet" href="uploader/css/jquery.fileupload-noscript.css"></noscript>
<noscript><link rel="stylesheet" href="uploader/css/jquery.fileupload-ui-noscript.css"></noscript>

<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="uploader/js/vendor/jquery.ui.widget.js"></script>
<!-- The Templates plugin is included to render the upload/download listings -->
<script src="uploader/js/tmpl.min.js"></script>
<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
<script src="uploader/js/load-image.min.js"></script>
<!-- The Canvas to Blob plugin is included for image resizing functionality -->
<script src="uploader/js/canvas-to-blob.min.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="uploader/js/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="uploader/js/jquery.fileupload.js"></script>
<!-- The File Upload processing plugin -->
<script src="uploader/js/jquery.fileupload-process.js"></script>
<!-- The File Upload image preview & resize plugin -->
<script src="uploader/js/jquery.fileupload-image.js"></script>
<!-- The File Upload audio preview plugin -->
<script src="uploader/js/jquery.fileupload-audio.js"></script>
<!-- The File Upload video preview plugin -->
<script src="uploader/js/jquery.fileupload-video.js"></script>
<!-- The File Upload validation plugin -->
<script src="uploader/js/jquery.fileupload-validate.js"></script>
<!-- The File Upload user interface plugin -->
<script src="uploader/js/jquery.fileupload-ui.js"></script>
<!-- The main application script -->
<script src="uploader/js/main.js"></script>
<!-- The XDomainRequest Transport is included for cross-domain file deletion for IE 8 and IE 9 -->
<!--[if (gte IE 8)&(lt IE 10)]>
<script src="uploader/js/cors/jquery.xdr-transport.js"></script>
<![endif]-->

<!-- WYSI HTML5 -->
<link rel="stylesheet" type="text/css" href="src/bootstrap-wysihtml5.css" />
<script src="lib/js/wysihtml5-0.3.0.js"></script>
<script src="src/bootstrap3-wysihtml5.js"></script>

<!-- VALIDATE FORM -->
<script src="js/jquery.validate.min.js"></script>
<script src="js/jquery.validate.additional-method.js"></script>

<script>
    var t_photoupload_ready = false;
    var t_photoupload_error_slideshow = false;
    var t_photoupload_error_category = false;


    $(function () {
        'use strict';


        $('#photo_fileupload .btn-lg').prop('disabled', true);


        $('#slideshow-select').change(function(e){
            var optionSelected = $('#slideshow-select option:selected', this);
            var valueSelected = this.value;
            $('.slideshow-hide').val(valueSelected);
            $('.slideshow-new-hide').val("");
            if (valueSelected == "new") {
                $('#slideshow-new-box').show();
                $('#slideshow-new').val("");

                t_photoupload_error_slideshow = false;
            } else {
                $('#slideshow-new-box').hide();

                t_photoupload_error_slideshow = true;
            }

            if (t_photoupload_error_slideshow == true && t_photoupload_error_category == true) {
                t_photoupload_ready = true;
                $('#photo_fileupload .btn-lg').prop('disabled', false);
            }
            else {
                t_photoupload_ready = false;
                $('#photo_fileupload .btn-lg').prop('disabled', true);
            }

            console.log(t_photoupload_ready);
        });

        $('#slideshow-new').on('input',function(){
            var txt_is_ok = 0;
            $('.slideshow-new-hide').val($('#slideshow-new').val());
        });

        $('#slideshow-new').change(function(){
            if ($(this).val().length > 3) {
                t_photoupload_error_slideshow = true;
            } else {
                t_photoupload_error_slideshow = false;
            }

            if (t_photoupload_error_slideshow == true && t_photoupload_error_category == true) {
                t_photoupload_ready = true;
                $('#photo_fileupload .btn-lg').prop('disabled', false);
            }
            else {
                t_photoupload_ready = false;
                $('#photo_fileupload .btn-lg').prop('disabled', true);
            }

            console.log(t_photoupload_ready);
        });
        $('.categoryGroup input[type=checkbox]').change(function(e){
            var t_categories = "";

            if ($('.categoryGroup input[type=checkbox]:checked').length>0) {
                t_photoupload_error_category = true;
            } else {
                t_photoupload_error_category = false;
            }

            if (t_photoupload_error_slideshow == true && t_photoupload_error_category == true) {
                t_photoupload_ready = true;
                $('#photo_fileupload .btn-lg').prop('disabled', false);
            }
            else {
                t_photoupload_ready = false;
                $('#photo_fileupload .btn-lg').prop('disabled', true);
            }

            $('.categoryGroup input[type=checkbox]:checked').each(function(i){
                if (t_categories.length>0) {
                    t_categories = t_categories + ";" + $(this).val();
                }
                else {
                    t_categories = $(this).val();
                }
            });
            $('#category-collect').val(t_categories);
            $('.categories-hide').val(t_categories);

            console.log(t_categories);

            console.log(t_photoupload_ready);
        });

        $('#photoupload-reset').on('click',function() {
            $('#slideshow-new-box').show();
            $('#slideshow-select').val("new");
            $('#slideshow-new').val("");
            t_photoupload_error_slideshow = false;

            $('#category-collect').val("");
            $('.categories-hide').val("");
            t_photoupload_error_category = false;
            $('.categoryGroup input[type=checkbox]').attr("checked", false);

            t_photoupload_ready = false;
            $('#photo_fileupload .btn-lg').prop('disabled', true);
        })
    });
</script>
EOI;
// END OF INCLUDES

function pageContent($page)
{
?>

                <?php

                // Start Database connection
                global $db;
                global $site;

                ?>

                <div class="row">
                    <div class="col-lg-12">
                        <h1><?php _e($page->pageTitle); ?> <small><?php _e($page->pageDescription); ?></small></h1>
                        <ol class="breadcrumb">
                            <li><a href="index.php"><i class="fa fa-dashboard"></i>Dashboard</a></li>
                            <li><a href="photos.php"><i class="fa <?php _e($page->pageParentIcon); ?>"></i><?php _e($page->pageParent); ?></a></li>
                            <li class="active"><i class="fa <?php _e($page->pageIcon); ?>"></i><?php _e($page->pageTitle); ?></li>
                        </ol>
                    </div>

                    <div class="col-lg-12">
                        <form name="fileupload" id="photo_fileupload" class="fileupload form" role="form" action="<?php _e(SITE_URL . "/" . SITE_ADMIN . "/"); ?>uploader/php/" method="post" enctype="multipart/form-data" target="_self">
                            <div class="col-xs-12 col-sm-12 col-lg-4">
                                
                                <div class="form-group">
                                    <label for="slideshow">Slideshow</label>
                                    <select name="slideshow" id="slideshow-select" class="form-control">
                                        <option value="new">New slideshow</option>
                                        <optgroup label="Or select existing slideshow...">
                                            <?php
                                            $q_status = '2';
                                            $table = 'slideshows';
                                            $q = "SELECT * FROM $table";
                                            $q .= " WHERE (`status`='$q_status')";
                                            $q .= " ORDER BY `id` DESC";
                                            $r = $db->query($q);
                                            while ($a = $db->fetch_array_assoc($r)) {
                                            ?>
                                            <option value="<?php _e($a['id']); ?>"><?php _e($a['title']); ?></option>
                                        <?php } ?>
                                        </optgroup>
                                    </select>
                                </div>
                                <div class="form-group" id="slideshow-new-box">
                                    <input type="text" id="slideshow-new" name="slideshow-new" class="form-control" placeholder="New Slideshow Title...">
                                </div>

                                <div class="form-group">
                                    <label>Category</label>

                                    <div class="categoryGroup">
                                        <?php
                                        // set db query
                                        $q2 = "SELECT * FROM `categories` ORDER BY `title` ASC";
                                        $r2 = $db->query($q2);

                                        // parse category options
                                        $categories = explode(";", $a['category']);
                                        ?>
                                        <?php while ($a2 = $db->fetch_array_assoc($r2)) { ?>
                                        <div class="col-xs-6 col-sm-4"><label><input type="checkbox" name="category[]" value="<?php _e($a2['id']); ?>" <?php if (isCategory($categories,$a2['id'])) _e("checked"); ?>> <?php _e($a2['title']); ?></label></div>
                                        <?php } ?>
                                        <input type="hidden" id="category-collect" name="categories_collect" value="">
                                    </div>
                                </div>

                                <div class="form-buttons fileupload-buttonbar">
                                    <!-- The fileinput-button span is used to style the file input field as button -->
                                    <span class="btn btn-success btn-lg fileinput-button">
                                            <i class="glyphicon glyphicon-plus"></i>
                                            <span>Add photos...</span>
                                            <input type="file" name="files[]" multiple>
                                    </span>
                                    <button type="submit" class="btn btn-primary btn-lg start">
                                        <i class="glyphicon glyphicon-upload"></i>
                                        <span>Upload photos</span>
                                    </button>
                                    <button id="photoupload-reset" type="reset" class="btn btn-info btn-lg">
                                        <i class="fa fa-refresh"></i>
                                        <span>Reset</span>
                                    </button>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-lg-8">
                                <!-- Redirect browsers with JavaScript disabled to the origin page -->
                                <noscript><input type="hidden" name="redirect" value="<?php _e(SITE_URL); ?>"></noscript>

                                <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
                                <div class="row fileupload-buttonbar">
                                    <div class="col-lg-6">
                                        <button type="reset" class="btn btn-warning cancel">
                                            <i class="glyphicon glyphicon-ban-circle"></i>
                                            <span>Clear Queue</span>
                                        </button>
                                        <!-- The global file processing state -->
                                        <span class="fileupload-process"></span>
                                    </div>

                                    <div class="clearfix"></div>

                                    <!-- The global progress state -->
                                    <div class="col-lg-12 fileupload-progress fade">
                                        <!-- The global progress bar -->
                                        <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                                                <div class="progress-bar progress-bar-success" style="width:0%;"></div>
                                        </div>
                                        <!-- The extended global progress state -->
                                        <div class="progress-extended">&nbsp;</div>
                                    </div> <!-- /.fileupload-buttonbar -->

                                </div> <!-- /.fileupload-buttonbar -->
                                <div class="photo-grid uploadePreview" role="presentation">
                                    <div class="photo-manager-box-container"><div class="files"></div></div>
                                    <div class="clearfix"></div>
                                </div> <!-- /.photo-grid -->
                            </div> <!-- /.col-xs-12 col-sm-12 col-lg-8 -->
                        </form> <!-- /#photo_extrafields -->

                    </div> <!-- /.col-lg-12 -->

                </div><!-- /.row -->

<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}


    <div class="grid grid-upload template-upload">
        <div class="grid-content">
            <div class="picture">
                <span class="preview"></span>
            </div>
            <div class="pictureDetails">
                <p>
                    <input required class="photoTitle" name="title[]" size="50" style="width:100%" placeholder="Title for your photo...">
                </p>
                <p>
                    <textarea name="description[]" cols="50" rows="2" style="width:100%" placeholder="Add description (optional)"></textarea>
                </p>
                <input class="slideshow-hide" type="hidden" name="slideshow[]" value="{%=$('#slideshow-select option:selected').val()%}">
                <input class="slideshow-new-hide" type="hidden" name="slideshow_new[]" value="{%=$('#slideshow-new').val()%}">
                <input class="categories-hide" type="hidden" name="categories[]" value="{%=$('#category-collect').val()%}">
            </div>
            <div class="grid-upload-processing">
                <div class="grid-upload-progress">
                    <div class="size">Processing...</div>
                    <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
                    <div class="clearfix"></div>
                </div>
                <button class="btn btn-primary start hidden" disabled>
                        <i class="glyphicon glyphicon-upload"></i>
                        <span></span>
                </button>
                <div class="grid-upload-buttons">
                    <button class="btn btn-warning cancel">
                            <i class="glyphicon glyphicon-ban-circle"></i>
                            <span></span>
                    </button>
                </div>
            </div>
        </div> <!-- /.grid-content -->
    </div> <!-- /.grid -->
{% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}

    <div class="grid" data-id="{%=file.id%}">
        <div class="grid-content">
            <div class="picture">
                <img src="<?php _e(SITE_URL . "/" . SITE_UPLOADS); ?>/preview/{%=file.name%}" width="<?php _e($site->gallery_prev_width); ?>">
            </div>
            <div class="pictureTitle">
                <div class="title">
                {% if (file.title !== "") { %}
                {%=file.title%}</div>
                <div class="filename"><i class="fa fa-file-o"></i> {%=file.name%}</div>
                {% } else { %}
                {%=file.name%}</div>
                {% } %}
            </div>
            <div class="pictureDetails">
                <div class="description"><i class="fa fa-quote-left"></i> {%=file.description%}</div>
                <div class="date">{%=file.timestamp%}</div>
                <ul class="tableHoverMenu">
                    <li class="tableHoverMenuPrimary"><a data-toggle="modal" data-target="#modal" class=""  href="photo-edit.php?id={%=file.id%}&ref=upload.php">Edit</a></li>
                    <li class="tableHoverMenuWarning"><a data-toggle="modal" data-target="#modal" class="" href="photo-del.php?id={%=file.id%}&ref={%=file.id%}">Delete</a></li>
                </ul>
            </div>
        </div> <!-- /.grid-content -->
    </div> <!-- /.grid -->
{% } %}
</script>

<?php
}; // end of pageContent()

include('template.php');
?>