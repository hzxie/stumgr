<link rel="stylesheet" media="screen" href="<?php echo base_url('/css/home/messenger.css'); ?>">
<link rel="stylesheet" media="screen" href="<?php echo base_url('/css/home/messenger-theme-future.css'); ?>">

<div class="alert alert-info">
    <?php
        $number_of_students = count($students);
        $min_number_of_excellent_students = floor($number_of_students * $options['min_excellent_percents']);
        $max_number_of_excellent_students = floor($number_of_students * $options['max_excellent_percents']);
        $min_number_of_good_students      = floor($number_of_students * $options['min_good_percents']);
        $max_number_of_good_students      = floor($number_of_students * $options['max_good_percents']);
        $min_number_of_medium_students    = floor($number_of_students * $options['min_medium_percents']);
        $max_number_of_medium_students    = floor($number_of_students * $options['max_medium_percents']);
    ?>
    <strong>温馨提示: </strong>
    <?php echo $extra['student_name']; ?>同学您好, 欢迎参加合肥工业大学<?php echo $school_year ?>~<?php echo ($school_year + 1) ?>学年综合测评.<br />
    您所在的班级是<?php echo $extra['grade']; ?>级<?php echo $extra['class']; ?>班, 共有学生<?php echo $number_of_students ?>人.<br />
    因此各项优秀人数应在<?php echo $min_number_of_excellent_students.'~'.$max_number_of_excellent_students; ?>人之间,
            良好人数应在<?php echo $min_number_of_good_students.'~'.$max_number_of_good_students; ?>人之间,
            中差人数应在<?php echo $min_number_of_medium_students.'~'.$max_number_of_medium_students; ?>人之间.<br />
    其中优良中差对应的分值分别为<?php echo $options['excellent_score'].'分, '.$options['good_score'].'分, '.$options['medium_score'].'分, '.$options['poor_score'].'分.'; ?>

</div>
<table class="table table-striped">
    <thead>
        <tr>
            <td rowspan="2">学号</td>
            <td rowspan="2">姓名</td>
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
    <tbody>
        <?php
            foreach ( $students as $student ) {
                if ( $student['student_id'] == $extra['student_id'] ) {
                    continue;
                }
                echo '<tr>';
                echo '<td>'.$student['student_id'].'</td>';
                echo '<td>'.$student['student_name'].'</td>';
                echo '<td class="left-border"><input type="radio" name="moral-'.$student['student_id'].'" class="moral excellent" value="excellent" /></td>';
                echo '<td><input type="radio" name="moral-'.$student['student_id'].'" class="moral good" value="good" checked /></td>';
                echo '<td><input type="radio" name="moral-'.$student['student_id'].'" class="moral medium" value="medium" /></td>';
                echo '<td><input type="radio" name="moral-'.$student['student_id'].'" class="moral poor" value="poor" /></td>';
                echo '<td class="left-border"><input type="radio" name="strength-'.$student['student_id'].'" class="strength excellent" value="excellent" /></td>';
                echo '<td><input type="radio" name="strength-'.$student['student_id'].'" class="strength good" value="good" checked /></td>';
                echo '<td><input type="radio" name="strength-'.$student['student_id'].'" class="strength medium" value="medium" /></td>';
                echo '<td><input type="radio" name="strength-'.$student['student_id'].'" class="strength poor" value="poor" /></td>';
                echo '<td class="left-border"><input type="radio" name="ability-'.$student['student_id'].'" class="ability excellent" value="excellent" /></td>';
                echo '<td><input type="radio" name="ability-'.$student['student_id'].'" class="ability good" value="good" checked /></td>';
                echo '<td><input type="radio" name="ability-'.$student['student_id'].'" class="ability medium" value="medium" /></td>';
                echo '<td><input type="radio" name="ability-'.$student['student_id'].'" class="ability poor" value="poor" /></td>';
                echo '</tr>';
            }
        ?>
    </tbody>
</table>
<button id="submit" class="btn btn-primary" onclick="javascript:post_votes_request();" disabled="disabled">提交</button>
<button class="btn btn-cancel" onclick="javascript:reset_options();">重置</button>

