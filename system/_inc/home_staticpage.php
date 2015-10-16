            <!-- STATIC PAGE TEMPLATE -->
            <div class="container-fluid">
                <!-- PAGE HEADER NOT NEEDED -->
                <div class="page-content">
                    <div class="page-static">
                    <% _.each(homepage, function(spage) { %>
                    <%= spage.get('content') %>
                    <% }); %>
                    </div>

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
                if ($('.accordion').length > 0) {

                    $(function(){
                        console.log("Accordion initiated --- CONTENT");

                        var allPanels = $('.accordion ul').hide();
                        $('.accordion ul').first().show();

                        $('.accordion h4').on('click', function(e) {
                            console.log('Accordion header click');
                            var grp = $(this).next();
                            console.log(grp);
                            $('.accordion ul').slideUp('500');
                            $(this).next().slideDown('500');
                            return false;
                        });

                    });
                }

                if ($('#contactForm').length > 0) {
                    $('#contactForm').bootstrapValidator({
                        message: 'This value is not valid',
                        feedbackIcons: {
                            valid: 'glyphicon glyphicon-ok',
                            invalid: 'glyphicon glyphicon-remove',
                            validating: 'glyphicon glyphicon-refresh'
                        },
                        fields: {
                            name: {
                                validators: {
                                    notEmpty: {
                                        message: 'Your name is required'
                                    }
                                }
                            },
                            email: {
                                validators: {
                                    notEmpty: {
                                        message: 'Your email address is required'
                                    }
                                }
                            },
                            subject: {
                                validators: {
                                    notEmpty: {
                                        message: 'Subject is required'
                                    }
                                }
                            },
                            message: {
                                validators: {
                                    notEmpty: {
                                        message: 'Message is required'
                                    }
                                }
                            }
                        },
                        submitHandler: function(validator, form, submitButton) {
                            console.log('sending contact form...');
                            var serializedData = form.serialize();
                            var $inputs = form.find("input, select, button, textarea");
                            $inputs.prop("disabled", true);
                            // Use Ajax to submit form data
                            var contactRequest = $.ajax({
                                type: "POST",
                                url: "/contact-form-send.php",
                                data: serializedData,
                                dataType: "json"
                            });

                            contactRequest.done(function( data ) {
                               console.log( "Data Loaded: " + data );
                               $('#contactForm').prepend('<div class="alert alert-success">Email was sent successfully.</div>');
                            });
                        }
                    });
                }

                showPage();

                navbarAccordion('#responsive-nav');
            </script>