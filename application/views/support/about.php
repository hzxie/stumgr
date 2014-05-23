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
				  <li><a>帮助和支持</a> <span class="divider">></span></li>
				  <li class="active">关于我们</li>
				</ul>
			</div> <!-- /maia-breadcrumb -->
			<div id="main-content" class="row">
				<div id="navigator" class="span3">
					<ul class="nav nav-list sidenav">
						<li id="nav-developer" class="active">
							<i class="icon-chevron-right"></i>
							<a href="#developer">开发人员</a>
						</li>
						<li id="nav-license">
							<i class="icon-chevron-right"></i>
							<a href="#license">许可协议</a>
						</li>
						<li id="nav-changelog">
							<i class="icon-chevron-right"></i>
							<a href="#changelog">更新日志</a>
						</li>
					</ul>
				</div> <!-- /navigator -->
				<div id="global-content" class="span7">
					<section id="developer" class="section-content">
						<div class="page-header">
							<h1>开发人员</h1>
						</div>
						<p><strong>本软件由如下志愿者提供技术支持:</strong></p>
						<ul id="supporter">
							<li>谢浩哲 &lt;<a href="mailto:zjhzxhz@gmail.com">zjhzxhz@gmail.com</a>&gt;</li>
							<li>王磊</li>
							<li>陈舜斌 &lt;<a href="mailto:chenshunbin1992@gmail.com">chenshunbin1992@gmail.com</a>&gt;</li>
							<li>靳昌&nbsp;&nbsp;&nbsp;&nbsp;&lt;<a href="mailto:amosjin45@gmail.com">amosjin45@gmail.com</a>&gt;</li>
						</ul>
						<p><strong>特别致谢:</strong></p>
						<ul id="supporter">
							<li>金柳颀 &lt;<a href="mailto:kinuxroot@163.com">kinuxroot@163.com</a>&gt;</li>
							<li>李翀&nbsp;&nbsp;&nbsp;&nbsp;&lt;<a href="mailto:aresherochong@gmail.com">aresherochong@gmail.com</a>&gt;</li>
							<li>华心童 &lt;<a href="mailto:ideal19920402@gmail.com">ideal19920402@gmail.com</a>&gt;</li>
						</ul>
					</section>
					<section id="license" class="section-content">
						<div class="page-header">
							<h1>许可协议</h1>
						</div>
						<p><strong>本软件授权给: </strong>合肥工业大学软件学院</p>
						<p>本软件使用与发行遵循 <a href="http://www.gnu.org/licenses/gpl.html">通用公共许可(GPL)</a> 协议.</p>
					</section>
					<section id="changelog" class="section-content">
						<div class="page-header">
							<h1>更新日志</h1>
						</div>
						<div id="version-2.0">
							<h3 class="version-header">
								V 2.1 <small>(2013年09月30日)</small>
							</h3>
							<ul>
								<li>修改部分UI细节</li>
								<li>完全兼容IE8浏览器</li>
								<li>使用Messenger.js进行消息提醒</li>
							</ul>
						</div> <!-- /version-2.1 -->
						<div id="version-2.0">
							<h3 class="version-header">
								V 2.0 <small>(2013年08月30日)</small>
							</h3>
							<ul>
								<li>使用BootStrap重新设计前端框架</li>
								<li>重构了全部代码</li>
								<li>优化了数据库结构</li>
							</ul>
						</div> <!-- /version-2.0 -->
						<div id="version-1.0">
							<h3 class="version-header">
								V 1.0 <small>(2013年06月30日)</small>
							</h3>
							<ul>
								<li>首次在软件学院使用该软件</li>
							</ul>
						</div> <!-- /version-1.0 -->
						<p><strong>Follow us on GitHub: </strong><a href="https://github.com/zjhzxhz/stumgr">GitHub@stumgr</a></p>
					</section>
				</div> <!-- /help-content -->
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
		var documentHeight = 0, headerHeight = $('#header').height(), topPadding = 96;
		$(document).ready(function() {
			var offset = $("#navigator").offset();
			documentHeight = $(document).height();
			$(window).scroll(function() {
				var sideBarHeight = $("#navigator").height();
				var currentPosition = $(window).scrollTop(),
					developerContentPosition = $('#developer').offset().top - 32,
					licenseContentPosition = $('#license').offset().top - 32,
					versionContentPosition = $('#changelog').offset().top - 32;
				if ( currentPosition > offset.top) {
					var newPosition = ($(window).scrollTop() - offset.top) + topPadding;
					var maxPosition = documentHeight - (sideBarHeight + 368);
					if (newPosition > maxPosition) {
						newPosition = maxPosition;
					}
					$("#navigator").offset({ top: headerHeight + newPosition, left: offset.left });
					if ( currentPosition < licenseContentPosition ) {
						$('#nav-developer').addClass('active');
						$('#nav-license').removeClass('active');
						$('#nav-changelog').removeClass('active');
					} else if ( currentPosition >= developerContentPosition && currentPosition < versionContentPosition ) {
						$('#nav-developer').removeClass('active');
						$('#nav-license').addClass('active');
						$('#nav-changelog').removeClass('active');
					} else {
						$('#nav-developer').removeClass('active');
						$('#nav-license').removeClass('active');
						$('#nav-changelog').addClass('active');
					}
				} else {
					$("#navigator").offset({ top: headerHeight + topPadding, left: offset.left });
				};   
			});   
		}); 
	</script>
</body>
</html>