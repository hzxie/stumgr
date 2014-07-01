<div id="selector">
	<select id="available-years" class="span2">
		<?php
			foreach ( $available_years as $available_year ) {
				$year = $available_year['school_year'];
				echo '<option value="'.$year.'">'.$year.'-'.($year + 1).'学年</option>';
			}
		?>
	</select>
	<select id="available-semesters" class="span2">
		<option value="1">第一学期</option>
		<option value="2">第二/三学期</option>
	</select>
</div>
<div id="list">
	<table id="hygiene-records" class="table table-hover">
		<thead>
			<tr>
				<td>时间</td>
				<td>寝室</td>
				<td>分数</td>
				<td>平均分</td>
			</tr>
		</thead>
		<tbody></tbody>
	</table>
</div>
<div id="page-error" class="alert alert-error hide"><strong>温馨提示: </strong>未找到可用数据.</div>

<script type="text/javascript">
	function get_hygiene_records(year, time) {
		$.ajax({
            type: 'GET',
            async: true,
            url: "<?php echo base_url().'home/get_hygiene_records/'; ?>" + year + '/' + time,
            dataType: 'JSON',
            success: function(result) {
            	$('#hygiene-records tbody').empty();
                if ( result['is_successful'] ) {
                	var total_records = result['records'].length;
                	for ( var i = 0; i < total_records; ++ i ) {
                		$('#hygiene-records').append(
                			'<tr class="table-datum">' + 
                			'<td>第' + result['records'][i]['week'] + '周</td>' + 
                			'<td>' + result['records'][i]['room'] + '</td>' + 
                			'<td>' + result['records'][i]['score'] + '</td>' + 
                			'<td>' + result['records'][i]['avg_score'] + '</td>' + 
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
	function prepare_get_hygiene_records() {
		var year      = $('#available-years').val(),
			semester  = $('#available-semesters').val();
		get_hygiene_records(year, semester);
	}
	$(document).ready(function(){
		prepare_get_hygiene_records();
	});
	$('select').change(function(){
		prepare_get_hygiene_records();
	});
</script>