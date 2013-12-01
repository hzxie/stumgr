<div id="nav-bar">
	<ul class="inline">
		<?php
			foreach ( $navigator_item as $key => $value ) {
				echo '<li><a href="'.$value.'">'. $key.'</a></li>';
			}
		?>
	</ul>
</div> <!-- /nav-bar -->
<div id="user-bar">
	<img id="logo" src="<?php echo base_url(); ?>public/img/product-logo.png" alt="软件学院学生管理系统" />
	<div id="user">
		<a id="profile-trigger" href="javascript:void(0)"><ul class="inline">
			<li><?php echo ( isset($profile) ? $profile['display_name'] : 'Unknown' ); ?></li>
			<li>
				<span id="gravatar-small"><img src="<?php echo base_url(); ?>public/img/gravatar.png" alt="gravatar" /></span>
				<span id="scroll-down-button"></span>
			</li>
		</ul></a>
	</div> <!-- /user -->
	<div id="profile">
		<span id="arrow"></span>
		<span id="arrow-shadow"></span>
		<div id="brief-profile">
			<div id="gravatar-large">
				<img src="<?php echo base_url(); ?>public/img/gravatar.png" alt="gravatar" />
			</div> <!-- gravatar-large -->
			<div id="profile-info">
				<div id="user-info">
					<span id="display-name"><?php echo ( isset($profile) ? $profile['display_name'] : 'Unknown' ); ?></span>
					<span id="username"><?php echo ( isset($profile) ? $profile['username'] : 'Unknown' ); ?></span>
				</div>
				<button class="btn btn-primary" onclick="javascript:window.location.href='<?php 
							if ( $profile['is_administrator'] ) {
								echo base_url().'admin#profile';
							} else {
								echo base_url().'home#profile';
							} 
						?>'">查看个人资料</button>
			</div> <!-- /profile-info -->
		</div> <!-- /brief-profile -->
		<div id="sign-out">
			<button class="btn" 
					onclick="javascript:window.location.href='<?php echo base_url(); ?>accounts/signout'">退出</button>
		</div> <!-- /sign-out -->
	</div> <!-- /profile -->
</div> <!-- /user-bar -->

<script type="text/javascript">
	$('#profile-trigger').click(function(event){
		if ($('#profile').is(':visible')) {
			$("#profile").slideUp(36);
		} else {
			$("#profile").slideDown(36);
		}
		event.stopPropagation();
		
		$(document).click(function() {
			$("#profile").slideUp(36);
		});
	});
</script>