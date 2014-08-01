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
	</div>
	<div id="list">
		<h2>概况</h2>
		<table id="evaluation-records" class="table table-striped">
            <thead>
                <tr>
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
		<h2>互评结果</h2>
		<table id="assessment-records" class="table table-striped">
            <thead>
                <tr>
                    <td colspan="4" class="text-center">道德(<?php echo $options['moral_percents'] * 100 ?>%)</td>
                    <td colspan="4" class="left-border text-center">体育(<?php echo $options['strength_percents'] * 100 ?>%)</td>
                    <td colspan="4" class="left-border text-center">能力(<?php echo $options['ability_percents'] * 100 ?>%)</td>
                </tr>
                <tr>
                    <td>优</td>
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
            <tbody></tbody>
        </table>
	</div> <!-- #list -->
	<div id="page-error" class="alert alert-error hide"><strong>温馨提示: </strong>未找到可用数据.</div>
</div> <!-- /result-content -->

<!-- JavaScript for result -->
<script type="text/javascript">
    function get_evaluation_records(school_year, grade) {
        $.ajax({
            type: 'GET',
            async: true,
            url: "<?php echo base_url().'home/get_evaluation_records/'; ?>" + school_year,
            dataType: 'JSON',
            success: function(result) {
                console.log(result);
                $('#evaluation-records tbody').empty();
                if ( result['is_successful'] ) {
                    $('#evaluation-records').append(
                        '<tr>' + 
                            '<td>' + result['evaluation_records']['moral'] + '</td>' +
                            '<td>' + result['evaluation_records']['intelligence'] + '</td>' +
                            '<td>' + result['evaluation_records']['strength'] + '</td>' +
                            '<td>' + result['evaluation_records']['ability'] + '</td>' +
                            '<td>' + result['evaluation_records']['extra'] + '</td>' +
                            '<td>' + result['evaluation_records']['total_score'] + '</td>' +
                            '<td>' + result['evaluation_records']['ranking'] + '</td>' +
                            '<td>' + result['evaluation_records']['intelligence_ranking'] + '</td>' +
                        '</tr>'
                    );
                    $('#assessment-records').append(
                        '<tr>' + 
                            '<td>' + result['assessment_records']['moral_excellent'] + '</td>' +
                            '<td>' + result['assessment_records']['moral_good'] + '</td>' +
                            '<td>' + result['assessment_records']['moral_medium'] + '</td>' +
                            '<td>' + result['assessment_records']['moral_poor'] + '</td>' +
                            '<td>' + result['assessment_records']['strength_excellent'] + '</td>' +
                            '<td>' + result['assessment_records']['strength_good'] + '</td>' +
                            '<td>' + result['assessment_records']['strength_medium'] + '</td>' +
                            '<td>' + result['assessment_records']['strength_poor'] + '</td>' +
                            '<td>' + result['assessment_records']['ability_excellent'] + '</td>' +
                            '<td>' + result['assessment_records']['ability_good'] + '</td>' +
                            '<td>' + result['assessment_records']['ability_medium'] + '</td>' +
                            '<td>' + result['assessment_records']['ability_poor'] + '</td>' +
                        '</tr>'
                    );
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
        var school_year = $('#available-years').val();

        return get_evaluation_records(school_year);
    }
</script>
<script type="text/javascript">
    $('select#available-years').change(function() {
        return prepare_get_evaluation_records();
    });
</script>
<script type="text/javascript">
    $(document).ready(function(){
        prepare_get_evaluation_records();
    });
</script>
