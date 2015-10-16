var selected_filter_id;
$(document).ready(function() {

	/* TEXT EDITOR */
	// if ($('.texteditor').length > 0) {
		/*$('.wysihtml5').wysihtml5({
			"html": true
		});*/
		// $('.texteditor').jqte();
	// }

	/* BOOTSTRAP WYSIWYG */
	if ($('.wysihtml5').length>0) {
		$('.wysihtml5').wysihtml5({
		    "font-styles": true, //Font styling, e.g. h1, h2, etc. Default true
		    "emphasis": true, //Italics, bold, etc. Default true
		    "lists": true, //(Un)ordered lists, e.g. Bullets, Numbers. Default true
		    "html": true, //Button which allows you to edit the generated HTML. Default false
		    "link": true, //Button to insert a link. Default true
		    "image": true, //Button to insert an image. Default true,
		    "color": false, //Button to change color of font
		    "size": 'sm' //Button size like sm, xs etc.
		});
	}


	/* NESTED LIST SORTABLE */
	var listSortableFunc = function() {
		if ($('.listsortable').length > 0) {
			$('.hoverMenu').hide();
			$('.listsortable li').mouseover(function(e){
				$(this).find('.hoverMenu').first().show();
			}).mouseout(function(e){
				$(this).find('.hoverMenu').first().hide();
			});

			$('#theMenuList').nestedSortable({
				forcePlaceholderSize: true,
				handle: '.menuItemDrag',
				helper:	'clone',
				items: 'li',
				opacity: .6,
				placeholder: 'placeholder',
				revert: 250,
				tabSize: 25,
				tolerance: 'pointer',
				toleranceElement: '> span',
				maxLevels: 2,

				isTree: true,
				expandOnHover: 700,
				startCollapsed: true
	        });

	        $('.menuItemAdd').on('click', function(ev) {
	        	ev.preventDefault();
	        	var $this = $(this);
	        	var $item = $this.parent().parent();
	        	var $clone = $item.clone();
	        	$clone.appendTo( "#theMenuList" );
	        });

	        $('.menuItemRemove').on('click', function(ev) {
	        	ev.preventDefault();
	        	
	        })
		}
	} // end of listSortableFunc
	listSortableFunc();

	var slideshowSortableFunc = function(){
		if ($('#tab-galleries-published .slideshow-thumbnails').length > 0) {
			var group = $("#tab-galleries-published .slideshow-thumbnails").sortable({
				nested: false,
				handle: 'a.slideshowDragger',
				vertical: true,
				onDrop: function (item, container, _super) {

					var $this = $(this);
					if(!container.options.drop)
	      				item.clone().insertAfter(item)

	      			console.log("CONTAINER ID: " + container.el.attr('data-id'));
	      			var containerID = container.el.attr('data-id');

	      			var objData = container.el.sortable("serialize").get();
					console.log(objData.length);
					console.log(objData);

					// initiate objects
					var result = "";
					var parObj;
					var childObj = "";
					var temp;
					console.log(result);

					console.log("Result String Length: " + result.length);
					for (i=0;i!=objData.length; i++) {

						if (objData[i].id != '0') {
							if (result.length > 0) {
								result += ";" + objData[i].id;
							} else {
								result += objData[i].id;
							}
						}
					}

					console.log(result);
					// $('#theMenuListWrapper .serialize_output').text(result);
					
					var dataToSend = "data=" + result + "&id=" + containerID;
					$.ajax({
						url: "gallery-order-action.php",
						type: "post",
						data: dataToSend,
						cache: false,
						dataType: "text",
						success: function () {
							console.log("gallery order - updated...");
							$.bootstrapGrowl("<strong>Gallery</strong> has been updated.",{
								ele: container.el,
								offset: {from: 'top', amount: 0},
								type: 'success',
								align: 'center',
								delay: 500,
								stackup_spacing: 0
							});
						}
					});
					
					_super(item, container);
				},
				serialize: function ($parent, $children, parentIsContainer) {
					var result = $.extend({}, {id:$parent.data('id')});
					if(parentIsContainer)
						return $children;
					else if ($children[0]) 
						result.children = $children;
					return jQuery.makeArray(result);
				},
				isValidTarget: function ($item, container) {
					var depth = 1, // Start with a depth of one (the element itself)
						maxDepth = 1,
						children = $item.find('ol').first().find('li');

					// Add the amount of parents to the depth
					depth += container.el.parents('ol').length;

					// Increment the depth for each time a child
					while (children.length) {
						depth++;
						children = children.find('ol').first().find('li');
					}

					return depth <= maxDepth;
				}
			});

			$('#tab-galleries-published .slideshowRemover').on('click',function(e){
				e.preventDefault();
				var $btn = $(this);
				var $list = $btn.parent();
				var $container = $list.parent();
				var photo_id = $list.attr('data-id');
				var gid = $container.attr('data-id');

				$list.remove();
				console.log("Removing photo: " + photo_id);

				console.log(">>>>>>>> " + gid);

				var objData = $container.sortable("serialize").get();
				console.log("OBJECT DATA LENGTH: " + objData.length);
				console.log(objData);

				// initiate objects
				var result = "";
				var parObj;
				var childObj = "";
				var temp;

				console.log("Result String Length: " + result.length);
				for (i=0;i!=objData.length; i++) {

					if (objData[i].id != '0') {
						if (result.length > 0) {
							result += ";" + objData[i].id;
						} else {
							result += objData[i].id;
						}
					}
				}

				if (objData.length==0) {
					$container.prepend('<li><img src="css/defaul-thumb.jpg" width="80px"></li>');
				}

				console.log("OBJECT STRING RESULT: " + result);
				
				var dataToSend = "data=" + result + "&id=" + gid;
				$.ajax({
					url: "gallery-order-action.php",
					type: "post",
					data: dataToSend,
					cache: false,
					dataType: "text",
					success: function () {
						console.log("gallery order - updated...");
						$.bootstrapGrowl("<strong>Gallery</strong> has been updated.",{
							ele: $container.parent(),
							offset: {from: 'top', amount: 0},
							type: 'success',
							align: 'center',
							delay: 500,
							stackup_spacing: 0
						});
					}
				});
			});
		}

		$('.slideshow-thumbnails .modalButton').css({
			'margin-top': 0,
		})

	    if ($('.table-slideshows-expand').length>0) {

	    	$(function() {
	    		$(window).resize(function(){
	    			$('.table-slideshows-content').each(function() {
	    				var $UL = $(this).find('.slideshow-thumbnails');
	    				var $expander = $(this).find('.table-slideshows-expand');
	    				$UL.removeClass('collapse');
	    				if ($UL.height()<=84) {
	    					// $expander.hide();
	    					$expander.find('a').attr('disabled', true);
	    				} else {
	    					// $expander.show();
	    					$expander.find('a').attr('disabled', false);
	    				}
	    				console.log($UL.height());
	    				$UL.addClass('collapse');
	    			});
	    		});
	    		$(window).resize();

		    	$('.table-slideshows-expand a').on('click', function(ev) {
		    		ev.preventDefault();
		    		var $expander = $(this);
		    		var $UL = $expander.parent().parent().find('.slideshow-thumbnails');
		    		var $icon = $expander.find('i')
			
					if ($UL.is('.expand')) {
						$UL.removeClass('expand').addClass('collapse');
		    			$icon.removeClass('fa-caret-up').addClass('fa-caret-down');
					}
		    		else {
		    			$UL.removeClass('collapse').addClass('expand');
		    			$icon.removeClass('fa-caret-down').addClass('fa-caret-up');
		    		}
		    	});
		    });
	    }

	} // end of slideshowSortableFunc
	slideshowSortableFunc();


	var photoSortableFunc = function(){
		$('#photoItems .photoRemover').hide();
		$('#photoSelected .photoRemover').show();
		if ($('#photoSelectGroup').length > 0) {
			var group = $("#photoSelected").sortable({
				group: 'photoSelected',
				nested: false,
				handle: 'a.photoDragger',
				vertical: false,
				onDrop: function (item, container, _super) {

					photoRemoverFunc();
					$('#photoSelected .photoRemover').show();

					var $this = $(this);
					if(!container.options.drop)
	      				item.clone().insertAfter(item)

	      			console.log("CONTAINER ID: " + container.el.attr('data-id'));
	      			var containerID = container.el.attr('data-id');

	      			var objData = container.el.sortable("serialize").get();
					console.log(objData.length);
					console.log(objData);

					// initiate objects
					var result = "";
					var parObj;
					var childObj = "";
					var temp;
					console.log(result);

					console.log("Result String Length: " + result.length);
					for (i=0;i!=objData.length; i++) {

						if (objData[i].id != '0') {
							if (result.length > 0) {
								result += ";" + objData[i].id;
							} else {
								result += objData[i].id;
							}
						}
					}

					console.log(result);
					// $('#theMenuListWrapper .serialize_output').text(result);
					
					var dataToSend = "data=" + result + "&id=" + containerID;
					console.log(dataToSend);
					
					$.ajax({
						url: "slideshow-order-action.php",
						type: "post",
						data: dataToSend,
						cache: false,
						dataType: "text",
						success: function () {
							console.log("slideshow photos order - updated...");

							// slideshowCoverUpdate();
							ajaxCallbackFunc_GridThumb('#photoSelected');

							$.bootstrapGrowl("<strong>Slideshow Photos</strong> has been updated.",{
								ele: container.el,
								offset: {from: 'top', amount: 0},
								type: 'success',
								align: 'center',
								delay: 500,
								stackup_spacing: 0
							});
						}
					});
					
					_super(item, container);
				},
				serialize: function ($parent, $children, parentIsContainer) {
					var result = $.extend({}, {id:$parent.data('id')});
					if(parentIsContainer)
						return $children;
					else if ($children[0]) 
						result.children = $children;
					return jQuery.makeArray(result);
				},
				isValidTarget: function ($item, container) {
					var depth = 1, // Start with a depth of one (the element itself)
						maxDepth = 1,
						children = $item.find('ul').first().find('li');

					// Add the amount of parents to the depth
					depth += container.el.parents('ul').length;

					// Increment the depth for each time a child
					while (children.length) {
						depth++;
						children = children.find('ul').first().find('li');
					}

					return depth <= maxDepth;
				}
			});
			
			var photoRemoverFunc = function() {
				$('#photoSelected .photoRemover').on('click',function(e){
					e.preventDefault();
					var $parent = $(this).parent().parent();
					var $container = $parent.parent();
					var gid = $container.attr('data-id');
					$parent.remove();
					console.log("Removing menu item: " + gid);
					console.log($container);

					var objData = $container.sortable("serialize").get();
						console.log(objData.length);
						console.log(objData);

						// initiate objects
						var result = "";
						var parObj;
						var childObj = "";
						var temp;
						console.log(result);

						console.log("Result String Length: " + result.length);
						for (i=0;i!=objData.length; i++) {

							if (objData[i].id != '0') {
								if (result.length > 0) {
									result += ";" + objData[i].id;
								} else {
									result += objData[i].id;
								}
							}
						}

						console.log("Object Data Result: " + result);
						
						var dataToSend = "data=" + result + "&id=" + gid;
						$.ajax({
							url: "slideshow-order-action.php",
							type: "post",
							data: dataToSend,
							cache: false,
							dataType: "text",
							success: function () {
								console.log("slideshow photo order - updated...");
								$.bootstrapGrowl("<strong>Slideshow Photos</strong> has been updated.",{
									ele: $container,
									offset: {from: 'top', amount: 0},
									type: 'success',
									align: 'center',
									delay: 500,
									stackup_spacing: 0
								});
							}
						});
				});
			}
			photoRemoverFunc();
			
			$("#photoItems").sortable({
				group: 'photoSelected',
				drop: false,
				nested: false
			});
		}

	} // end of photoSortableFunc
	photoSortableFunc();


	if ($('.formCancel').length>0) {
		$('.formCancel').on("click", function(){
		parent.history.back();
		return false;
		});
	}

	if ($('.dropdown.keep-open').length>0) {
		$('.dropdown.keep-open').on({
			"shown.bs.dropdown": function() { $(this).data('closable', false); },
			"click": function() { $(this).data('closable', true);  },
			"hide.bs.dropdown": function() { return $(this).data('closable'); }
		});
	}


	/* TABLE SORTING */

	if ($('.table').length > 0) {
		$('.table .header').on('click', function(e){
			var $theader = $(this);
			// revert to default sort icon everytime you click a header
			$('.table .header').find('i').removeClass (function (index, css) {
				return (css.match (/\bfa-sort\S+/g) || []).join(' ');
			}).addClass('fa-sort');

			// set asc or desc sort
			if ($theader.hasClass('headerSortUp')) {
				$theader.find('i').removeClass (function (index, css) {
					return (css.match (/\bfa-sort\S+/g) || []).join(' ');
				}).addClass('fa-sort-desc');
			} else if ($theader.hasClass('headerSortDown')) {
				$theader.find('i').removeClass (function (index, css) {
					return (css.match (/\bfa-sort\S+/g) || []).join(' ');
				}).addClass('fa-sort-asc');
			} else {
				$theader.find('i').removeClass (function (index, css) {
					return (css.match (/\bfa-sort\S+/g) || []).join(' ');
				}).addClass('fa-sort-desc');
			}
		});
	}


	/* FILE UPLOAD STUFF */
	if ($('.fileupload').length>0) {
		$('#photo_fileupload').fileupload({
			// url: 'uploader/php/',
			previewMaxWidth : 230,
			previewMaxHeight : 340
		}).on('fileuploadsubmit', function (e, data) {
			data.formData = data.context.find(':input').serializeArray();
		});


	}

	if ($('#site-options-logo').length>0) {
		$(function () {
		    'use strict';
		    // Change this to the location of your server-side upload handler:
		    var url = 'uploader/php/logo.php';
		    $('#fileupload').fileupload({
		        url: url,
		        dataType: 'json',
		        done: function (e, data) {
		            $.each(data.result.files, function (index, file) {
		            	console.log(file);
		            	$('#site-option-logo-box img').attr("src",file.previewUrl);
		                $('<p/>').text(file.name).appendTo('#files');
		            });
		        },
		        progressall: function (e, data) {
		            var progress = parseInt(data.loaded / data.total * 100, 10);
		            $('#progress .progress-bar').css(
		                'width',
		                progress + '%'
		            );
		        }
		    }).prop('disabled', !$.support.fileInput)
		        .parent().addClass($.support.fileInput ? undefined : 'disabled');
		});

	}

	if ($('.photo-delete').length>0) {
		// check all checkbox
		$('.delete-buttonbar .delete-toggle').on('click',function(){
			var $this = $(this);
			if ($this.is(':checked')) {
				$this.parent().find('span').text('Uncheck all');
				$('.grid .deleteBox').prop('checked', true);
				
			} else {
				$this.parent().find('span').text('Check all');
				$('.grid .deleteBox').prop('checked', false);
			}	
			
			var checkedBoxes;
			$('.grid .deleteBox:checked').each(function() {
				if (checkedBoxes != undefined)
					checkedBoxes += ";" + $(this).val();
				else
					checkedBoxes = $(this).val();
			});
			$('#photo_delete #items').val(checkedBoxes);
			
			console.log($('#photo_delete #items').val());
		});

		$('.grid .deleteBox').click(function(e){
				var checkedBoxes;
				$('.grid .deleteBox:checked').each(function() {
					if (checkedBoxes != undefined)
						checkedBoxes += ";" + $(this).val();
					else
						checkedBoxes = $(this).val();
				});
				$('#photo_delete #items').val(checkedBoxes);
				
				console.log($('#photo_delete #items').val());
		})

		$('.delete-buttonbar .deleteButton').on('click',function(e){
			$('#photo_delete').submit();
		});
	}

	/* FORMS */
	if ($('.form').length>0) {

		/* VALIDATE SLIDESHOW FORM */
		/*$('.slideshowForm').validate({
			rules: {
				title: {
					required: true,
					minlength: 5
				},
				status: {
					required: true
				}
			},
			submitHandler: function(form) {
				form.submit();
			},
			messages: {
				title: {
					required: "A title is needed for your slideshow."
				},
				status: "Please select a status for your slideshow."
			}
		});
		// FOR BOTH NEW AND EDIT SLIDESHOW FORM
		$('.slideshowForm #formSubmit').on('click', function(e){
			$('.slideshowForm').valid();
		});*/



		/* VALIDATE PAGE FORM */
		/*$('.pageForm').validate({
			rules: {
				title: {
					required: true,
					minlength: 3
				},
				content: {
					required: true
				},
				status: {
					required: true
				}
			},
			submitHandler: function(form) {
				form.submit();
			},
			messages: {
				title: {
					required: "A title is needed for your page."
				},
				status: "Please select a status for your page."
			}
		});
		// FOR BOTH NEW AND EDIT PAGE FORM
		$('.pageForm #formSubmit').on('click', function(e){
			$('.pageForm').valid();
		});*/


		// VALIDATE THE CATEGORY FORM 
		/*$('.categoryForm').validate({
			rules: {
				title: {
					required: true,
					minlength: 5
				}
			},
			submitHandler: function(form) {
				form.submit();
			}
		});
		// NEW CATEGORY FORM 
		$('#categoryCreate #formSubmit').on('click', function(e){
			$('#categoryCreate').valid();
		});

		$('.categoryUpdateForm').validate({
				rules: {
				title: {
					required: true,
					minlength: 5
				}
			},
			submitHandler: function(form) {
				form.submit();
			}
		});*/
	}


	/* MODALS */

	// REMOVE CACHE CONTENT
	$('body').on('hidden.bs.modal', '.modal', function () {
		$(this).removeData('bs.modal');
		$(this).empty();
		console.log('clearing modal content...');
	});

	// EDIT CATEGORY MODAL
	$('.modalButton').on('click', function(e){
		
		e.preventDefault();

		var url = $(this).attr('href');
		if (url.indexOf('#') == 0) {
			$(url).modal('open');
		} else {
			$.get(url, function(data) {
				$('<div class="modal" role="dialog">' + data + '</div>').modal();
			}).success(function(){
				// do something...
			});
		}
		console.log('modal init'); 
	});

	// FIX BOSY SCROLL WHEN MODAL IS OPEN
	$('body').on('shown.bs.modal', '.modal', function () {
		$("body").addClass("modal-open");
	});
	// REVERT SCROLL BAR WHEN MODAL IS CLOSED
	$('body').on('hidden.bs.modal', '.modal', function () {
		$("body").removeClass("modal-open");
	});


	/* CUSTOM BUTTONS */
	$('.customButton').on('click',function(e){
		e.preventDefault();
		var url = $(this).attr('href');
		window.location = url;
	});



	/* TABBED MENU */

	if ($('.nav-tabs').length > 0) {
		// SLIDESHOWS
		/*$('#slideshow-tabs a').click(function (e) {
			e.preventDefault();
			$(this).tab('show');
		});*/
		// Javascript to enable link to tab
		var url = document.location.toString();
		if (url.match('#')) {
			$('.nav-tabs a[href=#'+url.split('#')[1]+']').tab('show') ;
		} 

		// REMEBER ACTIVE TABS
		$(function() { 
			//for bootstrap 3 use 'shown.bs.tab' instead of 'shown' in the next line
			$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
				//save the latest tab; use cookies if you like 'em better:
				localStorage.setItem('lastTab', $(e.target).attr('href'));
				console.log("Remembering Tab: " + localStorage.getItem('lastTab'));
			});

			//go to the latest tab, if it exists:
			var lastTab = localStorage.getItem('lastTab');
			if (lastTab) {
				$('a[href=' + lastTab + ']').tab('show');
			}
		});
	}



	/* TOOLTIPS */

	if ($('[data-toggle="tooltip"]').length>0) {
		$('[data-toggle="tooltip"]').tooltip();
	}


	/* PAGINATION */
	/*if ($('.paginationWrapper').length > 0) {
		$(function(){
			var perPage = 6;
			var opened = 1;
			var onClass = 'active';
			var paginationSelector = '.paginationWrapper';
			$('.gallery-slideshow-list').simplePagination(perPage, opened, onClass, paginationSelector);
		});
	}*/


	/* AJAX LINK --- RELOAD CONTENT OF SPECIFIC CONTAINER */
	
	// for menu items
	$(".refreshMenuTitle .ajaxLink").click(function (e) {
		e.preventDefault();

		// get the target container from hash
		var href = $(this).attr("href");
		var link = href.substring(0,href.indexOf('#'));
		var hash = $(this).prop("hash");

		console.log(link + " --- " + hash);

    	$(hash).load(link + " " + hash, listSortableFunc);
	});


	/* Menu Item Type Selection */

	if ($('#menuTypePanels').length > 0) {
		$('.pageType').change(function(){
			$this = $(this);
			$('#menuTypePanels .menu-type-panel').removeClass('show').hide();

			if ($this.val() == '0')
				$('#panel-placeholder').addClass('show');
			else if ($this.val() == '1')
				$('#panel-page').addClass('show');
			else if ($this.val() == '2')
				$('#panel-hyperlink').addClass('show');
			else if ($this.val() == '3' || $this.val() == '4')
				$('#panel-slideshow').addClass('show');
			else if ($this.val() == '5' || $this.val() == '6')
				$('#panel-gallery').addClass('show');
		});
	}


	/* Options Page - Checkbox Triggers */

	if ($('#site-homepage-default').length > 0) {
		$('#site-homepage-default').change(function(){
			if($("#site-homepage-default").prop('checked') == true)
				$('#site-homepage-target-set').attr('disabled', true);
			else
				$('#site-homepage-target-set').attr('disabled', false);
		});


		$('#site-homepage-type-select a').on('click',function(ev){
			$('#site-homepage-type-select .active').removeClass('active');
			$(this).parent().addClass('active');
			ev.preventDefault();
			$this = $(this);
			$('#site-homepage-type-select-collect').val($this.attr('data-id'));
			$('#site-homepage-type-select-button').html($this.html() + ' <span class="caret"></span>');
			$('.site-homepage-target-select.show').removeClass('show');
			$('#site-homepage-target-select-type'+$this.attr('data-id')).addClass('show');
		});
	}

	// slideshow cover update

	var slideshowCoverUpdate = function() {

	    if ($('#photoSelected').length>0) {

	    	$('#photoSelected a.photoCover').on('click',function(ev){
	    		ev.preventDefault();
	    		var gid = $('#photoSelected').attr('data-id');
	    		var $gridObj = $(this).parent().parent();
	    		var id = $gridObj.attr('data-id');
	    		var gridThumbTarget = "#grid-thumb-"+id;
	    		var url = $(this).attr('href');
	    		var pageUrl = "slideshow-edit.php?id=" + gid;

	    		// remove current active thumbnail
	    		$('#photoSelected .active').removeClass('active');

			    $.ajax({
					type: "POST",
					url: url,
					// data: $("#photoUpdateForm<?php _e($id); ?>").serialize(),
					success: function(data)
					{

					// update the table list
					var loadUrlContainer = " " + gridThumbTarget + " .grid-thumbs-content";
					var loadUrl = pageUrl + loadUrlContainer;
					console.log(loadUrl);
					$(gridThumbTarget).addClass('preloadPhoto').load(loadUrl,
						function (responseText, textStatus, XMLHttpRequest) {
							if (textStatus == "success") {
								$gridObj.addClass('active');
								ajaxCallbackFunc_GridThumb(gridThumbTarget);

								$(gridThumbTarget).removeClass('preloadPhoto');

								// close the modal box
								// $('.modal').modal('hide');
							}
						});
					}
				});
				return false;
			});
		}
    }
    slideshowCoverUpdate();

    var slideshowUpdate = function() {

    	if ($('#slideshowUpdateForm').length>0) {

			$('#photoItems a.photoCover').on('click', function(ev){
				ev.preventDefault();
				return false;
				console.log("asdfasdfasdf");
			});

			$("#slideshowUpdateForm").submit(function() {

	    		var gid = $('#photoSelected').attr('data-id');
	    		var pageUrl = "slideshow-edit.php?id=" + gid;

			    var url = "slideshow-action-update.php";

			    $.ajax({
			           type: "POST",
			           url: url,
			           data: $("#slideshowUpdateForm").serialize(),
			           success: function(data)
			           {
							// update the table list

							$('#photoItemsBox').addClass('preload').load(pageUrl + ' #photoItems',
								function (responseText, textStatus, XMLHttpRequest) {
									if (textStatus == "success") {

										ajaxCallbackFunc_slideshowUpdate();

										$('#photoItemsBox').removeClass('preload');

										// apply pagination
										$('.paginationWrapper').empty();
										$(function(){
											var perPage = 16;
											var opened = 1;
											var onClass = 'active';
											var paginationSelector = '.paginationWrapper';
											$('#photoItems').simplePagination(perPage, opened, onClass, paginationSelector);
										});
										$('.paginationWrapper').css({'display':'block'});
									}
								});
						}
			         });

			    return false;
			});
		}
    }
    slideshowUpdate();

    var photoManagerUpdate = function() {

    	if ($('#photo-manager-box').length>0) {
    		
			$('.photo-filer a').on('click', function(ev){
				ev.preventDefault();

				var filter_id = $(this).parent().attr('data-id');

				$('.photo-filer .active').removeClass('active');

				if (filter_id != selected_filter_id) {

					if ($(this).html() == "None") {
						var newFilterMenuLabel = '<i class="fa fa-filter"></i> Filter by category <span class="caret"></span>';
					} else {
						var newFilterMenuLabel = '<i class="fa fa-filter"></i> Filtered by: ' + $(this).html() + ' <span class="caret"></span>';
					}
		    		var pageUrl = $(this).attr('href');

					$('#photo-manager-box').addClass('preload').load(pageUrl + ' #photo-manager-box .photo-manager-box-container',
						function (responseText, textStatus, XMLHttpRequest) {
							if (textStatus == "success") {
								ajaxCallbackFunc_Photos('#photo-manager-box');
								$('#photo-manager-box').removeClass('preload');
							}
						});
		    		
		    		$('#filter-menu').html(newFilterMenuLabel);

		    		selected_filter_id = filter_id;
		    	}

		    	$(this).parent().addClass('active');
		    	$('#filter-menu').click();
				return false;
			});
		}
    }
    photoManagerUpdate();

});



