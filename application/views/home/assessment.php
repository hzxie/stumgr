<div id="peer-assessment-header" class="page-header">
	<h1>学生互评</h1>
</div> <!-- /peer-assessment-header -->
<div id="peer-assessment-content" class="section">
	<?php
		if ( !$options['is_peer_assessment_active'] ) {
			echo '<div class="alert alert-error"><strong>温馨提示: </strong>学生互评系统已关闭</div>';
		} else if ( $extra['is_participated'] ) {
			echo '<div class="alert alert-success"><strong>温馨提示: </strong>您已成功完成评价</div>';
		} else {
			require_once(APPPATH.'views/home/assessment-content.php');
		}
	?>
</div> <!-- /peer-assessment-content -->