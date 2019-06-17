<div id="transcripts-header" class="page-header">
    <h1>成绩分析</h1>
</div> <!-- /transcripts-header -->
<div id="transcripts-content" class="section">
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
		<select id="available-courses" class="span2"></select>
	</div> <!-- /selector -->
	<div id="list">
        <table id="transcripts-records" class="table table-striped">
            <thead>
                <tr>
                    <td>班级</td>
                    <td>学号</td>
                    <td>姓名</td>
                    <td>综合成绩</td>
                    <td>排名</td>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div> <!-- /list -->
    <div id="page-error" class="alert alert-error hide"><strong>温馨提示: </strong>未找到可用数据.</div>
</div> <!-- /transcripts-content -->

<!-- JavaScript for trascripts -->
<script type="text/javascript">
    function get_available_courses(school_year, grade) {
        $.ajax({
            type: 'GET',
            async: true,
            url: "<?php echo base_url('admin/get_available_courses/'); ?>" + school_year + '/' + grade,
            dataType: 'JSON',
            success: function(result) {
                $('#available-courses').empty();
                $('#available-courses').append('<option value="all">(综合排名)</option>');
                if ( result['is_successful'] ) {
                    var total_records = result['available_courses'].length;
                    for ( var i = 0; i < total_records; ++ i ) {
                        $('#available-courses').append(
                            '<option value="' + 
                            result['available_courses'][i]['course_id'] + '">' +
                            result['available_courses'][i]['course_name'] + 
                            '</option>'
                        );
                    }
                    set_visible('#page-error', false);
                    set_visible('#list', true);

                    prepare_get_trascripts_records();
                } else {
                    set_visible('#page-error', true);
                    set_visible('#list', false);
                }
            }
        });
    }
</script>
<script type="text/javascript">
    function get_transcripts_records(school_year, grade, course_id) {
        $.ajax({
            type: 'GET',
            async: true,
            url: "<?php echo base_url('admin/get_transcripts_records/'); ?>" + school_year + '/' + grade + '/' + course_id,
            dataType: 'JSON',
            success: function(result) {
                console.log(result);
                $('#transcripts-records tbody').empty();
                if ( result['is_successful'] ) {
                    var total_records = result['records'].length;
                    for ( var i = 0; i < total_records; ++ i ) {
                        $('#transcripts-records').append(
                            '<tr>' + 
                                '<td>' + result['records'][i]['grade'] + '级' + result['records'][i]['class'] + '班</td>' +
                                '<td>' + result['records'][i]['student_id'] + '</td>' + 
                                '<td>' + result['records'][i]['student_name'] + '</td>' +
                                '<td>' + result['records'][i]['final_score'] + '</td>' +
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
    function prepare_get_courses_records() {
        var school_year = $('#available-years').val(),
            grade       = $('#available-grades').val();

        return get_available_courses(school_year, grade);
    }
</script>
<script type="text/javascript">
    function prepare_get_trascripts_records() {
        var school_year = $('#available-years').val(),
            grade       = $('#available-grades').val(),
            course_id   = $('#available-courses').val();

        return get_transcripts_records(school_year, grade, course_id);
    }
</script>
<script type="text/javascript">
    $('select#available-years').change(function() {
        return prepare_get_courses_records();
    });
</script>
<script type="text/javascript">
    $('select#available-grades').change(function() {
        return prepare_get_courses_records();
    });
</script>
<script type="text/javascript">
    $('select#available-courses').change(function() {
        return prepare_get_trascripts_records();
    })
</script>
<script type="text/javascript">
    $(document).ready(function(){
        prepare_get_courses_records();
    });
</script>

