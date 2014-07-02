<link href="<?php echo base_url(); ?>assets/css/fineuploader.min.css" media="screen" rel="stylesheet" type="text/css" />

<div id="score-settings-header" class="page-header">
    <h1>参数设置</h1>
</div> <!-- /score-settings-header -->
<div id="score-settings-section" class="section">
    <ul class="nav nav-tabs">
        <li id="scores-nav" class="active"><a href="javascript:void(0)">导入成绩</a></li>
        <li id="courses-nav"><a href="javascript:void(0)">课程设置</a></li>
        <li id="education-plan-nav"><a href="javascript:void(0)">教学计划</a></li>
    </ul>
    <div class="tab-content">
        <div id="scores-tab" class="tab-pane active">
            <div id="import-scores-header" class="page-header">
                <h2>从Excel文件导入</h2>
            </div> <!-- /import-scores-header -->
            <div id="import-scores-section" style="overflow: hidden">
                <form action="<?php echo base_url(); ?>admin/import_scores" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                    <table class="table no-border">
                        <tr class="no-border">
                            <td><small><a href="<?php echo base_url().'assets/tpl/template-scores.xlsx'; ?>">查看文件模板</a></small></td>
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
        <div id="education-plan-tab" class="tab-pane">
            <div id="education-plan-header" class="page-header">
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
            <div id="courses">
                <select id="all-courses-list" multiple="multiple" style="height: 300px;">
                    <?php if ($courses): ?>
                        <?php foreach ( $courses as $course ) { ?>
                        <option value="<?php echo $course['course_id']; ?>"><?php echo $course['course_name']; ?></option>
                        <?php } ?>
                    <?php endif; ?>
                </select>
                <select id="selected-courses-list" multiple="multiple" style="height: 300px;">
                </select>
            </div> <!-- /courses -->
        </div> <!-- /education-plan-tab -->
    </div>
</div> <!-- /score-settings-section -->

<script type="text/javascript">
    $('#scores-nav').click(function(){
        $('#education-plan-nav').removeClass('active');
        $('#education-plan-tab').removeClass('active');
        $('#courses-nav').removeClass('active');
        $('#courses-tab').removeClass('active');
        $('#scores-nav').addClass('active');
        $('#scores-tab').addClass('active');
        set_footer_position();
    });
    $('#education-plan-nav').click(function(){
        $('#scores-nav').removeClass('active');
        $('#scores-tab').removeClass('active');
        $('#courses-nav').removeClass('active');
        $('#courses-tab').removeClass('active');
        $('#education-plan-nav').addClass('active');
        $('#education-plan-tab').addClass('active');
        set_footer_position();
    });
    $('#courses-nav').click(function(){
        $('#scores-nav').removeClass('active');
        $('#scores-tab').removeClass('active');
        $('#education-plan-nav').removeClass('active');
        $('#education-plan-tab').removeClass('active');
        $('#courses-nav').addClass('active');
        $('#courses-tab').addClass('active');
        set_footer_position();
    });
</script>

<!-- JavaScript for scores tab -->
<script type="text/javascript" src="<?php echo base_url().'assets/js/fineuploader.min.js'; ?>"></script>
<script>
    $(document).ready(function () {
        $('#jquery-wrapped-fine-uploader').fineUploader({
            request: {
                endpoint: "<?php echo base_url(); ?>" + 'admin/import_scores/'
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