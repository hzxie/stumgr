<link href="<?php echo base_url('/css/admin/switchery.min.css'); ?>" media="screen" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('/css/admin/eveluation-settings.css'); ?>" media="screen" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="<?php echo base_url('/js/switchery.min.js'); ?>"></script>

<div id="evaluation-settings-header" class="page-header">
    <h1>参数设置</h1>
</div> <!-- /evaluation-settings-header -->
<div id="evaluation-settings-content" class="section">
    <table>
        <tbody>
            <tr>
                <td colspan="2"><h2>系统状态</h2></td>
            </tr>
            <tr>
                <td>打开/关闭互评系统</td>
                <td>
                    <?php if ( $options['is_peer_assessment_active'] ): ?>
                        <input type="checkbox" class="js-switch" checked/>
                    <?php else: ?>
                        <input type="checkbox" class="js-switch" />
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td colspan="2"><h2>系统参数</h2></td>
            </tr>
            <tr>
                <td>道德项权重</td>
                <td><?php echo $options['moral_percents'] * 100 ?> %</td>
            </tr>
            <tr>
                <td>体育项权重</td>
                <td><?php echo $options['strength_percents'] * 100 ?> %</td>
            </tr>
            <tr>
                <td>能力项权重</td>
                <td><?php echo $options['ability_percents'] * 100 ?> %</td>
            </tr>
            <tr>
                <td>优秀比例</td>
                <td><?php echo $options['min_excellent_percents'] * 100 ?> % ~ <?php echo $options['max_excellent_percents'] * 100 ?> %</td>
            </tr>
            <tr>
                <td>良好比例</td>
                <td><?php echo $options['min_good_percents'] * 100 ?> % ~ <?php echo $options['max_good_percents'] * 100 ?> %</td>
            </tr>
            <tr>
                <td>中差比例</td>
                <td><?php echo $options['min_medium_percents'] * 100 ?> % ~ <?php echo $options['max_medium_percents'] * 100 ?> %</td>
            </tr>
            <tr>
                <td>优秀得分</td>
                <td><?php echo $options['excellent_score'] ?> 分</td>
            </tr>
            <tr>
                <td>良好得分</td>
                <td><?php echo $options['good_score'] ?> 分</td>
            </tr>
            <tr>
                <td>中等得分</td>
                <td><?php echo $options['medium_score'] ?> 分</td>
            </tr>
            <tr>
                <td>差劲得分</td>
                <td><?php echo $options['poor_score'] ?> 分</td>
            </tr>
        </tbody>
    </table>
</div> <!-- /evaluation-settings-content -->

<script type="text/javascript">
    var element = document.querySelector('.js-switch');
    var init = new Switchery(element);
</script>
<script type="text/javascript">
    $('.js-switch').change(function() {
        var is_checked  = $(this).is(':checked') ? 1 : 0,
            post_data   = 'is_peer_assessment_active=' + is_checked;
        $.ajax({
            type: 'POST',
            async: true,
            url: "<?php echo base_url('admin/switch_is_peer_assessment_active'); ?>",
            data: post_data,
            dataType: 'JSON',
            success: function(result) {
                if ( !result['is_successful'] ) {
                    set_loading_block(false, true); // defined in index.php
                }
            }
        });
    });
</script>