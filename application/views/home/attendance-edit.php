<link href="<?php echo base_url(); ?>assets/css/datetimepicker.css" media="screen" rel="stylesheet" type="text/css" />

<div id="selector">
	<select id="available-years" class="span2" disabled="disabled">
		<?php
			foreach ( $available_years as $available_year ) {
				$year = $available_year['school_year'];
				echo '<option value="'.$year.'">'.$year.'-'.($year + 1).'学年</option>';
			}
		?>
	</select>
	<select id="available-semesters" class="span2" disabled="disabled">
		<option value="1">第一学期</option>
		<option value="2">第二/三学期</option>
	</select>
	<button class="btn btn-info float-right" style="margin-bottom: 12px;" onclick="javascript:add_new_table_datum();">添加记录</button>
</div> <!-- /selector -->
<div id="post-result" class="alert hide"></div> <!-- /post-result -->
<div id="list">
	<table id="attendence-list" class="table">
		<thead>
			<tr>
				<td>姓名</td>
				<td>时间</td>
				<td>原因</td>
				<td>加减分</td>
			</tr>
		</thead>
		<tbody></tbody>
	</table>
	<button class="btn btn-primary" onclick="javascript:add_attendance_records();">提交</button>
	<button class="btn" onclick="javascript:reset_form();">重置</button>
</div> <!-- /list -->

<!-- DateTime Packer -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/datetimepicker.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/datetimepicker.zh-CN.js"></script>
<script type="text/javascript">
    function initialize_datetimepicker() {
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
    }
</script>

<!-- JavaScript for Document -->
<script type="text/javascript">
	jQuery.extend({
    	get_extra_data: function() {
    		var extra_data = null;
			$.ajax({
	            type: 'GET',
	            async: false,
	            url: "<?php echo base_url().'home/get_extra_attendance_data_for_administration'; ?>",
	            dataType: 'JSON',
	            success: function(data) {
	            	extra_data = data;
	            }
	        });
	        return extra_data;
	    }
	});
	var extra_data = $.get_extra_data();
</script>
<script type="text/javascript">
	function add_new_table_datum(number_of_new_rows) {
		number_of_new_rows = ( typeof number_of_new_rows === 'undefined' ? 1 : number_of_new_rows );

		var current_id = $('#attendence-list tr').length;
		for ( i = 0; i < number_of_new_rows; ++ i, ++ current_id ) {
			$('#attendence-list').append(
				'<tr class="table-datum">' + 
					'<td>' +
						'<select id="students-' + current_id + '" class="span2">' + 
						'</select>' + 
					'</td>' +
					'<td>' +
						'<div id="datetimepicker" class="controls input-append date form_datetime" data-date-format="yyyy MM dd - hh:ii" data-link-field="datetime-' + current_id + '">' + 
							'<input size="16" type="text" value="" class="span2" readonly>' +
							'<span class="add-on"><i class="icon-remove"></i></span>' +
							'<span class="add-on"><i class="icon-th"></i></span>' +
							'<input type="hidden" id="datetime-' + current_id + '" value="" /><br/>' +
						'</div>' +
					'</td>' +
					'<td>' +
						'<select id="reason-' + current_id + '" class="span2 reason">' + 
						'</select>' +
					'</td>' +
					'<td id="additional-points-' + current_id + '" class="text-normal">0</td>' +
				'</tr>'
			);
			get_students_options('students-' + current_id);
			get_reason_options('reason-' + current_id);
		}
		initialize_datetimepicker();
		$('#datetimepicker').datetimepicker('setEndDate', today());
		set_footer_position();
	}
</script>
<script type="text/javascript">
	function get_students_options(element) {
		var total_students = extra_data['students'].length;
		$('#' + element).append(new Option('', ''));
		for ( j = 0; j < total_students; ++ j ) {
			var student_id   = extra_data['students'][j]['student_id'],
				student_name = extra_data['students'][j]['student_name'],
				option_value = student_id,
				option_text  = student_name + '(' + student_id + ')';
			$('#' + element).append(new Option(option_text, option_value));
		}
	}
</script>
<script type="text/javascript">
	function get_reason_options(element) {
		var total_rules = extra_data['rules'].length;
		$('#' + element).append(new Option('', ''));
		for ( j = 0; j < total_rules; ++ j ) {
			var option_value = extra_data['rules'][j]['rule_name'],
				option_text  = extra_data['rules'][j]['description'];
			$('#' + element).append(new Option(option_text, option_value));
		}
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
	$(document).ready(function(){
		var current_semester = <?php echo $extra['current_semester']; ?>;
		$('#available-semesters').val(current_semester);
		add_new_table_datum(3);
	});
</script>
<script type="text/javascript">
	$('#attendence-list').delegate('.reason', 'change', function(){
		var additional_points = get_additional_points($(this).val());
		$(this).parent().next(':last').text(additional_points);
	});
</script>
<script type="text/javascript">
	function get_additional_points(rule_name) {
		var total_rules = extra_data['rules'].length;
		for ( i = 0; i < total_rules; ++ i ) {
			if ( extra_data['rules'][i]['rule_name'] == rule_name ) {
				return extra_data['rules'][i]['additional_points'];
			}
		}
		return 0;
	}
</script>
<script type="text/javascript">
	function add_attendance_records() {
		var result = confirm('您确定要提交吗?\n提交后将无法修改!');
        if (result != true) {
        	return;
        }
		var total_records = $('#attendence-list tr').length - 1;
		var is_successful = true,
			number_of_records = 0;
			error_message = '';
		for ( i = 1; i <= total_records; ++ i ) {
			var student_id = $('#students-' + i).val(),
				datetime   = $('#datetime-' + i).val(),
				reason  = $('#reason-' + i).val();
			if ( student_id && datetime && reason ) {
				if ( !add_attendance_record(student_id, datetime, reason) ) {
					error_message += '无法导入记录: [' + student_id + ', ' +
									 datetime + ',' + reason + ']<br />';
					is_successful = false;
				} else {
					++ number_of_records;
				}
			}
		}
		if ( is_successful ) {
			$('#post-result').removeClass('alert-error');
			$('#post-result').addClass('alert-success');
			$('#post-result').html('已成功添加 ' + number_of_records + ' 条记录.');
			reset_fields();
		} else {
			$('#post-result').removeClass('alert-success');
			$('#post-result').addClass('alert-error');
			$('#post-result').html(error_message);
		}
		$('#post-result').fadeIn();
	}
</script>
<script type="text/javascript">
	function add_attendance_record(student_id, datetime, reason) {
		var post_data     = 'student_id=' + student_id + '&datetime=' + datetime +
		                    '&reason=' + reason;
		var is_successful = false;
		$.ajax({
            type: 'POST',
            async: false,
            url: "<?php echo base_url(); ?>" + 'home/add_attendance_record/',
            data: post_data,
            dataType: 'JSON',
            success: function(result) {
                if ( result['is_successful'] ) {
                	is_successful = true;
                }
            }
        });
        return is_successful;
	}
</script>
<script type="text/javascript">
	function reset_form() {
		var result = confirm('您确定要重置表单吗?\n该操作将无法恢复!');
        if (result == true) {
        	reset_fields();
        }
	}
</script>
<script type="text/javascript">
	function reset_fields() {
		$('#attendence-list tbody').empty();
    	add_new_table_datum(3);
    	$('#main-container').scrollTop(0);
	}
</script>