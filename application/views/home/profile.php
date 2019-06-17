<h1 class="redtext">账户管理</h1>
<div id="basic-information">
	<div id="basic-information-header" class="page-header">
		<span class="title">基本信息</span><span><a id="open-edit-profile-dialog" href="javascript:void(0)">编辑</a></span>
	</div> <!-- /basic-information-header -->
	<div id="basic-information-content" class="section">
		<table class="table no-border" style="width: 84%;">
			<tr class="no-border">
				<td><strong>学号:</strong></td><td><?php echo $profile['student_id']; ?></td>
				<td><strong>姓名:</strong></td><td><?php echo $profile['student_name']; ?></td>
			</tr>
			<tr class="no-border">
				<td><strong>班级:</strong></td><td><?php echo $profile['grade'].'级'.$profile['class'].'班'; ?></td>
				<td><strong>寝室:</strong></td><td><?php echo $profile['room']; ?></td>
			</tr>
			<tr class="no-border">
				<td><strong>移动电话:</strong></td><td><?php echo $profile['mobile']; ?></td>
				<td><strong>电子邮件:</strong></td><td><?php echo $profile['email']; ?></td>
			</tr>
		</table>
	</div> <!-- /basic-information-content -->
</div> <!-- /basic-information -->
<div id="password-information">
	<div id="password-information-header" class="page-header">
		<span class="title">密码</span><span><a id="open-change-password-dialog" href="javascript:void(0)">更改密码</a></span>
	</div> <!-- /password-information-header -->
	<div id="password-information-content" class="section">
		<table class="table no-border" style="width: 84%;">
			<tr class="no-border"><td>
				<?php
					if ( $profile['last_time_change_password'] == '0000-00-00 00:00:00' ) {
						echo '您尚未修改过密码, 我们强烈建议您修改密码.';	
					} else {
						$time = strtotime($profile['last_time_change_password']);
						echo '上次修改时间: <strong>'.date('Y年m月d日 H:i',$time).'</strong>';
					}
				?>
			</td></tr>
		</table>
	</div> <!-- /password-information-content -->
</div> <!-- /password-information -->

<!-- Profile Modal -->
<div id="profile-dialog" class="modal hide dialog">
 	<div class="modal-header">
    	<button type="button" class="close">×</button>
    	<h2 id="profile-dialog-title">更新联系信息</h2>
  	</div>
  	<div class="modal-body">
  		<div id="profile-error-message" class="alert alert-error hide"></div>
    	<table class="table no-border">
    		<tr class="no-border">
    			<td class="text-bold">移动电话</td>
    			<td><input type="text" name="mobile" maxlength="11" value="<?php echo $profile['mobile']; ?>" /></td>
    		</tr>
    		<tr class="no-border">
    			<td class="text-bold">电子邮件</td>
    			<td><input type="text" name="email"　maxlength="36" value="<?php echo $profile['email']; ?>" /></td>
    		</tr>
    	</table>
  	</div>
  	<div class="modal-footer">
  		<button id="edit-profile" class="btn btn-primary">确认</button>
  		<button class="btn btn-cancel">取消</button>
 	 </div>
</div> <!-- /Profile Modal -->

<!-- Password Modal -->
<div id="password-dialog" class="modal hide dialog">
 	<div class="modal-header">
    	<button type="button" class="close">×</button>
    	<h2 id="password-dialog-title">更改密码</h2>
  	</div>
  	<div class="modal-body">
  		<div id="password-error-message" class="alert alert-error hide"></div>
    	<table class="table no-border">
    		<tr class="no-border">
    			<td class="text-bold">旧密码</td>
    			<td><input type="password" name="old-password" maxlength="16" /></td>
    		</tr>
    		<tr class="no-border">
    			<td class="text-bold">新密码</td>
    			<td><input type="password" name="new-password" maxlength="16" /></td>
    		</tr>
    		<tr class="no-border">
    			<td class="text-bold">确认新密码</td>
    			<td><input type="password" name="password-again" maxlength="16" /></td>
    		</tr>
    	</table>
  	</div>
  	<div class="modal-footer">
 	   <button id="change-password" class="btn btn-primary">确认</button>
 	   <button class="btn btn-cancel">取消</button>
 	</div>
</div> <!-- /Password Modal -->

