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
	<link rel="stylesheet" href="<?php echo base_url(); ?>public/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>public/css/bootstrap-responsive.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>public/css/signin-page.css">
	<!-- Java Script -->
	<script src="<?php echo base_url(); ?>public/js/jquery-1.11.0.min.js"></script>
</head>

<body>
	<div id="wrapper">
		<div id="header">
			<img id="logo" src="<?php echo base_url(); ?>public/img/product-logo.png" alt="软件学院学生管理系统" />
			<span id="signup-button">
				<button id="signup" class="btn btn-danger">创建新账户</button>
			</span> <!-- /signup-button -->
		</div> <!-- /header -->
		<div id="content" class="row">
			<div id="introduction" class="span6">
				<img src="<?php echo base_url(); ?>public/img/bg.png" />
			</div>
			<div id="signin-box" class="span3">
				<h2>用户登录</h2>
				<form id="signin-form" action="<?php echo base_url(); ?>accounts/signin" method="post" accept-charset="utf-8">
					<div id="username-div">
						<label for="username"><strong>用户名</strong></label>
						<input type="text" id="username" name="username" maxlength="16" 
							   value="<?php echo ( isset($username) ? $username : '' ); ?>" />
						<?php 
							if ( isset($is_username_empty) && $is_username_empty ) {
								echo '<span id="errormsg_Username" class="errormsg">请输入您的用户名</span>';
							}
						?>
					</div> <!-- /username-div -->
					<div id="password-div">
						<label for="password"><strong>密码</strong></label>
						<input type="password" id="password" name="password" maxlength="16" />
						<?php 
							$is_print = false;
							if ( ( isset($is_password_empty) && $is_password_empty) ) {
								$is_print = true;
								echo '<span id="errormsg_Password" class="errormsg">请输入您的密码</span>';
							} 
							if ( !$is_print && isset($is_password_correct) && !$is_password_correct ) {
								echo '<span id="errormsg_Password" class="errormsg">用户名或密码不正确</span>';
							}
						?>
					</div> <!-- /password-div -->
					<button  type="submit" class="btn btn-primary">登录</button>
					<label id="remember" class="checkbox">
						<input id="persistent-cookie" name="persistent-cookie" type="checkbox" />
						<label id="remember-label">保持登录状态</label>
					</label>
				</form>
			</div> <!-- /signin-form -->
		</div> <!-- /content -->
	</div> <!-- /container -->
	<?php
		require_once APPPATH.'views/common/footer.php';
	?>
	<!-- Java Script -->
	<!-- Placed at the end of the document so the pages load faster -->
	<script type="text/javascript">
		$(document).ready(function() {
			$(window).resize(function() {
				set_footer_position()
			});
		});
	</script> 
	<script type="text/javascript">
		function set_footer_position() {
			$('#footer').css('position', 'relative');
			if ( $(window).height() < $(document).height() ) {
				$('#footer').css('position', 'relative');
			} else {
				$('#footer').css('position', 'absolute');
			}
		}
	</script>
</body>
</html>
