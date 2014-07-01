<div id="page-error" class="alert alert-error hide"><strong>温馨提示: </strong>发生未知错误.</div>
<div id="selector">
	<span>
		<select id="available-grades" class="span2">
			<?php
				foreach ( $available_grades as $available_grade ) {
					$grade = $available_grade['grade'];
					echo '<option value="'.$grade.'">'.$grade.'级</option>';
				}
			?>
		</select>
	</span>
    <span>
        <button id="delete-accounts" class="btn btn-danger float-right" style="margin-bottom: 12px;">删除全部用户</button>
    </span>
</div> <!-- /selector -->
<div id="list">
	<table id="students-list" class="table table-striped">
		<thead>
			<tr>
				<td>学号</td>
				<td>姓名</td>
				<td>班级</td>
				<td>寝室</td>
				<td>移动电话</td>
				<td>电子邮件</td>
				<td>用户组</td>
			</tr>
		</thead>
		<tbody></tbody>
	</table>
</div> <!-- /list -->

<!-- Common -->
<script type="text/javascript">
    function set_error_message(element, is_visible) {
        if ( is_visible ) {
            $(element).css('display', 'block');
        } else {
            $(element).css('display', 'none');
        }
        set_footer_position();  // which is defined in index.php
    }
</script>

<!-- Load Students List -->
<script type="text/javascript">
	function get_students_profile_list(grade) {
		$.ajax({
            type: 'GET',
            async: true,
            url: "<?php echo base_url().'admin/get_students_profile_list/'; ?>" + grade,
            dataType: 'JSON',
            success: function(result) {
                $('#students-list tbody').empty();
                if ( result['is_successful'] ) {
                	var total_students = result['students'].length;
                	for ( var i = 0; i < total_students; ++ i ) {
                		$('#students-list').append(
                			'<tr class="table-datum">' + 
                			'<td>' + result['students'][i]['student_id'] + '</td>' + 
                			'<td>' + result['students'][i]['student_name'] + '</td>' + 
                			'<td>' + result['students'][i]['class'] + '班</td>' + 
                			'<td>' + result['students'][i]['room'] + '</td>' + 
                			'<td>' + result['students'][i]['mobile'] + '</td>' + 
                			'<td>' + result['students'][i]['email'] + '</td>' + 
                			'<td>' + result['students'][i]['user_group']['display_name'] + '</td>' + 
                			'</tr>'
                		);
                	}
                	set_error_message('#page-error', false);
                } else {
                	set_error_message('#page-error', true);
                }
            }
        });
	}
</script>
<script type="text/javascript">
	$(document).ready(function(){
		var grade = $("#available-grades").val();
		get_students_profile_list(grade);
		set_footer_position();
	});
	$("#available-grades").change(function() {
		var grade = $("#available-grades").val();
		return get_students_profile_list(grade);
	});
</script>

<!-- Edit Profile -->
<!-- Profile Modal -->
<div id="profile-dialog" class="modal dialog hide">
 	<div class="modal-header">
    	<button type="button" class="close">×</button>
    	<h3 id="profile-dialog-title">编辑资料</h3>
  	</div>
  	<div class="modal-body">
  		<div id="dialog-error" class="alert alert-error hide"></div>
    	<table class="table no-border">
    		<tr class="no-border">
    			<td class="text-bold">学号</td>
    			<td class="text-normal"><label name="student_id"></label></td>
    			<td class="text-bold">姓名</td>
    			<td><input type="text" class="span2" name="student_name" maxlength="24"></td>
    		</tr>
    		<tr class="no-border">
    			<td class="text-bold">年级</td>
    			<td>
    				<div class="input-append">
    					<input class="span1" name="grade" type="text" maxlength="4">
    					<span class="add-on">级</span>
    				</div>
    			</td>
    			<td class="text-bold">班级</td>
    			<td>
    				<div class="input-append">
    					<input class="span1" name="class" type="text" maxlength="2">
    					<span class="add-on">班</span>
    				</div>
    			</td>
    		</tr>
    		<tr class="no-border">
                <td class="text-bold">用户组</td>
                <td>
                    <select name="user-group" class="span2">
                        <?php
                            foreach ( $user_groups as $group ) {
                                echo '<option value="'.$group['group_name'].'">'.$group['display_name'].'</option>';
                            }
                        ?>
                    </select>
                </td>
    			<td class="text-bold">寝室</td>
    			<td><input type="text" class="span2" name="room" maxlength="7"></td>
    		</tr>
            <tr class="no-border">
                <td class="text-bold">移动电话</td>
                <td><input type="text" class="span2" name="mobile" maxlength="11"></td>
                <td class="text-bold">电子邮件</td>
                <td><input type="text" class="span2" name="email" maxlength="36"></td>
            </tr>
    		<tr class="no-border">
    			<td class="text-bold">密码</td>
    			<td><input type="password" class="span2" name="password"　maxlength="16" /></td>
    		</tr>

    	</table>
  	</div>
  	<div class="modal-footer">
  		<button id="delete-account" class="btn btn-danger float-left">删除</button>
		<button id="edit-profile" class="btn btn-primary">确认</button>
		<button class="btn btn-cancel">取消</button>
 	</div>
