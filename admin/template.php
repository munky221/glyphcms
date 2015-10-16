<!DOCTYPE html>
<html lang="<?php _e($site->site_lang); ?>">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="shortcut icon" href="favicon.ico">
	<link rel="icon" type="image/png" href="favicon.png" />

	<title><?php _e($page->pageTitle . " - " . $site->system_title . " - " . $site->system_version); ?></title>

	<!-- Bootstrap core CSS -->
	<link rel="stylesheet" href="<?php _e($site->url . SITE_LIB); ?>/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php _e($site->url . SITE_LIB); ?>/font-awesome/css/font-awesome.min.css">

	<!-- Add custom CSS here -->
	<link rel="stylesheet" href="css/styles.css">
	<link rel="stylesheet" href="css/custom.css">


<!--
========================================================

StyleFol.io by @carloabella (fb.com/carloabella)
Copyright 2014 XFX Virtual Media Interactive, Inc.
http://www.stylefol.io

========================================================
-->

	
</head>

<body>
	<div id="wrapper">

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
				<a class="navbar-brand" href="<?php _e(SITE_ADMIN_URL); ?>"><h1><?php _e($site->system_title); ?></h1><h4>version <?php _e($site->system_version); ?></h4></a>
			</div>

			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse navbar-ex1-collapse">

				<?php buildAdminMenu($pageArray,$page); ?>

				<?php
                $uid = $_SESSION['user_id'];
                $q = "SELECT * FROM `users` WHERE `user_id` = '$uid'";
                $r = $db->query($q);
                $a = $db->fetch_array_assoc($r);
                ?>
				<ul class="nav navbar-nav navbar-right navbar-user">
					<li class="dropdown user-dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<img src="<?php _e(get_gravatar($a['user_email'],40)); ?>">
						</i> Hello, <?php _e($a['nice_name']); ?> <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a href="profile.php"><i class="fa fa-user"></i> Profile</a></li>
							<!-- <li class="divider"></li> -->
							<li><a href="?logout"><i class="fa fa-power-off"></i> Log Out</a></li>
						</ul>
					</li>
				</ul>
				
			</div><!-- /.navbar-collapse -->
		</nav>

		<!-- TEMPLATE CONTENT -->
		<!-- #page-wrapper -->
		<div id="page-wrapper">

			<div class="modal fade bs-example-modal-lg" id="modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"><div id="modal-preloader"></div></div>

			<?php _e(pageContent($page)); ?>

		</div><!-- /#page-wrapper -->

		<div class="footer">
				<div class="col-lg-12">
					<p>Powered by <a href="<?php _e($site->system_url); ?>" target="_blank"><?php _e($site->system_name); ?></a> <?php _e(" &copy; " . date('Y')); ?> <a href="<?php _e($site->system_publisher_url); ?>" target="_blank"><?php _e($site->system_publisher); ?></a></p>
				</div>
		</div>

	</div><!-- /#wrapper -->

	<!-- JavaScript -->
	<script src="<?php _e($site->url . SITE_LIB); ?>/js/jquery-2.1.0.min.js"></script>
	<script src="<?php _e($site->url . SITE_LIB); ?>/js/bootstrap.min.js"></script>
	<script src="<?php _e($site->url); ?>/admin/js/bootstrap-growl.js"></script>

	<!-- Page Specific Includes -->
	<?php _e($page->pageIncludes); ?>

	<!-- custom scripts -->
	<script src="js/script.js"></script>


	<?php if (!empty($_SESSION['site']['return_msg'])) { ?>
	<script>
		$.bootstrapGrowl("<?php _e($_SESSION['site']['return_msg']); ?>",{
			ele: '.breadcrumb',
			offset: {from: 'top', amount: 0},
			type: '<?php _e($_SESSION['site']['return_type']); ?>',
			align: 'center',
			delay: 50000,
			stackup_spacing: 0
		});
	</script>
	<?php } ?>
	<?php unset($_SESSION['site']['return_msg']); ?>

</body>
</html>