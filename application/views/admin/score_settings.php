<link href="<?php echo base_url('assets/css/fineuploader.min.css'); ?>" media="screen" rel="stylesheet" type="text/css" />

<div id="score-settings-header" class="page-header">
    <h1>参数设置</h1>
</div> <!-- /score-settings-header -->
<div id="score-settings-section" class="section">
    <ul class="nav nav-tabs">
        <li id="scores-nav" class="active"><a href="javascript:void(0)">导入成绩</a></li>
        <li id="courses-nav"><a href="javascript:void(0)">课程设置</a></li>
        <li id="plan-nav"><a href="javascript:void(0)">教学计划</a></li>
    </ul>
    <div class="tab-content">
        <div id="scores-tab" class="tab-pane active">
            <div id="import-scores-header" class="page-header">
                <h2>从Excel文件导入</h2>
            </div> <!-- /import-scores-header -->
            <div id="import-scores-section" style="overflow: hidden">
                <form action="<?php echo base_url('admin/import_scores'); ?>" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                    <table class="table no-border">
                        <tr class="no-border">
                            <td><small><a href="<?php echo base_url('assets/tpl/template-scores.xlsx'); ?>">查看文件模板</a></small></td>
                        </tr>
                        <tr class="no-border">
                            <td><div id="jquery-wrapped-fine-uploader"></div></td>
                        </tr>
                    </table>
                </form>
            </div> <!-- /import-scores-section -->
        </div> <!-- /scores-tab -->
        <div id="courses-tab" class="tab-pane">
            <div id="list">
                <table id="courses-records" class="table table-striped">
                    <thead>
                        <tr>
                            <td>课程代码</td>
                            <td>课程名称</td>
                            <td>学分</td>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if ($courses): ?>
                        <?php foreach ( $courses as $course ) { ?>
                        <tr>
                            <td><?php echo $course['course_id']; ?></td>
                            <td><?php echo $course['course_name']; ?></td>
                            <td><?php echo $course['credits']; ?></td>
                        </tr>
                        <?php } ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div> <!-- /list -->
        </div> <!-- /courses-tab -->
        <div id="plan-tab" class="tab-pane">
            <div id="plan-header" class="page-header">
                <h2>教学计划</h2>
            </div> <!-- /education-plan-header -->
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
            <div id="courses" style="overflow: hidden;">
                <div id="all-courses" style="float: left; margin-right: 20px;">
                    <select id="all-courses-list" multiple="multiple" style="height: 300px;"></select>
                </div> <!-- #all-courses -->
                <div id="<operator></operator>" style="float: left; margin-right: 20px; width: 40px;">
                    <p><button id="add-selected-courses" class="btn">-&gt;</button></p>
                    <p><button id="remove-selected-courses" class="btn">&lt;-</button></p>
                </div> <!-- #operator -->
                <div id="selected-courses" style="float: left; margin-right: 20px;">
                    <select id="selected-courses-list" multiple="multiple" style="height: 300px;"></select>
                </div> <!-- #selected-courses -->
            </div> <!-- /courses -->
        </div> <!-- /education-plan-tab -->
    </div>
</div> <!-- /score-settings-section -->

<script type="text/javascript">
    $('#scores-nav').click(function(){
        $('#plan-nav').removeClass('active');
        $('#plan-tab').removeClass('active');
        $('#courses-nav').removeClass('active');
        $('#courses-tab').removeClass('active');
        $('#scores-nav').addClass('active');
        $('#scores-tab').addClass('active');
        set_footer_position();
    });
    $('#plan-nav').click(function(){
        $('#scores-nav').removeClass('active');
        $('#scores-tab').removeClass('active');
        $('#courses-nav').removeClass('active');
        $('#courses-tab').removeClass('active');
        $('#plan-nav').addClass('active');
        $('#plan-tab').addClass('active');
        set_footer_position();
    });
    $('#courses-nav').click(function(){
        $('#scores-nav').removeClass('active');
        $('#scores-tab').removeClass('active');
        $('#plan-nav').removeClass('active');
        $('#plan-tab').removeClass('active');
        $('#courses-nav').addClass('active');
        $('#courses-tab').addClass('active');
        set_footer_position();
    });
</script>

