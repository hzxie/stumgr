<div id="rewards-header" class="page-header">
	<h1>奖惩情况</h1>
</div> <!-- /rewards-header -->
<div id="rewards-content" class="section">
    <div id="selector" style="float: left;">
        <select id="available-years" class="span2">
            <?php
                foreach ( $available_years as $available_year ) {
                    $year = $available_year['school_year'];
                    echo '<option value="'.$year.'">'.$year.'-'.($year + 1).'学年</option>';
                }
            ?>
        </select>
    </div> <!-- /selector -->
    <div id="add-new" style="float: right; font-size: 13px;">
        <img src="<?php echo base_url() ?>public/img/icon-add.png" alt="Icon" />
        <a id="add-new-trigger" href="javascript:void(0);">添加记录</a>
    </div> <!-- /add-new -->
    <div id="list">
        <table id="reward-records" class="table table-striped">
            <thead>
                <tr>
                    <td>级别</td>
                    <td>加/减分缘由</td>
                    <td>加减分</td>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div> <!-- /list -->
    <div id="page-error" class="alert alert-error hide"><strong>温馨提示: </strong>未找到可用数据.</div>
</div> <!-- /rewards-content -->

<!-- New Rewards Modal -->
<div id="new-rewards-dialog" class="modal hide dialog">
    <div class="modal-header">
        <button type="button" class="close">×</button>
        <h2 id="rewards-dialog-title">添加奖惩情况</h2>
    </div>
    <div class="modal-body">
        <div id="rewards-notice-message" class="alert alert-warning">
            <button type="button" class="close">×</button>
            <strong>提示: </strong>您正在为<?php echo $current_school_year; ?>-<?php echo ($current_school_year + 1); ?>学年添加奖惩情况记录. 请仔细确认后提交, 因为提交后的内容将无法修改!
        </div>
        <div id="rewards-error-message" class="alert alert-error hide"></div>
        <table id="new-rewards-table" class="table no-border">
            <thead>
                <tr class="no-border">
                    <td>级别</td>
                    <td>加/减分缘由</td>
                    <td>加减分</td>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
        <div id="new-record-trigger" style="padding-left: 8px; font-size: 13px;">
            <img src="<?php echo base_url() ?>public/img/icon-add.png" alt="Icon" />
            <a id="new-record-trigger" href="javascript:void(0);">添加一行</a>
        </div> <!-- /new-record-trigger -->
    </div>
    <div class="modal-footer">
        <button id="add-rewards" class="btn btn-primary">确认</button>
        <button class="btn btn-cancel">取消</button>
     </div>
</div> <!-- /New Rewards Modal -->

<!-- JavaScript for the basic content -->
<script type="text/javascript">
    $('#available-years').change(function(){
        var school_year = $(this).val();
        get_reward_records(school_year);
    });
</script>
<script type="text/javascript">
    function get_reward_records(school_year) {
        $.ajax({
            type: 'GET',
            async: true,
            url: "<?php echo base_url().'home/get_reward_records/'; ?>" + school_year,
            dataType: 'JSON',
            success: function(result) {
                $('#reward-records tbody').empty();
                if ( result['is_successful'] ) {
                    var total_records = result['records'].length;
                    for ( var i = 0; i < total_records; ++ i ) {
                        $('#reward-records').append(
                            '<tr class="table-datum">' + 
                            '<td>' + result['records'][i]['description'] + '</td>' + 
                            '<td>' + result['records'][i]['detail'] + '</td>' + 
                            '<td>' + result['records'][i]['additional_score'] + '</td>' + 
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
    $(document).ready(function() {
        // loading reward records
        var school_year = $('#available-years').val();
        get_reward_records(school_year);

        // add three new lines in new rewards model
        var NUMBER_OF_LINES = 3;
        for ( i = 0; i < NUMBER_OF_LINES; ++ i ) {
            add_new_record();
        }
    });
</script>

<!-- JavaScript for New Rewards Model -->
<script type="text/javascript">
    $('#add-new-trigger').click(function() {
        $('#new-rewards-dialog').fadeIn();
    });
</script>
<script type="text/javascript">
    $('#rewards-notice-message .close').click(function() {
       $('#rewards-notice-message').fadeOut(); 
    });
</script>
<script type="text/javascript">
    $('.modal-header .close').click(function() {
       $('#new-rewards-dialog').fadeOut(); 
    });
    $('.btn-cancel').click(function() {
       $('#new-rewards-dialog').fadeOut(); 
    });
</script>
<script type="text/javascript">
    $('#new-record-trigger').click(function() {
        add_new_record();
    })
</script>
<script type="text/javascript">
    function add_new_record() {
        $('#new-rewards-table').append(
            '<tr class="no-border">' + 
            '<td>' + 
            '<select name="level" style="width: 80px;">' + 
            '<?php
                foreach ( $reward_levels as $reward_level ) {
                    echo '<option value="'.$reward_level['reward_level_id'].'">'.
                         $reward_level['description'].'</option>';
                }
            ?>' +
            '</select>' + 
            '</td>' + 
            '<td><input type="text" name="detail" maxlength="255" /></td>' + 
            '<td><input type="text" name="additional-score" /></td>' +
            '</tr>'
        );
    }
</script>
<script type="text/javascript">
    $('#new-rewards-table').delegate('input[name=additional-score]', 'change', function() {
        var number_string = $(this).val();
        if ( !is_a_number(number_string) ) {
            $(this).val('');
        }
    });
</script>
<script type="text/javascript">
    function is_a_number(number_string) {
        var number_regex = '^[-+]?[0-9]*\.?[0-9]+$';
        return number_string.match(number_regex);
    }
</script>
<script type="text/javascript">
    $('#add-rewards').click(function() {
        set_loading_mode(true);
        $('#new-rewards-table > tbody > tr').each(function() {
            var reward_level_id     = $(this).find('select[name=level]').val(),
                detail              = $(this).find('input[name=detail]').val(),
                additional_score    = $(this).find('input[name=additional-score]').val();
            if ( !is_empty(detail) && !is_empty(additional_score) ) {
                add_reward_record(reward_level_id, detail, additional_score);
            }
        });
        // reload this page
        load('rewards');
    });
</script>
<script type="text/javascript">
    function set_loading_mode(is_loading) {
        if ( is_loading ) {
            $('#new-rewards-dialog :input').attr('disabled', true);
        } else {
            $('#new-rewards-dialog :input').removeAttr('disabled');
        }
    }
</script>
<script type="text/javascript">
    function is_empty(str) {
        if ( !str || str.length == 0 ) {
            return true;
        }
        return !/[^\s]+/.test(str);
    }
</script>
<script type="text/javascript">
    function add_reward_record(reward_level_id, detail, additional_score) {
        var post_data = 'reward_level_id=' + reward_level_id + '&detail=' + detail + 
                        '&additional_score=' + additional_score;
        $.ajax({
            type: 'POST',
            async: true,
            url: "<?php echo base_url(); ?>" + 'home/add_reward_record/',
            data: post_data,
            dataType: 'JSON',
            success: function(result) {
            },
            error: function() {
            }
        });
    }
</script>