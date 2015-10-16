<?php
require ('core.php');
?>
<!doctype html>
<html class="no-js">
    <head>
        <meta name="google-site-verification" content="<?php _e($site->google_site_verification); ?>" />
        <meta charset="utf-8">
        <title><?php _e($site->title); ?></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">
        <link rel="shortcut icon" href="<?php _e($site->theme_url); ?>favicon.ico">
        <link rel="icon" type="system/image/png" href="<?php _e($site->theme_url); ?>images/favicon.png" />
        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
        <link rel="stylesheet" href="system/_lib/styles/vendor.min.css"/>
        <link rel="stylesheet" href="system/_lib/styles/flexvid.css"/>
        <link rel="stylesheet" href="<?php _e($site->theme_url); ?>styles/main.min.css"/>
        <script src="system/_lib/scripts/vendor/modernizr.min.js"></script>

        <meta property="og:title" content="<?php _e($site->title); ?>"/>
        <meta property="og:image" content="<?php _e($site->theme_url); ?>images/screenshot.png"/>
        <meta property="og:site_name" content="<?php _e($site->title); ?>"/>
        <meta property="og:type" content="portfolio"/>


<!--
========================================================

StyleFol.io by @carloabella (fb.com/carloabella)
Copyright 2014 XFX Virtual Media Interactive, Inc.
http://www.stylefol.io

