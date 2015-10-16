$(document).ready(function() {

	/* TEXT EDITOR */
	if ($('.texteditor').length > 0) {
		/*$('.wysihtml5').wysihtml5({
			"html": true
		});*/
		$('.texteditor').jqte();
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

			var group = $("#theMenuList").sortable({
				group: 'theMenuList',
				nested: true,
				onDrop: function (item, container, _super) {
					if(!container.options.drop)
	      				item.clone().insertAfter(item)

	      			item.removeClass('menuItem').find('.hide').removeClass('hide');
	      			item.find('.show').removeClass('show').addClass('hide');

					var objData = $('#theMenuList').sortable("serialize").get();
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

						// if obj has children
						if (objData[i].children) {

							// put parent id first
							if (result.length > 0) {
								result += "," + objData[i].id + ";";
							} else {
								result += objData[i].id + ";";
							}

							// add the children
							childObj = "";
							console.log("Child String Length: " + childObj.length);
							for (i2=0;i2!=objData[i].children.length; i2++) {
								if (childObj.length > 0) {
									childObj += "-" + objData[i].children[i2].id;
								} else {
									childObj += objData[i].children[i2].id;
								}
							}
							result += childObj;

						// when obj is sterile (lol @ parent)
						} else {
							if (result.length > 0) {
								result += "," + objData[i].id;
							} else {
								result += objData[i].id;
							}
							
						}
					}

					console.log(result);
					// $('#theMenuListWrapper .serialize_output').text(result);
					
					var dataToSend = "data=" + result;
					$.ajax({
						url: "menu-order-action.php",
						type: "post",
						data: dataToSend,
						cache: false,
						dataType: "text",
						success: function () {
							console.log("menu order - updated...");
							$.bootstrapGrowl("<strong>Menu List</strong> has been updated.",{
								ele: '#theMenuListWrapper',
								offset: {from: 'top', amount: 0},
								type: 'success',
								align: 'center'
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
						maxDepth = 2,
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

			$('.menuItemRemove').on('click',function(e){
				e.preventDefault();
				var $parent = $(this).parent().parent().parent();
				$parent.remove();
				console.log("Removing menu item: " + $parent.attr('data-id'));

				var objData = $('#theMenuList').sortable("serialize").get();
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

						// if obj has children
						if (objData[i].children) {

							// put parent id first
							if (result.length > 0) {
								result += "," + objData[i].id + ";";
							} else {
								result += objData[i].id + ";";
							}

							// add the children
							childObj = "";
							console.log("Child String Length: " + childObj.length);
							for (i2=0;i2!=objData[i].children.length; i2++) {
								if (childObj.length > 0) {
									childObj += "-" + objData[i].children[i2].id;
								} else {
									childObj += objData[i].children[i2].id;
								}
							}
							result += childObj;

						// when obj is sterile (lol @ parent)
						} else {
							if (result.length > 0) {
								result += "," + objData[i].id;
							} else {
								result += objData[i].id;
							}
							
						}
					}

					console.log(result);
					$('#theMenuList .serialize_output').text(result);
					
					var dataToSend = "data=" + result;
					$.ajax({
						url: "menu-order-action.php",
						type: "post",
						data: dataToSend,
						cache: false,
						dataType: "text",
						success: function () {
							console.log("menu order - updated...");
							$.bootstrapGrowl("<strong>Menu List</strong> has been updated.",{
								ele: '#theMenuListWrapper',
								offset: {from: 'top', amount: 0},
								type: 'success',
								align: 'center'
							});
						}
					});

			});
			
			$("#theMenuItems").sortable({
				group: 'theMenuList',
				drop: false,
				nested: false
			});

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
						url: "gallery-order-action.php",
						type: "post",
						data: dataToSend,
						cache: false,
						dataType: "text",
						success: function () {
							console.log("gallery order - updated...");
							$.bootstrapGrowl("<strong>Gallery</strong> has been updated.",{
								ele: $parentContainer,
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

	} // end of slideshowSortableFunc
	slideshowSortableFunc();


	$('.formCancel').on("click", function(){
		parent.history.back();
		return false;
	});

	$('.dropdown.keep-open').on({
		"shown.bs.dropdown": function() { $(this).data('closable', false); },
		"click": function() { $(this).data('closable', true);  },
		"hide.bs.dropdown": function() { return $(this).data('closable'); }
	});


	/* TABLE SORTING */

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


	/* FILE UPLOAD STUFF */
	if ($('.fileupload').length>0) {
		$('#photo_fileupload').fileupload({
			// url: 'uploader/php/',
			previewMaxWidth : 230,
			previewMaxHeight : 340
		}).on('fileuploadsubmit', function (e, data) {
			data.formData = data.context.find(':input').serializeArray();
		});

		/*
		$('#slideshow_fileupload').fileupload({
			url: 'uploader/php/index_3.php',
			acceptFileTypes : /(\.|\/)(gif|jpe?g|png)$/i,
			previewMaxWidth : 80,
			previewMaxHeight : 80

		}).on('fileuploadsubmit', function (e, data) {
			data.formData = data.context.find(':input').serializeArray();
		});
		*/

	}

	if ($('.photo-delete').length>0) {
		// check all checkbox
		$('.delete-buttonbar .delete-toggle').on('click',function(){
			var $this = $(this);
			if ($this.is(':checked')) {
				$('.grid .deleteBox').prop('checked', true);
				
			} else {
				$('.grid .deleteBox').prop('checked', false);
			}	
			
			var checkedBoxes;
			$('.grid .deleteBox:checked').each(function() {
				if (checkedBoxes != undefined)
					checkedBoxes += $(this).val() + ";";
				else
					checkedBoxes = $(this).val() + ";";
			});
			$('#photo_delete #items').val(checkedBoxes);
			
			console.log($('#photo_delete #items').val());
		});

		$('.grid .deleteBox').click(function(e){
				var checkedBoxes;
				$('.grid .deleteBox:checked').each(function() {
					if (checkedBoxes != undefined)
						checkedBoxes += $(this).val() + ";";
					else
						checkedBoxes = $(this).val() + ";";
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
		$('.slideshowForm').validate({
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
		});



		/* VALIDATE PAGE FORM */
		$('.pageForm').validate({
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
		});


		// VALIDATE THE CATEGORY FORM 
		$('.categoryForm').validate({
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
		});
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
		$('#slideshow-tabs a').click(function (e) {
			e.preventDefault();
			$(this).tab('show');
		});
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

	$('[data-toggle="tooltip"]').tooltip();


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
		$('#pageType0').change(function(){
			$('.menu-type-panel').removeClass('show').hide();
			$('#panel-placeholder').show();
		});
		$('#pageType1').change(function(){
			$('.menu-type-panel').removeClass('show').hide();
			$('#panel-page').show();
		});
		$('#pageType2').change(function(){
			$('.menu-type-panel').removeClass('show').hide();
			$('#panel-gallery').show();
		});
		$('#pageType3').change(function(){
			$('.menu-type-panel').removeClass('show').hide();
			$('#panel-hyperlink').show();
		});
	}

});



function openModal(url) {
	$.get(url, function(data) {
		$('<div class="modal" role="dialog">' + data + '</div>').modal();
	});
	console.log('modal init'); 
	return false;
}