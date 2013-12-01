<div id="visitor-bar">
	<a href="<?php echo base_url(); ?>"><img id="logo" src="<?php echo base_url(); ?>public/img/product-logo.png" alt="软件学院学生管理系统" /></a>
	<div id="signin-button">
		<form class="form-inline" action="<?php echo base_url(); ?>accounts/signin" method="post" accept-charset="utf-8">
			<input id="username" name="username" type="text" class="input-small" placeholder="用户名" maxlength="16" />
			<input id="password" name="password" type="password" class="input-small" placeholder="密码" maxlength="16" />
			<label id="remember" class="checkbox">
		    	<input type="checkbox" id="persistent-cookie" name="persistent-cookie"> 保持登录状态
		  	</label>
		  	<button type="submit" class="btn btn-primary">登录</button>
		</form>
	</div> <!-- /signup-button -->
</div> <!-- /visitor-bar -->

<script type="text/javascript" src="<?php echo base_url().'public/js/placeholder.min.js'; ?>"></script>
<script type="text/javascript">$('input, textarea').placeholder();</script>