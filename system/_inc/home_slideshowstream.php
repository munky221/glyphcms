            <!-- SLIDESHOW PRESENTATION TEMPLATE - HOME -->
            <div class="container-fluid">
                <div id="carousel">
                    <div class="carousel-controls">
                        <a class="carousel-prev" href="#/prev-slide"><i class="glyphicon glyphicon-chevron-left"></i></a>
                        <a class="carousel-thumbs" href="#/slideshow/<%= sid %>"><i class="glyphicon glyphicon-th-large"></i></a>
                        <a class="carousel-next" href="#/next-slide"><i class="glyphicon glyphicon-chevron-right"></i></a>
                    </div>
                    <% _.each(homepage, function(slide) { %>
                    <% if (slide.get('slide_id') == sid) { %>
                    <?php if (!$site->display_slider_filmstrip) { ?>
                    <% if (slide.get('show_filmstrip') == "1") { %>
                    <?php } ?>
                    <div id="carousel-filmstrip">
                        <ul>
                            <% _.each(homepage['0'].get('thumbnails'), function(thsrc,i) { %>
                                <% if (homepage['0'].get('slide_id') == sid) { %>
                                <li data-id="<%=i+1%>"><a href="#/slideshow/<%=sid%>/<%=i+1%>"><img src="<%= thsrc %>"></a></li>
                                <% } %>
                            <% }); %>
                        </ul>
                    </div>
                    <?php if (!$site->display_slider_filmstrip) { ?>
                    <% } %>
                    <?php } ?>
                    <% } %>
                    <% }) %>
                    <ul id="carousel-slides">
                        <li data-id="0" class="prevSlide"><img src="system/images/blank.gif" width="1" height="1"></li>
                            
                            
                            <% var i = 0; _.each(homepage, function(slide) { %>
                                <% _.each(slide.get('photos'), function(src) { i++ %>
                                    <% if (slide.get('slide_id') == sid) { %>

                                <li data-id="<%=i+1%>">
                                    <?php if (!$site->display_slider_infobar) { ?>
                                    <% if (slide.get('show_infobar') == "1") { %>
                                    <?php } ?>
                                    <div class="carousel-infobar">
                                        <div class="carousel-infobar-collapse">
                                            <div class="col-xs-6 col-sm-6 col-lg-6 carousel-infobar-title">
                                                <h1><%= slide.get('slide_title') %></h1>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-lg-6 carousel-infobar-share">
                                                <ul>
                                                    <li class="share-twitter"><a href="https://twitter.com/share/?url=<%= encodeURIComponent($(location).attr('href')) %>&media=<%= src %>&description=<%= slide.get('slide_title') %>" target="_blank">Twitter</a></li>
                                                    <li class="share-facebook"><a href="http://www.facebook.com/sharer.php?s=100&p[title]=<%= slide.get('slide_title') %>&p[summary]=<%= slide.get('slide_title') %>&p[url]=<%= encodeURIComponent($(location).attr('href')) %>&p[images][0]=<%= src %>" target="_blank">Facebook</a></li>
                                                    <li class="share-google"><a href="https://plus.google.com/share?url=<%= encodeURIComponent($(location).attr('href')) %>&media=<%= src %>&description=<%= slide.get('slide_title') %>" target="_blank">Google+</a></li>
                                                    <li class="share-pinterest"><a href="https://pinterest.com/pin/create/button/?url=<%= encodeURIComponent($(location).attr('href')) %>&media=<%= src %>&description=<%= slide.get('slide_title') %>" target="_blank">Pinteres</a></li>
                                                </ul>
                                            </div> <!-- /.share-item -->
                                        </div> <!-- /.carouse-infobar-collapse -->
                                    </div> <!-- /.carouse-infobar -->
                                    <?php if (!$site->display_slider_infobar) { ?>
                                    <% } %>
                                    <?php } ?>
                                    <img src="<%= src %>" width="1" height="1">
                                </li>

                                    <% } %>
                                <% }); %>
                            <% }); %>
                        
                        <li data-id="<%=i+1%>" class="nextSlide"><img src="system/images/blank.gif" width="1" height="1"></li>
                    </ul>
                </div> <!-- /#carousel -->
            </div> <!-- /.container-fluid -->
            <div class="clear"></div>
            <script>
            $(function(){
                var image_list = new Array(
                    <% _.each(homepage['0'].get('photos'), function(prevphoto,i) { %>
                    '<%= prevphoto %>',
                    <% }) %>
                    <% _.each(homepage['0'].get('thumbnails'), function(thphoto,i) { %>
                    '<%= thphoto %>',
                    <% }) %>
                    'system/images/blank.gif'
                    );

                preload_pictures(image_list, function(){

                    $('#carousel-filmstrip > a').on('click',function(ev){
                        return false;
                        carousel.showPane($(this).parent().attr('data-id')+1);
                        console.log("PESTE!");
                    })
                    
                    showPage();

                    /*
                    * CAROUSEL CURSOR
                    */
                    $carousel = $('#carousel');
                    $carousel.css( "cursor", "default" );
                    
                    carouselHoverPad = Math.floor($carousel.width()/3);
                    carouselWidth = $('#carousel').width();
                    carouselHeight = $('#carousel-slides').height() - 150;
                    carouselXPos = $carousel.offset().left;
                    carouselYPos = $carousel.offset().top + 150;
                    carouselXEnd = $carousel.offset().left + carouselWidth;
                    carouselYEnd = $carousel.offset().top + carouselHeight;

                    if (carouselControls == false) {
                        $(function(){
                            // console.log("Carousel Dimensions: " + carouselWidth + "x" + carouselHeight);
                            $carousel.css( "cursor", "default" );
                            $(window).resize(function(){
                                carouselHoverPad = Math.floor($carousel.width()/3);
                                carouselWidth = $('#carousel').width();
                                carouselHeight = $('#carousel-slides').height() - 140;
                                carouselXPos = $carousel.offset().left;
                                carouselYPos = $carousel.offset().top + 150;
                                carouselXEnd = $carousel.offset().left + carouselWidth;
                                carouselYEnd = $carousel.offset().top + carouselHeight;
                            });
                            var currentMousePos = { x: -1, y: -1 };
                            $(document).mousemove(function(event) {
                                if (carouselIsDragged == false) {
                                    currentMousePos.x = event.pageX;
                                    currentMousePos.y = event.pageY;
                                    // console.log("Mouse Location: " + currentMousePos.x + " - " + currentMousePos.y);
                                    if ((currentMousePos.x > carouselXPos) && (currentMousePos.x < carouselXPos+carouselHoverPad) && (currentMousePos.y > carouselYPos) && (currentMousePos.y < carouselYEnd)) {
                                        currentMouseState = "prev";
                                        $carousel.css( "cursor", "url('<?php _e($site->theme_url); ?>images/cursor_prev.png') 24 24, default" );
                                    } else if ((currentMousePos.x > carouselXEnd-carouselHoverPad) && (currentMousePos.x < carouselXEnd) && (currentMousePos.y > carouselYPos) && (currentMousePos.y < carouselYEnd)) {
                                        currentMouseState = "next";
                                        $carousel.css( "cursor", "url('<?php _e($site->theme_url); ?>images/cursor_next.png') 24 24, default" );
                                    } else if ((currentMousePos.x > ((carouselWidth/2)+carouselXPos)-(carouselHoverPad/2)) && (currentMousePos.x < ((carouselWidth/2)+carouselXPos)+(carouselHoverPad/2)) && (currentMousePos.y > carouselYPos) && (currentMousePos.y < carouselYEnd)) {
                                        currentMouseState = "thumb";
                                        $carousel.css( "cursor", "url('<?php _e($site->theme_url); ?>images/cursor_thumbs.png') 24 24, default" );
                                    } else {
                                        currentMouseState = "default";
                                        $carousel.css( "cursor", "default" );
                                    }
                                } else {
                                    $carousel.css( "cursor", "url('<?php _e($site->theme_url); ?>images/cursor_drag.png') 24 24, default" );
                                }
                                // console.log(currentMouseState + " --- DRAGGED? " + carouselIsDragged);
                            });
                            carouselControls = true;
                        });
                    } else {
                        $carousel = $('#carousel');
                        $carousel.css( "cursor", "default" );
                    }

                    

                    /**
                    * super simple carousel
                    * animation between panes happens with css transitions
                    */
                    function Carousel(element)
                    {
                        var self = this;
                        element = $(element);

                        var container = $("#carousel-slides", element);
                        var panes = $("#carousel-slides>li", element);

                        var pane_width = 0;
                        var pane_count = panes.length;

                        var current_pane = 1;


                        /**
                         * initial
                         */
                        this.init = function() {
                            setPaneDimensions();

                            $(window).on("load resize orientationchange", function() {
                                setPaneDimensions();
                            });
                        };

                        this.updateDimensions = function() {
                            return this.showPane(current_pane);
                        };


                        /**
                         * set the pane dimensions and scale the container
                         */
                        function setPaneDimensions() {
                            pane_width = element.width();
                            panes.each(function() {
                                $(this).width(pane_width);
                            });
                            container.width(pane_width*pane_count);
                        };


                        /**
                         * show pane by index
                         */
                        this.showPane = function(index, animate) {
                            // between the bounds
                            index = Math.max(0, Math.min(index, pane_count-1));
                            current_pane = index;

                            var offset = -((100/pane_count)*current_pane);
                            setContainerOffset(offset, animate);

                            var sid = <%= sid %>;
                            var t_slide_id = <%= sid %>;
                            var t_slide_total = 0;
                            var t_slide_num = 0;
                            for (var i = 0; i < homepageDump.length; i++) {

                                if (homepageDump[i]['slide_id'] == sid) {
                                    t_slide_id = homepageDump[i]['slide_id'];
                                    t_slide_num = i;
                                    t_slide_total = homepageDump[i]['photos'].length;
                                }
                            }

                            if (current_pane == t_slide_total+1) {
                                console.log("END OF THE LINE - SLIDESHOW NUMBER " + t_slide_num);

                                // go back to start of slideshow
                                current_pane = 1;
                                $('#carousel').stop().fadeOut('500',function(){
                                    self.showPane(current_pane);
                                    $('#carousel').fadeIn('500');
                                });
                                var newUrl = "#/slideshow/<%= sid %>/" + current_pane;
                                console.log(newUrl);
                                window.history.pushState('slide', document.getElementsByTagName("title")[0].innerHTML, newUrl);
                            } else if (current_pane == 0) {
                                console.log("START OF THE LINE - SLIDESHOW NUMBER " + t_slide_num);

                                current_pane = homepageDump['0']['photo_total'];
                                $('#carousel').stop().fadeOut('500',function(){
                                    self.showPane(current_pane);
                                    $('#carousel').fadeIn('500');
                                });
                                var newUrl = "#/slideshow/<%= sid %>/" + current_pane;
                                console.log(newUrl);
                                window.history.pushState('slide', document.getElementsByTagName("title")[0].innerHTML, newUrl);
                            } else {
                                var newUrl = window.location.pathname + "#/slideshow/<%= sid %>/" + current_pane;
                                window.history.pushState('slide', document.getElementsByTagName("title")[0].innerHTML, newUrl);

                                var shareDesc = $(document).find("title").text();
                                var shareImg = homepageDump[t_slide_num]['photos'][current_pane-1];
                                var shareUrl = encodeURIComponent(window.location);

                                // update the meta
                                $('meta[name=og\\:image]').attr('content', shareImg);
                            }

                            // update thumbnails
                            $('#carousel-filmstrip li').removeClass('active');
                            $('#carousel-filmstrip li').each(function(el,i){
                                var $th_this = $(this);
                                if ($th_this.attr('data-id') == (current_pane)) {
                                    $th_this.addClass('active');
                                }
                            });
                            var $th_active = $('#carousel-filmstrip li.active');
                            var $th_container = $('#carousel-filmstrip');
                            var th_container_width = $th_container.innerWidth();
                            var th_max = Math.round(th_container_width/$th_active.outerWidth());

                            if (parseInt(current_pane) <= 3)
                                var th_adjustment = 0;
                            else
                                if ((homepageDump['0']['photo_total'] > th_max))
                                    var th_adjustment = 0 - (current_pane * $th_active.outerWidth()) + ($th_active.outerWidth()*3);

                            console.log(current_pane + " --- " + th_max);
                            $('#carousel-filmstrip ul').css({
                                'left': th_adjustment + "px"
                            })
                        };


                        function setContainerOffset(percent, animate) {
                            container.removeClass("animate");

                            if(animate) {
                                container.addClass("animate");
                            }

                            if(Modernizr.csstransforms3d) {
                                container.css("transform", "translate3d("+ percent +"%,0,0) scale3d(1,1,1)");
                            }
                            else if(Modernizr.csstransforms) {
                                container.css("transform", "translate("+ percent +"%,0)");
                            }
                            else {
                                var px = ((pane_width*pane_count) / 100) * percent;
                                container.css("left", px+"px");
                            }
                        }

                        this.next = function() { return this.showPane(current_pane+1, true); };
                        this.prev = function() { return this.showPane(current_pane-1, true); };


                        function handleHammer(ev) {
                            // disable browser scrolling
                            ev.gesture.preventDefault();

                            switch(ev.type) {
                                case 'dragright':
                                case 'dragleft':
                                    // stick to the finger
                                    var pane_offset = -(100/pane_count)*current_pane;
                                    var drag_offset = ((100/pane_width)*ev.gesture.deltaX) / pane_count;

                                    // slow down at the first and last pane
                                    if((current_pane == 0 && ev.gesture.direction == "right") ||
                                        (current_pane == pane_count-1 && ev.gesture.direction == "left")) {
                                        drag_offset *= .4;
                                    }

                                    carouselIsDragged = true;
                                    setContainerOffset(drag_offset + pane_offset);
                                    break;

                                case 'swipeleft':
                                    self.next();
                                    ev.gesture.stopDetect();

                                    carouselIsDragged = false;
                                    break;

                                case 'swiperight':
                                    self.prev();
                                    ev.gesture.stopDetect();

                                    carouselIsDragged = false;
                                    break;

                                case 'release':
                                    // more then 50% moved, navigate
                                    if(Math.abs(ev.gesture.deltaX) > pane_width/2) {
                                        if(ev.gesture.direction == 'right') {
                                            self.prev();
                                        } else {
                                            self.next();
                                        }
                                    }
                                    else {
                                        self.showPane(current_pane, true);
                                    }

                                    carouselIsDragged = false;

                                    break;

                                case 'tap':
                                    console.log(currentMouseState);

                                    carouselTapX = ev.gesture.center.pageX;
                                    carouselTapY = ev.gesture.center.pageY;
                                    if ((carouselTapX > carouselXPos) && (carouselTapX < carouselXPos+carouselHoverPad) && (carouselTapY > carouselYPos) && (carouselTapY < carouselYEnd)) {
                                        self.prev();
                                    } else if ((carouselTapX > carouselXEnd-carouselHoverPad) && (carouselTapX < carouselXEnd) && (carouselTapY > carouselYPos) && (carouselTapY < carouselYEnd)) {
                                        self.next();
                                    } else if ((carouselTapX > ((carouselWidth/2)+carouselXPos)-(carouselHoverPad/2)) && (carouselTapX < ((carouselWidth/2)+carouselXPos)+(carouselHoverPad/2)) && (carouselTapY > carouselYPos) && (carouselTapY < carouselYEnd)) {
                                        var newUrl = "#/slideshow/<%= sid %>";
                                        console.log(newUrl);
                                        window.location.hash = newUrl;
                                    } else {
                                        var x_current_pane = $(ev.target).parent().parent().attr('data-id');
                                        if (x_current_pane)
                                            self.showPane(x_current_pane,true);
                                    }

                                    break;
                            }
                        }

                        new Hammer(element[0], { dragLockToAxis: true }).on("tap release dragleft dragright swipeleft swiperight", handleHammer);
                    }

                    var carousel = new Carousel("#carousel");
                    carousel.init();

                    // keep the current panel shown
                    $(window).resize(function(){
                        if ($('#carousel').length > 0) {
                            carousel.updateDimensions();
                        }
                    });

                    carousel.showPane(1);

                    $(function(){
                        // var element = $('.navbar-brand');
                        var next_el = $('.carousel-next').get(0);
                        var prev_el = $('.carousel-prev').get(0);
                        var thumb_el = $('.carousel-thumbs').get(0);
                        var options = {
                            preventDefault: true
                        };
                        var carouselNext = new Hammer(next_el, options);
                        carouselNext.on("tap", function(ev){
                            carousel.next();
                            console.log("NEXT...");
                            return false;
                        });
                        var carouselPrev = new Hammer(prev_el, options);
                        carouselPrev.on("tap", function(ev){
                            carousel.prev();
                            console.log("PREV...");
                            return false;
                        });
                        var carouselThumbs = new Hammer(thumb_el);
                        carouselThumbs.on("tap", function(ev){
                            window.location.hash = '#/slideshow/<%= sid %>';
                            return false;
                        });

                        $('.carousel-thumbs').on('click', function(e){
                            e.preventDefault();
                            console.log($(carouselThumbs));

                            return false;
                        });

                        $('.carousel-next').on('click', function(e){
                            e.preventDefault();
                            // carousel.next();

                            return false;
                        });

                        $('.carousel-prev').on('click', function(e){
                            e.preventDefault();
                            // carousel.prev();

                            return false;
                        });
                    });
                });
            });
            </script>