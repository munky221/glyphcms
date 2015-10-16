            <!-- EXTERNAL PAGE TEMPLATE -->
            <div class="container-fluid">
                <!-- PAGE HEADER NOT NEEDED -->
                <div class="page-content">
                    <% _.each(externalpage, function(xpage) { %>
                    <iframe class="externalFrame" src="<%= xpage.get('xurl') %>"></iframe>
                    <!-- Close your eyes give me your hand, darling. Do you feel my heart beating? Do you understand? Do you feel the same? Am I only dreaming. Is this burning an external frame... -->
                    <% }) %>

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
            <script type="text/javascript">

                $('.externalFrame').iFrameResize({
                    log                     : true,                  // Enable console logging
                    enablePublicMethods     : true,                  // Enable methods within iframe hosted page 
                    resizedCallback         : function(messageData){ // Callback fn when message is received
                        console.log(
                            '<b>Frame ID:</b> '    + messageData.iframe.id +
                            ' <b>Height:</b> '     + messageData.height +
                            ' <b>Width:</b> '      + messageData.width + 
                            ' <b>Event type:</b> ' + messageData.type
                        );
                    }
                });

                showPage();

                navbarAccordion('#responsive-nav');


            </script>