function openModal(url) {
	$.get(url, function(data) {
		$('<div class="modal" role="dialog">' + data + '</div>').modal();
	});
	console.log('modal init'); 
	return false;
}

function ajaxCallbackFunc_Gallery(tObj) {
	$(tObj + ' .slideshowRemover').on('click',function(e){
		e.preventDefault();
		var $btn = $(this);
		var $list = $btn.parent();
		var $container = $list.parent();
		var photo_id = $list.attr('data-id');
		var gid = $container.attr('data-id');

		$list.remove();
		console.log("Removing photo: " + photo_id);

		console.log(">>>>>>>> " + gid);

		var objData = $container.sortable("serialize").get();
		console.log("OBJECT DATA LENGTH: " + objData.length);
		console.log(objData);

		// initiate objects
		var result = "";
		var parObj;
		var childObj = "";
		var temp;

		for (i=0;i!=objData.length; i++) {

			if (objData[i].id != '0') {
				if (result.length > 0) {
					result += ";" + objData[i].id;
				} else {
					result += objData[i].id;
				}
			}
		}

		console.log(result);

		if (objData.length==0) {
			$container.prepend('<li><img src="css/defaul-thumb.jpg" width="80px"></li>');
		}
		
		var dataToSend = "data=" + result + "&id=" + gid;
		$.ajax({
			url: "gallery-order-action.php",
			type: "post",
			data: dataToSend,
			cache: false,
			dataType: "text",
			success: function () {
				console.log("gallery order - updated...");
				$.bootstrapGrowl("<strong>Gallery</strong> has been updated.",{
					ele: $container.parent(),
					offset: {from: 'top', amount: 0},
					type: 'success',
					align: 'center',
					delay: 500,
					stackup_spacing: 0
				});
			}
		});
	});

	var group = $(tObj + " .slideshow-thumbnails").sortable({
		nested: false,
		handle: 'a.slideshowDragger',
		vertical: true,
		onDrop: function (item, container, _super) {

			var $this = $(this);
			if(!container.options.drop)
					item.clone().insertAfter(item)

				console.log("CONTAINER ID: " + container.el.attr('data-id'));
				var containerID = container.el.attr('data-id');

				var objData = container.el.sortable("serialize").get();
			console.log(objData.length);
			console.log(objData);

			// initiate objects
			var result = "";
			var parObj;
			var childObj = "";
			var temp;
			console.log(result);

			console.log("Result String Length: " + result.length);
			for (i=0;i!=objData.length; i++) {

				if (objData[i].id != '0') {
					if (result.length > 0) {
						result += ";" + objData[i].id;
					} else {
						result += objData[i].id;
					}
				}
			}

			console.log(result);
			// $('#theMenuListWrapper .serialize_output').text(result);
			
			var dataToSend = "data=" + result + "&id=" + containerID;
			$.ajax({
				url: "gallery-order-action.php",
				type: "post",
				data: dataToSend,
				cache: false,
				dataType: "text",
				success: function () {
					console.log("gallery order - updated...");
					$.bootstrapGrowl("<strong>Gallery</strong> has been updated.",{
						ele: container.el,
						offset: {from: 'top', amount: 0},
						type: 'success',
						align: 'center',
						delay: 500,
						stackup_spacing: 0
					});
				}
			});
			
			_super(item, container);
		},
		serialize: function ($parent, $children, parentIsContainer) {
			var result = $.extend({}, {id:$parent.data('id')});
			if(parentIsContainer)
				return $children;
			else if ($children[0]) 
				result.children = $children;
			return jQuery.makeArray(result);
		},
		isValidTarget: function ($item, container) {
			var depth = 1, // Start with a depth of one (the element itself)
				maxDepth = 1,
				children = $item.find('ol').first().find('li');

			// Add the amount of parents to the depth
			depth += container.el.parents('ol').length;

			// Increment the depth for each time a child
			while (children.length) {
				depth++;
				children = children.find('ol').first().find('li');
			}

			return depth <= maxDepth;
		}
	});

	$(tObj + ' .modalButton').on('click', function(e){
		
		e.preventDefault();

		var url = $(this).attr('href');
		if (url.indexOf('#') == 0) {
			$(url).modal('open');
		} else {
			$.get(url, function(data) {
				$('<div class="modal" role="dialog">' + data + '</div>').modal();
			}).success(function(){
				// do something...
			});
		}
		console.log('modal init'); 
	});


	if ($(tObj + ' [data-toggle="tooltip"]').length>0) {
		$(tObj + ' [data-toggle="tooltip"]').tooltip();
	}


	$(window).resize();
    if ($(tObj + ' .table-slideshows-expand').length>0) {
    	$(tObj + ' .table-slideshows-expand a').on('click', function(ev) {
    		ev.preventDefault();

    		var $expander = $(this);
    		var $UL = $expander.parent().parent().find('.slideshow-thumbnails');
    		var $icon = $expander.find('i')
			
			if ($UL.is('.expand')) {
				$UL.removeClass('expand').addClass('collapse');
	    		$expander.find('a').attr('disabled', false);
    			$icon.removeClass('fa-caret-up').addClass('fa-caret-down');
			}
    		else {
    			$UL.removeClass('collapse').addClass('expand');
    			$icon.removeClass('fa-caret-down').addClass('fa-caret-up');
    		}
    	})
    }
}

