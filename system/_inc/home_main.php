            <!-- HOME TEMPLATE -->
            <div class="container-fluid">
                <!-- PAGE HEADER NOT NEEDED -->
                    <% _.each(homepage, function(hero) { %>
                    <div class="hero-picture"><img src="<%= hero.get('hero') %>"></div>
                    <% }); %>

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
            </div> <!-- /.container-fluid -->
            <div class="clear"></div>
            <script>

                preload_pictures([
                    <% _.each(homepage, function(hero) { %>
                    '<%= hero.get('hero') %>',
                    <% }); %>
                ], function(){
                    var $theImage = $('.hero-picture img');
                    var bgUrl = $theImage.attr('src').replace(/ /g, '%20');
                    $('.hero-picture').css({
                        'background-image': 'url("' + bgUrl + '")'
                    });

                    showPage();

                    navbarAccordion('#responsive-nav');
                });
            </script>