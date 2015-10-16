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
	$login_ref = 'options.php';
	// $login_ref = urlencode(curPageURL());
	header("location: login.php?ref=$login_ref");
}
?>

<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title" id="myModalLabel">Deleting Custom Logo</h4>
		</div>
		<div class="modal-body">

			<form class="form" name="form-delete-logo" id="optionsDeleteLogo" role="form" action="options-del-logo-action.php" method="post" enctype="multipart/form-data" target="_self">

			</form> <!-- /#form-create-slideshow -->

			<p>Are you sure you want to delete the custom logo <a href="javascript:void(0);" class="label label-info" data-toggle="tooltip" data-placement="top" title="Poof! Gone, just like magic!"><i class="fa fa-question"></i></a></p>

		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
			<button id="submitButton" type="button" class="btn btn-warning"><strong>Delete</strong></button>
		</div>
	</div>
</div>

<script>
	$('#submitButton').on('click',function(e){
		$('#optionsDeleteLogo').submit();
	});

	if ($('[data-toggle="tooltip"]').length>0) {
		$('[data-toggle="tooltip"]').tooltip();
	}


	$("#optionsDeleteLogo").submit(function() {

	    var url = "options-del-logo-action.php";

	    $('#site-options-logo-main').addClass('preload');

	    $.ajax({
	           type: "POST",
	           url: url,
	           success: function(data)
	           {

					$('#site-options-logo-main').load('options.php #site-options-logo',
						function (responseText, textStatus, XMLHttpRequest) {
							if (textStatus == "success") {

								$('#site-options-logo-main').removeClass('preload');



								if ($('#site-options-logo').length>0) {
									$(function () {
									    'use strict';
									    // Change this to the location of your server-side upload handler:
									    var url = 'uploader/php/logo.php';
									    $('#fileupload').fileupload({
									        url: url,
									        dataType: 'json',
									        done: function (e, data) {
									            /*$.each(data.result.files, function (index, file) {
									            	console.log(file);
									            	$('#site-option-logo-box img').attr("src",file.previewUrl);
									                $('<p/>').text(file.name).appendTo('#files');
									            });*/
									    
									    
									    		$('#site-options-logo-main').load('options.php #site-options-logo');
									    		
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
							}
						});


	           }
	         });

		

		// close the modal box
		$('.modal').modal('hide');

	    return false;
	});
</script>