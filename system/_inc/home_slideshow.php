            <!-- SLIDESHOW GRID TEMPLATE - HOME -->
            <div class="container-fluid">
                <!-- PAGE HEADER NOT NEEDED -->
                <div class="page-content">
                    <div class="grid">
                        <ul class="packery">
                            <% _.each(homepage['0'].get('previews'), function(prevphoto,i) { %>
                                <li class="item"><a href="#/slideshow/<%= sid %>/<%=i+1%>"><img src="<%= prevphoto %>" width="<?php _e($site->gallery_prev_width . "px"); ?>"></a></li>
                            <% }) %>
                        </ul>
                    </div> <!-- /.grid -->

                    <div class="responsiveNav">
                        <a class="navbar-brand" href="#/home">
                            <?php if (!empty($site->site_logo)) { ?>
                            <img src="<?php _e($site->url . $site->uploads_dir . "/static/" . $site->site_logo); ?>">
                            <?php } else { ?>                                
                            <img src="<?php _e($site->theme_url); ?>images/logo.png">
                            <?php } ?>
                        </a>
                        <?php
                            buildMenu($site->menu_data, "responsive-nav", 0, 0);
                        ?>
                        <div class="navbar-extras">
                            <ul class="socialLinks">
                                <?php if (!empty($site->social_twitter)) { ?><li class="social-twitter"><a href="//www.twitter.com/<?php _e($site->social_twitter); ?>" target="_blank">Follow us on Twitter</a></li><?php } ?>
                                <?php if (!empty($site->social_behance)) { ?><li class="social-behance"><a href="//www.behance.com/<?php _e($site->social_behance); ?>" target="_blank">Behance</a></li><?php } ?>
                                <?php if (!empty($site->social_facebook)) { ?><li class="social-facebook"><a href="//www.facebook.com/<?php _e($site->social_facebook); ?>" target="_blank">Like us on Facebook</a></li><?php } ?>
                                <?php if (!empty($site->social_facebook)) { ?><li class="social-likes"><a href="//www.facebook.com/<?php _e($site->social_facebook); ?>" target="_blank"><?php _e(facebook_like_count($site->social_facebook)); ?> Likes</a></li><?php } ?>
                            </ul>
                            <div class="copyright"><p><?php _e(date('Y')); ?> &copy; <a href="<?php _e($site->url); ?>">JanGonzales.com</a></p></div>
                        </div> <!-- /.navbar-extras -->
                    </div> <!-- /.responsiveNav -->

                </div> <!-- /.page-content -->
            </div> <!-- /.container-fluid -->
            <div class="clear"></div>
            <script>
                var packeryContainer = document.querySelector('.packery');
                var pckry = new Packery( packeryContainer );

                var image_list = new Array(
                    <% _.each(homepage['0'].get('previews'), function(prevphoto,i) { %>
                    '<%= prevphoto %>',
                    <% }) %>
                    <% _.each(homepage['0'].get('thumbnails'), function(thphoto,i) { %>
                    '<%= thphoto %>',
                    <% }) %>
                    'system/images/blank.gif'
                    );

                preload_pictures(image_list, function(){
                    pckry.layout();

                    showPage();

                    navbarAccordion('#responsive-nav');
                });
            </script>