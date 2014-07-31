<div id="gpa-header" class="page-header">
	<h1>GPA 查询</h1>
</div> <!-- /gpa-header -->
<div id="gpa-content" class="section">
	<p>
		<span><strong>所选课程总绩点:</strong></span>
		<span class="badge badge-info"><?php echo ( $gpa ? $gpa['total_grade_points'] : 0 ); ?></span>
	</p>
	<p>
		<span><strong>所选课程总学分:</strong></span>
		<span class="badge badge-info"><?php echo ( $gpa ? $gpa['total_credits'] : 0 ); ?></span>
	</p>
	<p>
		<span><strong>所选课程的GPA:</strong></span>
		<span class="badge badge-info"><?php echo ( $gpa ? $gpa['gpa'] : 0 ); ?></span>
	</p>
	<p>
		<span><strong>您在本年级的排名:</strong></span>
		<span class="badge badge-success"><?php echo ( $gpa ? $gpa['ranking'] : 0 ); ?></span>
	</p>
</div> <!-- /gpa-content -->
