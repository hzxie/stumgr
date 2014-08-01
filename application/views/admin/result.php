<div id="result-header" class="page-header">
    <h1>查看结果</h1>
</div> <!-- /result-header -->
<div id="result-content" class="section">
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
	</div> <!-- /selector -->
	<div id="list">
		<table id="evaluation-records" class="table table-striped">
            <thead>
                <tr>
                	<td>班级</td>
                	<td>学号</td>
                	<td>姓名</td>
                    <td>德育(<?php echo $options['moral_percents'] * 100; ?> %)</td>
                    <td>智育(<?php echo $options['intelligence_percents'] * 100; ?> %)</td>
                    <td>体育(<?php echo $options['strength_percents'] * 100; ?> %)</td>
                    <td>能力(<?php echo $options['ability_percents'] * 100; ?> %)</td>
                    <td>附加分</td>
                    <td>总分</td>
                    <td>智育排名</td>
                    <td>综合排名</td>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div> <!-- /list -->
    <div id="page-error" class="alert alert-error hide"><strong>温馨提示: </strong>未找到可用数据.</div>
</div> <!-- /result-content -->

<!-- JavaScript for result -->
<script type="text/javascript">
    function get_evaluation_records(school_year, grade) {
        $.ajax({
            type: 'GET',
            async: true,
            url: "<?php echo base_url().'admin/get_evaluation_records/'; ?>" + school_year + '/' + grade,
            dataType: 'JSON',
            success: function(result) {
                console.log(result);
                $('#evaluation-records tbody').empty();
                if ( result['is_successful'] ) {
                    var total_records = result['records'].length;
                    for ( var i = 0; i < total_records; ++ i ) {
                        $('#evaluation-records').append(
                            '<tr>' + 
                                '<td>' + result['records'][i]['class'] + '班</td>' +
                                '<td>' + result['records'][i]['student_id'] + '</td>' + 
                                '<td>' + result['records'][i]['student_name'] + '</td>' +
                                '<td>' + result['records'][i]['moral'] + '</td>' +
                                '<td>' + result['records'][i]['intelligence'] + '</td>' +
                                '<td>' + result['records'][i]['strength'] + '</td>' +
                                '<td>' + result['records'][i]['ability'] + '</td>' +
                                '<td>' + result['records'][i]['extra'] + '</td>' +
                                '<td>' + result['records'][i]['total_score'] + '</td>' +
                                '<td>' + result['records'][i]['ranking'] + '</td>' +
                                '<td>' + result['records'][i]['intelligence_ranking'] + '</td>' +
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
    function prepare_get_evaluation_records() {
        var school_year = $('#available-years').val(),
            grade       = $('#available-grades').val();

        return get_evaluation_records(school_year, grade);
    }
</script>
<script type="text/javascript">
    $('select#available-years').change(function() {
        return prepare_get_evaluation_records();
    });
</script>
<script type="text/javascript">
    $('select#available-grades').change(function() {
        return prepare_get_evaluation_records();
    });
</script>
<script type="text/javascript">
    $(document).ready(function(){
        prepare_get_evaluation_records();
    });
</script>