function ajaxCallbackFunc_Photos(tObj) {

	console.log(tObj);

	$(tObj + ' .modalButton').on('click', function(e){
		
		e.preventDefault();

		var url = $(this).attr('href');
		if (url.indexOf('#') == 0) {
			$(url).modal('open');
		} else {
			$.get(url, function(data) {
				$('<div class="modal" role="dialog">' + data + '</div>').modal();
			}).success(function(){
				// do something...
			});
		}
		console.log('modal init'); 
	});
}

function ajaxCallbackFunc_GridThumb(tObj) {
	$(tObj + ' a.photoCover').on('click',function(ev){
		ev.preventDefault();
		var gid = $('#photoSelected').attr('data-id');
		var $gridObj = $(this).parent().parent();
		var id = $gridObj.attr('data-id');
		var gridThumbTarget = "#grid-thumb-"+id;
		var url = $(this).attr('href');
		var pageUrl = "slideshow-edit.php?id=" + gid;

		// remove current active thumbnail
		$('#photoSelected .active').removeClass('active');

	    $.ajax({
			type: "POST",
			url: url,
			// data: $("#photoUpdateForm<?php _e($id); ?>").serialize(),
			success: function(data)
			{

			// update the table list
			var loadUrlContainer = " " + gridThumbTarget + " .grid-thumbs-content";
			var loadUrl = pageUrl + loadUrlContainer;
			console.log(loadUrl);
			$(gridThumbTarget).addClass('preloadPhoto').load(loadUrl,
				function (responseText, textStatus, XMLHttpRequest) {
					if (textStatus == "success") {
						$gridObj.addClass('active');
						ajaxCallbackFunc_GridThumb(gridThumbTarget);

						$(gridThumbTarget).removeClass('preloadPhoto');

						// close the modal box
						// $('.modal').modal('hide');
					}
				});
			}
		});
		return false;
	});
}

