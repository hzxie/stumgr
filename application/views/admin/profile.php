<h1 class="redtext">账户管理</h1>
<div id="password-information">
	<div id="password-information-header" class="page-header">
		<span class="title">密码</span><span><a id="open-change-password-dialog" href="javascript:void(0)">更改密码</a></span>
	</div> <!-- /password-information-header -->
	<div id="password-information-content" class="section">
		<table class="table no-border" style="width: 84%;">
			<tr class="no-border"><td>
				<?php
					if ( $account['last_time_change_password'] == '0000-00-00 00:00:00' ) {
						echo '您尚未修改过密码, 我们强烈建议您修改密码.';	
					} else {
						$time = strtotime($account['last_time_change_password']);
						echo '上次修改时间: <strong>'.date('Y年m月d日 H:i',$time).'</strong>';
					}
				?>
			</td></tr>
		</table>
	</div> <!-- /password-information-content -->
</div> <!-- /password-information -->

<!-- Password Modal -->
<div id="password-dialog" class="modal hide dialog">
 	<div class="modal-header">
    	<button type="button" class="close">×</button>
    	<h3 id="password-dialog-title">更改密码</h3>
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
		$('#open-change-password-dialog').click(function(){
			$('#password-error-message').css('display', 'none');
            $('#password-dialog').fadeIn();
		});
		$('.close').click(function(){
			$('#password-dialog').fadeOut();
		});
		$('.btn-cancel').click(function(){
			$('#password-dialog').fadeOut();
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
            $('#password-dialog :input').attr('disabled', true);
        } else {
            $('#password-dialog :input').removeAttr('disabled');
        }
    }
</script>
<script type="text/javascript">
	function post_change_password_request(old_password, new_password, password_again) {
        var post_data = 'old_password=' + old_password + '&new_password=' + new_password + 
        				'&password_again=' + password_again;
        $.ajax({
            type: 'POST',
            async: true,
            url: "<?php echo base_url(); ?>" + 'admin/change_password/',
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