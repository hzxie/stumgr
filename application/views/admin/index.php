<!DOCTYPE html>
<html>
<head>
	<title>软件学院学生管理系统</title>
	<meta charset='utf-8' />
	<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- Page Icon -->
	<link rel="shortcut icon" href="<?php echo base_url('assets/img/favicon.png'); ?>" />
	<!-- CSS -->
	<link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap-responsive.min.css'); ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/css/admin/style.css'); ?>">
	<!-- Java Script -->
	<script src="<?php echo base_url('assets/js/jquery-1.11.0.min.js'); ?>"></script>
</head>

<body>
	<div id="container">
		<div id="header">
			<?php
				require_once APPPATH.'views/common/header-for-users.php';
			?>
		</div> <!-- /header -->
		<div id="content">
			<div id="sidebar">
				<div id="sidebar-nav-frame-welcome" class="sidebar-nav">
					<a href="#welcome"><div id="sidebar-nav-welcome" class="sidebar-primary-nav">欢迎</div></a>
				</div>
				<div id="sidebar-nav-frame-users" class="sidebar-nav">
					<a href="javascript:void(0)"><div id="sidebar-nav-users" class="sidebar-primary-nav">用户管理</div></a>
					<a href="#add_users"><div id="sidebar-nav-add_users" class="sidebar-secondary-nav">添加用户</div></a>
					<a href="#edit_users"><div id="sidebar-nav-edit_users" class="sidebar-secondary-nav">编辑用户</div></a>
				</div>
				<div id="sidebar-nav-frame-routine" class="sidebar-nav">
					<a href="javascript:void(0)"><div id="sidebar-nav-routine" class="sidebar-primary-nav">每周常规</div></a>
					<a href="#rules"><div id="sidebar-nav-rules" class="sidebar-secondary-nav">规则设置</div></a>
					<a href="#attendance"><div id="sidebar-nav-attendance" class="sidebar-secondary-nav">考勤情况</div></a>
					<a href="#hygiene"><div id="sidebar-nav-hygiene" class="sidebar-secondary-nav">卫生情况</div></a>
				</div>
				<div id="sidebar-nav-frame-scores" class="sidebar-nav">
					<a href="javascript:void(0)"><div id="sidebar-nav-scores" class="sidebar-primary-nav">成绩分析</div></a>
					<a href="#score_settings"><div id="sidebar-nav-score_settings" class="sidebar-secondary-nav">参数设置</div></a>
					<a href="#transcripts"><div id="sidebar-nav-transcripts" class="sidebar-secondary-nav">成绩分析</div></a>
					<a href="#gpa"><div id="sidebar-nav-gpa" class="sidebar-secondary-nav">GPA计算</div></a>
				</div>
				<div id="sidebar-nav-frame-evaluation" class="sidebar-nav">
					<a href="javascript:void(0)"><div id="sidebar-nav-evaluation" class="sidebar-primary-nav">综合测评</div></a>
					<a href="#evaluation_settings"><div id="sidebar-nav-evaluation_settings" class="sidebar-secondary-nav">参数设置</div></a>
					<a href="#assessment"><div id="sidebar-nav-assessment" class="sidebar-secondary-nav">学生互评</div></a>
					<a href="#rewards"><div id="sidebar-nav-rewards" class="sidebar-secondary-nav">奖惩情况</div></a>
					<a href="#result"><div id="sidebar-nav-result" class="sidebar-secondary-nav">查看结果</div></a>
				</div>
			</div> <!-- /sidebar -->
			<div id="loading">正在加载...</div>
			<div id="shadow"></div>
			<div id="main-container">
				<noscript>
					<p>您的浏览器不支持 JavaScript 或 JavaScript 已停用.</p>
					<p>由于您的浏览器不支持 JavaScript 或已经停用 JavaScript, 因此我们无法显示请求的网页.</p>
				</noscript>
				<div id="main-content">
				</div> <!-- /main-content -->
				<?php
					require_once APPPATH.'views/common/footer.php';
				?>
			</div> <!-- /main-content -->
		</div> <!-- /content -->
	</div> <!-- /container -->
	<!-- Java Script -->
	<!-- Placed at the end of the document so the pages load faster -->
	<script type="text/javascript">
		$(document).ready(function() {
			$(window).resize(function() {
				set_footer_position();
			});
			$('#main-container').scroll(function() {
				if ( $('#main-content').position().top < 0 ) {
					$('#shadow').css('opacity', 1);
				} else {
					$('#shadow').css('opacity', 0);
				}
			});
		});
	</script> 
	<script type="text/javascript">
		$(document).ready(function() {
			var page_name = window.location.href.match(/#.*$/);
			if ( page_name == null ) {
				load('welcome');
			} else {
				load(window.location.href.match(/#.*$/)[0].substr(1));
			}
		});
	</script> 
	<script type="text/javascript">
		if ( ("onhashchange" in window) && !navigator.userAgent.toLowerCase().match(/msie/) ) {
			$(window).on('hashchange', function() {
				set_loading_block(true, true);
				load(window.location.href.match(/#.*$/)[0].substr(1));
			});
		} else {
			var prevHash = window.location.hash;
	        window.setInterval(function () {
	           if (window.location.hash != prevHash) {
	              prevHash = window.location.hash;
	              set_loading_block(true, true);
	              load(window.location.href.match(/#.*$/)[0].substr(1));
	           }
	        }, 100);
		}
	</script>
	<script type="text/javascript">
		function load(page){
			$('.active').removeClass('active');
			var sidebar_nav = $('#sidebar-nav-' + page);
			sidebar_nav.addClass('active');
			sidebar_nav.closest('.sidebar-nav').children().children('.sidebar-secondary-nav').slideDown(0);
			$.ajax( {
				type: 'GET',
				url: "<?php echo base_url('admin/load/'); ?>" + page,
				cache: false,
				async: false,
				error: function() {
					set_loading_block(false, true);
				}, // End of error function of ajax form
				success: function(html_content) {
					if ( html_content != '' ) {
						$("#main-content").html(html_content);
						set_loading_block(true, false);
					} else {
						set_loading_block(false, true);
					}
					set_footer_position();
				} // End of success function of ajax form
			}); // End of ajax call
		}
	</script>
	<script type="text/javascript">
		function set_loading_block(is_success, is_visible) {
			if ( is_success ) {
				$('#loading').removeClass('error');
				$('#loading').addClass('loading');
				$('#loading').html('正在加载...');
			} else {
				$('#loading').removeClass('loading');
				$('#loading').addClass('error');
				$("#main-content").html('');
				var page = window.location.href.match(/#.*$/)[0].substr(1);
				$('#loading').html('发生未知错误 <a href="#' + page + 
									'" onclick="javascript:set_loading_block(' + true + ', ' + true + '); ' + 
									'javascript:load(\'' + page + '\');' + ' return false;">重试</a>');
			}

			if ( is_visible ) {
				$('#loading').fadeIn();
			} else {
				$('#loading').css('display', 'none');
			}
		}
	</script>
	<script type="text/javascript">
		function set_footer_position() {
			$('#footer').css('position', 'relative');
			if ( $('#footer').position().top + 144 < $(document).height() ) {
				$('#footer').css('position', 'absolute');
			} else {
				$('#footer').css('position', 'relative');
			}
		}
	</script>
	<script type="text/javascript">
		$('.sidebar-primary-nav').click(function() {
			var secondary_nav_items = $(this).closest('.sidebar-nav').children().children('.sidebar-secondary-nav');
			if ( secondary_nav_items.is(':visible') ) {
				secondary_nav_items.slideUp(120);
			} else {
				secondary_nav_items.slideDown(120);
			}
		});
	</script>
</body>
</html>