function ajaxCallbackFunc_slideshowUpdate(tObj) {
	var group = $("#photoSelected").sortable({
		group: 'photoSelected',
		nested: false,
		handle: 'a.photoDragger',
		vertical: false,
		onDrop: function (item, container, _super) {

			photoRemoverFunc();
			$('#photoSelected .photoRemover').show();

			var $this = $(this);
			if(!container.options.drop)
  				item.clone().insertAfter(item)

  			console.log("CONTAINER ID: " + container.el.attr('data-id'));
  			var containerID = container.el.attr('data-id');

  			var objData = container.el.sortable("serialize").get();
			console.log(objData.length);
			console.log(objData);

			// initiate objects
			var result = "";
			var parObj;
			var childObj = "";
			var temp;
			console.log(result);

			console.log("Result String Length: " + result.length);
			for (i=0;i!=objData.length; i++) {

				if (objData[i].id != '0') {
					if (result.length > 0) {
						result += ";" + objData[i].id;
					} else {
						result += objData[i].id;
					}
				}
			}

			console.log(result);
			// $('#theMenuListWrapper .serialize_output').text(result);
			
			var dataToSend = "data=" + result + "&id=" + containerID;
			console.log(dataToSend);
			
			$.ajax({
				url: "slideshow-order-action.php",
				type: "post",
				data: dataToSend,
				cache: false,
				dataType: "text",
				success: function () {
					console.log("slideshow photos order - updated...");
					$.bootstrapGrowl("<strong>Slideshow Photos</strong> has been updated.",{
						ele: container.el,
						offset: {from: 'top', amount: 0},
						type: 'success',
						align: 'center',
						delay: 500,
						stackup_spacing: 0
					});
				}
			});
			
			_super(item, container);
		},
		serialize: function ($parent, $children, parentIsContainer) {
			var result = $.extend({}, {id:$parent.data('id')});
			if(parentIsContainer)
				return $children;
			else if ($children[0]) 
				result.children = $children;
			return jQuery.makeArray(result);
		},
		isValidTarget: function ($item, container) {
			var depth = 1, // Start with a depth of one (the element itself)
				maxDepth = 1,
				children = $item.find('ul').first().find('li');

			// Add the amount of parents to the depth
			depth += container.el.parents('ul').length;

			// Increment the depth for each time a child
			while (children.length) {
				depth++;
				children = children.find('ul').first().find('li');
			}

			return depth <= maxDepth;
		}
	});
	
	var photoRemoverFunc = function() {
		$('#photoSelected .photoRemover').on('click',function(e){
			e.preventDefault();
			var $parent = $(this).parent();
			var $parentContainer = $parent.parent();
			var containerID = $parentContainer.attr('data-id');
			$parent.remove();
			console.log("Removing menu item: " + $parent.attr('data-id'));

			var objData = $parentContainer.sortable("serialize").get();
				console.log(objData.length);
				console.log(objData);

				// initiate objects
				var result = "";
				var parObj;
				var childObj = "";
				var temp;
				console.log(result);

				console.log("Result String Length: " + result.length);
				for (i=0;i!=objData.length; i++) {

					if (objData[i].id != '0') {
						if (result.length > 0) {
							result += ";" + objData[i].id;
						} else {
							result += objData[i].id;
						}
					}
				}


				console.log(result);
				// $('#theMenuList .serialize_output').text(result);
				
				var dataToSend = "data=" + result + "&id=" + containerID;
				$.ajax({
					url: "slideshow-order-action.php",
					type: "post",
					data: dataToSend,
					cache: false,
					dataType: "text",
					success: function () {
						console.log("slideshow photo order - updated...");
						$.bootstrapGrowl("<strong>Slideshow Photos</strong> has been updated.",{
							ele: $parentContainer,
							offset: {from: 'top', amount: 0},
							type: 'success',
							align: 'center',
							delay: 500,
							stackup_spacing: 0
						});
						$(window).resize();
					}
				});
		});
	}
	photoRemoverFunc();
	
	$("#photoItems").sortable({
		group: 'photoSelected',
		drop: false,
		nested: false
	});
}