<script src="<?php echo base_url('/js/messenger.min.js'); ?>"></script> 
<script type="text/javascript">
    $._messengerDefaults = {
        extraClasses: 'messenger-fixed messenger-theme-future messenger-on-bottom messenger-on-right'
    }
    $('input[type="radio"]').click(function(){
        var votes    = get_votes_statistics();
        var is_legal = is_all_proportion_legal(votes.moral_votes, votes.strength_votes, votes.ability_votes);
        show_current_votes(votes.moral_votes, votes.strength_votes, votes.ability_votes, is_legal);

        if ( is_legal ) {
            $('#submit').removeAttr("disabled");
        } else {
            $('#submit').attr("disabled", "disabled");
        }
    });
</script>
<script type="text/javascript">
    function get_votes_statistics() {
        var moral_votes    = new Array(4),
            strength_votes = new Array(4),
            ability_votes  = new Array(4);
        moral_votes[0]    = $('.moral.excellent:checked').length;       moral_votes[1] = $('.moral.good:checked').length;
        moral_votes[2]    = $('.moral.medium:checked').length;          moral_votes[3] = $('.moral.poor:checked').length;
        strength_votes[0] = $('.strength.excellent:checked').length;    strength_votes[1] = $('.strength.good:checked').length;
        strength_votes[2] = $('.strength.medium:checked').length;       strength_votes[3] = $('.strength.poor:checked').length;
        ability_votes[0]  = $('.ability.excellent:checked').length;     ability_votes[1] = $('.ability.good:checked').length;
        ability_votes[2]  = $('.ability.medium:checked').length;        ability_votes[3] = $('.ability.poor:checked').length;

        return { moral_votes: moral_votes, strength_votes: strength_votes, ability_votes: ability_votes };
    }
</script>
<script type="text/javascript">
    function is_all_proportion_legal(moral_votes, strength_votes, ability_votes) {
        var min_number_of_excellent_students = <?php echo $min_number_of_excellent_students; ?>,
            max_number_of_excellent_students = <?php echo $max_number_of_excellent_students; ?>,
            min_number_of_good_students      = <?php echo $min_number_of_good_students; ?>,
            max_number_of_good_students      = <?php echo $max_number_of_good_students; ?>,
            min_number_of_medium_students    = <?php echo $min_number_of_medium_students; ?>,
            max_number_of_medium_students    = <?php echo $max_number_of_medium_students; ?>;
        var is_proportion_legal = true;
        if ( !(moral_votes[0]    >= min_number_of_excellent_students && moral_votes[0]    <= max_number_of_excellent_students) ||
             !(strength_votes[0] >= min_number_of_excellent_students && strength_votes[0] <= max_number_of_excellent_students) ||
             !(ability_votes[0]  >= min_number_of_excellent_students && ability_votes[0]  <= max_number_of_excellent_students) ) {
            is_proportion_legal = false;
        }
        if ( !(moral_votes[1]    >= min_number_of_good_students && moral_votes[1]    <= max_number_of_good_students) ||
             !(strength_votes[1] >= min_number_of_good_students && strength_votes[1] <= max_number_of_good_students) ||
             !(ability_votes[1]  >= min_number_of_good_students && ability_votes[1]  <= max_number_of_good_students) ) {
            is_proportion_legal = false;
        }
        if ( !((moral_votes[2]    + moral_votes[3])    >= min_number_of_medium_students && (moral_votes[2]    + moral_votes[3])    <= max_number_of_medium_students) ||
             !((strength_votes[2] + strength_votes[3]) >= min_number_of_medium_students && (strength_votes[2] + strength_votes[3]) <= max_number_of_medium_students) ||
             !((ability_votes[2]  + ability_votes[3])  >= min_number_of_medium_students && (ability_votes[2]  + ability_votes[3])  <= max_number_of_medium_students) ) {
            is_proportion_legal = false;
        }

        return is_proportion_legal;
    }
