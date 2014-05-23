<!DOCTYPE html>
<html>
<head>
	<title>软件学院学生管理系统</title>
	<meta charset='utf-8' />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- Page Icon -->
	<link rel="shortcut icon" href="http://rjxy.hfut.edu.cn/cms/wp-content/uploads/system-reserved/favicon.png" />
	<!-- CSS -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap-responsive.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/support.css">
	<!-- Java Script -->
	<script src="<?php echo base_url(); ?>assets/js/jquery-1.11.0.min.js"></script>	
</head>
<body>
	<div id="wrapper">
		<div id="header">
			<?php
				if ( isset( $profile ) ) {
					require_once APPPATH.'views/common/header-for-users.php';
				} else {
					require_once APPPATH.'views/common/header-for-visitors.php';
				}
			?>
		</div> <!-- /header -->
		<div id="content">
			<div id="maia-breadcrumb">
				<ul class="breadcrumb">
				  <li><a href="#">帮助和支持</a> <span class="divider">></span></li>
				  <li class="active">帮助中心</li>
				</ul>
			</div> <!-- /maia-breadcrumb -->
			<div id="main-content" class="row">
				<div id="navigator" class="span3">
					<ul class="nav nav-list sidenav">
						<li id="nav-developer" class="active">
							<i class="icon-chevron-right"></i>
							<a href="#developer">Title</a>
						</li>
					</ul>
				</div> <!-- /navigator -->
				<div id="global-content" class="span7">
				</div>
			</div> <!-- /main-content -->
		</div> <!-- /content -->
		<?php
			require_once APPPATH.'views/common/footer.php';
		?>
	</div> <!-- /container -->
	<!-- JavaScript -->
	<!-- Placed at the end of the document so the pages load faster -->
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>	
	<script type="text/javascript">   
		
	</script>
</body>
</html>