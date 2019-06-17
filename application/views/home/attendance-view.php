<div id="selector">
	<select id="available-years" class="span2">
		<?php
			foreach ( $available_years as $available_year ) {
				$year = $available_year['school_year'];
				echo '<option value="'.$year.'">'.$year.'-'.($year + 1).'学年</option>';
			}
		?>
	</select>
	<select id="available-time" class="span2">
		<option value="a-week">最近7天</option>
		<option value="two-weeks">最近14天</option>
		<option value="a-month">最近一个月</option>
		<option value="all">全部</option>
	</select>
	<?php
		if ( $extra['user_groups'] == 'Study-Monitors' || $extra['user_groups'] == 'Sports-Monitors' ) {
			echo '<select id="range" class="span2">';
			echo '<option value="myself">仅自己</option>';
			echo '<option value="all">全体同学</option>';
			echo '</select>';
		}
	?>
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
	function get_attendance_records(year, time, range) {
		$.ajax({
            type: 'GET',
            async: true,
            url: "<?php echo base_url('home/get_attendance_records/'); ?>" + year + '/' + time + '/' + range,
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
                			'<td>' + result['records'][i]['time'] + '</td>' + 
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
			time  = $('#available-time').val(),
			range = ( $('#range').length == 0 ? 'myself' : $('#range').val() );
		get_attendance_records(year, time, range);
	}
	$(document).ready(function(){
		prepare_get_attendance_records();
	});
	$('select').change(function(){
		prepare_get_attendance_records();
	});
</script>