</script>
<script type="text/javascript">
    function show_current_votes(moral_votes, strength_votes, ability_votes, is_legal) {
        $.globalMessenger().post({
          message: "当前选中人数:<br />" + 
                   "[道德] 优: " + moral_votes[0]      + "人, 良: " + moral_votes[1]  + 
                      "人, 中: " + moral_votes[2]      + "人, 差: " + moral_votes[3] + "人<br />" +
                   "[体育] 优: " + strength_votes[0]   + "人, 良: " + strength_votes[1]  + 
                      "人, 中: " + strength_votes[2]   + "人, 差: " + strength_votes[3] + "人<br />" +
                   "[能力] 优: " + ability_votes[0]    + "人, 良: " + ability_votes[1]  + 
                      "人, 中: " + ability_votes[2]    + "人, 差: " + ability_votes[3] + "人<br />",
          type: ( is_legal ? 'success' : 'error' ),
          showCloseButton: true
        });
    }
</script>
<script type="text/javascript">
    function post_votes_request() {
        var votes    = get_votes_statistics();
        var is_legal = is_all_proportion_legal(votes.moral_votes, votes.strength_votes, votes.ability_votes);
        if ( !is_legal ) {
            post_error_message('提交数据不合法.');
            return;
        }
        $.globalMessenger().post('正在提交, 请稍后…');
        set_loading_mode(true);
        var post_data = prepare_votes_data();
        $.ajax({
            type: 'POST',
            async: true,
            url: "<?php echo base_url('home/post_votes/'); ?>",
            data: post_data,
            dataType: 'JSON',
            success: function(result) {
                if ( result['is_successful'] ) {
                    load('assessment');
                    $.globalMessenger().hideAll();
                    set_loading_mode(false);
                } else {
                    if ( !result['is_peer_assessment_active'] ) {
                        post_error_message('学生互评系统已关闭, 请与系统管理员联系.');
                    } else if ( result['is_participated'] ) {
                        post_error_message('您已参与过学生互评, 请勿重复提交数据.');
                    } else if ( !result['is_post_successful'] ) {
                        post_error_message('提交数据失败, 请检查数据合法性.');
                    }
                    set_loading_mode(false);
                }
            },
            error: function() {
                post_error_message('发生未知错误.');
                set_loading_mode(false);
            }
        });
    }
</script>
<script type="text/javascript">
    function set_loading_mode(is_loading) {
        if ( is_loading ) {
            $('table :input').attr('disabled', true);
            $('button').attr('disabled', true);
        } else {
            $('table :input').removeAttr('disabled');
            $('button').removeAttr('disabled');
        }
    }
</script>
<script type="text/javascript">
    function prepare_votes_data() {
        <?php
            foreach ( $students as $student ) {
                if ( $student['student_id'] == $extra['student_id'] ) {
                    continue;
                }
                $student_id = $student['student_id'];
                echo 'var moral_'.$student_id.'='."$('input[name=\"moral-$student_id\"]:checked').val(),";
                echo 'strength_'.$student_id.'='."$('input[name=\"strength-$student_id\"]:checked').val(),";
                echo 'ability_'.$student_id.'='."$('input[name=\"ability-$student_id\"]:checked').val();\n";
            }
        ?>
        var post_data = <?php
            $is_empty = true;
            foreach ( $students as $student ) {
                $student_id = $student['student_id'];
                if ( $student_id == $extra['student_id'] ) {
                    continue;
                }
                echo ( $is_empty ? "'" : "+'&" );
                echo "moral-$student_id='+".'moral_'.$student_id;
                echo "+'&strength-$student_id='+".'strength_'.$student_id;
                echo "+'&ability-$student_id='+".'ability_'.$student_id;
                $is_empty = false;
            }
        ?>;
        return post_data;
    }
</script>
<script type="text/javascript">
    function post_error_message(error_message) {
        $.globalMessenger().post({
            message: error_message,
            type: 'error',
            actions: {
                retry: {
                    label: '重试',
                    action: function() {
                        post_votes_request();
                    }
                },
                cancel: {
                    label: '取消',
                    action: function() {
                        $.globalMessenger().hideAll();
                    }
                }
            }
        });
    }
</script>
<script type="text/javascript">
    function reset_options() {
        var result = confirm('您确定要重置所有选项吗?\n该操作将无法恢复!');
        if (result == true) {
            $('.good').prop('checked', true);
            $.globalMessenger().hideAll();
            $('#main-container').scrollTop(0);
      }
    }
</script>