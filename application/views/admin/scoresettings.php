<link href="<?php echo base_url(); ?>assets/css/fineuploader.min.css" media="screen" rel="stylesheet" type="text/css" />

<div id="score-settings-header" class="page-header">
    <h1>参数设置</h1>
</div> <!-- /score-settings-header -->
<div id="score-settings-section" class="section">
    <ul class="nav nav-tabs">
        <li id="scores-nav" class="active"><a href="javascript:void(0)">导入成绩</a></li>
        <li id="settings-nav"><a href="javascript:void(0)">参数设置</a></li>
        <li id="courses-nav"><a href="javascript:void(0)">课程设置</a></li>
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
        <div id="settings-tab" class="tab-pane">

        </div> <!-- /settings-tab -->
        <div id="courses-tab" class="tab-pane">

        </div> <!-- /courses-tab -->
    </div>
</div> <!-- /score-settings-section -->

<script type="text/javascript">
    $('#scores-nav').click(function(){
        $('#settings-nav').removeClass('active');
        $('#settings-tab').removeClass('active');
        $('#courses-nav').removeClass('active');
        $('#courses-tab').removeClass('active');
        $('#scores-nav').addClass('active');
        $('#scores-tab').addClass('active');
        set_footer_position();
    });
    $('#settings-nav').click(function(){
        $('#scores-nav').removeClass('active');
        $('#scores-tab').removeClass('active');
        $('#courses-nav').removeClass('active');
        $('#courses-tab').removeClass('active');
        $('#settings-nav').addClass('active');
        $('#settings-tab').addClass('active');
        set_footer_position();
    });
    $('#courses-nav').click(function(){
        $('#scores-nav').removeClass('active');
        $('#scores-tab').removeClass('active');
        $('#settings-nav').removeClass('active');
        $('#settings-tab').removeClass('active');
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