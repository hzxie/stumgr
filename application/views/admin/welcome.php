<div id="welcome-center-header" class="page-header">
	<h1>欢迎</h1>
</div> <!-- /welcome-center-header -->
<div id="welcome-center-section" class="section">
	<p><strong><?php echo $welcome['display_name']; ?></strong> 您好, 欢迎使用软件学院学生管理系统.</p>
	<p>您的IP地址: <strong><?php echo $welcome['ip_address']; ?></strong>.</p>
	<p><?php
		if ( $welcome['last_time_signin'] == '0000-00-00 00:00:00' ) {
			echo '这是您第一次使用该系统, 您可以单击<a href="'.base_url().'home#profile">此处</a>修改您的密码.';	
		} else {
			$time = strtotime($welcome['last_time_signin']);
			echo '上次访问时间: <strong>'.date('Y年m月d日 H:i',$time).'</strong>';
		}
	?></p>
</div> <!-- /welcome-center-section -->

<div id="dashboard-header" class="page-header">
	<h1>系统概况</h1>
</div> <!-- /accounts-header -->
<div id="dashboard-section" class="section">
	<table class="table" style="width: 84%;">
		
	</table>
</div> <!-- /accounts-section -->