========================================================
-->


    </head>
    <body>
        <!--[if lt IE 10]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

        <div class="container">

            <nav class="navbar navbar-default" role="navigation">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="#/home">
                            <?php if (!empty($site->site_logo)) { ?>
                            <img src="<?php _e($site->url . $site->uploads_dir . "/static/" . $site->site_logo); ?>">
                            <?php } else { ?>                                
                            <img src="<?php _e($site->theme_url); ?>images/logo.png">
                            <?php } ?>
                        </a>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse">
                        <?php
                            buildMenu($site->menu_data, 0, 0);
                        ?>
                        <div class="navbar-extras">
                            <ul class="socialLinks">
                                <?php if (!empty($site->social_twitter)) { ?><li class="social-twitter"><a href="//www.twitter.com/<?php _e($site->social_twitter); ?>" target="_blank">Follow us on Twitter</a></li><?php } ?>
                                <?php if (!empty($site->social_behance)) { ?><li class="social-behance"><a href="//www.behance.com/<?php _e($site->social_behance); ?>" target="_blank">Behance</a></li><?php } ?>
                                <?php if (!empty($site->social_tumblr)) { ?><li class="social-tumblr"><a href="//<?php _e($site->social_tumblr); ?>.tumblr.com" target="_blank">Tumblr</a></li><?php } ?>
                                <?php if (!empty($site->social_instagram)) { ?><li class="social-instagram"><a href="//www.instagram.com/<?php _e($site->social_instagram); ?>" target="_blank">Instagram</a></li><?php } ?>
                                <?php if (!empty($site->social_linkedin)) { ?><li class="social-linkedin"><a href="//www.linkedin.com/in/<?php _e($site->social_linkedin); ?>" target="_blank">LinkedIn</a></li><?php } ?>
                                <?php if (!empty($site->social_googleplus)) { ?><li class="social-googleplus"><a href="//plus.google.com/+<?php _e($site->social_googleplus); ?>/posts" target="_blank">Google+</a></li><?php } ?>
                                <?php if (!empty($site->social_facebook)) { ?><li class="social-facebook"><a href="//www.facebook.com/<?php _e($site->social_facebook); ?>" target="_blank">Like us on Facebook</a></li><?php } ?>
                                <?php if (!empty($site->social_facebook)) { ?><li class="social-likes"><a href="//www.facebook.com/<?php _e($site->social_facebook); ?>" target="_blank"><?php _e(facebook_like_count($site->social_facebook)); ?> Likes</a></li><?php } ?>
                            </ul>
                            <div class="copyright">
                                <p><?php _e(date('Y')); ?> &copy; <a href="<?php _e($site->url); ?>"><?php _e($site->name); ?></a></p>
                            </div>
                            <div class="poweredby">
                                <a href="<?php _e($site->system_url); ?>" target="_blank" title="Powered by <?php _e($site->system_name); ?>"><img src="<?php _e(SITE_URL); ?>/system/images/poweredby.png"></a>
                            </div>
                        </div> <!-- /.navbar-extras -->
                    </div> <!-- /.navbar-collapse -->
                </div> <!-- /.container-fluid -->
            </nav> <!-- /.navbar -->

            <div class="preloader"><div class="container-fluid"><div class="spinner"><span class="ball-1"></span><span class="ball-2"></span><span class="ball-3"></span><span class="ball-4"></span><span class="ball-5"></span><span class="ball-6"></span><span class="ball-7"></span><span class="ball-8"></span></div></div></div>

            <div class="page">
            </div> <!-- /.page -->

        </div>
        <!-- /.container -->

        <!-- backbone.js templates  -->
        <script type="text/template" id="homepage-template">
        <?php
        if ($site->site_homepage_default) {
            include("system/_inc/home_main.php");
        } else {
            if ($site->site_homepage_target_type == "1")
            { // STATIC PAGE TEMPLATE
                include("system/_inc/home_staticpage.php");
            }

            else if ($site->site_homepage_target_type == "2")
            { // EXTERNAL PAGE TEMPLATE
                include("system/_inc/home_page.php");
            }

            else if ($site->site_homepage_target_type == "3")
            { // SLIDESHOW PRESENTATION TEMPLATE
                echo '<% var sid = "' . $site->site_homepage_target . '" %>' . "\n";
                include("system/_inc/home_slideshowstream.php");
            }

            else if ($site->site_homepage_target_type == "4")
            { // SLIDESHOW GRID TEMPLATE 
                echo '<% var sid = "' . $site->site_homepage_target . '" %>' . "\n";
                include("system/_inc/home_slideshow.php");
            }

            else if ($site->site_homepage_target_type == "5")
            { // GALLERY GRID TEMPLATE
                // get first slideshow of gallery
                $gid = $site->site_homepage_target;
                $sid = getGalleryDefaultSlideshow($gid);
                echo '<% var sid = "' . $sid . '" %>' . "\n";
                echo '<% var gid = "' . $gid . '" %>' . "\n";
                include("system/_inc/home_gallerystream.php");
            }

            else if ($site->site_homepage_target_type == "6")
            { // GALLERY PRESENTATION TEMPLATE
                echo '<% var sid = "0" %>' . "\n";
                echo '<% var gid = "' . $site->site_homepage_target . '" %>' . "\n";
                include("system/_inc/home_gallery.php");
            }
        }
        ?>
        </script>

        <script type="text/template" id="staticpage-template">
        <?php include("system/_inc/tpl_staticpage.php"); ?>
        </script>

        <script type="text/template" id="externalpage-template">
        <?php include("system/_inc/tpl_externalpage.php"); ?>
        </script>

        <script type="text/template" id="slideshow-template">
        <?php include("system/_inc/tpl_slideshow.php"); ?>
        </script>

        <script type="text/template" id="slideshowstream-template">
        <?php include("system/_inc/tpl_slideshowstream.php"); ?>
        </script>

        <script type="text/template" id="gallery-template">
        <?php include("system/_inc/tpl_gallery.php"); ?>
        </script>

        <script type="text/template" id="gallerystream-template">
        <?php include("system/_inc/tpl_gallerystream.php"); ?>
        </script>
        <!-- end backbone.js templates -->

        <script src="system/_lib/scripts/vendor.min.js"></script>

        <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
        <script>
            (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
            function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
            e=o.createElement(i);r=o.getElementsByTagName(i)[0];
            e.src='//www.google-analytics.com/analytics.js';
            r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
            ga('create','<?php _e($site->google_analytics); ?>');ga('send','pageview');
        </script>

        <script src="system/_lib/scripts/plugins.min.js"></script>

        <script>$.ajaxPrefilter( function( options, originalOptions, jqXHR ) { options.url = '<?php _e($site->url); ?>system/rest' + options.url; });</script>

        <script src="<?php _e($site->theme_url); ?>scripts/main.min.js"></script>
</body>
</html>