</div> <!-- /Profile Modal -->

<script type="text/javascript">
	$(document).ready(function(){
	    $('#students-list').delegate('tr.table-datum', 'click', function(){
	        open_profile_dialog($(this).find('td').first().html());
	    });
	    $('.close').click(function(){
			close_profile_dialog();
		});
		$('.btn-cancel').click(function(){
			close_profile_dialog();
		});
        $('#edit-profile').click(function(){
            var student_id = $('label[name="student_id"]').text();
            set_loading_mode(true);
            edit_user_profile(student_id);
        });
	});
</script>
<script type="text/javascript">
	function open_profile_dialog(student_id) {
		get_user_profile(student_id);
		$('#profile-dialog').fadeIn();
	}
    function close_profile_dialog() {
        $('#profile-dialog').fadeOut();
    }
</script>
<script type="text/javascript">
    function set_loading_mode(is_loading) {
        if ( is_loading ) {
            $('#profile-dialog :input').attr('disabled', true);
        } else {
            $('#profile-dialog :input').removeAttr('disabled');
        }
    }
</script>
<script type="text/javascript">
	function get_user_profile(student_id) {
		$.ajax({
            type: 'GET',
            async: true,
            url: "<?php echo base_url().'admin/get_user_profile/'; ?>" + student_id,
            dataType: 'JSON',
            success: function(data) {
                $('label[name="student_id"]').text(data['student_id']);
                $('input[name="student_name"]').val(data['student_name']);
                $('input[name="grade"]').val(data['grade']);
                $('input[name="class"]').val(data['class']);
                $('select[name="user-group"]').val(data['user_group']['group_name']);
                $('input[name="room"]').val(data['room']);
                $('input[name="mobile"]').val(data['mobile']);
                $('input[name="email"]').val(data['email']);
            },
            error: function() {
                $('#dialog-error').html('发生未知错误.');
                set_error_message('#dialog-error', false);
            }
        });
	}
