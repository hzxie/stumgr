<div id="assessment-header" class="page-header">
    <h1>学生互评</h1>
</div> <!-- /assessment-header -->
<div id="assessment-content" class="section">
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
        <table id="assessment-records" class="table table-striped">
            <thead>
                <tr>
                    <td rowspan="2">学号</td>
                    <td rowspan="2">姓名</td>
                    <td rowspan="2">已测评</td>
                    <td colspan="4" class="left-border text-center">道德(<?php echo $options['moral_percents'] * 100 ?>%)</td>
                    <td colspan="4" class="left-border text-center">体育(<?php echo $options['strength_percents'] * 100 ?>%)</td>
                    <td colspan="4" class="left-border text-center">能力(<?php echo $options['ability_percents'] * 100 ?>%)</td>
                </tr>
                <tr>
                    <td class="left-border">优</td>
                    <td>良</td>
                    <td>中</td>
                    <td>差</td>
                    <td class="left-border">优</td>
                    <td>良</td>
                    <td>中</td>
                    <td>差</td>
                    <td class="left-border">优</td>
                    <td>良</td>
                    <td>中</td>
                    <td>差</td>
                </tr>
            </thead>
        </table>
    </div> <!-- /list -->
    <div id="page-error" class="alert alert-error hide"><strong>温馨提示: </strong>未找到可用数据.</div>
</div> <!-- /assessment-content -->

<script type="text/javascript">
    function get_assessment_records(school_year, grade) {
        $.ajax({
            type: 'GET',
            async: true,
            url: "<?php echo base_url('admin/get_assessment_records/'); ?>" + school_year + '/' + grade,
            dataType: 'JSON',
            success: function(result) {
                $('#assessment-records tbody').empty();
                if ( result['is_successful'] ) {
                    var total_records = result['records'].length;
                    for ( var i = 0; i < total_records; ++ i ) {
                        $('#assessment-records').append(
                            '<tr class="table-datum">' + 
                            '<td>' + result['records'][i]['student_id'] + '</td>' + 
                            '<td>' + result['records'][i]['student_name'] + '</td>' + 
                            '<td>' + (result['records'][i]['is_participated'] === '1' ? '是' : '否') + '</td>' + 
                            '<td class="left-border">' + result['records'][i]['moral_excellent'] + '</td>' + 
                            '<td>' + result['records'][i]['moral_good'] + '</td>' + 
                            '<td>' + result['records'][i]['moral_medium'] + '</td>' + 
                            '<td>' + result['records'][i]['moral_poor'] + '</td>' + 
                            '<td class="left-border">' + result['records'][i]['strength_excellent'] + '</td>' + 
                            '<td>' + result['records'][i]['strength_good'] + '</td>' + 
                            '<td>' + result['records'][i]['strength_medium'] + '</td>' + 
                            '<td>' + result['records'][i]['strength_poor'] + '</td>' + 
                            '<td class="left-border">' + result['records'][i]['ability_excellent'] + '</td>' + 
                            '<td>' + result['records'][i]['ability_good'] + '</td>' + 
                            '<td>' + result['records'][i]['ability_medium'] + '</td>' + 
                            '<td>' + result['records'][i]['ability_poor'] + '</td>' + 
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
    function prepare_get_assessment_records() {
        var school_year = $('#available-years').val(),
            grade       = $('#available-grades').val();

        get_assessment_records(school_year, grade);
    }
</script>
<script type="text/javascript">
    $('select').change(function(){
        prepare_get_assessment_records();
    });
</script>
<script type="text/javascript">
    $(document).ready(function(){
        prepare_get_assessment_records();
    });
</script>