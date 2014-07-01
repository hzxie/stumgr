<link href="<?php echo base_url(); ?>assets/css/datetimepicker.css" media="screen" rel="stylesheet" type="text/css" />

<div id="attendance-header" class="page-header">
	<h1>考勤情况</h1>
</div> <!-- /attendance-header -->
<div id="attendance-content" class="section">
	<div id="selector">
		<select id="available-years" class="span2">
			<?php
				foreach ( $available_years as $available_year ) {
					$year = $available_year['school_year'];
					echo '<option value="'.$year.'">'.$year.'-'.($year + 1).'学年</option>';
				}
			?>
		</select>
		<select id="available-grades" class="span2">
			<?php
				foreach ( $available_grades as $available_grade ) {
					$grade = $available_grade['grade'];
					echo '<option value="'.$grade.'">'.$grade.'级</option>';
				}
			?>
		</select>
		<select id="available-time" class="span2">
			<option value="a-week">最近7天</option>
			<option value="two-weeks">最近14天</option>
			<option value="a-month">最近一个月</option>
			<option value="all">全部</option>
		</select>
	</div>
	<div id="list">
		<table id="attendance-records" class="table table-hover">
			<thead>
				<tr>
					<td>学号</td>
					<td>姓名</td>
					<td>时间</td>
					<td>原因</td>
					<td>加减分</td>
				</tr>
			</thead>
			<tbody></tbody>
		</table>
	</div>
	<div id="page-error" class="alert alert-error hide"><strong>温馨提示: </strong>未找到可用数据.</div>
</div> <!-- /attendance-section -->

<!-- Attendance Record Modal -->
<div id="edit-attendance-record-dialog" class="modal dialog hide">
 	<div class="modal-header">
    	<button type="button" class="close">×</button>
    	<h3 id="attendance-dialog-title">编辑</h3>
  	</div>
  	<div class="modal-body">
  		<div id="error-message" class="alert alert-error hide"></div>
    	<table class="table no-border">
    		<tr class="no-border">
    			<td class="text-bold">学号</td>
    			<td class="text-normal"><label name="student_id"></label></td>
    			<td class="text-bold">姓名</td>
    			<td class="text-normal"><label name="student_name"></label></td>
    		</tr>
    		<tr class="no-border">
    			<td class="text-bold">时间</td>
    			<td>
    				<div id="datetimepicker" class="controls input-append date form_datetime" data-date-format="yyyy-mm-dd hh:ii">
						<input id="new-datetime" size="16" type="text" value="" class="span2" readonly>
						<span class="add-on"><i class="icon-remove"></i></span>
						<span class="add-on"><i class="icon-th"></i></span>
						<label id="old-datetime" class="hide"/></label><br/>
					</div>
    			</td>
    			<td class="text-bold">原因</td>
    			<td>
					<select name="reason" class="span2 reason">
						<?php
							foreach ( $rules as $rule ) {
								echo '<option value="'.$rule['rule_name'].'">'.$rule['description'].'</option>';
							}
						?>
					</select>
    			</td>
    		</tr>
    	</table>
  	</div>
  	<div class="modal-footer">
  		<button id="delete-record" class="btn btn-danger float-left">删除</button>
 	 	<button id="update-record" class="btn btn-primary">确认</button>
 	 	<button class="btn btn-cancel">取消</button>
 	</div>
</div> <!-- /Attendance Record Modal -->


<!-- DateTime Packer -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/datetimepicker.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/datetimepicker.zh-CN.js"></script>
<script type="text/javascript">
    $('.form_datetime').datetimepicker({
        language:  'zh-CN',
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		forceParse: 0,
        showMeridian: 1
    });
</script>

<!-- JavaScript for Document -->
<script type="text/javascript">
    $('#edit-attendance-nav').click(function(){
        $('#view-attendance-nav').removeClass('active');
        $('#view-attendance-tab').removeClass('active');
        $('#edit-attendance-nav').addClass('active');
        $('#edit-attendance-tab').addClass('active');
    });
    $('#view-attendance-nav').click(function(){
        $('#edit-attendance-nav').removeClass('active');
        $('#edit-attendance-tab').removeClass('active');
        $('#view-attendance-nav').addClass('active');
        $('#view-attendance-tab').addClass('active');
    });
