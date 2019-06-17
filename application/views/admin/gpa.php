<div id="gpa-header" class="page-header">
    <h1>GPA 计算</h1>
</div> <!-- /gpa-header -->
<div id="gpa-content" class="section">
	<div id="selector">
		<select id="available-grades" class="span2">
			<?php
				foreach ( $available_grades as $available_grade ) {
					$grade = $available_grade['grade'];
					echo '<option value="'.$grade.'">'.$grade.'级</option>';
				}
			?>
		</select>
	</div> <!-- /selector -->
	<div id="list">
        <table id="gpa-records" class="table table-striped">
            <thead>
                <tr>
                    <td>班级</td>
                    <td>学号</td>
                    <td>姓名</td>
                    <td>总绩点</td>
                    <td>总学分</td>
                    <td>GPA</td>
                    <td>排名</td>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div> <!-- /list -->
    <div id="page-error" class="alert alert-error hide"><strong>温馨提示: </strong>未找到可用数据.</div>
</div> <!-- /gpa-content -->

<!-- JavaScript for gpa -->
<script type="text/javascript">
    function get_gpa_records(grade) {
        $.ajax({
            type: 'GET',
            async: true,
            url: "<?php echo base_url('admin/get_gpa_records/'); ?>" + grade,
            dataType: 'JSON',
            success: function(result) {
                console.log(result);
                $('#gpa-records tbody').empty();
                if ( result['is_successful'] ) {
                    var total_records = result['records'].length;
                    for ( var i = 0; i < total_records; ++ i ) {
                        $('#gpa-records').append(
                            '<tr>' + 
                                '<td>' + result['records'][i]['grade'] + '级' + result['records'][i]['class'] + '班</td>' +
                                '<td>' + result['records'][i]['student_id'] + '</td>' + 
                                '<td>' + result['records'][i]['student_name'] + '</td>' +
                                '<td>' + result['records'][i]['total_grade_points'] + '</td>' +
                                '<td>' + result['records'][i]['total_credits'] + '</td>' +
                                '<td>' + result['records'][i]['gpa'] + '</td>' +
                                '<td>' + result['records'][i]['ranking'] + '</td>' +
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
    function prepare_get_gpa_records() {
        var grade       = $('#available-grades').val();
        return get_gpa_records(grade);
    }
</script>
<script type="text/javascript">
    $('select#available-grades').change(function() {
        return prepare_get_gpa_records();
    });
</script>
<script type="text/javascript">
    $(document).ready(function(){
        prepare_get_gpa_records();
    });
</script>