<div id="transcripts-header" class="page-header">
    <h1>成绩查询</h1>
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
        <select id="available-semesters" class="span2">
            <option value="1">第一学期</option>
            <option value="2">第二/三学期</option>
        </select>
    </div> <!-- /selector -->
    <div id="list">
        <table id="transcripts-records" class="table table-striped">
            <thead>
                <tr>
                    <td>课程代码</td>
                    <td>课程名称</td>
                    <td>卷面成绩</td>
                    <td>最终成绩</td>
                    <td>排名</td>
                    <td>绩点</td>
                    <td>补考成绩</td>
                    <td>学分</td>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div> <!-- /list -->
    <div id="page-error" class="alert alert-error hide"><strong>温馨提示: </strong>未找到可用数据.</div>
</div> <!-- /transcripts-content -->

<script type="text/javascript">
    $('#available-years').change(function(){
        var school_year = $(this).val(),
            semester    = $('#available-semesters').val();
        get_attendance_records(school_year, semester);
    });
    $('#available-semesters').change(function(){
        var school_year = $('#available-years').val(),
            semester    = $(this).val();
        get_attendance_records(school_year, semester);
    });
</script>
<script type="text/javascript">
    function get_attendance_records(school_year, semester) {
        $.ajax({
            type: 'GET',
            async: true,
            url: "<?php echo base_url().'home/get_transcripts_records/'; ?>" + school_year + '/' + semester,
            dataType: 'JSON',
            success: function(result) {
                $('#transcripts-records tbody').empty();
                if ( result['is_successful'] ) {
                    var total_records = result['records'].length;
                    for ( var i = 0; i < total_records; ++ i ) {
                        $('#transcripts-records').append(
                            '<tr class="table-datum">' + 
                            '<td>' + result['records'][i]['course_id'] + '</td>' + 
                            '<td>' + result['records'][i]['course_name'] + '</td>' + 
                            '<td>' + result['records'][i]['paper_score'] + '</td>' + 
                            '<td>' + result['records'][i]['final_score'] + '</td>' + 
                            '<td>' + result['records'][i]['ranking'] + '/' + result['records'][i]['total'] + '</td>' + 
                            '<td>' + result['records'][i]['grade_point'] + '</td>' + 
                            '<td>' + result['records'][i]['is_passed'] + '</td>' + 
                            '<td>' + result['records'][i]['credits'] + '</td>' + 
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
    $(document).ready(function(){
        var school_year = $('#available-years').val(),
            semester    = $('#available-semesters').val();
        get_attendance_records(school_year, semester);
    })
</script>