</script>

<!-- JavaScript for Documents -->
<script type="text/javascript">
	function format_datetime_string(time) {
		var last_quotation_marks_index = time.lastIndexOf(':');
		return time.substring(0, last_quotation_marks_index);
	}
</script>
<script type="text/javascript">
	$('#available-years').change(function(){
		var selected_year = $(this).val(),
		    current_year = <?php echo $extra['current_school_year']; ?>;
		if ( selected_year == current_year ) {
			$('#available-time').val('a-week');
		} else {
			$('#available-time').val('all');
		}
	});
</script>
<script type="text/javascript">
	function get_attendance_records(year, grade, time) {
		$.ajax({
            type: 'GET',
            async: true,
            url: "<?php echo base_url().'admin/get_attendance_records/'; ?>" + year + '/' + grade + '/' + time,
            dataType: 'JSON',
            success: function(result) {
            	$('#attendance-records tbody').empty();
                if ( result['is_successful'] ) {
                	var total_records = result['records'].length;
                	for ( var i = 0; i < total_records; ++ i ) {
                		$('#attendance-records').append(
                			'<tr class="table-datum">' + 
                			'<td>' + result['records'][i]['student_id'] + '</td>' + 
                			'<td>' + result['records'][i]['student_name'] + '</td>' + 
                			'<td>' + format_datetime_string(result['records'][i]['time']) + '</td>' + 
                			'<td>' + result['records'][i]['description'] + '</td>' + 
                			'<td>' + result['records'][i]['additional_points'] + '</td>' + 
                			'</tr>'
                		);
                	}
                	set_visible('#page-error', false);
                	set_visible('#list', true);
                } else {
                	set_visible('#page-error', true);
                	set_visible('#list', false);
                }
            }
        });
	}
</script>
<script type="text/javascript">
    function set_visible(element, is_visible) {
        if ( is_visible ) {
            $(element).css('display', 'block');
        } else {
            $(element).css('display', 'none');
        }
        set_footer_position();  // which is defined in index.php
    }
</script>
<script type="text/javascript">
	function prepare_get_attendance_records() {
		var year  = $('#available-years').val(),
			grade = $('#available-grades').val(),
			time  = $('#available-time').val();
		get_attendance_records(year, grade, time);
	}
	$(document).ready(function(){
		prepare_get_attendance_records();
	});
	$('select').change(function(){
		prepare_get_attendance_records();
	});
</script>
<script type="text/javascript">
	$(document).ready(function(){
		$('#attendance-records').delegate('tr.table-datum', 'click', function() {
	        var datum_items  = $(this).find('td');
            var student_id   = datum_items.eq(0).html(),
                student_name = datum_items.eq(1).html(),
                time         = datum_items.eq(2).html(),
                reason       = datum_items.eq(3).html();
	        open_edit_attendance_record_dialog(student_id, student_name, time, reason);
	    });
	});
</script>
<script type="text/javascript">
	$('.btn-cancel').click(function(){
		close_edit_attendance_record_dialog();
	});
	$('.close').click(function(){
		close_edit_attendance_record_dialog();
	});
</script>
<script type="text/javascript">
	function open_edit_attendance_record_dialog(student_id, student_name, time, reason) {
		$('label[name="student_id"]').text(student_id);
		$('label[name="student_name"]').text(student_name);
		$('#old-datetime').text(time);
		$('#new-datetime').val(time);
		$('#datetimepicker').datetimepicker('update');
		$('#datetimepicker').datetimepicker('setEndDate', today());
		$('#edit-attendance-record-dialog').fadeIn();
		set_selected_reason(reason);
	}
</script>
<script type="text/javascript">
	function today() {
		var currentDate = new Date(),
			day = currentDate.getDate(),
			month = currentDate.getMonth() + 1,
			year = currentDate.getFullYear();
		return year + '-' + month + '-' + day;
	}
</script>
<script type="text/javascript">
	function set_selected_reason(reason) {
		$('select[name="reason"] option').each(function(){
			if ( $(this).text() == reason ) {
				$('select[name="reason"]').val($(this).val());
			}
		});
	}
