<link href="<?php echo base_url('/css/fineuploader.min.css'); ?>" media="screen" rel="stylesheet" type="text/css" />

<div id="add-users-header" class="page-header">
    <h1>添加用户</h1>
</div> <!-- /add-users-header -->
<div id="add-users-section" class="section">
    <div class="tabbable">
        <ul class="nav nav-tabs">
            <li id="add-a-user-nav" class="active"><a href="javascript:void(0)">添加单用户</a></li>
            <li id="add-users-nav"><a href="javascript:void(0)">添加多用户</a></li>
        </ul>
        <div class="tab-content">
            <div id="add-a-user-tab" class="tab-pane active">
                <div id="add-a-user-header" class="page-header">
                    <h2>用户信息</h2>
                </div> <!-- /add-a-user-header -->
                <div id="add-a-user-section" style="overflow: hidden">
                    <div id="add-a-user-message" class="alert hide"></div>
                    <table class="table no-border">
                        <tr class="no-border">
                            <td class="text">学号</td>
                            <td><input type="text" name="student_id" maxlength="10" /></td>
                            <td class="text">姓名</td>
                            <td><input type="text" name="student_name"　maxlength="24" /></td>
                        </tr>
                        <tr class="no-border">
                            <td class="text">年级</td>
                            <td>
                                <div class="input-append">
                                    <input class="span1" name="grade" type="text" maxlength="4">
                                    <span class="add-on">级</span>
                                </div>
                            </td>
                            <td class="text">班级</td>
                            <td>
                                <div class="input-append">
                                    <input class="span1" name="class" type="text" maxlength="2">
                                    <span class="add-on">班</span>
                                </div>
                            </td>
                        </tr>
                        <tr class="no-border">
                            <td class="text">寝室</td>
                            <td><input type="text" name="room" maxlength="7" placeholder="eg. 6#N318" /></td>
                            <td class="text">密码</td>
                            <td><input type="password" name="password"　maxlength="16" /></td>
                        </tr>
                        <tr class="no-border">
                            <td>
                                <input id="upload" type="submit" class="btn btn-primary" value="确认" onclick="javascript:post_add_user_request()" />
                                <button class="btn" onclick="javascript:reset()">重置</button>
                            </td>
                        </tr>
                    </table>
                </div> <!-- /add-a-user-section -->
            </div> <!-- /add-a-user-tab -->
            <div id="add-users-tab" class="tab-pane">
                <div id="add-users-header" class="page-header">
                    <h2>从Excel文件导入</h2>
                </div> <!-- /add-users-header -->
                <div id="add-users-section" style="overflow: hidden">
                    <form action="<?php echo base_url('admin/add_users'); ?>" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                        <table class="table no-border">
                            <tr class="no-border">
                                <td><small><a href="<?php echo base_url('/tpl/template-users.xlsx'); ?>">查看文件模板</a></small></td>
                            </tr>
                            <tr class="no-border">
                                <td><div id="jquery-wrapped-fine-uploader"></div></td>
                            </tr>
                        </table>
                    </form>
                </div> <!-- /add-users-section -->
            </div> <!-- /add-users-tab -->
        </div> <!-- /tab-content -->
    </div> <!-- /tabbable -->
</div> <!-- /add-users-section -->

<script type="text/javascript">
    $('#add-users-nav').click(function(){
        $('#add-a-user-nav').removeClass('active');
        $('#add-a-user-tab').removeClass('active');
        $('#add-users-nav').addClass('active');
        $('#add-users-tab').addClass('active');
        set_footer_position();
    });
    $('#add-a-user-nav').click(function(){
        $('#add-users-nav').removeClass('active');
        $('#add-users-tab').removeClass('active');
        $('#add-a-user-nav').addClass('active');
        $('#add-a-user-tab').addClass('active');
        set_footer_position();
    });
</script>

<!-- JavaScript for add a user tab -->
<script type="text/javascript" src="<?php echo base_url('/js/placeholder.min.js'); ?>"></script>
<script type="text/javascript">$('input, textarea').placeholder();</script>
<script type="text/javascript">
    function post_add_user_request() {
        var student_id   = $('input[name="student_id"]').val(),
            student_name = $('input[name="student_name"]').val(),
            grade        = $('input[name="grade"]').val(),
            classx       = $('input[name="class"]').val(),
            room         = $('input[name="room"]').val(),
            password     = $('input[name="password"]').val();
        var post_data = 'student_id=' + student_id + '&student_name=' + student_name + '&grade=' + grade + 
                        '&class=' + classx + '&room=' + room + '&password=' + password;
        $.ajax({
            type: 'POST',
            async: true,
            url: "<?php echo base_url('admin/add_user/'); ?>",
            data: post_data,
            dataType: 'JSON',
            success: function(result) {
                if ( result['is_successful'] ) {
                    display_message(true, '操作成功完成');
                    $('input[type="text"]').val('');
                    $('input[type="password"]').val('');
                } else {
                    if ( result['is_required_empty'] ) {
                        display_message(false, '必填项不能为空.');
                    } else if ( !result['is_information_legal'] ) {
                        display_message(false, '提交信息不合法.');
                    } else if ( result['is_user_exists'] ) {
                        display_message(false, '您所添加的用户已存在.');
                    } else if ( result['is_query_successful'] ) {
                        display_message(false, '发生未知错误.');
                    }
                }
            },
            error: function() {
                display_message(false, '发生未知错误.');
            }
        });
    }
</script>
<script type="text/javascript">
    function display_message(is_successful, message) {
        $('#add-a-user-message').html(message);
        if ( is_successful ) {
            $('#add-a-user-message').removeClass('alert-error');
            $('#add-a-user-message').addClass('alert-success');
        } else {
            $('#add-a-user-message').removeClass('alert-success');
            $('#add-a-user-message').addClass('alert-error');
        }
        $('#add-a-user-message').fadeIn();
    }
</script>
<script type="text/javascript">
    function reset() {
        var result = confirm('您确定要重置吗?\n该操作将无法恢复!');
        if (result == true) {
            $('input[type="text"]').val('');
            $('input[type="password"]').val('');
        }
    }
</script>

<!-- JavaScript for add users tab -->
<script type="text/javascript" src="<?php echo base_url('/js/fineuploader.min.js'); ?>"></script>
<script>
    $(document).ready(function () {
        $('#jquery-wrapped-fine-uploader').fineUploader({
            request: {
                endpoint: "<?php echo base_url('admin/add_users/'); ?>"
            },
            text: {
                uploadButton: '上传文件'
            }
        }).on('complete', function(event, id, file_name, result) {
            if ( result['is_successful'] ) {
                $('.fileUploader-upload-fail').last().addClass('fileUploader-upload-success');
                $('.fileUploader-upload-fail').last().removeClass('fileUploader-upload-fail');
                $('.fileUploader-upload-status-text').last().html('已成功导入所有学生信息. <a href="#logs"><small>查看详情</small></a>');
            } else {
                if ( !result['is_upload_successful'] ) {
                    $('.fileUploader-upload-status-text').last().html(result['error_message']);
                } else {
                    $('.fileUploader-upload-status-text').last().html('部分学生的信息未能成功导入. <a href="#logs"><small>查看详情</small></a>');
                }
            }
        });
    });
</script>