</script>
<script type="text/javascript">
	function edit_user_profile(student_id) {
        var student_name        = $('input[name="student_name"]').val(),
            grade               = $('input[name="grade"]').val(),
            classx              = $('input[name="class"]').val(),
            user_group_name     = $('select[name="user-group"]').val(),
            room                = $('input[name="room"]').val(),
            mobile              = $('input[name="mobile"]').val(),
            email               = $('input[name="email"]').val(),
            password            = $('input[name="password"]').val();
        var post_data = 'student_name=' + student_name + '&grade=' + grade + '&class=' + classx +
                        '&user_group_name=' + user_group_name + '&room=' + room + '&mobile=' + mobile + 
                        '&email=' + email + '&password=' + password;
		$.ajax({
            type: 'POST',
            async: true,
            url: "<?php echo base_url().'admin/edit_user_profile/'; ?>" + student_id,
            data: post_data,
            dataType: 'JSON',
            success: function(result) {
                if ( result['is_successful'] ) {
                    close_profile_dialog();
                    refresh_datum_item(student_id, student_name, classx, room, mobile, email, user_group_name);
                } else {
                    var error_message = '';
                    if ( result['is_student_name_empty'] ) {
                        error_message += '请填写学生姓名.<br />';
                    } else if ( !result['is_student_name_legal'] ) {
                        error_message += '请填写正确的学生姓名.<br />';
                    }
                    if ( result['is_grade_empty'] ) {
                        error_message += '请填写学生所在年级.<br />';
                    } else if ( !result['is_grade_legal'] ) {
                        error_message += '请填写正确的年级.<br />';
                    }
                    if ( result['is_class_empty'] ) {
                        error_message += '请填写学生所在班级.<br />';
                    } else if ( !result['is_class_legal'] ) {
                        error_message += '请填写正确的班级.<br />';
                    }
                    if ( result['is_room_empty'] ) {
                        error_message += '请填写学生所在寝室.<br />';
                    } else if ( !result['is_room_legal'] ) {
                        error_message += '请填写正确的寝室.<br />';
                    }
                    if ( !result['is_mobile_legal'] ) {
                        error_message += '请填写正确的手机号码.<br />';
                    }
                    if ( !result['is_email_legal'] ) {
                        error_message += '请填写正确的电子邮件地址.<br />';
                    }
                    if ( !result['is_password_legal'] ) {
                        error_message += '密码长度必须为6-16个字符.<br />';
                    }
                    if ( error_message == '' ) {
                        error_message += '发生位置错误.<br />'
                    }
                    $('#dialog-error').html(error_message);
                    set_error_message('#dialog-error', true);
                }
            }
        });
        set_loading_mode(false);
	}
</script>
<script type="text/javascript">
    function refresh_datum_item(student_id, student_name, classx, room, mobile, email, user_group_name) {
        $('.table-datum').each(function(index) {
            if ( $(this).find('td').first().html() == student_id ) {
                var datum_items = $(this).find('td');
                datum_items.eq(1).html(student_name);
                datum_items.eq(2).html(classx + '班');
                datum_items.eq(3).html(room);
                datum_items.eq(4).html(mobile);
                datum_items.eq(5).html(email);
                datum_items.eq(6).html($('select[name="user-group"] option[value="' + user_group_name + '"]').text());
            }
        });
    }
</script>
<!-- Delete Accounts -->
<script type="text/javascript">
    $('#delete-account').click(function(){
        var student_id = $('label[name="student_id"]').text();
        set_loading_mode(true);
        delete_account(student_id);
    });
    $('#delete-accounts').click(function(){
        var result = confirm('您确定要删除该年级所有学生的信息吗?\n该操作将无法恢复!');
        if (result == true) {
            var grade = $('#available-grades').val();
            $(this).text('请稍候...');
            $(this).prop('disabled', true);
            $('select').prop('disabled', true);
            delete_accounts(grade);
        }
    });
</script>
<script type="text/javascript">
    function delete_account(student_id) {
        $.ajax({
            type: 'GET',
            async: true,
            url: "<?php echo base_url().'admin/delete_account/'; ?>" + student_id,
            dataType: 'JSON',
            success: function(result) {
                if ( result['is_successful'] ) {
                    close_profile_dialog();
                    remove_datum_item(student_id);
                } else {
                    set_error_message('#page-error', false);
                }
            },
            error: function() {
                set_error_message('#page-error', false);
            }
        });
        set_loading_mode(false);
    }
</script>
<script type="text/javascript">
    function remove_datum_item(student_id) {
        $('.table-datum').each(function(index) {
            if ( $(this).find('td').first().html() == student_id ) {
                $(this).remove();
            }
        });
    }
</script>
<script type="text/javascript">
    function delete_accounts(grade) {
        $.ajax({
            type: 'GET',
            async: true,
            url: "<?php echo base_url().'admin/delete_accounts/'; ?>" + grade,
            dataType: 'JSON',
            success: function(result) {
                if ( result['is_successful'] ) {
                    location.reload();
                } else {
                    set_error_message('#page-error', false);
                }
            },
            error: function() {
                set_error_message('#page-error', false);
            }
        });
        set_loading_mode(false);
    }
</script>