</script>
<script type="text/javascript">
	function close_edit_attendance_record_dialog() {
		$('#edit-attendance-record-dialog').fadeOut();
	}
</script>
<script type="text/javascript">
	$('#update-record').click(function(){
		var student_id = $('label[name="student_id"]').text(),
			old_time   = $('#old-datetime').text(),
			new_time   = $('#new-datetime').val(),
			reason     = $('select[name="reason"]').val();
		edit_attendace_record(student_id, old_time, new_time, reason);
	});
</script>
<script type="text/javascript">
	function edit_attendace_record(student_id, old_time, new_time, reason) {
		var post_data     = 'student_id=' + student_id + '&old_time=' + old_time +
		                    '&new_time=' + new_time + '&reason=' + reason;
		$.ajax({
            type: 'POST',
            async: false,
            url: "<?php echo base_url(); ?>" + 'admin/edit_attendance_records/',
            data: post_data,
            dataType: 'JSON',
            success: function(result) {
                if ( result['is_successful'] ) {
                	$('#error-message').fadeOut();
                	close_edit_attendance_record_dialog();
                	refresh_datum_item(student_id, old_time, new_time, reason);
                } else {
                	$('#error-message').html('无法完成请求: 可能是该记录已经存在.');
                	$('#error-message').fadeIn();
                }
            },
            error: function() {
            	$('#error-message').html('发生未知错误.');
            	$('#error-message').fadeIn();
            }
        });
	}
</script>
<script type="text/javascript">
	function refresh_datum_item(student_id, old_time, new_time, reason) {
		$('.table-datum').each(function(index) {
            if ( $(this).children().eq(0).html() == student_id &&
                 $(this).children().eq(2).html() == old_time ) {
            	$(this).children().eq(2).html(new_time);
            	$(this).children().eq(3).html(get_rules_description(reason));
            	$(this).children().eq(4).html(get_rules_additional_points(reason));
            }
        });
	}
</script>
<script type="text/javascript">
	function get_rules_description(rule_name) {
		var rules  = <?php echo json_encode($rules); ?>;
		var length = rules.length;
		for ( i = 0; i < length; ++ i ) {
			if ( rules[i]['rule_name'] == rule_name ) {
				return rules[i]['description'];
			}
		}
	}
</script>
<script type="text/javascript">
	function get_rules_additional_points(rule_name) {
		var rules = <?php echo json_encode($rules); ?>;
		var length = rules.length;
		for ( i = 0; i < length; ++ i ) {
			if ( rules[i]['rule_name'] == rule_name ) {
				return rules[i]['additional_points'];
			}
		}
	}
</script>
<script type="text/javascript">
	$('#delete-record').click(function(){
		var result = confirm('您确定要删除该考勤记录吗?\n该操作将无法恢复!');
	    if (result == true) {
			var student_id = $('label[name="student_id"]').text(),
				time   = $('#old-datetime').text();
			delete_attendace_record(student_id, time);
		}
	});
</script>
<script type="text/javascript">
	function delete_attendace_record(student_id, time) {
		var post_data = 'student_id=' + student_id + '&time=' + time;
		$.ajax({
            type: 'POST',
            async: false,
            url: "<?php echo base_url(); ?>" + 'admin/delete_attendance_records/',
            data: post_data,
            dataType: 'JSON',
            success: function(result) {
                if ( result['is_successful'] ) {
                	$('#error-message').fadeOut();
                	close_edit_attendance_record_dialog();
                	delete_datum_item(student_id, time);
                } else {
                	$('#error-message').html('发生未知错误.');
                	$('#error-message').fadeIn();
                }
            },
            error: function() {
            	$('#error-message').html('发生未知错误.');
            	$('#error-message').fadeIn();
            }
        });
	}
</script>
<script type="text/javascript">
	function delete_datum_item(student_id, time) {
		$('.table-datum').each(function(index) {
            if ( $(this).children().eq(0).html() == student_id &&
                 $(this).children().eq(2).html() == time ) {
            	$(this).remove();
            }
        });
	}
</script>
