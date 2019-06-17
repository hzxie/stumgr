<link href="<?php echo base_url('assets/css/datetimepicker.css'); ?>" media="screen" rel="stylesheet" type="text/css" />

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
	<select id="available-weeks" class="span2">

	</select>
</div> <!-- /selector -->
<div id="post-result" class="alert hide"></div> <!-- /post-result -->
<div id="list">
	<table id="hygiene-list" class="table">
		<thead>
			<tr>
				<td>寝室</td>
				<td>分数</td>
			</tr>
		</thead>
		<tbody></tbody>
	</table>
	<button class="btn btn-primary" onclick="javascript:add_attendance_records();">提交</button>
	<button class="btn" onclick="javascript:reset_form();">重置</button>
</div> <!-- /list -->

<script type="text/javascript">
	jQuery.extend({
    	get_extra_data: function() {
    		var extra_data = null;
			$.ajax({
	            type: 'GET',
	            async: false,
	            url: "<?php echo base_url('home/get_extra_hygiene_data_for_administration'); ?>",
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
	function add_new_table_datum(room) {
		$('#hygiene-list').append(
			'<tr class="table-datum">' + 
			'<td class="text-normal">' + room['room'] + '</td>' + 
			'</tr>'
		);
	}
</script>
<script type="text/javascript">
	$(document).ready(function(){
		var current_semester = <?php echo $extra['current_semester']; ?>;
		$('#available-semesters').val(current_semester);
		var number_of_rooms = extra_data.rooms.length;
		for ( i = 0; i < number_of_rooms; ++ i ) {
			add_new_table_datum(extra_data.rooms[i]);
		}
	});
</script>