<script type="text/javascript">
	$(document).ready(function() {
		$('#open-edit-profile-dialog').click(function(){
			if ( !$('#password-dialog').is(':visible') ) {
				$('#profile-error-message').addClass('hide');
				$('#profile-dialog').fadeIn();
			}
		});
		$('#open-change-password-dialog').click(function(){
			if ( !$('#profile-dialog').is(':visible') ) {
				$('#password-error-message').css('display', 'none');
				$('#password-dialog').fadeIn();
			}
		});
		$('.close').click(function(){
			$('#profile-dialog').fadeOut();
			$('#password-dialog').fadeOut();
		});
		$('.btn-cancel').click(function(){
			$('#profile-dialog').fadeOut();
			$('#password-dialog').fadeOut();
		});
		$('#edit-profile').click(function(){
			var mobile = $('input[name="mobile"]').val(),
                email = $('input[name="email"]').val();
            set_loading_mode(true);
            return post_change_profile_request(mobile, email);
		});
		$('#change-password').click(function(){
			var old_password = $('input[name="old-password"]').val(),
			    new_password = $('input[name="new-password"]').val(),
			    password_again = $('input[name="password-again"]').val();
            set_loading_mode(true);
			return post_change_password_request(old_password, new_password, password_again);
		});
	});
</script>
<script type="text/javascript">
    function set_loading_mode(is_loading) {
        if ( is_loading ) {
            $('#profile-dialog :input').attr('disabled', true);
            $('#password-dialog :input').attr('disabled', true);
        } else {
            $('#profile-dialog :input').removeAttr('disabled');
            $('#password-dialog :input').removeAttr('disabled');
        }
    }
</script>
<script type="text/javascript">
    function post_change_profile_request(mobile, email) {
        var post_data = 'mobile=' + mobile + '&email=' + email;
        $.ajax({
            type: 'POST',
            async: true,
            url: "<?php echo base_url('home/edit_profile/'); ?>",
            data: post_data,
            dataType: 'JSON',
            success: function(result) {
                if ( !result['is_successful'] ) {
                    var error_message = '';
                    if ( result['is_mobile_empty'] ) {
                        error_message += '请填写您的手机号码.<br />';
                    } else if ( !result['is_mobile_legal'] ) {
                        error_message += '请填写正确的手机号码.<br />';
                    }
                    if ( result['is_email_empty'] ) {
                        error_message += '请填写您的电子邮件地址.<br />';
                    } else if ( !result['is_email_legal'] ) {
                        error_message += '请填写正确的电子邮件地址.<br />';
                    }
                    if ( error_message == '' ) {
                        if ( !result['is_query_successful'] ) {
                            error_message = '发生未知错误.<br />';
                        }
                    }
                    $('#profile-error-message').html(error_message);
                    $('#profile-error-message').fadeIn();
                } else {
                    load('profile');  // Reload the div content
                }
            },
            error: function() {
                $('#profile-error-message').html('发生未知错误');
                $('#profile-error-message').fadeIn();
            }
        });
        set_loading_mode(false);
    }
</script>
<script type="text/javascript">
	function post_change_password_request(old_password, new_password, password_again) {
        var post_data = 'old_password=' + old_password + '&new_password=' + new_password + 
        				'&password_again=' + password_again;
        $.ajax({
            type: 'POST',
            async: true,
            url: "<?php echo base_url('home/change_password/'); ?>",
            data: post_data,
            dataType: 'JSON',
            success: function(result) {
            	if ( !result['is_successful'] ) {
            		var error_message = '';
            		if ( result['is_old_password_empty'] ) {
            			error_message += '请填写旧密码.<br />';
            		}
            		if ( result['is_new_password_empty'] ) {
            			error_message += '请填写新密码.<br />';
            		} else if ( !result['is_new_password_length_legal'] ) {
            			error_message += '新密码的长度必须为6-16个字符.<br />';
            		}
            		if ( result['is_password_again_empty'] ) {
            			error_message += '请确认新密码.<br />';
            		} else if ( !result['is_password_again_matched'] ) {
            			error_message += '新密码两次输入不一致.<br />';
            		}
                    if ( error_message == '' ) {
                        if ( !result['is_old_password_correct'] ) {
                            error_message = '旧密码不正确.<br />';
                        }
                    }
            		$('#password-error-message').html(error_message);
            		$('#password-error-message').fadeIn();
            	} else {
            		load('profile');  // Reload the div content
            	}
            },
            error: function() {
                $('#password-error-message').html('发生未知错误');
                $('#password-error-message').fadeIn();
            }
        });
        set_loading_mode(false);
    }
</script>