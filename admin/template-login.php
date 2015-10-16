<!DOCTYPE html>
<html lang="<?php _e(SITE_LANG); ?>">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="shortcut icon" href="favicon.ico">
	<link rel="icon" type="image/png" href="favicon.png" />

	<title><?php _e($page->pageTitle . " - " . SITE_TITLE . " - " . SITE_VERSION); ?></title>

<!--
==========================================  
                                            
    _ __ ___  _   _ _ __ | | ___   _        
   | '_ ` _ \| | | | '_ \| |/ / | | |       
   | | | | | | |_| | | | |   <| |_| |       
   |_| |_| |_|\__,_|_| |_|_|\_\__,  |       
                                |___/       
                                            
   Carlo Abella - Design.Develop            
   www.carloabella.com                      
   www.fb.com/carloabelladotcom             
   mnkbox-work@yahoo.com.sg                 
                                            
==========================================  
-->

	<!-- Bootstrap core CSS -->
	<link rel="stylesheet" href="<?php _e(SITE_URL . "/" . SITE_LIB); ?>/css/bootstrap.css">
	<link rel="stylesheet" href="<?php _e(SITE_URL . "/" . SITE_LIB); ?>/font-awesome/css/font-awesome.min.css">

	<!-- Add custom CSS here -->
	<link rel="stylesheet" href="css/styles.css">
	<link rel="stylesheet" href="css/custom.css">
	
</head>

<body>
	<div id="login-wrapper">

		<!-- Sidebar -->
		<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="<?php _e(SITE_ADMIN_URL); ?>"><h1><?php _e(SITE_TITLE); ?></h1><h4><?php _e(SITE_VERSION); ?></h4></a>
			</div>

			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse navbar-ex1-collapse">
			</div><!-- /.navbar-collapse -->
		</nav>

		<!-- TEMPLATE CONTENT -->
		<!-- #loginBox-wrapper -->
		<div id="loginBox-wrapper">

			<?php _e(pageContent($page)); ?>

		</div><!-- /#loginBox-wrapper -->

		<div class="footer">
				<div class="col-lg-12">
					<p>Powered by <a href="<?php _e($site->system_url); ?>" target="_blank"><?php _e($site->system_name); ?></a> <?php _e(" &copy; " . date('Y')); ?> <a href="<?php _e($site->system_publisher_url); ?>" target="_blank"><?php _e($site->system_publisher); ?></a></p>
				</div>
		</div>

	</div><!-- /#login-wrapper -->

	<!-- JavaScript -->
	<script src="<?php _e(SITE_URL . "/" . SITE_LIB); ?>/js/jquery-2.1.0.min.js"></script>
	<script src="<?php _e(SITE_URL . "/" . SITE_LIB); ?>/js/bootstrap.js"></script>
	<script src="<?php _e(SITE_ADMIN_URL); ?>/js/bootstrap-growl.js"></script>

	<!-- Page Specific Includes -->
	<?php _e($page->pageIncludes); ?>

	<!-- custom scripts -->
	<script src="js/script.js"></script>

</body>
</html>