<!-- JavaScript for scores tab -->
<script type="text/javascript" src="<?php echo base_url('assets/js/fineuploader.min.js'); ?>"></script>
<script>
    $(document).ready(function () {
        $('#jquery-wrapped-fine-uploader').fineUploader({
            request: {
                endpoint: "<?php echo base_url('admin/import_scores/'); ?>"
            },
            text: {
                uploadButton: '上传文件'
            }
        }).on('complete', function(event, id, file_name, result) {
            if ( result['is_successful'] ) {
                $('.fileUploader-upload-fail').last().addClass('fileUploader-upload-success');
                $('.fileUploader-upload-fail').last().removeClass('fileUploader-upload-fail');
                $('.fileUploader-upload-status-text').last().html('已成功导入所有学生的成绩. <a href="#logs"><small>查看详情</small></a>');
            } else {
                if ( !result['is_upload_successful'] ) {
                    $('.fileUploader-upload-status-text').last().html(result['error_message']);
                } else {
                    $('.fileUploader-upload-status-text').last().html('部分学生的成绩未能成功导入. <a href="#logs"><small>查看详情</small></a>');
                }
            }
        });
    });
</script>
<!-- JavaScript for education plans tab -->
<script type="text/javascript">
    function get_all_courses() {
        $.ajax({
            type: 'GET',
            async: false,
            url: "<?php echo base_url('admin/get_all_courses'); ?>",
            dataType: 'JSON',
            success: function(result) {
                $('#all-courses-list').empty();
                if ( result['is_successful'] ) {
                    var total_records = result['courses'].length;
                    for ( var i = 0; i < total_records; ++ i ) {
                        $('#all-courses-list').append(
                            '<option value="' + result['courses'][i]['course_id'] + '">' +
                            result['courses'][i]['course_name'] +
                            '</option>'
                        );
                    }
                }
                prepare_get_education_plan();
            }
        });
    }
</script>
<script type="text/javascript">
    function get_education_plan(school_year, grade) {
        $.ajax({
            type: 'GET',
            async: true,
            url: "<?php echo base_url('admin/get_available_courses/'); ?>" + school_year + '/' + grade,
            dataType: 'JSON',
            success: function(result) {
                $('#selected-courses-list').empty();
                if ( result['is_successful'] ) {
                    var total_records = result['available_courses'].length;
                    for ( var i = 0; i < total_records; ++ i ) {
                        var course_id       = result['available_courses'][i]['course_id'],
                            option_object   = $('option[value=' + course_id + ']');

                        $('#selected-courses-list').append(option_object);
                    }
                }
            }
        });
    }
</script>
<script type="text/javascript">
    function prepare_get_education_plan() {
        var school_year = $('#available-years').val(),
            grade       = $('#available-grades').val();

        return get_education_plan(school_year, grade);
    }
</script>
<script type="text/javascript">
    $('select#available-years').change(function() {
        get_all_courses();
    });
</script>
<script type="text/javascript">
    $('select#available-grades').change(function() {
        get_all_courses();
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        get_all_courses();
    });
</script>
<script type="text/javascript">
    $('#add-selected-courses').click(function() {
        $('#all-courses-list option').each(function() {
            if ( $(this).is(':selected') ) {
                var option_object   = $(this),
                    school_year     = $('#available-years').val(),
                    grade           = $('#available-grades').val(),
                    course_id       = $(option_object).val(),
                    post_data       = 'school_year=' + school_year + '&grade=' + grade +
                                      '&course_id=' + course_id;
                $.ajax({
                    type: 'POST',
                    async: true,
                    url: "<?php echo base_url('admin/add_education_plan'); ?>",
                    data: post_data,
                    dataType: 'JSON',
                    success: function(result) {
                        $('#selected-courses-list').append( $(option_object) );
                    }
                });
            }
        });
    });
</script>
<script type="text/javascript">
    $('#remove-selected-courses').click(function() {
        $('#selected-courses-list option').each(function() {
            if ( $(this).is(':selected') ) {
                var option_object   = $(this),
                    school_year     = $('#available-years').val(),
                    grade           = $('#available-grades').val(),
                    course_id       = $(option_object).val(),
                    post_data       = 'school_year=' + school_year + '&grade=' + grade +
                                      '&course_id=' + course_id;
                $.ajax({
                    type: 'POST',
                    async: true,
                    url: "<?php echo base_url('admin/delete_education_plan'); ?>",
                    data: post_data,
                    dataType: 'JSON',
                    success: function(result) {
                        $('#all-courses-list').append( $(option_object) );
                    }
                });
            }
        });
    });
</script>
